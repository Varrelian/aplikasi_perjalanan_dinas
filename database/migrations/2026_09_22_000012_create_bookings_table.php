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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('trips')->cascadeOnDelete();
            $table->enum('booking_type', ['flight', 'hotel', 'transport'])->index('idx_bookings_type');
            $table->string('provider_name', 150);
            $table->string('reference_code', 100)->nullable();
            $table->string('origin', 150)->nullable();
            $table->string('destination', 150)->nullable();
            $table->dateTime('start_datetime')->nullable();
            $table->dateTime('end_datetime')->nullable();
            $table->string('description', 255)->nullable();
            $table->decimal('amount', 15, 2)->default(0.00);
            $table->char('currency', 3)->default('IDR');
            $table->enum('policy_status', ['compliant', 'violation', 'exception_approved'])->default('compliant');
            $table->enum('status', ['selected', 'pending', 'confirmed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
