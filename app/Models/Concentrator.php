<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concentrator extends Model
{
    use HasFactory;
    protected $table = "concentrators";
    protected $fillable = ['project_id', 'device_id', 'concentrator_csq', 'concentrator_hp', 'battery_voltage', 'last_communication_packet_sent', 'last_assigned_ip_address', 'hardware_revision_number', 'remarks', 'status', 'created_at', 'updated_at'];
}