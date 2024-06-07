<?php

namespace App\Http\Controllers;

use App\Models\Concentrator;
use Exception;
use Illuminate\Http\Request;

class ConcentratorController extends Controller
{
    public function create(Request $request)
    {
        try {
            $concentrator_params = $request->only((new Concentrator)->getFillable());
            if (strlen($concentrator_params['device_id']) !== 8) {
                return render_unprocessable_entity('Concentrator device id not 64 bits');
            }
            $concentrator_id = Concentrator::insertGetId($concentrator_params);
            $concentrator = Concentrator::find($concentrator_id);
            return render_ok(["concentrator" => $concentrator]);

        } catch (Exception $e) {
            return render_error($e->getMessage());
        }
    }

    public function index()
    {
        try {
            return render_ok(["concentrators" => Concentrator::all()]);
        } catch (Exception $e) {
            return render_error($e->getMessage());
        }
    }

    public function get(Request $request)
    {

    }

    public function update(Request $request)
    {

    }

    public function delete(Request $request)
    {

    }
}
