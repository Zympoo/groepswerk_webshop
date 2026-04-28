<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Events\OrderPaid;
use App\Models\Order;
use App\Services\CartService;
use App\Services\StripeService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function success(Request $request, StripeService $stripe, CartService $cartService)
    {
        $sessionId = $request->query('session_id');

        if (! $sessionId) {
            return redirect()->route('checkout.cancel');
        }

        $session = $stripe->retrieveSession($sessionId);

        $order = Order::where('stripe_session_id', $sessionId)->first();

        if (! $order) {
            logger()->error('Order not found for session: '.$sessionId);

            return redirect()->route('checkout.cancel');
        }

        if ($session->payment_status !== 'paid') {
            return redirect()->route('checkout.cancel');
        }

        if ($order->status !== OrderStatus::PAID) {
            $order->update([
                'status' => OrderStatus::PAID,
                'stripe_payment_intent_id' => $session->payment_intent,
            ]);

            OrderPaid::dispatch($order);

            $cartService->clear();
        }

        return view('checkout.success', compact('order'));
    }

    public function cancel()
    {
        return view('checkout.cancel');
    }
}
