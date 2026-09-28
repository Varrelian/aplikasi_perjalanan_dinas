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
        Schema::create('travel_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_code', 50)->unique();
            $table->string('trip_id', 50);
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('cost_center', 50)->default('CC-402');
            $table->string('origin', 100);
            $table->string('origin_code', 10);
            $table->string('destination', 100);
            $table->string('dest_code', 10);
            $table->date('departure_date');
            $table->date('return_date');
            $table->string('departure_time_slot', 100)->nullable()->default('Morning Flight (06:00 - 11:00)');
            $table->string('return_time_slot', 100)->nullable()->default('Evening Flight (17:00 - 22:00)');
            $table->string('purpose_type', 100)->default('Client Meeting');
            $table->string('purpose_title', 255);
            $table->text('purpose_description');
            $table->decimal('flight_cost', 15, 2)->default(0.00);
            $table->decimal('hotel_cost', 15, 2)->default(0.00);
            $table->decimal('transit_cost', 15, 2)->default(0.00);
            $table->decimal('total_cost', 15, 2)->default(0.00);
            $table->string('currency', 10)->default('IDR');
            $table->enum('policy_status', ['compliant', 'warning', 'violation', 'exceeded'])->default('compliant');
            $table->enum('budget_status', ['available', 'warning', 'insufficient'])->default('available');
            $table->string('approval_stage', 100)->default('Line Manager Review');
            $table->unsignedTinyInteger('stage_step')->default(1);
            $table->unsignedTinyInteger('total_steps')->default(6);
            $table->enum('overall_status', [
                'draft',
                'pending_review',
                'pending_approval',
                'approved',
                'booking_ready',
                'booked',
                'completed',
                'cancelled',
            ])->default('pending_review')->index('idx_travel_requests_status');
            $table->timestamp('submitted_at')->nullable()->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_requests');
    }
};
