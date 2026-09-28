<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TravelRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('travel_requests')->updateOrInsert(
            ['request_code' => 'TRV-10291'],
            [
                'id' => 1,
                'trip_id' => 'TRIP-10291',
                'user_id' => 1,
                'cost_center' => 'CC-402',
                'origin' => 'Jakarta (CGK)',
                'origin_code' => 'CGK',
                'destination' => 'Surabaya (SUB)',
                'dest_code' => 'SUB',
                'departure_date' => '2026-10-12',
                'return_date' => '2026-10-15',
                'departure_time_slot' => 'Morning Flight (06:00 - 11:00)',
                'return_time_slot' => 'Evening Flight (17:00 - 22:00)',
                'purpose_type' => 'Client Meeting',
                'purpose_title' => 'PT Surabaya Digital Mandiri Kickoff',
                'purpose_description' => 'Technical kickoff meeting for phase 2 system integration and on-site infrastructure review.',
                'flight_cost' => 1700000.00,
                'hotel_cost' => 1500000.00,
                'transit_cost' => 0.00,
                'total_cost' => 3200000.00,
                'currency' => 'IDR',
                'policy_status' => 'compliant',
                'budget_status' => 'available',
                'approval_stage' => 'Finance Review',
                'stage_step' => 5,
                'total_steps' => 6,
                'overall_status' => 'booking_ready',
                'submitted_at' => '2026-10-08 02:21:00',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('trips')->updateOrInsert(
            ['trip_code' => 'TRIP-10291'],
            [
                'id' => 1,
                'travel_request_id' => 1,
                'company_id' => 1,
                'primary_traveler_id' => 1,
                'status' => 'booking_ready',
                'booked_at' => null,
                'started_at' => null,
                'completed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
