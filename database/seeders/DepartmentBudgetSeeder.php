<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentBudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $budgets = [
            [
                'id' => 1,
                'department_name' => 'Engineering & Technology',
                'cost_center' => 'CC-402',
                'fiscal_year' => 2026,
                'budget_amount' => 250000000.00,
                'spent_amount' => 180000000.00,
                'currency' => 'IDR',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'department_name' => 'Enterprise Sales',
                'cost_center' => 'CC-201',
                'fiscal_year' => 2026,
                'budget_amount' => 200000000.00,
                'spent_amount' => 195000000.00,
                'currency' => 'IDR',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'department_name' => 'Global Operations',
                'cost_center' => 'CC-305',
                'fiscal_year' => 2026,
                'budget_amount' => 300000000.00,
                'spent_amount' => 150000000.00,
                'currency' => 'IDR',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'department_name' => 'Corporate Finance',
                'cost_center' => 'CC-101',
                'fiscal_year' => 2026,
                'budget_amount' => 100000000.00,
                'spent_amount' => 35000000.00,
                'currency' => 'IDR',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($budgets as $budget) {
            DB::table('department_budgets')->updateOrInsert(
                ['cost_center' => $budget['cost_center']],
                $budget
            );
        }
    }
}
