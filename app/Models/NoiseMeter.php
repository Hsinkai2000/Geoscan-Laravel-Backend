<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NoiseMeter extends Model
{
    use HasFactory;
    protected $table = 'noise_meters';

    protected $fillable = [
        'serial_number',
        'brand',
        'last_calibration_date',
        'remarks',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'last_calibration_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function measurementPoint(): BelongsTo
    {
        return $this->belongsTo(MeasurementPoint::class, 'id', 'noise_meter_id');
    }

    public function noise_data(): HasMany
    {
        return $this->hasMany(NoiseData::class, 'noise_meter_id');
    }

    public function project_data(): HasMany
    {
        $project_id = $this->project_id ?? "NULL";
        return $this->hasMany(NoiseDatum::class, 'project_id', 'project_id')->where('project_id', $project_id);
    }

    public function hasProject()
    {
        return $this->measurementPoint->hasProject() !== null;
    }

    public function has_running_project()
    {
        return $this->measurementPoint->has_running_project();
    }
}