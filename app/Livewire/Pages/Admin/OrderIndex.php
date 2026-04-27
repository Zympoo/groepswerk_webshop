<?php

namespace App\Livewire\Pages\Admin;

use App\Models\Order;
use App\Enums\OrderStatus;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;

#[Layout('components.layouts.admin')]
class OrderIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';

    #[Computed]
    public function orders()
    {
        return Order::query()
            ->with('user')
            ->when($this->search, fn($query) => $query->where('order_number', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn($query) => $query->where('status', $this->statusFilter))
            ->latest()
            ->paginate(15);
    }

    public function render()
    {
        return <<<'HTML'
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Bestellingen</h1>
                        <p class="text-sm text-gray-500">Beheer en volg de status van klantbestellingen.</p>
                    </div>
                </div>

                <div class="mb-4 flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <flux:input wire:model.live.debounce.300ms="search" placeholder="Zoek op ordernummer..." icon="magnifying-glass" />
                    </div>
                    <div class="w-full md:w-64">
                        <flux:select wire:model.live="statusFilter" placeholder="Filter op status...">
                            <flux:select.option value="">Alle statussen</flux:select.option>
                            @foreach(App\Enums\OrderStatus::cases() as $status)
                                <flux:select.option value="{{ $status->value }}">{{ $status->label() }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                </div>

                <div class="bg-white dark:bg-forest-black shadow rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-teal-gray">
                        <thead class="bg-gray-50 dark:bg-deep-teal">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-silver-teal uppercase tracking-wider">Order Nr</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-silver-teal uppercase tracking-wider">Datum</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-silver-teal uppercase tracking-wider">Klant</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-silver-teal uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-silver-teal uppercase tracking-wider">Totaal</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-silver-teal uppercase tracking-wider">Acties</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-forest-black divide-y divide-gray-200 dark:divide-teal-gray">
                            @forelse($this->orders as $order)
                                <tr wire:key="{{ $order->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        #{{ $order->order_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-silver-teal">
                                        {{ $order->created_at->format('d-m-Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $order->user->name ?? 'Onbekende Klant' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $badgeVariant = match($order->status) {
                                                App\Enums\OrderStatus::PAID => 'success',
                                                App\Enums\OrderStatus::PENDING => 'warning',
                                                App\Enums\OrderStatus::SHIPPED => 'primary',
                                                App\Enums\OrderStatus::CANCELLED, App\Enums\OrderStatus::REFUNDED => 'danger',
                                                default => 'neutral',
                                            };
                                        @endphp
                                        <flux:badge :variant="$badgeVariant" size="sm">{{ $order->status->label() }}</flux:badge>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-bold">
                                        {{ $order->formattedTotal }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <flux:button href="{{ route('dashboard.orders.show', $order) }}" variant="ghost" icon="eye" size="sm" />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500 dark:text-silver-teal">
                                        Geen bestellingen gevonden.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $this->orders->links() }}
                </div>
            </div>
        HTML;
    }
}
