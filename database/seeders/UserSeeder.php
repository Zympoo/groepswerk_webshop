<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'role_id' => 1,
                'email' => 'admin@webshop.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('12345678'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Bart',
                'role_id' => 2,
                'email' => 'bart@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('12345678'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'John',
                'role_id' => 2,
                'email' => 'john@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('12345678'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
