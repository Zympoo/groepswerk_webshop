<?php

namespace App\Livewire\Backend;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Dashboard extends Component
{
    /**
     * Total Revenue: Sum of totaalbedrag from orders where status is paid.
     */
    #[Computed]
    public function totalRevenue(): string
    {
        $revenue = Order::where('status', OrderStatus::PAID)->sum('total_amount');

        return $this->formatCurrency((float) $revenue);
    }

    /**
     * Active Orders: Count of orders with status pending or paid.
     */
    #[Computed]
    public function activeOrders(): int
    {
        return Order::whereIn('status', [OrderStatus::PENDING, OrderStatus::PAID])->count();
    }

    /**
     * Low Stock: Count of products where stock is less than 5.
     */
    #[Computed]
    public function lowStock(): int
    {
        return Product::where('stock', '<', 5)->count();
    }

    /**
     * Format currency to the requested format: € 12.500,50
     */
    private function formatCurrency(float $amount): string
    {
        return '€ ' . number_format($amount, 2, ',', '.');
    }

    public function render()
    {
        return view('livewire.backend.dashboard')
            ->layout('components.layouts.admin');
    }
}