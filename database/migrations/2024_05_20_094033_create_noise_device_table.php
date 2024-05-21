<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PhpOption\None;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('noise_device');
        Schema::create('noise_device', function (Blueprint $table) {
            $table->id();
            $table->integer('project_id')->nullable();
            $table->string('device_id', 255)->nullable();
            $table->string('serial_number', 255)->nullable();
            $table->string('brand', 255)->nullable();
            $table->string('last_calibration_date', 20)->nullable();
            $table->string('remarks', 255)->nullable();
            $table->float('inst_leq')->nullable()->default(0);
            $table->integer('leq_temp')->nullable()->default(0);
            $table->decimal('dose_flag', 11, 0)->default(0);
            $table->string('device_latling', 255)->nullable();
            $table->string('device_location', 255)->nullable();
            $table->dateTime('noise_alert_at')->nullable();
            $table->dateTime('leq_5_mins_last_alert_at')->nullable();
            $table->dateTime('leq_1_hour_last_alert_at')->nullable();
            $table->dateTime('leq_12_hours_last_alert_at')->nullable();
            $table->dateTime('dose_70_last_alert_at')->nullable();
            $table->dateTime('dose_100_last_alert_at')->nullable();
            $table->dateTime('last_calibrated_at')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->dateTime('last_alert')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('noise_device');
    }
};