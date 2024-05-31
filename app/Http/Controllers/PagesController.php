<?php

namespace App\Http\Controllers;

use App\Models\Concentrator;
use App\Models\NoiseData;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Libraries\GeoscanLib;

class PagesController extends Controller
{

    public function input(Request $request)
    {
        $geoscanLib = new GeoscanLib($request->all());
        if (!$this->initial_conditions_valid($geoscanLib)) {
            return;
        }
        switch ($geoscanLib->message_type()) {
            case 0x00:
                debug_log('inside message 0', []);
                $this->message_0_callback($request, $geoscanLib);
                break;
            case 0x01:
                debug_log('inside message 1', []);
                $this->message_1_callback($request, $geoscanLib);
                break;
            default:
                debug_log('inside message default', []);
                break;
        }
    }

    private function message_0_callback($request, GeoscanLib $geoscanLib)
    {
        try {
            $concentrator = $geoscanLib->concentrator();
            if (!$this->check_message_0_conditions($concentrator)) {
                debug_log("message_0_callback failed");
                return;
            }
            $s_values = $geoscanLib->summary_values();
            $this->updateConcentrator($request, $s_values, $concentrator);

            render_message("ok");
        } catch (Exception $e) {
            Log::error('Error in message0Callback', ['exception' => $e]);
        }
    }

    private function message_1_callback($request, GeoscanLib $geoscanLib)
    {
        try {
            $noise_meter = $geoscanLib->noise_meter();
            $concentrator = $geoscanLib->concentrator();
            if (!$this->check_message_1_conditions($noise_meter, $geoscanLib, $concentrator)) {
                debug_log("failed conditional check");
                return;
            }
            $measurement_point = $noise_meter->measurementPoint;
            $s_values = $geoscanLib->summary_values();
            $noise_data = $this->noise_data_params($geoscanLib, $s_values);
            $existing_noise_data = NoiseData::where('received_at', $noise_data->received_at)->where('measurement_point_id', $measurement_point->id)->first();
            if (is_null($existing_noise_data)) {
                $measurement_point->noiseData()->save($noise_data);
                $this->updateConcentrator($request, $s_values, $concentrator);

                $ndevice_params = $this->prepareNdeviceParams($noise_data, $measurement_point);
                $this->update_measurement_point($measurement_point, $ndevice_params);
            } else {
                $existing_noise_data->update($noise_data->toArray());
            }
            $measurement_point->check_last_data_for_alert();

            render_message("ok");
        } catch (Exception $e) {
            Log::error('Error in message1Callback', ['exception' => $e]);
        }
    }

    private function prepareNdeviceParams($noiseData, $measurementPoint)
    {
        $ndeviceParams = ['inst_leq' => $noiseData->leq];

        if ($measurementPoint->dose_flag_reset()) {
            $ndeviceParams = array_merge($ndeviceParams, [
                'leq_temp' => $noiseData->leq,
                'dose_flag' => 0,
            ]);
        }

        return $ndeviceParams;
    }

    private function update_measurement_point($measurement_point, $ndevice_params)
    {
        try {
            $measurement_point->update($ndevice_params);
        } catch (Exception $e) {
            Log::error('Failed to update measurement point', [
                'measurement point id' => $measurement_point->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function updateConcentrator(Request $request, array $s_values, Concentrator $concentrator)
    {
        try {
            $updatedValues = $this->prepareUpdatedValues($request, $s_values);
            $concentrator->update($updatedValues);
            Log::info('Concentrator updated successfully', ['concentrator_id' => $concentrator->id]);
        } catch (\Exception $e) {
            Log::error('Failed to update concentrator', [
                'concentrator_id' => $concentrator->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function prepareUpdatedValues(Request $request, array $s_values)
    {
        return [
            'last_assigned_ip_address' => $request->ip(),
            'last_communication_packet_sent' => $this->getCurrentTime(),
            'battery_voltage' => $this->getBatteryVoltage($s_values),
            'concentrator_hp' => $s_values['ConcentratorHp'] ?? null,
            'concentrator_csq' => $s_values['CsqParam'] ?? null,
        ];
    }

    private function getBatteryVoltage($s_values)
    {
        return isset($s_values['AdcBattVolt']) ? $s_values['AdcBattVolt'] / 100.00 : null;
    }

    private function getCurrentTime()
    {
        $currentTime = new DateTime('now', new DateTimeZone('UTC'));
        $currentTime->modify('+8 hours');
        return $currentTime->format('Y-m-d H:i:s');
    }

    private function noise_data_params($geoscanLib, $s_values)
    {
        try {
            $noise_data_value = $geoscanLib->noise_data_value();
            $noise_leq = empty($noise_data_value) ? -1 : round($noise_data_value['NoiseData'], 2);

            $round_time = ceil($s_values['Timestamp'] / 300);

            $seconds = 300 * $round_time;
            $time = (new DateTime())->setTimestamp($seconds);

            $noise_data = new NoiseData([
                'measurement_point_id' => $geoscanLib->noise_meter()->measurementPoint->id,
                'leq' => $noise_leq,
                'received_at' => $time->format('Y-m-d H:i:s'),
            ]);

            return $noise_data;
        } catch (\Exception $e) {
            Log::error('Error creating noise data parameters', [
                'error' => $e->getMessage(),
                's_values' => $s_values,
                'geoscanLib' => $geoscanLib,
            ]);
            throw $e;
        }

    }

    private function check_message_1_conditions($noise_meter, $geoscanLib, $concentrator)
    {
        return !(
            $this->noise_meter_empty($noise_meter, $geoscanLib) ||
            $this->noise_meter_project_empty($noise_meter) ||
            $this->noise_meter_project_not_running($noise_meter) ||
            $this->concentrator_empty($concentrator)
        );
    }

    private function noise_meter_empty($noise_meter, $geoscanLib)
    {
        if ($noise_meter == null) {
            render_error('noise device is not available ' . $geoscanLib->noise_serial_number());
            return true;
        }
    }

    private function noise_meter_project_empty($noise_meter)
    {
        if (!$noise_meter->hasProject()) {
            render_error('Noise device is not in a project');
            return true;
        }
    }

    private function noise_meter_project_not_running($noise_meter)
    {
        if (!$noise_meter->has_running_project()) {
            render_error('Project is not currently running');
            return true;
        }
    }

    private function check_message_0_conditions($concentrator)
    {
        return !(
            $this->concentrator_empty($concentrator) ||
            $this->concentrator_not_running($concentrator)
        );
    }

    private function concentrator_empty($concentrator)
    {
        if ($concentrator == null) {
            render_error('Concentrator is not available');
            return true;
        }
    }

    private function concentrator_not_running($concentrator)
    {
        if (!$concentrator->has_running_project()) {
            render_error('Project is not currently running');
            return true;
        }
    }

    private function initial_conditions_valid(GeoscanLib $geoscanLib)
    {
        return (
            $this->check_params_valid($geoscanLib) &&
            $this->check_crc32_valid($geoscanLib)
        );
    }

    private function check_params_valid(GeoscanLib $geoscanLib)
    {
        if ($geoscanLib->params_not_valid()) {
            render_error('Not enough parameters in the request');
            return false;
        }
        return true;
    }

    private function check_crc32_valid(GeoscanLib $geoscanLib)
    {
        if (!$geoscanLib->crc32_valid()) {
            render_error('CRC32 does not match');
            return false;
        }
        return true;
    }

}