<?php

namespace App\Actions\Cart;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;

class UpdateCartItemAction
{
    public function __construct(
        private CartService $cartService
    ) {}

    public function handle(int $productId, int $quantity): void
    {
        $product = Product::findOrFail($productId);

        $quantity = max(1, min($quantity, $product->stock));

        if (Auth::check()) {
            $cart = $this->cartService->getCart();

            $item = $cart->items()
                ->where('product_id', $productId)
                ->first();

            if (!$item) return;

            $item->update(['quantity' => $quantity]);
            return;
        }

        $cart = session($this->cartService->getSession(), []);

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId]['quantity'] = $quantity;
        }

        session()->put($this->cartService->getSession(), $cart);
    }
}
