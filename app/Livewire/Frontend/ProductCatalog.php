<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Livewire\WithPagination;

class ProductCatalog extends Component
{
    use WithPagination; // Vereist voor paginatie 

    // Realtime filters vereist door examen 
    public $search = '';
    public $category = '';

    public function render()
    {
        /* * Senior reflex: We gebruiken hier dummy data voor de frontend demo.
         * Later wordt dit vervangen door Eloquent queries met Scopes.
         */
        $dummyProducts = [
            (object) ['id' => 1, 'name' => 'MacBook Pro 16"', 'slug' => 'macbook-pro', 'category' => 'Laptops', 'price' => 3299.00, 'description' => 'De ultieme laptop voor developers.'],
            (object) ['id' => 2, 'name' => 'Keychron K2', 'slug' => 'keychron-k2', 'category' => 'Accessoires', 'price' => 119.00, 'description' => 'Mechanisch toetsenbord voor snelle codeersessies.'],
            (object) ['id' => 3, 'name' => 'Dell 27" 4K', 'slug' => 'dell-27-4k', 'category' => 'Monitoren', 'price' => 549.00, 'description' => 'Kristalhelder beeld voor elke pixel.'],
        ];

        return view('livewire.frontend.product-catalog', [
            'products' => collect($dummyProducts)
        ])->layout('components.layouts.app');
    }
}