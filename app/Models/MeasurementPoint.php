<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MeasurementPoint extends Model
{
    use HasFactory;

    protected $table = 'measurement_points';
    protected $fillable = ['project_id', 'noise_meter_id', 'concentrator_id', 'noise_data_id', 'point_name', 'remarks', 'inst_leq', 'leq_temp', 'dose_flag', 'device_latling', 'device_location', 'noise_alert_at', 'leq_5_mins_last_alert_at', 'leq_1_hour_last_alert_at', 'leq_12_hours_last_alert_at', 'dose_70_last_alert_at', 'dose_100_last_alert_at', 'last_alert', 'created_at', 'updated_at'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function concentrator(): HasOne
    {
        return $this->hasOne(Concentrator::class, 'concentrator_id', 'id');
    }
    public function noiseMeter(): HasOne
    {
        return $this->hasOne(NoiseMeter::class, 'noise_meter_id', 'id');
    }

    public function noiseData(): HasMany
    {
        return $this->hasMany(NoiseData::class, 'measurement_point_id', 'id');
    }
    public function soundLimit(): HasOne
    {
        return $this->hasOne(SoundLimit::class, 'measurement_point_id', 'id');
    }

    public function hasProject()
    {
        return $this->project !== null;
    }

    public function has_running_project()
    {
        return $this->project->isRunning();
    }


    public function dose_flag_reset()
    {
        $last_leq = $this->noiseData()->latest()->first();
        $received_at = new DateTime($last_leq->received_at);

        $hour = $received_at->format('H');
        $min = $received_at->format('i');

        $reset_hours = $this->dose_reset_hours($received_at);

        if (in_array($hour, $reset_hours) && ($min < 6)) {
            return true;
        }
        return false;
    }
    function dose_reset_hours($time)
    {
        if ($this->is_leq12($time)) {
            return [7, 19];
        } else {
            return array_merge(range(0, 6), range(19, 23));
        }
    }

    function is_leq12($time)
    {
        return $time->format('H') < 12;
    }

    public function check_last_data_for_alert()
    {
        $last_noise_data = $this->noiseData()->latest()->first();

        [$leq_5mins_should_alert, $limit] = $this->leq_5_mins_exceed_and_alert($last_noise_data);
        debug_log("leq5 should alert : leq5 limit", [$leq_5mins_should_alert, $limit]);
        if ($leq_5mins_should_alert) {
            $this->send_alert();
        }

        // [$leq_12_hours_should_alert, $limit] = $this->leq_12_hours_exceed_and_alert($last_noise_data);
        [$leq_1_hour_should_alert, $limit] = $this->leq_1_hour_exceed_and_alert($last_noise_data);
        return;
    }
    private function send_alert()
    {
        return;
    }


    private function leq_5_mins_exceed_and_alert($last_noise_data = null)
    {
        $limit = $this->soundLimit->leq5_limit($last_noise_data->received_at);
        $should_alert = $last_noise_data->leq >= $limit && $this->leq_last_alert_allowed($this->leq_5_mins_last_alert_at, $last_noise_data->received_at);
        return [$should_alert, $limit];
    }

    private function leq_12_hours_exceed_and_alert($last_noise_data = null)
    {
        $limit = $this->soundLimit->leq12_limit($last_noise_data->received_at);
    }

    private function leq_1_hour_exceed_and_alert($last_noise_data = null)
    {
        $one_hr_leq = $this->calc_1_hour_leq();
        $limit = $this->soundLimit->leq1h_limit($last_noise_data->received_at);
        $should_alert = $one_hr_leq >= $limit && $this->leq_last_alert_allowed($this->leq_1_hour_last_alert_at, $last_noise_data->received_at);
        debug_log("leq1hr should alert:leq1hr limit ", [$should_alert, $limit]);
        return [$should_alert, $limit];
    }


    private function get_hour_to_now_leq()
    {
        $noise_data = $this->noiseData();
        $last_noise_data_base_hour = new Datetime($noise_data->latest()->first()->received_at);
        $last_noise_data_base_hour->setTime($last_noise_data_base_hour->format("H"), 0, 0);
        $hour_to_now_leqs = $this->noiseData()->where('received_at', '>=', $last_noise_data_base_hour)->get()->reverse()->take(12);
        return $hour_to_now_leqs;
    }

    private function linearise_leq($leq)
    {
        return pow(10, $leq / 10);
    }

    private function calc_1_hour_leq()
    {
        $sum = 0.0;
        $hour_to_now_leqs = $this->get_hour_to_now_leq();
        for ($data_index = 0; $data_index < count($hour_to_now_leqs); $data_index++) {
            $current_leq = $hour_to_now_leqs[$data_index]->leq;
            $sum += round($this->linearise_leq($current_leq), 1);
        }
        $one_hr_leq = round(10 * log10($sum / count($hour_to_now_leqs)), 1);
        debug_log('1hr leq', [$one_hr_leq]);
        return $one_hr_leq;
    }

    private function leq_last_alert_allowed($leq_freq_last_alert_at, $last_received_datetime)
    {
        $last_received_datetime = new DateTime($last_received_datetime);
        $leq_mins_last_alert_at = new DateTime($leq_freq_last_alert_at);
        if (!is_null($leq_freq_last_alert_at) || ($last_received_datetime->getTimestamp() - $leq_mins_last_alert_at->getTimestamp()) <= 3 * 3600) {
            return false;
        }
        return true;
    }
}