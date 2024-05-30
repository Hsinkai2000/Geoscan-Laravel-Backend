<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Project extends Model
{
    use HasFactory;
    protected $table = 'projects';
    protected $fillable = ['job_number', 'client_name', 'billing_address', 'project_description', 'jobsite_location', 'planning_area', 'vibration_quantity_active', 'vibration_trigger_level', 'vibration_remarks', 'sound_quantity_active', 'sound_trigger_level', 'sound_remarks', 'status', 'layout_file_name', 'layout_content_type', 'layout_file_size', 'layout_updated_at', 'created_at', 'updated_at', 'pjtLat', 'pjtLng', 'completed_at'];

    //references dont want

    // Define relationships

    public function measurement_point(): HasMany
    {
        return $this->hasMany(MeasurementPoint::class, 'project_id', 'id');
    }
    public function contacts(): HasOne
    {
        return $this->hasOne(Contact::class, 'project_id', 'id');
    }

    // Define methods
    public function isRunning()
    {
        return $this->status === 'Ongoing';
    }
}