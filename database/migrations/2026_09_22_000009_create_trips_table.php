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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('trip_code', 30)->unique();
            $table->foreignId('travel_request_id')->unique()->constrained('travel_requests')->cascadeOnDelete();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('primary_traveler_id')->constrained('users')->restrictOnDelete();
            $table->enum('status', [
                'approved',
                'booking_ready',
                'booked',
                'ongoing',
                'completed',
                'cancelled',
            ])->default('approved');
            $table->timestamp('booked_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'status'], 'idx_trips_company_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
