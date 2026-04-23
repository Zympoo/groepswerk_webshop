<div>
    <div class="mb-8">
        <h1 class="font-serif text-4xl mb-2">Dashboard Overzicht</h1>
        <p class="text-cool-gray">Welkom bij het controlepaneel van uw MongoDB-stijl webshop.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-forest-black text-white p-6 rounded-xl border border-teal-gray shadow-lg">
            <span class="tech-label text-mongo-green block mb-2">Totale Omzet</span>
            <div class="text-3xl font-bold">€ {{ number_format($total_sales, 2, ',', '.') }}</div>
        </div>

        <div class="bg-white border border-silver-teal p-6 rounded-xl shadow-sm">
            <span class="tech-label text-mongo-dark-green block mb-2">Actieve Bestellingen</span>
            <div class="text-3xl font-bold">{{ $active_orders }}</div>
        </div>

        <div class="bg-white border border-silver-teal p-6 rounded-xl shadow-sm">
            <span class="tech-label text-red-600 block mb-2">Lage Voorraad</span>
            <div class="text-3xl font-bold">{{ $low_stock }}</div>
        </div>
    </div>

    <div class="bg-light-input p-8 rounded-2xl border border-silver-teal">
        <h3 class="font-bold text-xl mb-4">Snelle Acties</h3>
        <div class="flex gap-4">
            <button
                class="bg-mongo-dark-green text-white px-6 py-3 rounded-lg font-bold hover:scale-105 transition-transform">
                Nieuw Product Toevoegen
                </a>
                <button
                    class="bg-white border border-silver-teal px-6 py-3 rounded-lg font-bold hover:bg-silver-teal transition-colors">
                    Rapport Downloaden
                    </a>
        </div>
    </div>
</div>