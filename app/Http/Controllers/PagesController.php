<?php

namespace App\Http\Controllers;

use App\Models\NoiseData;
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
        if (!self::initial_conditions_valid($geoscanLib)) {
            debug_log('inside initial condition', []);
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
                self::message_1_callback($request, $geoscanLib);
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
        self::updateConcentrator($request, $s_values, $concentrator);

        render_message("ok");
    }

    private static function message_1_callback($request, GeoscanLib $geoscanLib)
    {
        $noise_meter = $geoscanLib->noise_meter();
        $concentrator = $geoscanLib->concentrator();
        debug_log("noise device: ", [$noise_meter]);
        if (!self::check_message_1_conditions($noise_meter, $geoscanLib, $concentrator)) {
            debug_log("failed conditional check");
            return;
        }
        $measurement_point = $noise_meter->measurementPoint;
        $s_values = $geoscanLib->summary_values();
        $noise_data = self::noise_data_params($geoscanLib, $s_values);
        $measurement_point->noiseData()->save($noise_data);
        self::updateConcentrator($request, $s_values, $concentrator);

        $ndevice_params = [
            'inst_leq' => $noise_data->leq,
        ];
        if ($measurement_point->dose_flag_reset()) {
            $ndevice_params = array_merge($ndevice_params, [
                'leq_temp' => $noise_data->leq,
                'dose_flag' => 0,
            ]);
        }
        self::update_measurement_point($measurement_point, $ndevice_params);
        $measurement_point->check_last_data_for_alert();
        render_message("ok");
    }

    private static function update_measurement_point($measurement_point, $ndevice_params)
    {
        $measurement_point->update($ndevice_params);
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

    private static function noise_data_params($geoscanLib, $s_values)
    {
        $noise_data_value = $geoscanLib->noise_data_value();
        $noise_leq = empty($noise_data_value) ? -1 : round($noise_data_value['NoiseData'], 2);

        // Compute round_time with proper ceiling behavior
        $round_time = ceil($s_values['Timestamp'] / 300);

        // Convert round_time to seconds and create DateTime object
        $seconds = 300 * $round_time;
        $time = (new DateTime())->setTimestamp($seconds);

        // Create a new NoiseData object
        $noise_data = new NoiseData([
            'measurement_point_id' => $geoscanLib->noise_meter()->measurementPoint->id,
            'leq' => $noise_leq,
            'received_at' => $time->format('Y-m-d H:i:s')
        ]);

        return $noise_data;
    }


    private static function check_message_1_conditions($noise_meter, $geoscanLib, $concentrator)
    {
        return !(
            self::noise_meter_empty($noise_meter, $geoscanLib) ||
            self::noise_meter_project_empty($noise_meter) ||
            self::noise_meter_project_not_running($noise_meter) ||
            self::concentrator_empty($concentrator)
        );
    }


    private static function noise_meter_empty($noise_meter, $geoscanLib)
    {
        if ($noise_meter == null) {
            render_error('noise device is not available ' . $geoscanLib->noise_serial_number());
            return true;
        }
    }

    private static function noise_meter_project_empty($noise_meter)
    {
        if (!$noise_meter->hasProject()) {
            render_error('Noise device is not in a project');
            return true;
        }
    }

    private static function noise_meter_project_not_running($noise_meter)
    {
        if (!$noise_meter->has_running_project()) {
            render_error('Project is not currently running');
            return true;
        }
    }

    private static function check_message_0_conditions($concentrator)
    {
        return !(
            self::concentrator_empty($concentrator) ||
            self::concentrator_not_running($concentrator)
        );
    }



    private static function concentrator_empty($concentrator)
    {
        if ($concentrator == null) {
            render_error('Concentrator is not available');
            return true;
        }
    }

    private static function concentrator_not_running($concentrator)
    {
        if (!$concentrator->has_running_project()) {
            render_error('Project is not currently running');
            return true;
        }
    }

    private static function initial_conditions_valid(GeoscanLib $geoscanLib)
    {
        return (
            self::check_params_valid($geoscanLib) &&
            self::check_crc32_valid($geoscanLib)
        );
    }


    private static function check_params_valid(GeoscanLib $geoscanLib)
    {
        if ($geoscanLib->params_not_valid()) {
            render_error('Not enough parameters in the request');
            return false;
        }
        return true;
    }

    private static function check_crc32_valid(GeoscanLib $geoscanLib)
    {
        if (!$geoscanLib->crc32_valid()) {
            render_error('CRC32 does not match');
            return false;
        }
        return true;
    }

}