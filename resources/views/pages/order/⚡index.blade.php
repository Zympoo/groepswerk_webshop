<?php

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Livewire\Component;

new #[Layout('components.layouts.app')]
class extends Component {
    use WithPagination;

    public function getOrdersProperty()
    {
        return Order::query()
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
    }
};
?>

<div class="min-h-screen bg-white py-16">
    <div class="max-w-5xl mx-auto px-4">

        <div class="mb-12">
            <span class="tech-label text-mongo-dark-green mb-2 block">Orders</span>
            <h2 class="text-[36px] font-medium">
                Mijn <span class="mongo-underline">bestellingen</span>
            </h2>
        </div>

        <div class="space-y-4">
            @forelse($this->orders as $order)
                <a href="/orders/{{ $order->id }}"
                   class="block border border-silver-teal rounded-[16px] p-6 hover:border-action-blue transition">

                    <div class="flex justify-between">
                        <div>
                            <p class="font-medium">
                                Order #{{ $order->order_number }}
                            </p>
                            <p class="text-sm text-cool-gray">
                                {{ $order->created_at->format('d/m/Y') }}
                            </p>
                        </div>

                        <div class="text-right">
                            <p class="font-medium">
                                {{ $order->formatted_total }}
                            </p>

                            <span class="text-sm text-cool-gray">
                                {{ $order->status->value }}
                            </span>
                        </div>
                    </div>

                </a>
            @empty
                <p class="text-cool-gray text-center py-12">
                    Je hebt nog geen bestellingen.
                </p>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $this->orders->links() }}
        </div>

    </div>
</div>
