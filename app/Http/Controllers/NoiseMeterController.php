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
            if (strlen($noise_meter_params['serial_number']) === 4) {
                $noise_meter_id = NoiseMeter::insertGetId($noise_meter_params);
                $noise_meter = NoiseMeter::find($noise_meter_id);
                render_message(["noise_meter" => $noise_meter]);
            } else {
                throw new Exception('Noise Meter serial number have to be 32 bits');
            }
        } catch (Exception $e) {
            render_error($e->getMessage());
        }
    }

    public function index()
    {
        try {
            render_message(["noise_meters" => NoiseMeter::all()]);
        } catch (Exception $e) {
            render_error($e->getMessage());
        }
    }

    // public function get(Request $request)
    // {
    //     try {

    //     } catch (Exception $e) {
    //         Log::error('Error in fetching Noise Meter', ['exception' => $e]);
    //         render_error($e->getMessage());
    //     }
    // }

    // public function update(Request $request)
    // {
    //     try {

    //     } catch (Exception $e) {
    //         Log::error('Error in updating Noise Meter', ['exception' => $e]);
    //         render_error($e->getMessage());
    //     }
    // }

    // public function delete(Request $request)
    // {
    //     try {

    //     } catch (Exception $e) {
    //         Log::error('Error in deleting Noise Meter', ['exception' => $e]);
    //         render_error($e->getMessage());
    //     }
    // }

}
