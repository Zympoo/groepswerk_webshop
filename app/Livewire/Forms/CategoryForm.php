<?php

namespace App\Livewire\Forms;

use App\Models\Category;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Validation\Rule;

class CategoryForm extends Form
{
    public ?Category $category = null;

    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public bool $is_active = true;

    /**
     * Senior Reflex: We define rules dynamically to handle the 'unique' ignore case.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:3|max:255',
            'slug' => [
                'nullable',
                'max:255',
                Rule::unique('categories', 'slug')->ignore($this->category?->id),
            ],
            'description' => 'nullable|max:1000',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Populate the form with existing category data.
     */
    public function setCategory(Category $category): void
    {
        $this->category = $category;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description ?? '';
        $this->is_active = (bool) $category->is_active;
    }

    /**
     * Reset the form to default values.
     */
    public function resetForm(): void
    {
        $this->reset(['name', 'slug', 'description', 'is_active', 'category']);
    }
}
