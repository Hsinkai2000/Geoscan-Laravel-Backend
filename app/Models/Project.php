<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $table = 'projects';
    protected $fillable = ['project_id','job_number','client_name','billing_address','project_description','jobsite_location','planning_area','vibration_quantity_active','vibration_trigger_level','vibration_remarks','sound_quantity_active','sound_trigger_level','sound_remarks','status','layout_file_name','layout_content_type','layout_file_size','layout_updated_at','reference_1_file_name','reference_1_content_type','reference_1_file_size','reference_1_updated_at','reference_2_file_name','reference_2_content_type','reference_2_file_size','reference_2_updated_at','reference_3_file_name','reference_3_content_type','reference_3_file_size','reference_3_updated_at','reference_4_file_name','reference_4_content_type','reference_4_file_size','reference_4_updated_at','reference_5_file_name','reference_5_content_type','reference_5_file_size','reference_5_updated_at','created_at','updated_at','pjtLat','pjtLng','completed_at'];
}
