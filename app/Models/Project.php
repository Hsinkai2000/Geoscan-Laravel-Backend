<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Project extends Model
{
    use HasFactory;
    protected $table = 'projects';

    protected $fillable = ['user_id', 'job_number', 'client_name', 'end_user_name', 'sms_count', 'project_type', 'billing_address', 'project_description', 'jobsite_location', 'bca_reference_number', 'status', 'created_at', 'updated_at', 'completed_at'];

    //references dont want

    // Define relationships

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

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

    public function increment_sms_count()
    {
        $this->sms_count += 1;
        $this->save();
    }
}
