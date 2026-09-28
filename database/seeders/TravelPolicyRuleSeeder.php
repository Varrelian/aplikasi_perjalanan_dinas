<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TravelPolicyRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rules = [
            [
                'id' => 1,
                'company_id' => 1,
                'name' => 'Domestic Flight Limit',
                'rule_type' => 'flight_limit',
                'travel_scope' => 'domestic',
                'amount_limit' => 2000000.00,
                'currency' => 'IDR',
                'allowed_class' => null,
                'minimum_job_level' => null,
                'is_blocking' => 1,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'company_id' => 1,
                'name' => 'Hotel Nightly Limit',
                'rule_type' => 'hotel_limit',
                'travel_scope' => 'all',
                'amount_limit' => 500000.00,
                'currency' => 'IDR',
                'allowed_class' => null,
                'minimum_job_level' => null,
                'is_blocking' => 1,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'company_id' => 1,
                'name' => 'Default Travel Class',
                'rule_type' => 'travel_class',
                'travel_scope' => 'all',
                'amount_limit' => null,
                'currency' => 'IDR',
                'allowed_class' => 'economy',
                'minimum_job_level' => null,
                'is_blocking' => 1,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'company_id' => 1,
                'name' => 'Manager Business Class',
                'rule_type' => 'travel_class',
                'travel_scope' => 'all',
                'amount_limit' => null,
                'currency' => 'IDR',
                'allowed_class' => 'business',
                'minimum_job_level' => 'manager',
                'is_blocking' => 0,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($rules as $rule) {
            DB::table('travel_policy_rules')->updateOrInsert(
                ['id' => $rule['id']],
                $rule
            );
        }
    }
}
