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
        Schema::create('department_budgets', function (Blueprint $table) {
            $table->id();
            $table->string('department_name', 100);
            $table->string('cost_center', 50)->unique();
            $table->year('fiscal_year')->default(2026);
            $table->decimal('budget_amount', 15, 2)->default(0.00);
            $table->decimal('spent_amount', 15, 2)->default(0.00);
            $table->string('currency', 10)->default('IDR');
            $table->decimal('utilized_percentage', 6, 2)
                ->nullable()
                ->storedAs('CASE WHEN `budget_amount` > 0 THEN (`spent_amount` / `budget_amount`) * 100 ELSE 0 END');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_budgets');
    }
};
