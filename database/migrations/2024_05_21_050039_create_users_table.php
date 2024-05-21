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
        Schema::dropIfExists('users');
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_type', 255);
            $table->string('username', 255);
            $table->string('email', 255);
            $table->string('encrypted_password', 255);
            $table->string('reset_password_token', 255)->nullable();
            $table->dateTime('reset_password_sent_at')->nullable();
            $table->dateTime('remember_created_at')->nullable();
            $table->integer('sign_in_count')->nullable();
            $table->dateTime('current_sign_in_at')->nullable();
            $table->dateTime('last_sign_in_at')->nullable();
            $table->string('current_sign_in_ip', 255)->nullable();
            $table->string('last_sign_in_ip', 255)->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};