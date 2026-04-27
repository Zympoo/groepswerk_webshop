<?php

use App\Models\Category;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;

new #[Layout('components.layouts.app')]
class extends Component {
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public ?int $category = null;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }

    #[Computed]
    public function products()
    {
        return Product::query()
            ->with('category')
            ->where('is_active', true)
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->category, function ($query) {
                $query->where('category_id', $this->category);
            })
            ->latest()
            ->paginate(9);
    }

    #[Computed]
    public function categories()
    {
        return Category::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }
};
?>

<div class="min-h-screen bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-12 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <span class="tech-label text-mongo-dark-green mb-2 block">Catalogus</span>
                <h2 class="text-[36px] font-medium leading-tight">
                    Onze <span class="mongo-underline">Producten</span>
                </h2>
            </div>

            <div class="flex flex-col sm:flex-row gap-4">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Zoek op productnaam..."
                    class="border border-silver-teal rounded px-4 py-2 focus:border-action-blue outline-none"
                >

                <select
                    wire:model.live="category"
                    class="border border-silver-teal rounded px-4 py-2 bg-white outline-none focus:border-action-blue"
                >
                    <option value="">Alle categorieën</option>

                    @foreach($this->categories as $cat)
                        <option value="{{ $cat->id }}">
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($this->products as $product)
                <div
                    class="bg-white border border-silver-teal rounded-[16px] shadow-forest overflow-hidden transition-transform hover:-translate-y-1 flex flex-col">

                    <div
                        class="bg-light-input aspect-video p-6 flex items-center justify-center border-b border-silver-teal">
                        <span class="text-silver-teal font-medium uppercase tracking-widest">
                            Product_Image
                        </span>
                    </div>

                    <div class="p-6 flex flex-col flex-1">
                        <span class="tech-label text-cool-gray mb-1 block">
                            {{ $product->category?->name ?? 'Geen categorie' }}
                        </span>

                        <h3 class="text-[24px] font-medium mb-2">
                            <a href="/products/{{ $product->slug }}" class="hover:text-action-blue">
                                {{ $product->name }}
                            </a>
                        </h3>

                        <p class="text-[16px] font-light text-cool-gray mb-6 line-clamp-2">
                            {{ $product->description }}
                        </p>

                        <div class="flex items-center justify-between mt-auto">
                            <span class="text-[20px] font-medium">
                                €{{ number_format($product->price, 2, ',', '.') }}
                            </span>

                            <button
                                class="bg-mongo-dark-green text-white rounded-full px-6 py-2 font-bold hover:scale-105 transition-transform shadow-md">
                                In winkelmandje
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-center py-12 text-cool-gray">
                    Geen producten gevonden.
                </p>
            @endforelse
        </div>
        <div class="mt-10 flex justify-center">
            {{ $this->products->links() }}
        </div>
    </div>
</div>
