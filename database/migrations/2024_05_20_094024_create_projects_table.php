<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->integer('project_id')->nullable();
            $table->string('job_number',255)->nullable();
            $table->string('client_name',255)->nullable();
            $table->string('billing_address',255)->nullable();
            $table->string('project_description',255)->nullable();
            $table->string('jobsite_location',255)->nullable();
            $table->string('planning_area',255)->nullable();
            $table->integer('vibration_quantity_active')->nullable();
            $table->float('vibration_trigger_level')->nullable();
            $table->string('vibration_remarks',255)->nullable();
            $table->integer('sound_quantity_active')->nullable();
            $table->float('sound_trigger_level')->nullable();
            $table->string('sound_remarks',255)->nullable();
            $table->string('status',255)->nullable();
            $table->string('layout_file_name',255)->nullable();
            $table->string('layout_content_type',255)->nullable();
            $table->integer('layout_file_size')->nullable();
            $table->dateTime('layout_updated_at')->nullable();
            $table->string('reference_1_file_name',255)->nullable();
            $table->string('reference_1_content_type',255)->nullable();
            $table->integer('reference_1_file_size')->nullable();
            $table->dateTime('reference_1_updated_at')->nullable();
            $table->string('reference_2_file_name',255)->nullable();
            $table->string('reference_2_content_type',255)->nullable();
            $table->integer('reference_2_file_size')->nullable();
            $table->dateTime('reference_2_updated_at')->nullable();
            $table->string('reference_3_file_name',255)->nullable();
            $table->string('reference_3_content_type',255)->nullable();
            $table->integer('reference_3_file_size')->nullable();
            $table->dateTime('reference_3_updated_at')->nullable();
            $table->string('reference_4_file_name',255)->nullable();
            $table->string('reference_4_content_type',255)->nullable();
            $table->integer('reference_4_file_size')->nullable();
            $table->dateTime('reference_4_updated_at')->nullable();
            $table->string('reference_5_file_name',255)->nullable();
            $table->string('reference_5_content_type',255)->nullable();
            $table->integer('reference_5_file_size')->nullable();
            $table->dateTime('reference_5_updated_at')->nullable();                   
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->float('pjtLat')->nullable();
            $table->float('pjtLng')->nullable();
            $table->dateTime('completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
