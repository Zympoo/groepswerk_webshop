<?php

namespace App\Actions\Cart;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AddItemToCartAction
{
    public function __construct(
        private CartService $cartService
    ) {}

    public function handle(Product $product, int $quantity): void
    {
        $quantity = max(1, min($quantity, $product->stock));

        if (Auth::check()) {
            $cart = $this->cartService->getCart();

            $item = $cart->items()->firstOrNew([
                'product_id' => $product->id,
            ]);

            $item->quantity = min(
                ($item->quantity ?? 0) + $quantity,
                $product->stock
            );

            $item->save();

            return;
        }

        $cart = Session::get($this->cartService->getSession(), []);

        if (!isset($cart[$product->id])) {
            $cart[$product->id] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'stock' => $product->stock,
                'quantity' => 0,
            ];
        }

        $cart[$product->id]['quantity'] = min(
            $cart[$product->id]['quantity'] + $quantity,
            $product->stock
        );

        Session::put($this->cartService->getSession(), $cart);
    }
}
