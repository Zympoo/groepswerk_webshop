<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webshop | Warre & Kamil</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans antialiased text-black bg-white">

    <header class="bg-forest-black text-white pt-6 pb-20 px-4 sm:px-6 lg:px-8 border-b border-teal-gray">
        <div class="max-w-7xl mx-auto flex items-center justify-between mb-16">
            <a href="/" class="text-mongo-green font-serif text-2xl font-bold tracking-tight">
                Webshop<span class="text-white">.</span>
            </a>

            <div class="flex items-center gap-8">
                <nav class="hidden md:flex gap-8 font-medium text-[16px] items-center">
                    <a href="/products" class="hover:text-action-blue transition-colors">Producten</a>
                    <a href="/cart" class="hover:text-action-blue transition-colors">Winkelmandje</a>

                    @auth
                        <a href="/dashboard" class="hover:text-action-blue transition-colors">Mijn orders</a>

                        @if(auth()->user()->role === 'admin')
                            <a href="/admin/dashboard"
                                class="bg-mongo-dark-green text-white px-5 py-2 rounded-full text-sm font-bold hover:scale-105 transition-transform border border-mongo-green shadow-[0px_1px_6px_rgba(0,237,100,0.2)]">
                                Admin Paneel
                            </a>
                        @endif
                    @else
                        <a href="/login" class="hover:text-action-blue transition-colors">Inloggen</a>
                        <a href="/register" class="hover:text-action-blue transition-colors">Registreren</a>
                    @endauth
                </nav>
            </div>
        </div>

        <div class="max-w-7xl mx-auto">
            <span class="tech-label text-mongo-green block mb-4">Systeem Online</span>
            <h1 class="font-serif text-[64px] md:text-[96px] font-normal leading-tight mb-6">
                Ontwikkel met <br>
                <span class="text-white mongo-underline">Autoriteit.</span>
            </h1>
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>

    @livewireScripts
</body>

</html>