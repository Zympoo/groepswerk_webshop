<?php

namespace App\Livewire\Pages\Admin;

use App\Actions\Categories\StoreCategoryAction;
use App\Livewire\Forms\CategoryForm;
use App\Models\Category;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class CategoryUpsert extends Component
{
    public CategoryForm $form;
    public ?Category $category = null;

    /**
     * Initialize the component for create or edit.
     */
    public function mount(?Category $category = null): void
    {
        if ($category && $category->exists) {
            $this->category = $category;
            $this->form->setCategory($category);
        }
    }

    /**
     * Handle the form submission.
     */
    public function save(StoreCategoryAction $action): void
    {
        $this->form->validate();

        $action->handle($this->form->all(), $this->category);

        session()->flash('status', 'Categorie succesvol opgeslagen.');

        $this->redirectRoute('dashboard.categories.index', navigate: true);
    }

    public function render()
    {
        $title = $this->category ? 'Categorie Bewerken' : 'Nieuwe Categorie';

        return <<<HTML
            <div class="p-6 max-w-2xl mx-auto">
                <div class="mb-6">
                    <flux:button href="{{ route('dashboard.categories.index') }}" icon="chevron-left" variant="ghost">Terug naar overzicht</flux:button>
                    <h1 class="text-2xl font-bold text-gray-800 mt-2">{{ \$title }}</h1>
                </div>

                <div class="bg-white p-6 shadow rounded-lg">
                    <form wire:submit="save" class="space-y-4">
                        <flux:input wire:model="form.name" label="Naam" placeholder="Bijv. Sneakers" />
                        <flux:input wire:model="form.slug" label="Slug" placeholder="bijv. sneakers" hint="Laat leeg om automatisch te genereren op basis van naam." />
                        <flux:textarea wire:model="form.description" label="Beschrijving" placeholder="Korte omschrijving..." />
                        
                        <div class="flex items-center gap-2">
                            <flux:checkbox wire:model="form.is_active" label="Actief" />
                        </div>

                        <div class="flex justify-end pt-4">
                            <flux:button type="submit" variant="primary" icon="check">
                                {{ \$category ? 'Bijwerken' : 'Opslaan' }}
                            </flux:button>
                        </div>
                    </form>
                </div>
            </div>
        HTML;
    }
}
