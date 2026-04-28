<?php

use App\Actions\Cart\AddItemToCartAction;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Support\Facades\Session;

/**
 * Cart Feature Tests
 *
 * Verifies that the guest cart (session-based) and the
 * authenticated cart (database-based) work as expected.
 */

describe('Guest Cart', function () {

    it('allows a guest to add a product to their session cart', function () {
        // Arrange: a simple active product with stock
        $product = Product::factory()->create(['stock' => 10, 'is_active' => true]);

        // Act: fire the action as a guest (no auth)
        app(AddItemToCartAction::class)->handle($product, 1);

        // Assert: the product is in the session cart
        $cart = Session::get(app(CartService::class)->getSession(), []);

        expect($cart)->toHaveKey($product->id)
            ->and($cart[$product->id]['quantity'])->toBe(1)
            ->and($cart[$product->id]['product_id'])->toBe($product->id);
    });

    it('accumulates quantity when the same product is added twice', function () {
        $product = Product::factory()->create(['stock' => 10, 'is_active' => true]);

        $action = app(AddItemToCartAction::class);
        $action->handle($product, 2);
        $action->handle($product, 3);

        $cart = Session::get(app(CartService::class)->getSession(), []);

        expect($cart[$product->id]['quantity'])->toBe(5);
    });

    it('does not exceed the product stock limit', function () {
        $product = Product::factory()->create(['stock' => 3, 'is_active' => true]);

        // Try to add more than available stock
        app(AddItemToCartAction::class)->handle($product, 99);

        $cart = Session::get(app(CartService::class)->getSession(), []);

        expect($cart[$product->id]['quantity'])->toBe(3);
    });

});
