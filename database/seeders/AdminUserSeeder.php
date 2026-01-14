<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Use updateOrCreate to prevent duplicate entries if run multiple times
        Employee::updateOrCreate(
            ['email' => 'admin@autocare.com'], // Check by email
            [
                'first_name'   => 'System',
                'last_name'    => 'Administrator',
                'password' => md5('admin123'), // Securely hashes the password
                'phone_number' => '0912345678', // Fits your CHAR(10) column [cite: 470]
                'hire_date'    => Carbon::now(),
                'role'         => 'admin',
                'is_active'    => 'active', // Matches your ENUM values [cite: 474]
            ]
        );
    }
}