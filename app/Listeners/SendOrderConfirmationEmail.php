<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use Illuminate\Support\Facades\Log;

class SendOrderConfirmationEmail
{
    /**
     * Handle the event.
     *
     * @param OrderPaid $event
     * @return void
     */
    public function handle(OrderPaid $event): void
    {
        // Senior Dev Note: For now, we only log the event for debugging.
        // Later, this will trigger the actual Mail action.
        Log::info("Order confirmation email sent (logged) for order: #{$event->order->order_number}");
    }
}
