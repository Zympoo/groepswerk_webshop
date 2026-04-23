<?php

namespace App\Livewire\Backend;

use Livewire\Component;

class Dashboard extends Component
{
    /* * Senior reflex: We laden hier later statistieken in via repositories.
     */
    public function render()
    {
        return view('livewire.backend.dashboard', [
            'total_sales' => 12500.50,
            'active_orders' => 12,
            'low_stock' => 5
        ])->layout('components.layouts.admin');
    }
}