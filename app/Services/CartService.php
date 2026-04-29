<?php

namespace App\Services;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected string $sessionKey = 'cart';

    public function getCart()
    {
        return Auth::check()
            ? Cart::firstOrCreate(['user_id' => Auth::id()])
            : null;
    }

    public function getItems()
    {
        if (Auth::check()) {
            return $this->getCart()
                ?->items()
                ->with('product')
                ->get()
                ->map(function ($item) {

                    return (object) [
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'product' => (object) [
                            'name' => $item->product->name,
                            'price' => $item->product->price,
                            'stock' => $item->product->stock,
                        ],
                    ];
                }) ?? collect();
        }

        return collect(Session::get($this->sessionKey, []))
            ->map(function ($item) {

                return (object) [
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'product' => (object) [
                        'name' => $item['name'],
                        'price' => $item['price'],
                        'stock' => $item['stock'] ?? 0,
                    ],
                ];
            });
    }

    public function clear(): void
    {
        if (Auth::check()) {
            $cart = $this->getCart();

            if ($cart) {
                $cart->items()->delete();
            }
        } else {
            Session::forget($this->sessionKey);
        }
    }

    public function getSession(): string
    {
        return $this->sessionKey;
    }
}
