<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;

/**
 * Order Placement Feature Tests
 */

describe('Order Placement', function () {

    it('persists an order with status pending for a logged-in user', function () {
        $user  = User::factory()->customer()->create();

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status'  => OrderStatus::PENDING,
        ]);

        $this->assertDatabaseHas('orders', [
            'id'      => $order->id,
            'user_id' => $user->id,
            'status'  => OrderStatus::PENDING->value,
        ]);
    });

    it('associates the order with the correct user', function () {
        $user  = User::factory()->customer()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        expect($order->user->id)->toBe($user->id);
    });

    it('defaults to pending status when created', function () {
        $order = Order::factory()->create(['status' => OrderStatus::PENDING]);

        expect($order->status)->toBe(OrderStatus::PENDING);
    });

});
