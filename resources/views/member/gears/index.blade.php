<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-white flex items-center space-x-2">
                    <span>🛡️</span>
                    <span>Katalog Perlengkapan Superhero</span>
                </h1>
                <p class="text-sm text-slate-400 mt-1">Pilih kostum dan persenjataan legendaris pensiunan pahlawan Kota Metrodana</p>
            </div>
            <!-- Indikator Kuota Anggota -->
            <div class="bg-slate-900 border border-slate-800 px-4 py-2 rounded-xl text-xs flex items-center space-x-3 self-start md:self-auto">
                <span class="text-slate-400">Kuota Sewa Anda:</span>
                <span class="font-bold {{ $activeRentalsCount >= 2 ? 'text-rose-400' : 'text-emerald-400' }}">
                    {{ $activeRentalsCount }} / 2 Aktif
                </span>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Pencarian & Filter -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 shadow-lg">
            <form method="GET" action="{{ route('member.gears.index') }}" class="flex flex-col sm:flex-row gap-3">
                <!-- Search Keyword (Pencarian Nama) -->
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari perlengkapan (misal: Jubah, Palu, Perisai)..." class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 text-sm">
                </div>

                <!-- Filter Kategori -->
                <div class="sm:w-64">
                    <select name="category_id" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-white text-sm focus:outline-none focus:border-amber-400">
                        <option value="">-- Semua Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="px-6 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl text-sm transition">
                    Cari
                </button>

                @if(request()->hasAny(['search', 'category_id']))
                    <a href="{{ route('member.gears.index') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white rounded-xl text-sm flex items-center justify-center transition border border-slate-700">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Grid Kartu Perlengkapan -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($gears as $gear)
                @php
                    $isAvailable = $gear->isAvailable();
                    $canRent = $isAvailable && ($activeRentalsCount < 2);
                @endphp
                <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl flex flex-col transition hover:border-slate-700 hover:shadow-2xl">
                    <!-- Photo Header -->
                    <div class="relative h-48 bg-slate-800 overflow-hidden">
                        @if($gear->photo)
                            <img src="{{ asset('storage/' . $gear->photo) }}" alt="{{ $gear->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-600 bg-gradient-to-br from-slate-800 to-slate-900">
                                <span class="text-5xl mb-2">🦸</span>
                                <span class="text-xs text-slate-500">Kostum Legendaris</span>
                            </div>
                        @endif

                        <!-- Status Badge -->
                        <div class="absolute top-3 right-3">
                            @if($isAvailable)
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-500/90 text-white shadow-md">
                                    Tersedia
                                </span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-amber-500/90 text-slate-950 shadow-md">
                                    Sedang Disewa
                                </span>
                            @endif
                        </div>

                        <!-- Unit Code Badge -->
                        <div class="absolute bottom-3 left-3">
                            <span class="px-2.5 py-1 text-xs font-mono font-bold rounded-lg bg-slate-900/80 backdrop-blur text-amber-400 border border-slate-700">
                                {{ $gear->unit_code }}
                            </span>
                        </div>
                    </div>

                    <!-- Content Body -->
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <h2 class="text-lg font-black text-white hover:text-amber-400 transition">
                                <a href="{{ route('member.gears.show', $gear) }}">{{ $gear->name }}</a>
                            </h2>

                            <!-- Categories -->
                            <div class="flex flex-wrap gap-1 mt-2">
                                @foreach($gear->categories as $c)
                                    <span class="px-2 py-0.5 rounded text-[11px] bg-slate-800 text-slate-300 border border-slate-700">
                                        {{ $c->name }}
                                    </span>
                                @endforeach
                            </div>

                            <p class="text-xs text-slate-400 mt-3 line-clamp-2">
                                {{ $gear->description ?? 'Perlengkapan legendaris dari superhero pensiunan.' }}
                            </p>
                        </div>

                        <!-- Action Footer -->
                        <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between">
                            <a href="{{ route('member.gears.show', $gear) }}" class="text-xs font-semibold text-slate-400 hover:text-white transition">
                                Lihat Detail &rarr;
                            </a>

                            @if($canRent)
                                <form action="{{ route('member.gears.rent', $gear) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyewa {{ $gear->name }} ({{ $gear->unit_code }}) selama 5 hari?')">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl text-xs transition shadow-md shadow-amber-500/20">
                                        Sewa Sekarang
                                    </button>
                                </form>
                            @else
                                <button type="button" disabled class="px-4 py-2 bg-slate-800 text-slate-500 font-medium rounded-xl text-xs cursor-not-allowed border border-slate-700">
                                    {{ !$isAvailable ? 'Sedang Disewa' : 'Kuota Penuh' }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center bg-slate-900 border border-slate-800 rounded-2xl">
                    <span class="text-4xl block mb-2">🔍</span>
                    <h3 class="text-base font-bold text-white">Tidak ada perlengkapan yang ditemukan.</h3>
                    <p class="text-xs text-slate-400 mt-1">Coba gunakan kata kunci pencarian yang lain.</p>
                </div>
            @endforelse
        </div>

        @if($gears->hasPages())
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4">
                {{ $gears->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
