<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['id' => 1, 'name' => 'employee', 'display_name' => 'Employee', 'created_at' => now()],
            ['id' => 2, 'name' => 'manager', 'display_name' => 'Manager', 'created_at' => now()],
            ['id' => 3, 'name' => 'finance', 'display_name' => 'Finance', 'created_at' => now()],
            ['id' => 4, 'name' => 'travel_admin', 'display_name' => 'Travel Admin', 'created_at' => now()],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['id' => $role['id']],
                $role
            );
        }
    }
}
