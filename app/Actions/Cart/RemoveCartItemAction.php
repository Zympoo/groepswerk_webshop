<?php

namespace App\Actions\Cart;

use App\Services\CartService;
use Illuminate\Support\Facades\Auth;

class RemoveCartItemAction
{
    public function __construct(
        private CartService $cartService
    ) {}

    public function handle(int $productId): void
    {
        if (Auth::check()) {
            $cart = $this->cartService->getCart();

            $cart->items()
                ->where('product_id', $productId)
                ->delete();

            return;
        }

        $cart = session($this->cartService->getSession(), []);

        unset($cart[$productId]);

        session()->put($this->cartService->getSession(), $cart);
    }
}
