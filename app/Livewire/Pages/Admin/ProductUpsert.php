<?php

namespace App\Livewire\Pages\Admin;

use App\Actions\Products\StoreProductAction;
use App\Livewire\Forms\ProductForm;
use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;

#[Layout('components.layouts.admin')]
class ProductUpsert extends Component
{
    public ProductForm $form;
    public ?Product $product = null;

    public function mount(?Product $product = null): void
    {
        if ($product && $product->exists) {
            $this->product = $product;
            $this->form->setProduct($product);
        }
    }

    public function save(StoreProductAction $action): void
    {
        $this->form->validate();

        $action->handle($this->form->all(), $this->product);

        session()->flash('status', 'Product succesvol opgeslagen.');

        $this->redirectRoute('dashboard.products.index', navigate: true);
    }

    #[Computed]
    public function categories()
    {
        return Category::orderBy('name')->get();
    }

    public function render()
    {
        return <<<'HTML'
            <div class="p-6 max-w-4xl mx-auto">
                <div class="mb-6">
                    <flux:button href="{{ route('dashboard.products.index') }}" icon="chevron-left" variant="ghost">Terug naar overzicht</flux:button>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white mt-2">{{ $this->product ? 'Product Bewerken' : 'Nieuw Product' }}</h1>
                </div>

                <div class="bg-white dark:bg-forest-black p-6 shadow rounded-lg">
                    <form wire:submit="save" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <flux:input wire:model="form.name" label="Naam" placeholder="Bijv. Nike Air Max" />
                            <flux:input wire:model="form.slug" label="Slug" placeholder="bijv. nike-air-max" hint="Laat leeg om automatisch te genereren." />
                        </div>

                        <flux:select wire:model="form.category_id" label="Categorie" placeholder="Kies een categorie...">
                            @foreach($this->categories as $category)
                                <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
                            @endforeach
                        </flux:select>

                        <flux:textarea wire:model="form.description" label="Beschrijving" placeholder="Uitgebreide omschrijving..." />
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <flux:input type="number" step="0.01" wire:model="form.price" label="Prijs" placeholder="100.00" />
                            <flux:input type="number" wire:model="form.stock" label="Voorraad" placeholder="10" />
                        </div>

                        <div class="flex items-center gap-4">
                            <flux:switch wire:model.boolean="form.is_active" label="Actief" />
                            <flux:switch wire:model.boolean="form.is_featured" label="Uitgelicht" />
                        </div>

                        <div class="flex justify-end pt-4">
                            <flux:button type="submit" variant="primary" icon="check">
                                {{ $this->product ? 'Bijwerken' : 'Opslaan' }}
                            </flux:button>
                        </div>
                    </form>
                </div>
            </div>
        HTML;
    }
}
