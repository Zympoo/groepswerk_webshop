<?php

use App\Models\Product;

/**
 * Unit Tests: Product::scopeAvailable()
 *
 * Verifies that the scopeAvailable() scope correctly filters the collection
 * to only return products that:
 * - Have stock > 0
 * - Are marked as active (is_active = true)
 *
 * Products that are out of stock OR inactive must be excluded.
 */

describe('Product::scopeAvailable()', function () {

    it('returns products that are in stock and active', function () {
        $available = Product::factory()->create([
            'stock'     => 10,
            'is_active' => true,
        ]);

        $results = Product::available()->get();

        expect($results)->toHaveCount(1)
            ->and($results->first()->id)->toBe($available->id);
    });

    it('excludes products with zero stock', function () {
        Product::factory()->create(['stock' => 0, 'is_active' => true]);

        $results = Product::available()->get();

        expect($results)->toBeEmpty();
    });

    it('excludes products that are inactive', function () {
        Product::factory()->create(['stock' => 10, 'is_active' => false]);

        $results = Product::available()->get();

        expect($results)->toBeEmpty();
    });

    it('excludes products that are both out of stock and inactive', function () {
        Product::factory()->create(['stock' => 0, 'is_active' => false]);

        $results = Product::available()->get();

        expect($results)->toBeEmpty();
    });

    it('returns only the available products from a mixed set', function () {
        $available1 = Product::factory()->create(['stock' => 5,  'is_active' => true]);
        $available2 = Product::factory()->create(['stock' => 1,  'is_active' => true]);
        Product::factory()->create(['stock' => 0,  'is_active' => true]);  // out of stock
        Product::factory()->create(['stock' => 10, 'is_active' => false]); // inactive

        $results = Product::available()->pluck('id');

        expect($results)->toHaveCount(2)
            ->and($results)->toContain($available1->id)
            ->and($results)->toContain($available2->id);
    });

    it('correctly handles the boundary: stock of 1 is considered available', function () {
        $product = Product::factory()->create(['stock' => 1, 'is_active' => true]);

        $results = Product::available()->get();

        expect($results)->toHaveCount(1)
            ->and($results->first()->id)->toBe($product->id);
    });

});
