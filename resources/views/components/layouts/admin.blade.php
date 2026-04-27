<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Warre & Kamil</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-forest-black text-white font-sans antialiased">

    <div class="flex min-h-screen">
        <aside class="w-64 bg-forest-black border-r border-teal-gray flex flex-col">
            <div class="p-6 border-b border-teal-gray">
                <a href="/" class="text-mongo-green font-serif text-xl font-bold tracking-tight">
                    Webshop<span class="text-white">.</span><span class="text-xs ml-2 text-cool-gray">ADMIN</span>
                </a>
            </div>

            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('dashboard.index') }}"
                    class="flex items-center gap-3 px-4 py-2 {{ request()->routeIs('dashboard.index') ? 'text-mongo-green bg-deep-teal' : 'text-silver-teal hover:bg-deep-teal hover:text-white' }} rounded-lg font-medium">
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('dashboard.products.index') }}"
                    class="flex items-center gap-3 px-4 py-2 {{ request()->routeIs('dashboard.products.*') ? 'text-mongo-green bg-deep-teal' : 'text-silver-teal hover:bg-deep-teal hover:text-white' }} rounded-lg transition-colors">
                    Productbeheer
                </a>
                <a href="{{ route('dashboard.categories.index') }}"
                    class="flex items-center gap-3 px-4 py-2 {{ request()->routeIs('dashboard.categories.*') ? 'text-mongo-green bg-deep-teal' : 'text-silver-teal hover:bg-deep-teal hover:text-white' }} rounded-lg transition-colors">
                    Categorieën
                </a>
                <a href="#"
                    class="flex items-center gap-3 px-4 py-2 text-silver-teal hover:bg-deep-teal hover:text-white rounded-lg transition-colors">
                    Bestellingen
                </a>
                <a href="#"
                    class="flex items-center gap-3 px-4 py-2 text-silver-teal hover:bg-deep-teal hover:text-white rounded-lg transition-colors">
                    Klanten
                </a>
            </nav>

            <div class="p-4 border-t border-teal-gray text-xs text-cool-gray">
                Ingelogd als: <span class="text-white">{{ auth()->user()->name }}</span>
            </div>
        </aside>

        <main class="flex-1 bg-white text-black">
            <header class="bg-white border-b border-silver-teal px-8 py-4 flex justify-between items-center">
                <h2 class="tech-label text-mongo-dark-green">Systeem Status: Online</h2>
                <div class="flex items-center gap-4">
                    <a href="/" class="text-sm text-action-blue hover:underline">Naar de website</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 font-bold">Uitloggen</button>
                    </form>
                </div>
            </header>

            <div class="p-8">
                {{ $slot }}
            </div>
            
            <flux:toast />
        </main>
    </div>

    @livewireScripts
</body>

</html>