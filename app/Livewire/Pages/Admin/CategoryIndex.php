<?php

namespace App\Livewire\Pages\Admin;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Flux\Flux;

#[Layout('components.layouts.admin')]
class CategoryIndex extends Component
{
    use WithPagination;

    public string $search = '';

    /**
     * Handle category deletion.
     */
    public function delete(Category $category): void
    {
        $category->delete();
        // Flux::toast('Categorie succesvol verwijderd.'); // Assuming Flux toast helper
    }

    #[Computed]
    public function categories()
    {
        return Category::query()
            ->when($this->search, fn ($query) => $query->where('name', 'like', "%{$this->search}%"))
            ->withCount('products')
            ->latest()
            ->paginate(10);
    }

    public function render()
    {
        return <<<'HTML'
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Categorieën</h1>
                        <p class="text-sm text-gray-500">Beheer de productcategorieën van je shop.</p>
                    </div>
                    <flux:button href="{{ route('dashboard.categories.create') }}" icon="plus" variant="primary">
                        Nieuwe Categorie
                    </flux:button>
                </div>

                <div class="mb-4">
                    <flux:input wire:model.live.debounce.300ms="search" placeholder="Zoek op naam..." icon="magnifying-glass" />
                </div>

                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Naam</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producten</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acties</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($this->categories as $category)
                                <tr wire:key="{{ $category->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $category->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $category->slug }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $category->products_count }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <flux:button href="{{ route('dashboard.categories.edit', $category) }}" icon="pencil-square" variant="ghost" size="sm" />
                                        <flux:button wire:click="delete({{ $category->id }})" wire:confirm="Weet je zeker dat je deze categorie wilt verwijderen?" icon="trash" variant="danger" size="sm" />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-500">
                                        Geen categorieën gevonden.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $this->categories->links() }}
                </div>
            </div>
        HTML;
    }
}
