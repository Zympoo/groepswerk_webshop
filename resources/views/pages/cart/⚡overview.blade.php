<?php

use App\Actions\Cart\RemoveCartItemAction;
use App\Actions\Cart\UpdateCartItemAction;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Services\CartService;

new #[Layout('components.layouts.app')]
class extends Component {

    public function updateQuantity(UpdateCartItemAction $action, int $productId, $quantity)
    {
        $action->handle($productId, (int)$quantity);
    }

    public function removeItem(RemoveCartItemAction $action, int $productId)
    {
        $action->handle($productId);
    }

    #[Computed]
    public function cartItems()
    {
        return app(CartService::class)->getItems();
    }

    #[Computed]
    public function total()
    {
        return $this->cartItems->reduce(function ($carry, $item) {
            return $carry + ($item->product->price * $item->quantity);
        }, 0);
    }
};
?>

<div class="min-h-screen bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-12">
            <span class="tech-label text-mongo-dark-green mb-2 block">Winkelmand</span>
            <h2 class="text-[36px] font-medium leading-tight">
                Jouw <span class="mongo-underline">Cart</span>
            </h2>
        </div>

        <div class="space-y-6">

            @forelse($this->cartItems as $item)
                <div class="bg-white border border-silver-teal rounded-[16px] shadow-forest p-6 flex items-center justify-between">

                    <div>
                        <h3 class="text-[20px] font-medium">
                            {{ $item->product->name }}
                        </h3>

                        <p class="text-cool-gray">
                            €{{ number_format($item->product->price, 2, ',', '.') }}
                        </p>
                    </div>

                    <div class="flex items-center gap-4">

                        <input
                            type="number"
                            min="1"
                            max="{{ $item->product->stock ?? 999 }}"
                            value="{{ $item->quantity }}"
                            wire:change="updateQuantity({{ $item->product_id }}, $event.target.value)"
                            class="border border-silver-teal rounded px-3 py-2 w-20"
                        />

                        <button
                            wire:click="removeItem({{ $item->product_id }})"
                            class="text-red-500 font-bold hover:underline"
                        >
                            Verwijder
                        </button>

                    </div>
                </div>
            @empty
                <p class="text-center text-cool-gray py-12">
                    Je winkelmand is leeg.
                </p>
            @endforelse

        </div>

        <div class="mt-10 flex justify-end">
            <div class="text-right">
                <p class="text-[20px] font-medium">
                    Totaal: €{{ number_format($this->total, 2, ',', '.') }}
                </p>

                <button class="mt-4 bg-mongo-dark-green text-white rounded-full px-6 py-3 font-bold">
                    Afrekenen
                </button>
            </div>
        </div>

    </div>
</div>
