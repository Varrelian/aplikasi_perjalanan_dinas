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
        Schema::create('travel_policies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->enum('rule_type', ['flight_limit', 'hotel_limit', 'travel_class', 'per_diem'])->index('idx_travel_policies_rule_type');
            $table->enum('scope', ['domestic', 'international', 'all'])->default('all')->index('idx_travel_policies_scope');
            $table->enum('applicable_band', ['All', 'Band 1', 'Band 2', 'Band 3', 'Band 4', 'Band 5'])->default('All');
            $table->decimal('amount_limit', 15, 2)->nullable();
            $table->string('currency', 10)->default('IDR');
            $table->string('allowed_class', 100)->nullable();
            $table->text('description');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_policies');
    }
};
