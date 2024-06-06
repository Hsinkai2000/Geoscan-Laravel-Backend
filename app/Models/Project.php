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

    protected $fillable = ['job_number', 'client_name', 'billing_address', 'project_description', 'jobsite_location', 'BCA Reference Number', 'status', 'created_at', 'updated_at', 'completed_at'];

    //references dont want

    // Define relationships

    public function measurement_point(): HasMany
    {
        return $this->hasMany(MeasurementPoint::class, 'project_id', 'id');
    }
    public function contact(): HasOne
    {
        return $this->hasOne(Contact::class, 'project_id', 'id');
    }

    // Define methods
    public function isRunning()
    {
        return $this->status == 'Ongoing';
    }

    public function get_contact_details()
    {
        return [$this->contact->phone_number, $this->contact->email];
    }
}
