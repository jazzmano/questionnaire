<?php
namespace App\Http\Controllers;

use App\Models\QuestionnaireSession;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\QuestionnaireAction;

class QuestionnaireReportController extends Controller
{
    public function download(QuestionnaireSession $session)
    {
        $this->cleanReport($session->id);
        $session->load('actions', 'user');

        $pdf = Pdf::loadView('pdf.questionnaire-report', compact('session'));
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
