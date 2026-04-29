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

        // Handle image upload if a new one is provided
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            // Delete old image if it exists
            if ($product && $product->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
            }
            
            // Store the new image
            $data['image'] = $data['image']->store('products', 'public');
        } else {
            // Remove image from data if not uploading a new one, 
            // so we don't accidentally overwrite the existing path with null
            unset($data['image']);
        }

        if ($product) {
            $product->update($data);
            return $product;
        }

        return Product::create($data);
    }
}
