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
        Schema::create('travel_policy_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('name', 150);
            $table->enum('rule_type', ['flight_limit', 'hotel_limit', 'travel_class']);
            $table->enum('travel_scope', ['domestic', 'international', 'all'])->default('all');
            $table->decimal('amount_limit', 15, 2)->nullable();
            $table->char('currency', 3)->default('IDR');
            $table->enum('allowed_class', ['economy', 'premium_economy', 'business', 'first'])->nullable();
            $table->string('minimum_job_level', 50)->nullable();
            $table->boolean('is_blocking')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_policy_rules');
    }
};
