<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\AdminManagerSeeder;
use Database\Seeders\ProductSeeder;

use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(RolePermissionSeeder::class);
        $this->call(AdminManagerSeeder::class);
        $this->call(ProductSeeder::class);

        User::factory()->create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'phone' => '1234465678', // ✅ ADD THIS
    'bio' => 'Test bio',
    'image' => 'default.png',
    'password' => Hash::make('User@123'),
    'role' => 'user',
]);
    }
}
