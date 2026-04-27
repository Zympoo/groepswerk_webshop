<?php

namespace App\Actions\Products;

use App\Models\Product;

class RestoreProductAction
{
    /**
     * Restore a soft-deleted product.
     * 
     * @param int $id
     * @return bool
     */
    public function handle(int $id): bool
    {
        $product = Product::onlyTrashed()->find($id);

        if (!$product) {
            return false;
        }

        return $product->restore();
    }
}
