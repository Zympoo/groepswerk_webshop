<?php

use App\Models\Order;
use App\Models\User;

/**
 * Order Authorization Feature Tests
 *
 * Verifies the OrderPolicy:
 * - A user can see their own orders.
 * - A user receives a 403 when trying to view another user's order.
 * - An admin can view any order.
 */

describe('Order Authorization (Policy)', function () {

    it('allows a user to view their own order', function () {
        $user  = User::factory()->customer()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        expect($user->can('view', $order))->toBeTrue();
    });

    it('blocks a user from viewing another user\'s order via policy', function () {
        $owner = User::factory()->customer()->create();
        $other = User::factory()->customer()->create();
        $order = Order::factory()->create(['user_id' => $owner->id]);

        expect($other->can('view', $order))->toBeFalse();
    });

    it('blocks a customer from accessing the admin orders list via middleware', function () {
        $customer = User::factory()->customer()->create();

        $this->actingAs($customer)
            ->get(route('dashboard.orders.index'))
            ->assertForbidden();
    });

    it('returns 403 when a customer accesses an admin order detail page', function () {
        $customer = User::factory()->customer()->create();
        $order    = Order::factory()->create();

        $this->actingAs($customer)
            ->get(route('dashboard.orders.show', $order))
            ->assertForbidden();
    });

    it('allows an admin to view any order', function () {
        $admin = User::factory()->admin()->create();
        $order = Order::factory()->create();

        expect($admin->can('view', $order))->toBeTrue();
    });

});
