<?php

namespace App\Http\Controllers;

use App\Models\IdentificationDetails;
use App\Models\QuestionnaireAction;
use App\Models\QuestionnaireSession;
use Barryvdh\DomPDF\Facade\Pdf;

class QuestionnaireReportController extends Controller
{
    public function download($uuid)
    {
        $session = QuestionnaireSession::where('uuid', $uuid)->firstOrFail();

        // Security check: Only allow access to completed sessions
        if (! $session->completed_at) {
            abort(403, 'This report is not yet available.');
        }

        $this->cleanReport($session->id);
        $session->load('actions', 'user');
        $identifier = IdentificationDetails::where('questionnaire_session_id', $session->id)->first();

        $pdf = Pdf::loadView('pdf.questionnaire-report', compact('session', 'identifier'));

        return $pdf->download('questionnaire-report.pdf');
    }

    public function cleanReport($questionnaireSessionId)
    {

        $nodeKeys = QuestionnaireAction::where('questionnaire_session_id', $questionnaireSessionId)
            ->whereNotNull('selected_option')
            ->pluck('node_key');

        // Step 1: Get the newest record for each node_key
        $latestPerNode = QuestionnaireAction::where('questionnaire_session_id', $questionnaireSessionId)
            ->whereIn('node_key', $nodeKeys)
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('node_key');

        // Step 2: Delete all other records not in this list
        $idsToKeep = $latestPerNode->pluck('id');

        QuestionnaireAction::where('questionnaire_session_id', $questionnaireSessionId)
            ->whereIn('node_key', $nodeKeys)
            ->whereNotIn('id', $idsToKeep)
            ->delete();
    }
}
