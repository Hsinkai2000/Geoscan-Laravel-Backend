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
        Schema::dropIfExists('noise_data');
        Schema::create('noise_data', function (Blueprint $table) {
            $table->id();
            $table->integer('measurement_point_id')->nullable();
            $table->float('leq')->nullable();
            $table->dateTime('received_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('noise_data');
    }
};