<?php

namespace App\Actions\Products;

use App\Models\Product;
use Illuminate\Support\Str;

class StoreProductAction
{
    /**
     * Create or update a product.
     * 
     * @param array $data
     * @param Product|null $product
     * @return Product
     */
    public function handle(array $data, ?Product $product = null): Product
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        if ($product) {
            $product->update($data);
            return $product;
        }

        return Product::create($data);
    }
}
