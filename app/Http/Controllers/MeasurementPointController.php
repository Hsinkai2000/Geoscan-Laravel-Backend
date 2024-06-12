<?php

namespace App\Http\Controllers;

use App\Models\MeasurementPoint;
use Exception;
use Illuminate\Http\Request;

class MeasurementPointController extends Controller
{

    public function show()
    {
        return view('web.measurementPoint');
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
            $measurement_point = MeasurementPoint::find($id);
            if (!$measurement_point) {
                return render_unprocessable_entity("Unable to find Measurement point with id " . $id);
            }
            return render_ok(["measurement_point" => $measurement_point]);
        } catch (Exception $e) {
            return render_error($e->getMessage());
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
