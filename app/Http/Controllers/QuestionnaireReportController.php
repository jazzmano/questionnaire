<?php

namespace App\Http\Controllers;

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
        
        // Get identification details from session (GDPR compliant)
        $identifier = (object) session('identification_details', []);

        $pdf = Pdf::loadView('pdf.questionnaire-report', compact('session', 'identifier'));

        // Create filename using system name, fallback to default if not available
        $systemName = $identifier->system_name ?? 'questionnaire-report';
        $filename = $this->sanitizeFilename($systemName) . '.pdf';

        // Clear identification details from session after PDF generation
        session()->forget('identification_details');

        return $pdf->download($filename);
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

    private function sanitizeFilename($filename)
    {
        // Remove or replace characters that are not safe for filenames
        $sanitized = preg_replace('/[^a-zA-Z0-9\-_\s]/', '', $filename);
        
        // Replace multiple spaces with single spaces and trim
        $sanitized = preg_replace('/\s+/', ' ', trim($sanitized));
        
        // Replace spaces with underscores for better filename compatibility
        $sanitized = str_replace(' ', '_', $sanitized);
        
        // Ensure filename is not empty, use fallback if needed
        return !empty($sanitized) ? $sanitized : 'questionnaire-report';
    }
}
