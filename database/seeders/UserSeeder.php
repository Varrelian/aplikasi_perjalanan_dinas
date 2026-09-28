<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds for accounts.
     */
    public function run(): void
    {
        $users = [
            [
                'id' => 1,
                'name' => 'Andi Pratama',
                'email' => 'andi.pratama@travelsys.internal',
                'password' => Hash::make('password'),
                'employee_code' => 'EMP-4091',
                'job_title' => 'Senior Cloud Consultant',
                'department' => 'Engineering & Technology',
                'band' => 'Band 2',
                'role' => 'Employee / Traveler',
                'phone' => null,
                'avatar_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80',
            ],
            [
                'id' => 2,
                'name' => 'Siti Rahma',
                'email' => 'siti.rahma@travelsys.internal',
                'password' => Hash::make('password'),
                'employee_code' => 'EMP-1022',
                'job_title' => 'VP of Product Management',
                'department' => 'Product & Design',
                'band' => 'Band 4',
                'role' => 'Approver / Line Manager',
                'phone' => null,
                'avatar_url' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150&auto=format&fit=crop&q=80',
            ],
            [
                'id' => 3,
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@travelsys.internal',
                'password' => Hash::make('password'),
                'employee_code' => 'EMP-0504',
                'job_title' => 'Corporate Finance Director',
                'department' => 'Corporate Finance',
                'band' => 'Band 5',
                'role' => 'Finance Approver / C-Suite',
                'phone' => null,
                'avatar_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80',
            ],
            [
                'id' => 4,
                'name' => 'Raion',
                'email' => 'farhan260908@gmail.com',
                'password' => Hash::make('password'),
                'employee_code' => 'EMP-5913',
                'job_title' => 'Lead Software Engineer',
                'department' => 'Engineering & Tech',
                'band' => 'Band 2',
                'role' => 'Employee / Traveler',
                'phone' => null,
                'avatar_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80',
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
