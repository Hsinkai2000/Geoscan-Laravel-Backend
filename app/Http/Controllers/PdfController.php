<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\MeasurementPoint;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Illuminate\Http\Request;

class PdfController extends Controller
{

    public function generatePdf(Request $request)
    {
        $measurmentPointId = $request->route('id');
        $start_date = $request->date('start_date');
        $end_date = $request->date('end_date');
        debug_log("dates", [$measurmentPointId, $start_date, $end_date]);
        $measurementPoint = MeasurementPoint::find($measurmentPointId);
        $contacts = Contact::where('project_id', $measurementPoint->project->id)->get();

        $data = [
            'measurementPoint' => $measurementPoint,
            'contacts' => $contacts,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];
        $footerHtml = view('pdfs.footer');
        $pdf = PDF::loadView('pdfs.noise-data-report', $data)->setPaper('a4');
        $pdf->setoptions([
            'margin-bottom' => 8,
            'footer-spacing' => 0,
            'encoding' => 'UTF-8',
            'footer-html' => $footerHtml,
            'enable-javascript' => true]);
        return $pdf->stream();

    }
}