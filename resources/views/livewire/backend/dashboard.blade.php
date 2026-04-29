<div>
    <div class="mb-8">
        <h1 class="font-serif text-4xl mb-2">Dashboard Overzicht</h1>
        <p class="text-cool-gray">Welkom bij het controlepaneel van uw MongoDB-stijl webshop.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-forest-black text-white p-6 rounded-xl border border-teal-gray shadow-lg">
            <span class="tech-label text-mongo-green block mb-2">Totale Omzet</span>
            <div class="text-3xl font-bold">{{ $this->totalRevenue }}</div>
        </div>

        <div class="bg-white dark:bg-forest-black border border-silver-teal dark:border-teal-gray p-6 rounded-xl shadow-sm">
            <span class="tech-label text-mongo-dark-green dark:text-mongo-green block mb-2">Actieve Bestellingen</span>
            <div class="text-3xl font-bold dark:text-white">{{ $this->activeOrders }}</div>
        </div>

        <div class="bg-white dark:bg-forest-black border border-silver-teal dark:border-teal-gray p-6 rounded-xl shadow-sm">
            <span class="tech-label text-red-600 block mb-2">Lage Voorraad</span>
            <div class="text-3xl font-bold dark:text-white">{{ $this->lowStock }}</div>
        </div>
    </div>

    <div class="bg-light-input dark:bg-deep-teal p-8 rounded-2xl border border-silver-teal dark:border-teal-gray">
        <h3 class="font-bold text-xl mb-4 dark:text-white">Snelle Acties</h3>
        <div class="flex gap-4">
            <a href="/dashboard/products/create"
                class="bg-mongo-dark-green text-white px-6 py-3 rounded-lg font-bold hover:scale-105 transition-transform">
                Nieuw Product Toevoegen
            </a>
        </div>
    </div>
</div>