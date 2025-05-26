<?php
namespace App\Http\Controllers;

use App\Models\QuestionnaireSession;
use Barryvdh\DomPDF\Facade\Pdf;

class QuestionnaireReportController extends Controller
{
    public function download(QuestionnaireSession $session)
    {
        $session->load('actions', 'user');

        $pdf = Pdf::loadView('pdf.questionnaire-report', compact('session'));
        return $pdf->download('questionnaire-report.pdf');
    }
}
