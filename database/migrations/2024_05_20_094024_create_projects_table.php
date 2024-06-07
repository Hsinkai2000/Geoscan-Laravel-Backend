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
        Schema::dropIfExists('projects');
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('job_number', 255);
            $table->string('client_name', 255);
            $table->string('project_description', 255)->nullable();
            $table->string('jobsite_location', 255);
            $table->string('BCA Reference Number', 255)->nullable();
            $table->string('status', 255)->nullable()->default('Draft');
            $table->dateTime('created_at')->default(now());
            $table->dateTime('updated_at')->default(now());
            $table->dateTime('completed_at')->nullable();
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
