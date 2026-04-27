<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Product;

new #[Layout('components.layouts.app')]
class extends Component {

    public Product $product;

    public function mount(Product $product)
    {
        // Zorg dat category geladen is
        $this->product = $product->load('category');

        // Optioneel: blokkeer inactieve producten
        abort_if(!$this->product->is_active, 404);
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

            <div class="bg-light-input rounded-[16px] overflow-hidden border border-silver-teal aspect-square flex items-center justify-center">
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
                    <span class="text-green-600 mb-4">Op voorraad</span>
                @else
                    <span class="text-red-500 mb-4">Uitverkocht</span>
                @endif

                <button
                    class="bg-mongo-dark-green text-white rounded-full px-8 py-3 font-bold hover:scale-105 transition-transform shadow-md w-fit"
                >
                    In winkelmandje
                </button>

            </div>
        </div>
    </div>
</div>
