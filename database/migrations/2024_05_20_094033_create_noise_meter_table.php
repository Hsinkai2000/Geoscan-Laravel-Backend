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
        Schema::dropIfExists('noise_meters');
        Schema::create('noise_meters', function (Blueprint $table) {
            $table->id();
            $table->boolean('use_flag')->default(false);
            $table->string('serial_number', 32);
            $table->string('noise_meter_label', 255)->nullable();
            $table->string('brand', 255);
            $table->date('last_calibration_date');
            $table->string('remarks', 255)->nullable();
            $table->dateTime('created_at')->default(now());
            $table->dateTime('updated_at')->default(now());
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('noise_meters');
    }
};