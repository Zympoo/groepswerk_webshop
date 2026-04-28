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
        $sneakerCategory = Category::where('name', 'Sneakers')->first() ?? Category::factory()->create(['name' => 'Sneakers']);
        $saleCategory = Category::where('name', 'Sale')->first() ?? Category::factory()->create(['name' => 'Sale']);
        $nieuwCategory = Category::where('name', 'Nieuw')->first() ?? Category::factory()->create(['name' => 'Nieuw']);

        $products = [
            [
                'name' => 'Nike Air Force 1 \'07',
                'price' => 119.99,
                'description' => 'The radiance lives on in the Nike Air Force 1 \'07, the b-ball icon that puts a fresh spin on what you know best.',
                'category_id' => $sneakerCategory->id,
            ],
            [
                'name' => 'Adidas Ultraboost Light',
                'price' => 189.99,
                'description' => 'Experience epic energy with the new Ultraboost Light, our lightest Ultraboost ever.',
                'category_id' => $nieuwCategory->id,
            ],
            [
                'name' => 'Air Jordan 1 Retro High OG',
                'price' => 179.99,
                'description' => 'Familiar but always fresh, the iconic Air Jordan 1 is remastered for today\'s sneakerhead culture.',
                'category_id' => $sneakerCategory->id,
            ],
            [
                'name' => 'Nike Dunk Low Retro',
                'price' => 109.99,
                'description' => 'Created for the hardwood but taken to the streets, the Nike Dunk Low Retro returns with crisp overlays.',
                'category_id' => $saleCategory->id,
            ],
            [
                'name' => 'New Balance 550',
                'price' => 139.99,
                'description' => 'The return of a legend. Originally worn by pros, the new 550 pays tribute to the 1989 original.',
                'category_id' => $sneakerCategory->id,
            ],
            [
                'name' => 'Vans Old Skool',
                'price' => 74.99,
                'description' => 'The Old Skool, the Vans classic skate shoe and first to bare the iconic sidestripe.',
                'category_id' => $sneakerCategory->id,
            ],
            [
                'name' => 'Converse Chuck Taylor All Star',
                'price' => 69.99,
                'description' => 'The iconic high top you know and love. Canvas upper, classic patch, and timeless silhouette.',
                'category_id' => $sneakerCategory->id,
            ],
            [
                'name' => 'Puma Suede Classic',
                'price' => 84.99,
                'description' => 'Meet the Suede. It\'s been kicking around for a long time. From 1968 to today.',
                'category_id' => $saleCategory->id,
            ],
            [
                'name' => 'Reebok Club C 85',
                'price' => 94.99,
                'description' => 'Clean, minimalist design. These shoes stay true to their original 80s tennis style.',
                'category_id' => $sneakerCategory->id,
            ],
            [
                'name' => 'Asics Gel-Lyte III',
                'price' => 119.99,
                'description' => 'The GEL-LYTE III OG sneaker emerges once again with its original shape and construction.',
                'category_id' => $sneakerCategory->id,
            ],
            [
                'name' => 'Nike Air Max 90',
                'price' => 149.99,
                'description' => 'Nothing as fly, nothing as comfortable, nothing as proven. The Nike Air Max 90 stays true to its roots.',
                'category_id' => $sneakerCategory->id,
            ],
            [
                'name' => 'Adidas Forum Low',
                'price' => 99.99,
                'description' => 'More than just a shoe, it\'s a statement. The Adidas Forum hit the scene in \'84.',
                'category_id' => $nieuwCategory->id,
            ],
            [
                'name' => 'New Balance 2002R',
                'price' => 149.99,
                'description' => 'The New Balance 2002R lifestyle shoe draws inspiration from the performance running shoes of the 2000s.',
                'category_id' => $sneakerCategory->id,
            ],
            [
                'name' => 'Nike Air Jordan 4 Retro',
                'price' => 209.99,
                'description' => 'One of the most popular models of the Jordan family, the AJ4 is a true masterpiece of design.',
                'category_id' => $nieuwCategory->id,
            ],
            [
                'name' => 'Adidas Samba OG',
                'price' => 119.99,
                'description' => 'Born on the pitch, the Samba is a timeless icon of street style.',
                'category_id' => $sneakerCategory->id,
            ],
            [
                'name' => 'Nike Blazer Mid \'77',
                'price' => 109.99,
                'description' => 'In the \'70s, Nike was the new shoe on the block. So new in fact, we were still breaking into the basketball scene.',
                'category_id' => $saleCategory->id,
            ],
            [
                'name' => 'Vans Sk8-Hi',
                'price' => 84.99,
                'description' => 'The Sk8-Hi was introduced in 1978 as Style 38, and showcased the now iconic Vans Sidestripe.',
                'category_id' => $sneakerCategory->id,
            ],
            [
                'name' => 'Salomon XT-6',
                'price' => 174.99,
                'description' => 'Launched in 2013, the XT-6 is the preferred footwear of world-class athletes for ultra-distance races.',
                'category_id' => $nieuwCategory->id,
            ],
            [
                'name' => 'Hoka Clifton 9',
                'price' => 149.99,
                'description' => 'The ninth iteration of our award-winning Clifton franchise has launched, lighter and more cushioned than ever.',
                'category_id' => $sneakerCategory->id,
            ],
            [
                'name' => 'New Balance 990v6',
                'price' => 239.99,
                'description' => 'The designers of the first 990 were tasked with creating the single best running shoe on the market.',
                'category_id' => $nieuwCategory->id,
            ],
        ];

        foreach ($products as $productData) {
            Product::factory()->create($productData);
        }
    }
}
