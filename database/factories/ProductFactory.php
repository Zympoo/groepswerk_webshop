<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);
        
        // Senior logic: Generate and save placeholder image
        $imageName = $this->generatePlaceholderImage($name);

        return [
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph,
            'price' => $this->faker->numberBetween(1000, 50000) / 100, // Decimal-safe random price
            'stock' => $this->faker->numberBetween(0, 100),
            'image' => $imageName,
            'is_active' => true,
        ];
    }

    /**
     * Senior approach: Save placeholder image to storage and return filename.
     */
    private function generatePlaceholderImage(string $name): ?string
    {
        $directory = 'products';
        
        // Ensure directory exists
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        $filename = Str::slug($name) . '.png';
        $path = "{$directory}/{$filename}";

        try {
            // Generate placeholder image from placehold.co
            // Using a background color and text for better visual representation
            $response = Http::withoutVerifying()->get("https://placehold.co/600x400/000000/FFFFFF/png?text=" . urlencode($name));
            
            if ($response->successful()) {
                Storage::disk('public')->put($path, $response->body());
                return $path;
            }
        } catch (\Exception $e) {
            // Fallback if HTTP request fails
            return null;
        }

        return null;
    }
}
