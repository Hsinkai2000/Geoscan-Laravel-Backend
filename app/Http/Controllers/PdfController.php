<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\MeasurementPoint;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PdfController extends Controller
{

    public function generatePdf(Request $request)
    {
        $measurmentPointId = $request->route('id');
        $measurementPoint = MeasurementPoint::find($measurmentPointId);
        $contacts = Contact::where('project_id', $measurementPoint->project->id)->get();
        // $start_date = Carbon::now()->subDay()->format('d-m-Y');
        // $end_date = Carbon::now()->addDay()->format('d-m-Y');
        $start_date = Carbon::createFromFormat('d-m-Y', '08-07-2024');
        $end_date = Carbon::createFromFormat('d-m-Y', '12-07-2024');

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
        return $pdf->inline();

    }
}