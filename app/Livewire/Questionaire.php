<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\QuestionnaireSession;
use Illuminate\Support\Facades\Auth;
use App\Models\QuestionnaireAction;
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

    public function mount()
    {
        $json = file_get_contents(resource_path('starterfil.json'));
        $this->data = json_decode($json, true)['nodes'];
        $this->currentNodeKey = json_decode($json, true)['entry'] ?? '0';
        $this->currentNode = $this->data[$this->currentNodeKey];
        $this->session = QuestionnaireSession::create(['user_id' => Auth::user()->id, 'final_node_key' => null, 'started_at' => now()]);
    }


    public function selectOption($nextNodeKey, $actions = null, $answer = null)
    {
        if($nextNodeKey === 'your_system_is_an_AI_system' || $nextNodeKey === 'not_subject_to_the_AI_Act') {
            $this->session->final_node_key = $nextNodeKey;
            $this->session->completed_at = now();
            $this->session->save();
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

        // If no justification needed, continue immediately
        $this->finalizeStep(null);
    }

    public function submitJustification()
    {
        $this->finalizeStep($this->justification_text);

        $this->requiresJustification = false;
        $this->proceedWithFlow = true;
        $this->justification_text = null;
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
        }

        // Reset pending state
        $this->pendingNodeKey = null;
        $this->pendingAnswer = null;
        $this->pendingActions = null;
    }
    public function logAndTime()
    {
        // TODO: Implement logging and timing functionality
    }

    public function requireJustification()
    {
        // TODO: Implement justification requirement functionality
    }

    public function render()
    {
        return view('livewire.questionaire');
    }
}
