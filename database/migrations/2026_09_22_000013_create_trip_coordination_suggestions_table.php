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
        Schema::create('trip_coordination_suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('base_trip_id')->constrained('trips')->cascadeOnDelete();
            $table->string('destination', 150);
            $table->date('travel_date_from');
            $table->date('travel_date_to');
            $table->text('reason');
            $table->enum('status', ['new', 'reviewed', 'accepted', 'dismissed'])->default('new');
            $table->timestamps();

            $table->index(['company_id', 'status'], 'idx_coordination_company_status');
            $table->index(['destination', 'travel_date_from', 'travel_date_to'], 'idx_coordination_destination_dates');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_coordination_suggestions');
    }
};
