<?php

namespace App\Http\Controllers;

use App\Models\NoiseMeter;
use Exception;
use Illuminate\Http\Request;

class NoiseMeterController extends Controller
{
    public function create(Request $request)
    {
        try {
            $noise_meter_params = $request->only((new NoiseMeter)->getFillable());
            if (strlen($noise_meter_params['serial_number']) !== 4) {
                return render_unprocessable_entity('Noise meter serial number not 16 bits');
            }
            $noise_meter_id = NoiseMeter::insertGetId($noise_meter_params);
            $noise_meter = NoiseMeter::find($noise_meter_id);
            return render_ok(["noise_meter" => $noise_meter]);

        } catch (Exception $e) {
            return render_error($e->getMessage());
        }
    }

    public function get_available_noise_meters()
    {
        try {
            $noise_meter = NoiseMeter::where('use_flag', 0)->get();
            return render_ok(['noise_meter' => $noise_meter]);
        } catch (Exception $e) {
            return render_error($e);
        }
    }

    public function index()
    {
        try {

            return render_ok(["noise_meters" => NoiseMeter::all()]);
        } catch (Exception $e) {
            return render_error($e->getMessage());
        }
    }

    public function get(Request $request)
    {
        try {
            $id = $request->route('id');
            $noise_meter = NoiseMeter::find($id);
            if (!$noise_meter) {
                return render_unprocessable_entity("Unable to find noise meter with id " . $id);
            }
            return render_ok(["noise_meter" => $noise_meter]);
        } catch (Exception $e) {
            return render_error($e->getMessage());
        }
    }

    public function update(Request $request)
    {
        try {
            $id = $request->route('id');
            $noise_meter_params = $request->only((new NoiseMeter)->getFillable());
            $noise_meter = NoiseMeter::find($id);
            if (!$noise_meter) {
                return render_unprocessable_entity("Unable to find noise meter with id " . $id);
            }

            if (!$noise_meter->update($noise_meter_params)) {
                throw new Exception("Unable to update noise meter");
            }

            return render_ok(["noise_meter" => $noise_meter]);

        } catch (Exception $e) {
            render_error($e->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            $id = $request->route('id');
            $noise_meter = NoiseMeter::find($id);
            if (!$noise_meter) {
                return render_unprocessable_entity("Unable to find noise meter with id " . $id);
            }
            if (!$noise_meter->delete()) {
                throw new Exception("Unable to delete noise meter");
            }
            return render_ok(["noise_meter" => $noise_meter]);

        } catch (Exception $e) {
            return render_error($e->getMessage());
        }
    }

}