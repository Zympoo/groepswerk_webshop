<?php

namespace App\Livewire\Pages\Admin;

use App\Models\Order;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;

#[Layout('components.layouts.admin')]
class OrderDetail extends Component
{
    public Order $order;
    public string $status;

    public function mount(Order $order): void
    {
        Gate::authorize('view', $order);

        $this->order = $order->load(['user', 'details.product']);
        $this->status = $order->status->value;
    }

    /**
     * Handle status updates.
     */
    public function updatedStatus($value, \App\Actions\Orders\UpdateOrderStatusAction $action): void
    {
        $newStatus = \App\Enums\OrderStatus::from($value);
        $action->handle($this->order, $newStatus);

        \Flux\Flux::toast('Bestelstatus succesvol bijgewerkt.');
    }

    #[Computed]
    public function details()
    {
        return $this->order->details;
    }

    public function render()
    {
        return <<<'HTML'
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <flux:button href="{{ route('dashboard.orders.index') }}" icon="chevron-left" variant="ghost" class="mb-2">Terug naar overzicht</flux:button>
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Bestelling Details: #{{ $order->order_number }}</h1>
                    </div>
                    <div>
                         @php
                            $badgeVariant = match($order->status) {
                                App\Enums\OrderStatus::PAID => 'success',
                                App\Enums\OrderStatus::PENDING => 'warning',
                                App\Enums\OrderStatus::SHIPPED => 'primary',
                                App\Enums\OrderStatus::CANCELLED, App\Enums\OrderStatus::REFUNDED => 'danger',
                                default => 'neutral',
                            };
                        @endphp
                        <flux:badge :variant="$badgeVariant" size="lg">{{ $order->status->label() }}</flux:badge>
                    </div>
                </div>

                <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <div class="bg-white dark:bg-forest-black shadow rounded-lg border border-gray-200 dark:border-teal-gray p-4 flex items-center justify-between">
                            <div class="text-sm font-medium text-gray-700 dark:text-silver-teal">Status Bijwerken:</div>
                            <div class="w-64">
                                <flux:select wire:model.live="status">
                                    @foreach(\App\Enums\OrderStatus::cases() as $s)
                                        <flux:select.option value="{{ $s->value }}">{{ $s->label() }}</flux:select.option>
                                    @endforeach
                                </flux:select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Linkerkolom: Bestelde items -->
                    <div class="md:col-span-2 space-y-6">
                        <div class="bg-white dark:bg-forest-black shadow rounded-lg overflow-hidden border border-gray-200 dark:border-teal-gray">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-teal-gray bg-gray-50 dark:bg-deep-teal">
                                <h3 class="font-bold text-gray-800 dark:text-white text-lg">Bestelde Items</h3>
                            </div>
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-teal-gray">
                                <thead class="bg-gray-50 dark:bg-deep-teal">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-silver-teal uppercase tracking-wider">Product</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-silver-teal uppercase tracking-wider text-center">Aantal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-silver-teal uppercase tracking-wider text-right">Prijs (Snapshot)</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-silver-teal uppercase tracking-wider text-right">Subtotaal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-teal-gray">
                                    @foreach($this->details as $detail)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $detail->product_name_snapshot }}
                                                <div class="text-xs text-gray-500 dark:text-silver-teal">ID: {{ $detail->product_id }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">
                                                {{ $detail->quantity }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right font-medium">
                                                € {{ number_format($detail->price_snapshot, 2, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right font-bold">
                                                € {{ number_format($detail->subtotal, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50 dark:bg-deep-teal">
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-right text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">Totaalbedrag:</td>
                                        <td class="px-6 py-4 text-right text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $order->formattedTotal }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Rechterkolom: Klant & Adres details -->
                    <div class="space-y-6">
                        <div class="bg-white dark:bg-forest-black shadow rounded-lg border border-gray-200 dark:border-teal-gray">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-teal-gray bg-gray-50 dark:bg-deep-teal">
                                <h3 class="font-bold text-gray-800 dark:text-white text-lg">Klant Informatie</h3>
                            </div>
                            <div class="p-6 space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-silver-teal uppercase">Naam</label>
                                    <div class="text-sm text-gray-900 dark:text-white font-medium">{{ $order->user->name ?? 'Gast' }}</div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-silver-teal uppercase">E-mail</label>
                                    <div class="text-sm text-gray-900 dark:text-white font-medium">{{ $order->user->email ?? 'N/A' }}</div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-silver-teal uppercase">Besteldatum</label>
                                    <div class="text-sm text-gray-900 dark:text-white font-medium">{{ $order->created_at->format('d-m-Y H:i:s') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-forest-black shadow rounded-lg border border-gray-200 dark:border-teal-gray">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-teal-gray bg-gray-50 dark:bg-deep-teal">
                                <h3 class="font-bold text-gray-800 dark:text-white text-lg">Verzendadres</h3>
                            </div>
                            <div class="p-6">
                                @if($order->address_details)
                                    <div class="text-sm text-gray-900 dark:text-white leading-relaxed">
                                        <p class="font-bold">{{ $order->address_details['first_name'] ?? '' }} {{ $order->address_details['last_name'] ?? '' }}</p>
                                        <p>{{ $order->address_details['street'] ?? '' }} {{ $order->address_details['house_number'] ?? '' }}</p>
                                        <p>{{ $order->address_details['postal_code'] ?? '' }} {{ $order->address_details['city'] ?? '' }}</p>
                                        <p>{{ $order->address_details['country'] ?? '' }}</p>
                                    </div>
                                @else
                                    <div class="text-sm text-gray-500 italic">Geen adresgegevens beschikbaar.</div>
                                @endif
                            </div>
                        </div>

                        <div class="bg-white dark:bg-forest-black shadow rounded-lg border border-gray-200 dark:border-teal-gray">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-teal-gray bg-gray-50 dark:bg-deep-teal">
                                <h3 class="font-bold text-gray-800 dark:text-white text-lg">Betalingsinformatie</h3>
                            </div>
                            <div class="p-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-silver-teal uppercase mb-1">Stripe Referentie</label>
                                    @if($order->stripe_payment_id)
                                        <div class="text-sm font-mono bg-slate-100 dark:bg-deep-teal p-2 rounded border border-gray-200 dark:border-teal-gray text-gray-900 dark:text-white break-all">
                                            {{ $order->stripe_payment_id }}
                                        </div>
                                    @else
                                        <div class="text-sm text-gray-500 italic">
                                            {{ $order->status === \App\Enums\OrderStatus::PENDING ? 'Betaling in verwerking' : 'Geen referentie beschikbaar' }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        HTML;
    }
}
