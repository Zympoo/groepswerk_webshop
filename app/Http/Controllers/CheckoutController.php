<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Events\OrderPaid;
use App\Models\Order;
use App\Services\CartService;
use App\Services\StripeService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    use AuthorizesRequests;

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

    public function pay(Order $order, StripeService $stripe)
    {
        $this->authorize('view', $order);

        if ($order->status !== OrderStatus::PENDING) {
            abort(403, 'Order kan niet opnieuw betaald worden.');
        }

        $lineItems = $order->details->map(fn ($item) => [
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => $item->product_name_snapshot,
                ],
                'unit_amount' => $item->price_snapshot * 100,
            ],
            'quantity' => $item->quantity,
        ])->toArray();

        $session = $stripe->createCheckoutSession(
            $lineItems,
            route('checkout.success'),
            route('checkout.cancel')
        );

        $order->update([
            'stripe_session_id' => $session->id,
        ]);

        return redirect()->away($session->url);
    }

    public function cancel()
    {
        return view('checkout.cancel');
    }
}
