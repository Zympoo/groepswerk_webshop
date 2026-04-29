<?php

namespace App\Livewire\Pages\Admin;

use App\Actions\Products\StoreProductAction;
use App\Livewire\Forms\ProductForm;
use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;

#[Layout('components.layouts.admin')]
class ProductUpsert extends Component
{
    use WithFileUploads;

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
                    <form wire:submit="save" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <flux:input wire:model="form.name" label="Naam" placeholder="Bijv. Nike Air Max" />
                                    <flux:input wire:model="form.slug" label="Slug" placeholder="bijv. nike-air-max" hint="Laat leeg om automatisch te genereren." />
                                </div>

                                <flux:select wire:model="form.category_id" label="Categorie" placeholder="Kies een categorie...">
                                    @foreach($this->categories as $category)
                                        <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
                                    @endforeach
                                </flux:select>

                                <flux:textarea wire:model="form.description" label="Beschrijving" placeholder="Uitgebreide omschrijving..." rows="5" />
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <flux:input type="number" step="0.01" wire:model="form.price" label="Prijs" placeholder="100.00" />
                                    <flux:input type="number" wire:model="form.stock" label="Voorraad" placeholder="10" />
                                </div>

                                <div class="flex items-center gap-4">
                                    <flux:switch wire:model.boolean="form.is_active" label="Actief" />
                                    <flux:switch wire:model.boolean="form.is_featured" label="Uitgelicht" />
                                </div>
                            </div>

                            <div class="space-y-4">
                                <flux:label>Product Afbeelding</flux:label>
                                
                                <div 
                                    x-data="{ dragging: false }"
                                    x-on:dragover.prevent="dragging = true"
                                    x-on:dragleave.prevent="dragging = false"
                                    x-on:drop.prevent="dragging = false; $refs.fileInput.files = $event.dataTransfer.files; $refs.fileInput.dispatchEvent(new Event('change'))"
                                    class="border-2 border-dashed rounded-lg p-4 flex flex-col items-center justify-center min-h-[300px] relative overflow-hidden transition-colors"
                                    :class="dragging ? 'border-action-blue bg-blue-50 dark:bg-action-blue/10' : 'border-gray-300 dark:border-teal-gray bg-gray-50 dark:bg-deep-teal'"
                                >
                                    @if ($form->image)
                                        <img src="{{ $form->image->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                            <label for="image-upload" class="cursor-pointer bg-white dark:bg-forest-black px-4 py-2 rounded-lg shadow font-medium text-sm">Wijzigen</label>
                                            <flux:button wire:click="$set('form.image', null)" icon="trash" variant="danger" size="sm">Verwijderen</flux:button>
                                        </div>
                                    @elseif ($product && $product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" class="absolute inset-0 w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <label for="image-upload" class="cursor-pointer bg-white dark:bg-forest-black px-4 py-2 rounded-lg shadow font-medium">Nieuwe foto uploaden</label>
                                        </div>
                                    @else
                                        <div class="text-center pointer-events-none">
                                            <flux:icon icon="photo" class="mx-auto h-12 w-12 text-gray-400" />
                                            <div class="mt-4 flex text-sm text-gray-600 dark:text-silver-teal">
                                                <span class="relative cursor-pointer rounded-md font-medium text-action-blue hover:text-indigo-500">
                                                    Upload een bestand
                                                </span>
                                                <p class="pl-1">of sleep hierheen</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                                        </div>
                                        <label for="image-upload" class="absolute inset-0 cursor-pointer"></label>
                                    @endif

                                    <input x-ref="fileInput" id="image-upload" type="file" wire:model="form.image" class="sr-only">
                                </div>
                                <flux:error name="form.image" />
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-teal-gray">
                            <flux:button type="submit" variant="primary" icon="check" class="px-8">
                                {{ $this->product ? 'Product Bijwerken' : 'Product Opslaan' }}
                            </flux:button>
                        </div>
                    </form>
                </div>
            </div>
        HTML;
    }
}
