<?php

namespace App\Livewire\Pages\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class CategoryIndex extends Component
{
    public function render()
    {
        return <<<'HTML'
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Categoriebeheer</h1>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        Nieuwe Categorie
                    </button>
                </div>
                
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Naam</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producten</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acties</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Sneakers</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">12</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="#" class="text-indigo-600 hover:text-indigo-900">Bewerken</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        HTML;
    }
}
