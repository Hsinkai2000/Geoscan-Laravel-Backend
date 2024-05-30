<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('alert_logs');
        Schema::create('alert_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('alert-rule')->nullable();
            $table->dateTime('event_timestamp')->nullable();
            $table->string('email_messageId', 255)->nullable();
            $table->string('email_debug', 255)->nullable();
            $table->string('sms_messageId', 255)->nullable();
            $table->dateTime('sms_status_updated')->nullable();
            $table->string('sms_status', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_logs');
    }
};