<?php

use App\Actions\Cart\AddItemToCartAction;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Product;
use App\Services\CartService;

new #[Layout('components.layouts.app')]
class extends Component {

    public Product $product;

    public int $quantity = 1;

    public function mount(Product $product)
    {
        $this->product = $product->load('category');

        abort_if(!$this->product->is_active, 404);

        $this->quantity = 1;
    }

    public function increment()
    {
        if ($this->quantity < $this->product->stock) {
            $this->quantity++;
        }
    }

    public function decrement()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function updatedQuantity($value)
    {
        $value = (int)$value;

        if ($this->product->stock <= 0) {
            $this->quantity = 0;
            return;
        }

        $this->quantity = max(1, min($value, $this->product->stock));
    }

    public function addToCart(AddItemToCartAction $action)
    {
        $action->handle($this->product, $this->quantity);

        $this->dispatch('cart-updated');
    }
};
?>

<div class="min-h-screen bg-white py-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-12">
            <a href="/products" class="text-action-blue hover:underline">
                ← Terug naar overzicht
            </a>
        </div>

        <div class="grid md:grid-cols-2 gap-12">

            <!-- IMAGE -->
            <div
                class="bg-light-input rounded-[16px] overflow-hidden border border-silver-teal aspect-square flex items-center justify-center">
                @if($product->image)
                    <img
                        src="{{ asset('storage/' . $product->image) }}"
                        class="object-cover w-full h-full"
                    >
                @else
                    <span class="text-silver-teal uppercase tracking-widest">
                        Geen afbeelding
                    </span>
                @endif
            </div>

            <!-- DETAILS -->
            <div class="flex flex-col">

                <span class="tech-label text-cool-gray mb-2">
                    {{ $product->category->name ?? 'Geen categorie' }}
                </span>

                <h1 class="text-[36px] font-medium mb-4">
                    {{ $product->name }}
                </h1>

                <p class="text-cool-gray mb-6">
                    {{ $product->description }}
                </p>

                <div class="text-[28px] font-semibold mb-6">
                    €{{ number_format($product->price, 2, ',', '.') }}
                </div>

                @if($product->stock > 0)
                    <span class="text-green-600 mb-4">
                        Op voorraad ({{ $product->stock }})
                    </span>
                @else
                    <span class="text-red-500 mb-4">
                        Uitverkocht
                    </span>
                @endif

                <!-- QUANTITY SELECTOR -->
                <div class="flex items-center gap-4 mb-6">

                    <button
                        wire:click="decrement"
                        class="px-3 py-1 border border-silver-teal rounded"
                        @disabled($product->stock <= 0)
                    >
                        -
                    </button>

                    <input
                        type="number"
                        min="1"
                        max="{{ $product->stock }}"
                        wire:model.live="quantity"
                        class="w-20 border border-silver-teal rounded px-3 py-2 text-center"
                        @disabled($product->stock <= 0)
                    >

                    <button
                        wire:click="increment"
                        class="px-3 py-1 border border-silver-teal rounded"
                        @disabled($product->stock <= 0)
                    >
                        +
                    </button>

                </div>

                <!-- ADD TO CART -->
                <button
                    wire:click="addToCart"
                    @disabled($product->stock <= 0)
                    class="bg-mongo-dark-green text-white rounded-full px-8 py-3 font-bold hover:scale-105 transition-transform shadow-md w-fit disabled:opacity-50"
                >
                    In winkelmandje ({{ $quantity }})
                </button>

            </div>

        </div>
    </div>
</div>
