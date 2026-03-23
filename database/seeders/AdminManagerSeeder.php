<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminManagerSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Admin
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('Admin@123'),
                'role' => 'admin',

                // ✅ Other columns
                'phone' => '9999999999',
                'bio' => 'System Administrator',
                'image' => 'default.png',
            ]
        );

        // ✅ Manager
        User::updateOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Manager',
                'password' => Hash::make('Manag@123'),
                'role' => 'manager',

                // ✅ Other columns
                'phone' => '8888888888',
                'bio' => 'Project Manager',
                'image' => 'default.png',
            ]
        );
    }
}