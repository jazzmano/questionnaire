<?php

namespace App\Livewire;

use App\Models\QuestionnaireAction;
use Livewire\Component;
use App\Models\QuestionnaireSession;
use Illuminate\Support\Str;
use App\Models\IdentificationDetails;

class Questionaire extends Component
{
    public $data;
    public $currentNodeKey;
    public $currentNode;
    public $justification_text;
    public QuestionnaireSession $session;
    public $pendingNodeKey;
    public $pendingAnswer;
    public $pendingActions;
    public $completedFlow = false;
    public $fileContents;
    public $explanationText;
    public $markdownFile = '';
    public $nodeHistory = [];
    public $visitedNodes = []; // Track complete path taken by user
    public $questionsAnswered = 0;
    public $identificationQuestions = [];
    public $multiSelectedAnswers = []; // for multi-select tracking
    public $requiresJustification = false; // flag to indicate if justification is required
    public $userDetails = [
        'Name' => '',
        'E-mail' => '',
        'Company' => '',
        'System' => ''
    ];
    public $pendingNodeQueue = []; // queue of node keys to visit
    public $multiSelect = false;
    public $selectedAnswer; // for single select tracking
    public $isMultiSelectNode = false; // flag to determine if current node supports multi-select
    public $showOptions = true; // flag to show/hide answer options

    public function mount()
    {
        $this->fileContents = file_get_contents(resource_path('starterfil.json'));
        $jsonData = json_decode($this->fileContents, true);

        if (!$jsonData || !isset($jsonData['nodes'])) {
            throw new \Exception('Invalid JSON structure in starterfil.json');
        }

        $this->data = $jsonData['nodes'];
        $this->currentNodeKey = $jsonData['entry'] ?? '0'; // Start with first question, not identification
        $this->identificationQuestions = $this->data['identification']['questions'][0] ?? [];
        $this->currentNode = $this->data[$this->currentNodeKey] ?? null;
        $this->markdownFile = $this->currentNode['explanation'] ?? '';

        // Initialize visited nodes with starting node
        $this->visitedNodes = [$this->currentNodeKey];


        $this->session = QuestionnaireSession::create([
            'uuid' => Str::uuid(),
            'final_node_key' => null,
            'started_at' => now()
        ]);

        $this->setNodeProperties();
    }

    public function selectOption($optionIndex = null)
    {
        $this->questionsAnswered++;
        if ($this->currentNodeKey == 'introduction') {
            $this->moveToNode('0');
        }
        // Validate justification if required for the selected option
        if ($this->selectedAnswer !== null && isset($this->currentNode['options'][$this->selectedAnswer])) {
            $selectedOption = $this->currentNode['options'][$this->selectedAnswer];
            $actions = $selectedOption['actions'] ?? [];

            if (in_array('require_justification', $actions)) {
                $this->validate([
                    'justification_text' => 'required|min:10'
                ], [
                    'justification_text.required' => 'Please provide a justification for your answer.',
                    'justification_text.min' => 'Justification must be at least 10 characters long.'
                ]);
            }
        }

        if ($this->currentNodeKey === 'identification') {
            $this->handleIdentificationSubmission();
            return;
        }

        if ($this->isMultiSelectNode) {
            $this->handleMultiSelectSubmission();
        } else {
            if ($optionIndex === null && $this->selectedAnswer !== null) {
                $optionIndex = $this->selectedAnswer;
            }
            $this->handleSingleSelectSubmission($optionIndex);
        }
    }

    // Removed old finalizeStep method - replaced with new flow methods above
    public function goBack()
    {
        $this->questionsAnswered--;
        if (empty($this->nodeHistory)) {
            return; // Cannot go back further
        }

        // Remove current node from history and go to previous
        $previousNodeKey = array_pop($this->nodeHistory);

        // Remove current node from visited nodes when going back
        if (!empty($this->visitedNodes) && end($this->visitedNodes) === $this->currentNodeKey) {
            array_pop($this->visitedNodes);
        }

        $this->currentNodeKey = $previousNodeKey;
        $this->currentNode = $this->data[$this->currentNodeKey] ?? null;

        if (!$this->currentNode) {
            throw new \Exception('Previous node not found: ' . $previousNodeKey);
        }

        // Reset form state
        $this->selectedAnswer = null;
        $this->multiSelectedAnswers = [];
        $this->justification_text = null;
        $this->resetValidation(); // Clear validation errors
        $this->completedFlow = false;
        $this->showOptions = true; // Re-enable options when going back

        // Set node properties
        $this->setNodeProperties();
    }

    protected function setNodeProperties()
    {
        $this->isMultiSelectNode = $this->currentNodeKey === 'unsure_followup';
        $this->markdownFile = $this->currentNode['explanation'] ?? '';
        $this->requiresJustification = $this->checkIfJustificationRequired();
        $this->resetValidation(); // Clear any validation errors when setting up a new node
    }

    protected function checkIfJustificationRequired()
    {
        if (!isset($this->currentNode['options'])) return false;

        foreach ($this->currentNode['options'] as $option) {
            $actions = $option['actions'] ?? [];
            if (in_array('require_justification', $actions)) {
                return true;
            }
        }
        return false;
    }

    protected function handleIdentificationSubmission()
    {
        // Validate identification details
        $this->validate([
            'userDetails.Name' => 'required|min:2',
            'userDetails.E-mail' => 'required|E-mail',
            'userDetails.Company' => 'required|min:2',
            'userDetails.System' => 'required|min:2'
        ], [
            'userDetails.Name.required' => 'Name is required.',
            'userDetails.E-mail.required' => 'Email is required.',
            'userDetails.E-mail.email' => 'Please enter a valid email address.',
            'userDetails.Company.required' => 'Company name is required.',
            'userDetails.System.required' => 'System name is required.'
        ]);

        // Store identification details in PHP session (temporary, GDPR compliant)
        session(['identification_details' => [
            'name' => $this->userDetails['Name'],
            'email' => $this->userDetails['E-mail'],
            'company' => $this->userDetails['Company'],
            'system_name' => $this->userDetails['System'],
        ]]);

        // If there's a stored final node key, proceed to completion
        if ($this->session->final_node_key) {
            $this->handleFlowCompletion($this->session->final_node_key);
        } else {
            // Otherwise, start the questionnaire (legacy flow)
            $nextNodeKey = $this->currentNode['options'][0]['next'] ?? '0';
            $this->moveToNode($nextNodeKey, 'Identification completed');
        }
    }

    protected function handleMultiSelectSubmission()
    {
        if (empty($this->multiSelectedAnswers)) {
            return;
        }

        // Special handling for unsure_followup
        if ($this->currentNodeKey === 'unsure_followup') {
            $this->handleUnsureFollowupSelection();
        }
    }

    protected function handleUnsureFollowupSelection()
    {
        $selectedValues = [];

        foreach ($this->multiSelectedAnswers as $selectedIndex) {
            $option = $this->currentNode['options'][$selectedIndex] ?? null;
            if (!$option) continue;

            // Handle "I don't know what I don't know" option
            if (isset($option['all_values'])) {
                $selectedValues = array_merge($selectedValues, $option['all_values']);
            } elseif (isset($option['value'])) {
                $selectedValues[] = $option['value'];
            }
        }

        // Remove duplicates and sort
        $selectedValues = array_unique($selectedValues);
        sort($selectedValues);

        // Add selected node keys to the queue
        $this->pendingNodeQueue = $selectedValues;

        // Log the multi-select action
        QuestionnaireAction::create([
            'questionnaire_session_id' => $this->session->id,
            'node_key' => $this->currentNodeKey,
            'node_question' => $this->currentNode['question'] ?? $this->currentNode['message'] ?? 'Multi-select question',
            'selected_option' => implode("\n", $this->getAnswersFromMultiSelectQuestion($this->multiSelectedAnswers)),
            'justification' => $this->justification_text,
        ]);

        // Move to first node in queue or default
        $this->processQueue();
    }

    protected function handleSingleSelectSubmission($optionIndex)
    {
        if ($optionIndex === null || !isset($this->currentNode['options'][$optionIndex])) {
            return;
        }

        $option = $this->currentNode['options'][$optionIndex];
        $nextNodeKey = $option['next'] ?? null;

        // Log the action
        QuestionnaireAction::create([
            'questionnaire_session_id' => $this->session->id,
            'node_key' => $this->currentNodeKey,
            'node_question' => $this->currentNode['question'] ?? $this->currentNode['message'] ?? 'Question not available',
            'selected_option' => $option['label'] ?? '',
            'justification' => $this->justification_text,
        ]);

        // Handle routing based on node configuration
        if (isset($option['next_node_from_selected_values']) && $option['next_node_from_selected_values']) {
            $this->processQueue();
        } elseif ($nextNodeKey) {
            $this->moveToNode($nextNodeKey, $option['label'] ?? '');
        }
    }

    protected function processQueue()
    {
        if (empty($this->pendingNodeQueue)) {
            // No more nodes in queue, determine default next node
            $routing = $this->currentNode['routing'] ?? null;
            $defaultNext = $routing['next_node_when_selected_values_empty'] ?? 'your_system_is_an_AI_system';
            $this->moveToNode($defaultNext, 'Queue completed');
            return;
        }

        // Get next node from queue
        $nextNodeKey = array_shift($this->pendingNodeQueue);
        $this->moveToNode($nextNodeKey, 'From queue');
    }

    protected function moveToNode($nodeKey, $answer = '')
    {
        if ($nodeKey === 'end_flow' || in_array($nodeKey, ['your_system_is_an_AI_system', 'not_subject_to_the_AI_Act'])) {
            // Before completing flow, redirect to identification if not filled yet
            if (
                empty($this->userDetails['name']) || empty($this->userDetails['email']) ||
                empty($this->userDetails['company']) || empty($this->userDetails['system_name'])
            ) {
                $this->moveToIdentification($nodeKey);
                return;
            }
            $this->handleFlowCompletion($nodeKey);
            return;
        }

        if (!isset($this->data[$nodeKey])) {
            throw new \Exception('Node not found: ' . $nodeKey);
        }

        // Update history
        $this->nodeHistory[] = $this->currentNodeKey;

        // Track visited nodes (complete path)
        if (!in_array($nodeKey, $this->visitedNodes)) {
            $this->visitedNodes[] = $nodeKey;
        }

        // Move to new node
        $this->currentNodeKey = $nodeKey;
        $this->currentNode = $this->data[$nodeKey];

        // Reset form state
        $this->selectedAnswer = null;
        $this->multiSelectedAnswers = [];
        $this->justification_text = null;
        $this->resetValidation(); // Clear validation errors

        // Set node properties
        $this->setNodeProperties();
    }

    protected function moveToIdentification($finalNodeKey)
    {
        // Store the final node key to proceed to after identification
        $this->session->final_node_key = $finalNodeKey;
        $this->session->save();

        // Update history
        $this->nodeHistory[] = $this->currentNodeKey;

        // Move to identification
        $this->currentNodeKey = 'identification';
        $this->currentNode = $this->data['identification'];

        // Reset form state
        $this->selectedAnswer = null;
        $this->multiSelectedAnswers = [];
        $this->justification_text = null;
        $this->resetValidation(); // Clear validation errors

        // Set node properties
        $this->setNodeProperties();
    }

    protected function handleFlowCompletion($finalNodeKey)
    {
        $this->session->final_node_key = $finalNodeKey;
        $this->session->completed_at = now();
        $this->session->save();

        $this->markdownFile = ''; // Clear explanation text on completion
        $this->completedFlow = true;
        $this->currentNode = $this->data[$finalNodeKey] ?? ['message' => 'Flow completed'];
        $this->explanationText = ''; // Clear explanation text on completion
        $this->requiresJustification = false; // Hide justification textarea
        $this->selectedAnswer = null; // Clear any selected answers
        $this->multiSelectedAnswers = []; // Clear multi-select answers
        $this->justification_text = null; // Clear justification text
        $this->showOptions = false; // Hide answer options when flow is complete
        $this->currentNodeKey = $finalNodeKey; // Set the current node key to the final node
    }
    public function render()
    {
        return view('livewire.questionaire');
    }
    protected function getAnswersFromMultiSelectQuestion(array $multiSelectedAnswers): array
    {
        $selectedLabels = [];
        $unsureOptionsArray = $this->data['unsure_followup']['options'];
        array_shift($unsureOptionsArray);


        foreach ($unsureOptionsArray as $option) {
            foreach ($multiSelectedAnswers as $selectedAnswerValue) {
                if ($selectedAnswerValue == $option['value']) {
                    array_push($selectedLabels, $option['label']);
                }
            }
        }
        return $selectedLabels;
    }
}
