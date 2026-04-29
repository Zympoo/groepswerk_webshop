<?php

use App\Actions\Orders\CreateOrderAction;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Services\CartService;

new #[Layout('components.layouts.app')]
class extends Component {

    public array $address = [];

    public $items; // collection

    public function mount(CartService $cartService)
    {
        $this->items = $cartService->getItems();

        if (Auth::check()) {
            $this->address['name'] = Auth::user()->name;
            $this->address['email'] = Auth::user()->email;
        }
    }

    public function checkout(CreateOrderAction $action)
    {
        $this->validate([
            'address.name' => 'required|string',
            'address.email' => 'required|email',
            'address.street' => 'required|string',
            'address.city' => 'required|string',
            'address.postal_code' => 'required|string',
            'address.country' => 'required|string',
        ], [
            'address.name.required' => 'Vul je naam in zodat we je bestelling kunnen verwerken.',
            'address.email.required' => 'Je e-mailadres is nodig om de orderbevestiging te sturen.',
            'address.email.email' => 'Geef een geldig e-mailadres op.',
            'address.street.required' => 'Straat en huisnummer zijn verplicht voor levering.',
            'address.city.required' => 'Vul je woonplaats in.',
            'address.postal_code.required' => 'Postcode is verplicht voor verzending.',
            'address.country.required' => 'Kies je land voor levering.',
        ]);

        if ($this->items->isEmpty()) {
            $this->addError('cart', 'Je winkelmand is leeg.');
            return;
        }

        // 🔥 Transform naar wat je Action verwacht
        $cartArray = $this->items->map(fn($item) => [
            'id' => $item->product_id,
            'name' => $item->product->name,
            'price' => $item->product->price,
            'quantity' => $item->quantity,
        ])->toArray();

        $url = $action->handle(
            $cartArray,
            $this->address,
            Auth::id()
        );

        return redirect()->away($url);
    }

    public function getTotalProperty()
    {
        return $this->items->sum(
            fn($item) => $item->product->price * $item->quantity
        );
    }
};
?>

<div class="min-h-screen bg-white py-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-12">
            <span class="tech-label text-mongo-dark-green mb-2 block">Checkout</span>
            <h2 class="text-[36px] font-medium leading-tight">
                Bestelling <span class="mongo-underline">afronden</span>
            </h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

            <!-- Adres -->
            <div class="space-y-6">

                <h3 class="text-[24px] font-medium">Adresgegevens</h3>

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 p-4 rounded mb-4">
                        <ul class="list-disc ml-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <input wire:model="address.name"  type="text" placeholder="Naam" class="input w-full rounded-lg border border-silver-teal bg-white px-4 py-3 text-sm focus:border-mongo-dark-green focus:ring-2 focus:ring-mongo-dark-green/20">
                <input wire:model="address.email" type="email" placeholder="Email" class="input w-full rounded-lg border border-silver-teal bg-white px-4 py-3 text-sm focus:border-mongo-dark-green focus:ring-2 focus:ring-mongo-dark-green/20">
                <input wire:model="address.street" type="text" placeholder="Straat" class="input w-full rounded-lg border border-silver-teal bg-white px-4 py-3 text-sm focus:border-mongo-dark-green focus:ring-2 focus:ring-mongo-dark-green/20">
                <input wire:model="address.city" type="text" placeholder="Stad" class="input w-full rounded-lg border border-silver-teal bg-white px-4 py-3 text-sm focus:border-mongo-dark-green focus:ring-2 focus:ring-mongo-dark-green/20">
                <input wire:model="address.postal_code" type="text" placeholder="Postcode" class="input w-full rounded-lg border border-silver-teal bg-white px-4 py-3 text-sm focus:border-mongo-dark-green focus:ring-2 focus:ring-mongo-dark-green/20">
                <input wire:model="address.country" type="text" placeholder="Land" class="input w-full rounded-lg border border-silver-teal bg-white px-4 py-3 text-sm focus:border-mongo-dark-green focus:ring-2 focus:ring-mongo-dark-green/20">

                @error('cart')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror

            </div>

            <!-- Overzicht -->
            <div class="bg-light-input border border-silver-teal rounded-[16px] p-6">

                <h3 class="text-[24px] font-medium mb-6">Jouw bestelling</h3>

                <div class="space-y-4">
                    @forelse($items as $item)
                        <div class="flex justify-between">
                            <div>
                                <p class="font-medium">{{ $item->product->name }}</p>
                                <p class="text-sm text-cool-gray">
                                    {{ $item->quantity }} x €{{ number_format($item->product->price, 2, ',', '.') }}
                                </p>
                            </div>
                            <span>
                                €{{ number_format($item->product->price * $item->quantity, 2, ',', '.') }}
                            </span>
                        </div>
                    @empty
                        <p class="text-cool-gray">Je winkelmand is leeg.</p>
                    @endforelse
                </div>

                <div class="border-t border-silver-teal mt-6 pt-4 flex justify-between font-medium text-lg">
                    <span>Totaal</span>
                    <span>€{{ number_format($this->total, 2, ',', '.') }}</span>
                </div>

                <button
                    wire:click="checkout"
                    class="mt-6 w-full bg-mongo-dark-green text-white py-3 rounded hover:opacity-90 hover:cursor-pointer"
                >
                    Ga naar betaling
                </button>

            </div>

        </div>
    </div>
</div>
