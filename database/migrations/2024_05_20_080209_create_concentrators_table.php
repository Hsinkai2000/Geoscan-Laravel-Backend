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
        //
        Schema::create('concentrators', function (Blueprint $table) {
            $table->id()->primary();
            $table->integer('project_id')->nullable();
            $table->string('device_id',255)->nullable();
            $table->integer('concentrator_csq')->nullable();
            $table->string('concentrator_hp',11)->nullable();
            $table->float('battery_voltage')->nullable();
            $table->dateTime('last_communication_packet_sent')->nullable();
            $table->string('last_assigned_ip_address',255)->nullable();
            $table->integer('hardware_revision_number')->nullable();
            $table->string('remarks',255)->nullable();
            $table->string('status',255)->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
