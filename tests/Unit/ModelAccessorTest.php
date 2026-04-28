<?php

use App\Models\Order;
use App\Models\Product;

/**
 * Unit Tests: Model Accessors / Attributes
 *
 * Verifies that the computed accessors on Product and Order correctly
 * format their values with the expected currency symbol and locale notation.
 *
 * These tests are pure model logic — no HTTP, no Livewire, no policies.
 */

describe('Product formattedPrice accessor', function () {

    it('formats a whole-number price correctly', function () {
        $product = Product::factory()->make(['price' => 100.00]);

        expect($product->formattedPrice)->toBe('€ 100,00');
    });

    it('formats a decimal price with two decimal places', function () {
        $product = Product::factory()->make(['price' => 119.99]);

        expect($product->formattedPrice)->toBe('€ 119,99');
    });

    it('formats a price with thousands separator correctly', function () {
        // 1000.00 should format as € 1.000,00 using Dutch notation
        $product = Product::factory()->make(['price' => 1000.00]);

        expect($product->formattedPrice)->toBe('€ 1.000,00');
    });

    it('formats a low price correctly', function () {
        $product = Product::factory()->make(['price' => 9.99]);

        expect($product->formattedPrice)->toBe('€ 9,99');
    });

    it('formats zero price correctly', function () {
        $product = Product::factory()->make(['price' => 0.00]);

        expect($product->formattedPrice)->toBe('€ 0,00');
    });

});

describe('Order formattedTotal accessor', function () {

    it('formats a standard order total correctly', function () {
        $order = Order::factory()->make(['total_amount' => 250.00]);

        expect($order->formattedTotal)->toBe('€ 250,00');
    });

    it('formats a total with thousands separator correctly', function () {
        $order = Order::factory()->make(['total_amount' => 1500.50]);

        expect($order->formattedTotal)->toBe('€ 1.500,50');
    });

    it('formats a zero total correctly', function () {
        $order = Order::factory()->make(['total_amount' => 0.00]);

        expect($order->formattedTotal)->toBe('€ 0,00');
    });

});
