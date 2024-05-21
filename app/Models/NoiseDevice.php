<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Noise_Device extends Model
{
    use HasFactory;
    protected $table = 'noise_device';
    protected $fillable = ['project_id', 'device_id', 'serial_number', 'brand', 'last_calibration_date', 'remarks', 'inst_leq', 'leq_temp', 'dose_flag', 'device_latling', 'device_location', 'noise_alert_at', 'leq_5_mins_last_alert_at', 'leq_1_hour_last_alert_at', 'leq_12_hours_last_alert_at', 'dose_70_last_alert_at', 'dose_100_last_alert_at', 'last_calibrated_at', 'created_at', 'updated_at', 'last_alert'];
}