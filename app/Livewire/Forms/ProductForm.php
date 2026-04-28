<?php

namespace App\Livewire\Forms;

use App\Models\Product;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Validation\Rule;

class ProductForm extends Form
{
    public ?Product $product = null;

    public $category_id = '';
    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public string $price = '';
    public string $stock = '';
    public $image;
    public bool $is_active = true;
    public bool $is_featured = false;

    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|min:3|max:255',
            'slug' => [
                'nullable',
                'max:255',
                Rule::unique('products', 'slug')->ignore($this->product?->id),
            ],
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'image' => 'nullable|image|max:2048', // Max 2MB
        ];
    }

    public function setProduct(Product $product): void
    {
        $this->product = $product;
        $this->category_id = $product->category_id;
        $this->name = $product->name;
        $this->slug = $product->slug;
        $this->description = $product->description ?? '';
        $this->price = $product->price;
        $this->stock = $product->stock;
        $this->is_active = (bool) $product->is_active;
        $this->is_featured = (bool) $product->is_featured;
    }

    public function resetForm(): void
    {
        $this->reset(['category_id', 'name', 'slug', 'description', 'price', 'stock', 'is_active', 'is_featured', 'product']);
    }
}
