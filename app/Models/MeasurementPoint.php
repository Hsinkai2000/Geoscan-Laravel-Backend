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
        $last_leq = $this->noiseData->latest()->first();

    }
}