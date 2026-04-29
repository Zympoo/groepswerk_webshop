<?php

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Enums\OrderStatus;

new #[Layout('components.layouts.app')]
class extends Component {

    public Order $order;

    public function mount(Order $order)
    {
        $this->authorize('view', $order);

        $this->order = $order->load('details.product');
    }
};
?>

<div class="min-h-screen bg-white py-16">
    <div class="max-w-4xl mx-auto px-4">

        <div class="mb-6">
            <a href="/orders"
               class="text-action-blue hover:underline">
                ← Terug naar mijn orders
            </a>
        </div>

        <div class="mb-12">
            <span class="tech-label text-mongo-dark-green mb-2 block">
                Order detail
            </span>

            <h2 class="text-[36px] font-medium">
                Order <span class="mongo-underline">#{{ $order->order_number }}</span>
            </h2>
        </div>

        @if($order->status === \App\Enums\OrderStatus::PENDING)
            <div class="bg-light-input border border-silver-teal rounded-[16px] p-6 mb-8 flex items-center justify-between">

                <div>
                    <p class="font-medium text-[18px]">
                        Deze bestelling is nog niet betaald
                    </p>

                    <p class="text-cool-gray text-sm mt-1">
                        Rond je betaling af om je order te bevestigen.
                    </p>
                </div>

                <form method="POST" action="{{ route('orders.pay', $order) }}">
                    @csrf

                    <button
                        class="bg-action-blue text-white px-6 py-3 rounded hover:opacity-90 transition-colors
                               font-medium hover:cursor-pointer"
                    >
                        Betaal nu
                    </button>
                </form>

            </div>
        @endif

        <!-- Order info -->
        <div class="bg-light-input border border-silver-teal rounded-[16px] p-6 mb-8">
            <p class="text-cool-gray">
                Datum: {{ $order->created_at->format('d/m/Y H:i') }}
            </p>

            <p class="text-cool-gray">
                Status: {{ $order->status->value }}
            </p>

            <p class="font-medium mt-2">
                Totaal: {{ $order->formatted_total }}
            </p>
        </div>

        <!-- Items -->
        <div class="space-y-4">
            @foreach($order->details as $item)
                <div class="flex justify-between border border-silver-teal rounded-[12px] p-4">

                    <div>
                        <p class="font-medium">
                            {{ $item->product_name_snapshot }}
                        </p>

                        <p class="text-sm text-cool-gray">
                            {{ $item->quantity }} x €{{ number_format($item->price_snapshot, 2, ',', '.') }}
                        </p>
                    </div>

                    <span class="font-medium">
                        €{{ number_format($item->subtotal, 2, ',', '.') }}
                    </span>

                </div>
            @endforeach
        </div>

    </div>
</div>
