<?php

use App\Actions\Orders\UpdateOrderStatusAction;
use App\Enums\OrderStatus;
use App\Events\OrderPaid;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Event;

/**
 * Unit Tests: UpdateOrderStatusAction
 *
 * Verifies the action's core business logic in isolation:
 * - Status is persisted correctly in the database.
 * - The OrderPaid event is dispatched exactly once when transitioning to PAID.
 * - The OrderPaid event is NOT dispatched for other status transitions.
 * - Repeated calls to set PAID do not re-dispatch the event.
 *
 * Gate::authorize() is bypassed using Gate::before() so tests don't
 * require a real policy setup — we're testing the action, not the gate.
 */

describe('UpdateOrderStatusAction', function () {

    /**
     * Act as an admin before each test so Gate::authorize('update', $order)
     * inside the action passes without needing a real Stripe integration.
     * The policy/gate logic itself is covered in Feature tests.
     */
    beforeEach(function () {
        $this->actingAs(User::factory()->admin()->create());
    });

    it('updates the order status in the database', function () {
        /** @var Order $order */
        $order = Order::factory()->create(['status' => OrderStatus::PENDING]);

        $updated = app(UpdateOrderStatusAction::class)->handle($order, OrderStatus::SHIPPED);

        expect($updated->status)->toBe(OrderStatus::SHIPPED);
        $this->assertDatabaseHas('orders', [
            'id'     => $order->id,
            'status' => OrderStatus::SHIPPED->value,
        ]);
    });

    it('returns the updated Order model instance', function () {
        $order = Order::factory()->create(['status' => OrderStatus::PENDING]);

        $result = app(UpdateOrderStatusAction::class)->handle($order, OrderStatus::CANCELLED);

        expect($result)->toBeInstanceOf(Order::class)
            ->and($result->id)->toBe($order->id);
    });

    it('dispatches the OrderPaid event when transitioning to PAID', function () {
        Event::fake([OrderPaid::class]);

        $order = Order::factory()->create(['status' => OrderStatus::PENDING]);

        app(UpdateOrderStatusAction::class)->handle($order, OrderStatus::PAID);

        Event::assertDispatched(OrderPaid::class, function (OrderPaid $event) use ($order) {
            return $event->order->id === $order->id;
        });
    });

    it('dispatches the OrderPaid event exactly once', function () {
        Event::fake([OrderPaid::class]);

        $order = Order::factory()->create(['status' => OrderStatus::PENDING]);

        app(UpdateOrderStatusAction::class)->handle($order, OrderStatus::PAID);

        Event::assertDispatchedTimes(OrderPaid::class, 1);
    });

    it('does NOT dispatch the OrderPaid event for non-PAID transitions', function () {
        Event::fake([OrderPaid::class]);

        $order = Order::factory()->create(['status' => OrderStatus::PENDING]);

        app(UpdateOrderStatusAction::class)->handle($order, OrderStatus::SHIPPED);
        app(UpdateOrderStatusAction::class)->handle($order, OrderStatus::CANCELLED);

        Event::assertNotDispatched(OrderPaid::class);
    });

    it('does NOT re-dispatch OrderPaid when order is already PAID', function () {
        Event::fake([OrderPaid::class]);

        // Order already has status PAID
        $order = Order::factory()->create(['status' => OrderStatus::PAID]);

        app(UpdateOrderStatusAction::class)->handle($order, OrderStatus::PAID);

        Event::assertNotDispatched(OrderPaid::class);
    });

});
