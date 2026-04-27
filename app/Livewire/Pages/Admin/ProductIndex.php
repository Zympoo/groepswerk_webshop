<?php

namespace App\Livewire\Pages\Admin;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Flux\Flux;

#[Layout('components.layouts.admin')]
class ProductIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showDeleted = false;

    /**
     * Handle product deletion.
     */
    public function delete(Product $product): void
    {
        $product->delete();
        Flux::toast('Product succesvol verwijderd.');
    }

    /**
     * Restore a soft-deleted product.
     */
    public function restore(int $id, \App\Actions\Products\RestoreProductAction $action): void
    {
        if ($action->handle($id)) {
            Flux::toast('Product succesvol hersteld.');
        } else {
            Flux::toast('Fout bij het herstellen van het product.', variant: 'danger');
        }
    }

    #[Computed]
    public function products()
    {
        return Product::query()
            ->with('category')
            ->when($this->showDeleted, fn($query) => $query->withTrashed())
            ->when($this->search, fn($query) => $query->where('name', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(10);
    }

    public function render()
    {
        return <<<'HTML'
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Productbeheer</h1>
                        <p class="text-sm text-gray-500">Beheer je assortiment en voorraad.</p>
                    </div>
                    <flux:button href="{{ route('dashboard.products.create') }}" icon="plus" variant="primary">
                        Nieuw Product
                    </flux:button>
                </div>

                <div class="mb-4 flex items-center justify-between gap-4">
                    <div class="flex-1">
                        <flux:input wire:model.live.debounce.300ms="search" placeholder="Zoek op naam..." icon="magnifying-glass" />
                    </div>
                    <flux:switch wire:model.live="showDeleted" label="Toon verwijderde items" />
                </div>

                <div class="bg-white dark:bg-forest-black shadow rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-teal-gray">
                        <thead class="bg-gray-50 dark:bg-deep-teal">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-silver-teal uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-silver-teal uppercase tracking-wider">Categorie</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-silver-teal uppercase tracking-wider">Prijs</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-silver-teal uppercase tracking-wider">Stock</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-silver-teal uppercase tracking-wider">Acties</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-forest-black divide-y divide-gray-200 dark:divide-teal-gray">
                             @forelse($this->products as $product)
                                <tr wire:key="{{ $product->id }}" class="{{ $product->trashed() ? 'opacity-50' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $product->name }}
                                            @if($product->trashed())
                                                <span class="ml-2 text-xs text-red-500 font-bold uppercase">(Verwijderd)</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-silver-teal">{{ $product->slug }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-silver-teal">
                                        {{ $product->category->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-bold">
                                        {{ $product->formattedPrice }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->stock > 5 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $product->stock }} op voorraad
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        @if($product->trashed())
                                            <flux:button wire:click="restore({{ $product->id }})" icon="arrow-path" variant="ghost" size="sm" />
                                        @else
                                            <flux:button href="{{ route('dashboard.products.edit', $product) }}" icon="pencil-square" variant="ghost" size="sm" />
                                            <flux:button wire:click="delete({{ $product->id }})" wire:confirm="Weet je zeker dat je dit product wilt verwijderen?" icon="trash" variant="danger" size="sm" />
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500 dark:text-silver-teal">
                                        Geen producten gevonden.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $this->products->links() }}
                </div>
            </div>
        HTML;
    }
}
