<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('companies')->updateOrInsert(
            ['id' => 1],
            [
                'name' => 'Nusantara Technology Group',
                'code' => 'NTG',
                'currency' => 'IDR',
                'timezone' => 'Asia/Jakarta',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
