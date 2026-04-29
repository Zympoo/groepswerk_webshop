<?php

namespace App\Actions\Orders;

use App\Models\Order;
use App\Enums\OrderStatus;
use App\Events\OrderPaid;
use Illuminate\Support\Facades\Gate;

class UpdateOrderStatusAction
{
    /**
     * Update the status of an order and trigger relevant events.
     * 
     * @param Order $order
     * @param OrderStatus $newStatus
     * @return Order
     */
    public function handle(Order $order, OrderStatus $newStatus): Order
    {
        Gate::authorize('update', $order);

        $oldStatus = $order->status;
        $order->status = $newStatus;
        $order->save();

        // If status changed to PAID, dispatch OrderPaid event
        if ($oldStatus !== OrderStatus::PAID && $newStatus === OrderStatus::PAID) {
            OrderPaid::dispatch($order);
        }

        return $order;
    }
}
