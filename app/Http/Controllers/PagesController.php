<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Libraries\GeoscanLib;


class PagesController extends Controller
{

    public function input(Request $request)
    {
        $geoscanLib = new GeoscanLib($request->all());
        if (!self::check_initial_conditions($geoscanLib)) {
            return;
        }
        debug_log('Message type: ', [$geoscanLib->message_type()]);
        switch ($geoscanLib->message_type()) {
            case 0x00:
                debug_log('inside message 0', []);
                self::message_0_callback($request, $geoscanLib);
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
    }

    private static function message_0_callback($request, GeoscanLib $geoscanLib)
    {
        $concentrator = $geoscanLib->concentrator();
        if (!self::check_message_0_conditions($concentrator)) {
            debug_log("failed");
            return;
        }
        $s_values = $geoscanLib->summary_values();

        debug_log('test', [$s_values]);
        self::updateConcentrator($request, $s_values, $concentrator);
    }

    private static function message_1_callback(GeoscanLib $geoscanLib)
    {

    }

    private static function updateConcentrator($request, $s_values, $concentrator)
    {
        $updatedValues = [
            'last_assigned_ip_address' => $request->ip(),
            'last_communication_packet_sent' => self::getCurrentTime(),
            'battery_voltage' => self::getBatteryVoltage($s_values),
            'concentrator_hp' => $s_values['ConcentratorHp'],
            'concentrator_csq' => $s_values['CsqParam'],
        ];
        $concentrator->update($updatedValues);
    }

    private static function getBatteryVoltage($s_values)
    {
        if (!empty($s_values['AdcBattVolt'])) {
            return $s_values['AdcBattVolt'] / 100.00;
        }
        return null;
    }

    private static function getCurrentTime()
    {
        $currentTime = new DateTime('now', new DateTimeZone('UTC'));
        $currentTime->modify('+8 hours');
        return $currentTime->format('Y-m-d H:i:s');
    }
    private static function check_message_0_conditions($concentrator)
    {
        if (self::concentrator_empty($concentrator)) {
            return false;
        }

        if (self::concentrator_not_running($concentrator)) {
            return false;
        }

        return true; // Return true only if all conditions are met
    }


    private static function concentrator_empty($concentrator)
    {
        if ($concentrator == null) {
            self::render_error('Concentrator is not available');
            return true;
        }
    }

    private static function concentrator_not_running($concentrator)
    {
        if (!$concentrator->has_running_project()) {
            self::render_error('Project is not currently running');
            return true;
        }
    }

    private static function check_initial_conditions(GeoscanLib $geoscanLib)
    {
        if (!self::check_params_valid($geoscanLib)) {
            return false;
        }
        ;
        if (!self::check_crc32_valid($geoscanLib)) {
            return false;
        }
        return true;
    }

    private static function check_crc32_valid(GeoscanLib $geoscanLib)
    {
        if (!$geoscanLib->crc32_valid()) {
            self::render_error('CRC32 does not match');
            return false;
        }
    }

    private static function check_params_valid(GeoscanLib $geoscanLib)
    {
        if ($geoscanLib->params_not_valid()) {
            self::render_error('Not enough parameters in the request');
            return false;
        }
    }

    private static function render_message($message)
    {
        response()->json($message, Response::HTTP_OK)->send();
    }
    private static function render_error(string $error_message)
    {
        response()->json(['error' => $error_message], Response::HTTP_UNPROCESSABLE_ENTITY)->send();
    }
}