<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'HeroVault') }} - Superhero Gear Vault</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-950 text-slate-100 min-h-screen flex flex-col">
        <div class="flex-1">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-slate-900/90 border-b border-slate-800 shadow-sm backdrop-blur">
                    <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Flash Notifications -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                @if(session('success'))
                    <div class="mb-4 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center justify-between text-sm shadow-lg">
                        <div class="flex items-center space-x-2">
                            <span>✅</span>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-400/60 hover:text-emerald-300">✕</button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 flex items-center justify-between text-sm shadow-lg">
                        <div class="flex items-center space-x-2">
                            <span>⚠️</span>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-rose-400/60 hover:text-rose-300">✕</button>
                    </div>
                @endif
            </div>

            <!-- Page Content -->
            <main class="py-6">
                {{ $slot }}
            </main>
        </div>

        <footer class="bg-slate-900 border-t border-slate-800/80 py-4 text-center text-xs text-slate-500">
            HeroVault &copy; 2026 Kota Metrodana. Sistem Peminjaman Kostum dan Senjata Superhero Pensiunan.
        </footer>
    </body>
</html>
