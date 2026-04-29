<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Senior Cleanup: Clear products directory before seeding
        if (Storage::disk('public')->exists('products')) {
            Storage::disk('public')->deleteDirectory('products');
            Storage::disk('public')->makeDirectory('products');
        }

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            OrderSeeder::class, // Added OrderSeeder for test orders
        ]);
    }
}
