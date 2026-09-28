<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CompanySeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            DepartmentSeeder::class,
            DepartmentBudgetSeeder::class,
            TravelPolicyRuleSeeder::class,
            TravelRequestSeeder::class,
        ]);
    }
}
