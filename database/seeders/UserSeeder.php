<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Senior Reflex: We use Eloquent to ensure casts and enums are correctly handled.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin',
            'role_id' => 1, // Traditional ID for legacy support
            'role' => UserRole::ADMIN, // Modern Enum usage
            'email' => 'admin@webshop.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('12345678'),
        ]);

        // Customer users
        User::create([
            'name' => 'Bart',
            'role_id' => 2,
            'role' => UserRole::CUSTOMER,
            'email' => 'bart@gmail.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'name' => 'John',
            'role_id' => 2,
            'role' => UserRole::CUSTOMER,
            'email' => 'john@gmail.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('12345678'),
        ]);

        // Extra user seen in screenshot
        User::create([
            'name' => 'kamil',
            'role_id' => 1, // Seems to be an admin in the screenshot? Or just an error.
            'role' => UserRole::ADMIN,
            'email' => 'kamil@test.com',
            'email_verified_at' => null,
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'name' => 'Warre',
            'role_id' => 1, // Seems to be an admin in the screenshot? Or just an error.
            'role' => UserRole::ADMIN,
            'email' => 'warre@test.com',
            'email_verified_at' => null,
            'password' => Hash::make('12345678'),
        ]);

    }
}
