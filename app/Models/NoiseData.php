<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NoiseData extends Model
{
    use HasFactory;

    protected $table = 'noise_data';
    protected $fillable = ['measurement_point_id', 'leq', 'received_at', 'created_at', 'updated_at'];

    public function measurementPoint(): BelongsTo
    {
        return $this->belongsTo(MeasurementPoint::class, 'id', 'measurement_point_id');
    }


}