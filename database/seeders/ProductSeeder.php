<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            return;
        }

        $products = [
            ['name' => 'Nike Air Max 90', 'price' => 149.99, 'stock' => 15],
            ['name' => 'Adidas Ultraboost', 'price' => 179.99, 'stock' => 20],
            ['name' => 'New Balance 550', 'price' => 129.50, 'stock' => 5],
            ['name' => 'Puma Suede Classic', 'price' => 85.00, 'stock' => 30],
            ['name' => 'Jordan 1 Retro High', 'price' => 189.99, 'stock' => 2],
            ['name' => 'Reebok Club C 85', 'price' => 95.00, 'stock' => 12],
            ['name' => 'Converse Chuck Taylor', 'price' => 70.00, 'stock' => 50],
            ['name' => 'Vans Old Skool', 'price' => 75.00, 'stock' => 40],
            ['name' => 'Asics Gel-Lyte III', 'price' => 120.00, 'stock' => 8],
            ['name' => 'Nike Dunk Low', 'price' => 110.00, 'stock' => 0], // Out of stock example
        ];

        foreach ($products as $p) {
            Product::create([
                'category_id' => $categories->random()->id,
                'name' => $p['name'],
                'slug' => Str::slug($p['name']),
                'description' => "Dit is een beschrijving voor de {$p['name']}. Een must-have voor elke sneakerliefhebber.",
                'price' => $p['price'],
                'stock' => $p['stock'],
                'is_active' => true,
                'is_featured' => rand(0, 1) === 1,
            ]);
        }
    }
}
