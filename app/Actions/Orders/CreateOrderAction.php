<?php

namespace App\Actions\Orders;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Services\StripeService;
use Illuminate\Support\Str;

class CreateOrderAction
{
    public function __construct(
        protected StripeService $stripe
    ) {}

    public function handle(array $cart, array $address, ?int $userId): string
    {
        $order = Order::create([
            'user_id' => $userId,
            'order_number' => Str::uuid(),
            'total_amount' => $this->calculateTotal($cart),
            'status' => OrderStatus::PENDING,
            'address_details' => $address,
        ]);

        foreach ($cart as $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'product_name_snapshot' => $item['name'],
                'price_snapshot' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['price'] * $item['quantity'],
            ]);
        }

        $lineItems = collect($cart)
            ->values()
            ->map(fn ($item) => [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                    'unit_amount' => $item['price'] * 100,
                ],
                'quantity' => $item['quantity'],
            ])->toArray();

        $session = $this->stripe->createCheckoutSession(
            $lineItems,
            route('checkout.success'),
            route('checkout.cancel')
        );

        $order->update([
            'stripe_session_id' => $session->id,
        ]);

        return $session->url;
    }

    private function calculateTotal(array $cart): float
    {
        return collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
    }
}
