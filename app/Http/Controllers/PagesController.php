<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Libraries\GeoscanLib;


class PagesController extends Controller
{

    public function input(Request $request)
    {
        $debug = [];
        $geoscanLib = new GeoscanLib($request->all());
        debug_log("\n\n\n\n\n\n");
        debug_log('Message type: ', [$geoscanLib->message_type()]);
        switch ($geoscanLib->message_type()) {
            case 0x00:
                debug_log('inside message 0', []);
                self::message_0_callback($geoscanLib);
                break;
            case 0x01:
                debug_log('inside message 1', []);
                self::message_1_callback($geoscanLib);
                break;
            // case 0x02:
            //     self::message_2_callback($geoscanLib);
            //     break;
            // case 0x03:
            //     self::message_3_callback($geoscanLib);
            //     break;
            // case 0x04:
            //     self::message_4_callback($geoscanLib);
            //     break;
            default:
                debug_log('inside message default', []);
                break;
        }
        return self::render_message($debug);
    }

    private static function message_0_callback(GeoscanLib $geoscanLib)
    {
        $concentrator = $geoscanLib->concentrator();
        self::check_message_0_conditions($concentrator);


        $s_vales = $geoscanLib->summary_values();
    }
    private static function message_1_callback(GeoscanLib $geoscanLib)
    {

    }

    private static function check_message_0_conditions($concentrator)
    {
        self::concentrator_empty($concentrator);
        self::concentrator_not_running($concentrator);
    }

    private static function concentrator_empty($concentrator)
    {
        if ($concentrator == null) {
            self::render_error("Concentrator is not available");
        }
    }

    private static function concentrator_not_running($concentrator)
    {
        if (!$concentrator->has_running_project()) {
            self::render_error("Project is not currently running");
        }
    }

    private static function check_initial_conditions(GeoscanLib $geoscanLib)
    {
        self::check_params_valid($geoscanLib);
        self::check_crc32_valid($geoscanLib);
    }

    private static function check_crc32_valid(GeoscanLib $geoscanLib)
    {
        if (!$geoscanLib->crc32_valid()) {
            self::render_error("CRC32 does not match");
        }
    }

    private static function check_params_valid(GeoscanLib $geoscanLib)
    {
        if ($geoscanLib->params_not_valid()) {
            self::render_error("Not enough parameters in the request");
        }
    }

    private static function render_message($message)
    {
        return response()->json($message, Response::HTTP_OK);
    }
    private static function render_error(string $error_message)
    {
        return response()->json(["error" => $error_message], Response::HTTP_UNPROCESSABLE_ENTITY)->send();
    }
}