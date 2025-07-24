<?php

namespace App\Livewire;

use Livewire\Component;

class Timeline extends Component
{
    public $visitedNodes = [];
    public $currentNodeKey;
    public $questionsAnswered = 0;
    public $jsonData;

    public function mount($visitedNodes = [], $currentNodeKey = null, $questionsAnswered = 0)
    {
        $this->visitedNodes = $visitedNodes;
        $this->currentNodeKey = $currentNodeKey;
        $this->questionsAnswered = $questionsAnswered;

        // Load JSON data for node information
        $fileContents = file_get_contents(resource_path('starterfil.json'));
        $this->jsonData = json_decode($fileContents, true);
    }

    public function getProgressPercentage()
    {
        $totalMainQuestions = 9; // Questions 0–7 + others like identification, etc.
        $currentStep = $this->getNumberFromCurrentNodeKey($this->currentNodeKey);

        $percentage = ($currentStep / $totalMainQuestions) * 100;

        return round($percentage);
    }

    public function getNumberFromCurrentNodeKey($currentNodeKey)
    {
        $nodeKeyNumberDict = [
            '0' => 1,
            '1' => 2,
            '2' => 3,
            '2_unsure_followup' => 3,
            '3' => 4,
            '3_unsure_followup' => 4,
            '4' => 5,
            '4_unsure_followup' => 5,
            '5' => 6,
            '5_unsure_followup' => 6,
            '6' => 7,
            '6_unsure_followup' => 7,
            '7' => 8,
            '7_unsure_followup' => 8,
            'unsure_followup' => 1,
            'identification' => 9,
            'introduction' => 11,
        ];

        return $nodeKeyNumberDict[$currentNodeKey] ?? 0; // or '-' if you prefer
    }

    public function getNodeDisplayName($nodeKey)
    {
        $nodeDisplayNames = [
            '0' => 'Intro',
            '1' => 'Machine-based',
            '2' => 'Autonomy',
            '3' => 'Adaptive',
            '4' => 'Objectives',
            '5' => 'Inference',
            '6' => 'Outputs',
            '7' => 'Influence',
            'identification' => 'Info',
            'unsure_followup' => 'Unsure',
            'introduction' => 'Start'
        ];

        return $nodeDisplayNames[$nodeKey] ?? $nodeKey;
    }

    public function adjustVisitedNodes()
    {
        $orderedSteps = ['0', '1', '2', '3', '4', '5', '6', '7', 'identification'];
        
        // Map unsure followup nodes to their corresponding main question
        $unsureToMainQuestion = [
            'unsure_followup' => '0',
            '2_unsure_followup' => '2',
            '3_unsure_followup' => '3',
            '4_unsure_followup' => '4',
            '5_unsure_followup' => '5',
            '6_unsure_followup' => '6',
            '7_unsure_followup' => '7'
        ];
        
        // Determine which main question we're currently on
        $currentMainQuestion = $this->currentNodeKey;
        
        // If current node is an unsure followup, map it to the main question
        if (isset($unsureToMainQuestion[$this->currentNodeKey])) {
            $currentMainQuestion = $unsureToMainQuestion[$this->currentNodeKey];
        }

        // Find the current step index
        $currentStepIndex = array_search($currentMainQuestion, $orderedSteps);

        if ($currentStepIndex !== false) {
            // Add all previous steps to visitedNodes if they're not already there
            for ($i = 0; $i < $currentStepIndex; $i++) {
                if (!in_array($orderedSteps[$i], $this->visitedNodes)) {
                    $this->visitedNodes[] = $orderedSteps[$i];
                }
            }
        }
    }

    public function getNodeStatus($nodeKey)
    {
        // Adjust visited nodes to mark previous steps as complete
        $this->adjustVisitedNodes();

        // Check if user is at identification and should show all as complete
        if ($this->currentNodeKey === 'identification' && $this->hasReachedEndFlow()) {
            return 'completed';
        }

        if (in_array($nodeKey, $this->visitedNodes)) {
            return 'completed';
        } elseif ($this->currentNodeKey === $nodeKey) {
            return 'current';
        }

        return 'upcoming';
    }

    private function hasReachedEndFlow()
    {
        // Check if user has reached any end flow nodes
        return in_array('your_system_is_an_AI_system', $this->visitedNodes) ||
            in_array('not_subject_to_the_AI_Act', $this->visitedNodes);
    }

    public function render()
    {
        return view('livewire.timeline');
    }
}
