<?php

namespace App\Http\Controllers;

use App\Models\Concentrator;
use App\Models\Contact;
use App\Models\MeasurementPoint;
use App\Models\NoiseMeter;
use App\Models\Project;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Enums\Format;
use Spatie\LaravelPdf\Facades\Pdf;

class MeasurementPointController extends Controller
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

        return Pdf::view('pdfs.noise-data-report', $data)
            ->footerView('pdfs.footer')
            ->format(Format::A4)
            ->name('report_' . $measurementPoint->noiseMeter->serial_number . '_' . $start_date->format('dmY') . '-' . $end_date->format('dmY'));
    }

    public function show()
    {
        return view('web.measurementPoint');
    }

    public function show_by_project($id)
    {
        $project = Project::find($id);
        return view('web.measurementPoint', ['project' => $project])->render();
    }

    public function create(Request $request)
    {
        try {
            $measurement_point_params = $request->only((new MeasurementPoint)->getFillable());

            $measurement_point_id = MeasurementPoint::insertGetId($measurement_point_params);
            $measurement_point = MeasurementPoint::find($measurement_point_id);

            $this->toggle_use_flags($measurement_point->concentrator_id, $measurement_point->noise_meter_id);
            return render_ok(["measurement_point" => $measurement_point]);

        } catch (Exception $e) {
            return render_error($e);
        }
    }

    public function index()
    {
        try {
            return view("measurement_points", ['measurement_point' => MeasurementPoint::all()]);
        } catch (Exception $e) {
            return render_error($e->getMessage());
        }
    }

    public function get(Request $request)
    {
        try {

            $id = $request->route('id');
            debug_log($id);
            $measurementPoint = MeasurementPoint::where('project_id', $id)->get();
            if (!$measurementPoint) {
                return render_unprocessable_entity("Unable to find Measurement point with id " . $id);
            }
            $data = $measurementPoint->map(function ($measurementPoint) {
                $concentrator = $measurementPoint->concentrator;
                $noise_meter = $measurementPoint->noiseMeter;
                return [
                    'id' => $measurementPoint->id,
                    'project_id' => $measurementPoint->project_id,
                    'noise_meter_id' => $measurementPoint->noise_meter_id,
                    'concentrator_id' => $measurementPoint->concentrator_id,
                    'point_name' => $measurementPoint->point_name,
                    'device_location' => $measurementPoint->device_location,
                    'remarks' => $measurementPoint->remarks,
                    'concentrator_label' => $concentrator->concentrator_label,
                    'device_id' => $concentrator->device_id,
                    'battery_voltage' => $concentrator->battery_voltage,
                    'concentrator_csq' => $concentrator->concentrator_csq,
                    'last_communication_packet_sent' => $concentrator->last_communication_packet_sent->format('Y-m-d H:m:s'),
                    'noise_meter_label' => $noise_meter->noise_meter_label,
                    'serial_number' => $noise_meter->serial_number,
                    'data_status' => $measurementPoint->check_data_status(),
                ];
            });

            return render_ok(['measurement_point' => $data]);
        } catch (Exception $e) {
            return render_error($e);
        }
    }

    public function update(Request $request)
    {
        try {
            $id = $request->route('id');
            $measurement_point_params = $request->only((new MeasurementPoint)->getFillable());
            $measurement_point = MeasurementPoint::find($id);
            if (!$measurement_point) {
                return render_unprocessable_entity("Unable to find Measurement point with id " . $id);
            }

            $this->toggle_use_flags($measurement_point->concentrator_id, $measurement_point->noise_meter_id);

            if (!$measurement_point->update($measurement_point_params)) {
                throw new Exception("Unable to update Measurement point");
            }

            $this->toggle_use_flags($measurement_point_params['concentrator_id'], $measurement_point_params['noise_meter_id']);

            return render_ok(["measurement_point" => $measurement_point]);

        } catch (Exception $e) {
            render_error($e->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            $id = $request->route('id');
            $measurement_point = MeasurementPoint::find($id);
            if (!$measurement_point) {
                return render_unprocessable_entity("Unable to find Measurement point with id " . $id);
            }
            $this->toggle_use_flags($measurement_point->concentrator_id, $measurement_point->noise_meter_id);
            if (!$measurement_point->delete()) {
                throw new Exception("Unable to delete Measurement point");
            }
            return render_ok(["measurement_point" => $measurement_point]);

        } catch (Exception $e) {
            return render_error($e->getMessage());
        }
    }

    private function toggle_use_flags($concentrator_id, $noise_meter_id)
    {

        $concentrator = Concentrator::find($concentrator_id);
        $concentrator->toggle_use_flag();

        $noise_meter = NoiseMeter::find($noise_meter_id);
        $noise_meter->toggle_use_flag();
    }
}