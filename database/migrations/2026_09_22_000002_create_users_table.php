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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('employee_code', 50)->unique();
            $table->string('job_title', 100);
            $table->string('department', 100)->index('idx_users_department');
            $table->enum('band', ['Band 1', 'Band 2', 'Band 3', 'Band 4', 'Band 5'])->default('Band 2')->index('idx_users_band');
            $table->string('role', 50)->default('Employee / Traveler');
            $table->string('phone', 50)->nullable();
            $table->string('avatar_url', 500)->nullable();
            $table->rememberToken();
            $table->timestamps();
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
