<?php

namespace App\Livewire;

use App\Models\QuestionnaireAction;
use Livewire\Component;
use App\Models\QuestionnaireSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use PDO;

class Questionaire extends Component
{
    public $data;
    public $currentNodeKey;
    public $currentNode;
    public $requiresJustification = false;
    public $proceedWithFlow = true;
    public $justification_text;
    public QuestionnaireSession $session;
    public $pendingNodeKey;
    public $pendingAnswer;
    public $pendingActions;
    public $completedFlow = false;
    public $fileContents;
    public $explanationText;
    public $nodeHistory = [];

    public function mount()
    {
        $this->fileContents = file_get_contents(resource_path('starterfil.json'));
        $this->data = json_decode($this->fileContents, true)['nodes'];
        $this->currentNodeKey = json_decode($this->fileContents, true)['entry'] ?? '0';
        $this->currentNode = $this->data[$this->currentNodeKey];
        $this->session = QuestionnaireSession::create(['user_id' => Auth::user()->id, 'final_node_key' => null, 'started_at' => now()]);
        $this->explanationText = $this->currentNode['explanation'] ?? '';
        $this->explanationText = $this->markdownFileContents($this->currentNode['explanation']);
    }

    public function selectOption($nextNodeKey, $actions = null, $answer = null)
    {
        if ($nextNodeKey === 'your_system_is_an_AI_system' || $nextNodeKey === 'not_subject_to_the_AI_Act') {
            $this->session->final_node_key = $nextNodeKey;
            $this->session->completed_at = now();
            $this->session->save();
            $this->requiresJustification = false;
        }
        $this->pendingNodeKey = $nextNodeKey;
        $this->pendingAnswer = $answer;
        $this->pendingActions = $actions;

        foreach ($actions ?? [] as $action) {
            $type = is_array($action) && isset($action['type']) ? $action['type'] : $action;

            if ($type === 'log_and_time' || $type === 'require_justification') {
                $this->proceedWithFlow = false;
                $this->requiresJustification = true;
                return; // Wait for justification input before proceeding
            }
        }


        $this->finalizeStep(null);
    }

    public function submitJustification()
    {
        $this->finalizeStep($this->justification_text);

        $this->requiresJustification = false;
        $this->proceedWithFlow = true;
        $this->justification_text = null;
    }
    public function markdownFileContents($fileName)
    {
        if  (file_exists(resource_path("decisiontree/{$fileName}"))) {
            return Str::markdown(file_get_contents(resource_path("decisiontree/{$fileName}")));
        }
        else{
            return $fileName;
        }
    }

    public function goBack()
    {
        array_pop($this->nodeHistory);

        if (empty($this->nodeHistory)) {
            $this->currentNodeKey = json_decode($this->fileContents, true)['entry'] ?? '0';
        } else {
            $this->currentNodeKey = end($this->nodeHistory);
        }

        $this->currentNode = $this->data[$this->currentNodeKey] ?? null;

        // Update explanation text
        if (isset($this->currentNode['explanation'])) {
            $this->explanationText = $this->markdownFileContents($this->currentNode['explanation']);
        } else {
            $this->explanationText = 'Missing explanation';
        }

        // Reset state flags
        $this->requiresJustification = false;
        $this->proceedWithFlow = true;
        $this->completedFlow = false;
    }

    protected function finalizeStep($justification = null)
    {
        \App\Models\QuestionnaireAction::create([
            'questionnaire_session_id' => $this->session->id,
            'node_key' => $this->currentNodeKey,
            'selected_option' => $this->pendingAnswer,
            'justification' => $justification,
        ]);
        if (isset($this->data[$this->pendingNodeKey])) {
            $this->currentNodeKey = $this->pendingNodeKey;
            $this->currentNode = $this->data[$this->pendingNodeKey];
            $this->nodeHistory[] = $this->currentNodeKey;

            if(isset($this->currentNode['explanation'])){
                $this->explanationText = $this->markdownFileContents($this->currentNode['explanation']);
            } else {
                $this->explanationText = 'Missing explanation';
            }

            if (($this->currentNode['next'] ?? null) === 'end_flow') {
                $this->completedFlow = true;
                $this->explanationText = '';
            }
        }

        // Reset pending state
        $this->pendingNodeKey = null;
        $this->pendingAnswer = null;
        $this->pendingActions = null;
    }

    public function render()
    {
        return view('livewire.questionaire');
    }
}
