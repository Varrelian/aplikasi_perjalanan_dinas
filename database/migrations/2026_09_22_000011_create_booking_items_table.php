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
        Schema::create('booking_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_request_id')->constrained('travel_requests')->cascadeOnDelete();
            $table->enum('item_type', ['flight', 'hotel', 'transit']);
            $table->string('provider', 100);
            $table->string('title', 255);
            $table->string('subtitle', 255);
            $table->string('booking_code', 50)->nullable();
            $table->string('seat_or_room_type', 100)->nullable();
            $table->dateTime('departure_datetime')->nullable();
            $table->dateTime('arrival_datetime')->nullable();
            $table->string('duration', 50)->nullable();
            $table->unsignedInteger('nights')->nullable();
            $table->decimal('cost', 15, 2)->default(0.00);
            $table->string('currency', 10)->default('IDR');
            $table->boolean('is_compliant')->default(true);
            $table->boolean('is_selected')->default(true);
            $table->string('hotel_image_url', 500)->nullable();
            $table->string('distance_to_client', 100)->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_items');
    }
};
