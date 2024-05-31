<?php

namespace App\Models;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Concentrator extends Model
{
    use HasFactory;
    protected $table = "concentrators";

    protected $fillable = [
        'project_id',
        'device_id',
        'concentrator_csq',
        'concentrator_hp',
        'battery_voltage',
        'last_communication_packet_sent',
        'last_assigned_ip_address',
        'hardware_revision_number',
        'remarks',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'project_id' => 'integer',
        'device_id' => 'string',
        'concentrator_csq' => 'integer',
        'concentrator_hp' => 'string',
        'battery_voltage' => 'float',
        'last_communication_packet_sent' => 'datetime',
        'last_assigned_ip_address' => 'string',
        'hardware_revision_number' => 'integer',
        'remarks' => 'string',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function measurement_point(): BelongsTo
    {
        return $this->belongsTo(MeasurementPoint::class, 'concentrator_id');
    }

    public function has_running_project()
    {
        return $this->project !== null && $this->project->isRunning();
    }
}