<?php

namespace App\Http\Controllers;

use App\Models\MeasurementPoint;
use App\Models\Project;
use Exception;
use Illuminate\Http\Request;

class MeasurementPointController extends Controller
{

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
            return render_ok(["measurement_point" => $measurement_point]);

        } catch (Exception $e) {
            return render_error($e->getMessage());
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
                $lastND = $measurementPoint->getLastLeqData();
                return [
                    'id' => $measurementPoint->id,
                    'project_id' => $measurementPoint->project_id,
                    'noise_meter_id' => $measurementPoint->noise_meter_id,
                    'concentrator_id' => $measurementPoint->concentrator_id,
                    'point_name' => $measurementPoint->point_name,
                    'device_location' => $measurementPoint->device_location,
                    'concentrator_label' => $concentrator->concentrator_label,
                    'device_id' => $concentrator->device_id,
                    'battery_voltage' => $concentrator->battery_voltage,
                    'concentrator_csq' => $concentrator->concentrator_csq,
                    'last_communication_packet_sent' => $concentrator->last_communication_packet_sent->format('Y-m-d H:m:s'),
                    'noise_meter_label' => $noise_meter->noise_meter_label,
                    'serial_number' => $noise_meter->serial_number,
                    'leq' => $lastND->leq,
                    'received_at' => $lastND->received_at->format('Y-m-d H:m:s'),
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

            if (!$measurement_point->update($measurement_point_params)) {
                throw new Exception("Unable to update Measurement point");
            }

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
            if (!$measurement_point->delete()) {
                throw new Exception("Unable to delete Measurement point");
            }
            return render_ok(["measurement_point" => $measurement_point]);

        } catch (Exception $e) {
            return render_error($e->getMessage());
        }
    }
}