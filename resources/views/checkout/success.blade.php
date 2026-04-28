<x-layouts.app>
    <div class="min-h-screen bg-white py-16">
    <div class="max-w-3xl mx-auto text-center">

        <span class="tech-label text-green-600 mb-2 block">
            Betaling gelukt
        </span>

        <h2 class="text-[36px] font-medium mb-6">
            Bedankt voor je bestelling!
        </h2>

        <p class="text-cool-gray mb-4">
            Ordernummer: <strong>{{ $order->order_number }}</strong>
        </p>

        <p class="text-cool-gray mb-8">
            Totaal: <strong>{{ $order->formatted_total }}</strong>
        </p>

        <a href="/products"
           class="bg-action-blue text-white px-6 py-3 rounded">
            Verder shoppen
        </a>

    </div>
</div>
</x-layouts.app>
