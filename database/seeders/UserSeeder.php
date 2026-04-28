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
        // 1 Admin user (fixed credentials)
        User::create([
            'name' => 'Admin',
            'role' => UserRole::ADMIN,
            'email' => 'admin@webshop.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('12345678'),
        ]);

        // 2 Test customers
        User::create([
            'name' => 'Customer One',
            'role' => UserRole::CUSTOMER,
            'email' => 'customer1@test.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'name' => 'Customer Two',
            'role' => UserRole::CUSTOMER,
            'email' => 'customer2@test.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('12345678'),
        ]);

        // Extra admins from user request
        User::create([
            'name' => 'kamil',
            'role_id' => 1, 
            'role' => UserRole::ADMIN,
            'email' => 'kamil@test.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'name' => 'Warre',
            'role_id' => 1, 
            'role' => UserRole::ADMIN,
            'email' => 'warre@test.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('12345678'),
        ]);
    }
}
