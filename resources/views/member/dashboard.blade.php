<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-white flex items-center space-x-2">
                    <span>⚡</span>
                    <span>Halo, Pahlawan {{ $user->name }}!</span>
                </h1>
                <p class="text-sm text-slate-400 mt-1">Selamat datang di HeroVault. Siap beraksi atau bergaya hari ini?</p>
            </div>
            <a href="{{ route('member.gears.index') }}" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl text-sm transition shadow-lg shadow-amber-500/20 self-start sm:self-auto">
                🔍 Jelajahi Perlengkapan
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Status Kuota & Jatuh Tempo Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Kuota Sewa Aktif -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl relative overflow-hidden">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Kuota Peminjaman Aktif</span>
                    <span class="text-2xl">📦</span>
                </div>
                <div class="flex items-baseline space-x-2">
                    <span class="text-4xl font-black text-white">{{ $activeCount }}</span>
                    <span class="text-slate-400 font-bold text-lg">dari 2 Unit Maksimal</span>
                </div>
                <div class="w-full bg-slate-800 h-2.5 rounded-full mt-4 overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500 {{ $activeCount >= 2 ? 'bg-rose-500' : ($activeCount == 1 ? 'bg-amber-400' : 'bg-emerald-400') }}" style="width: {{ ($activeCount / 2) * 100 }}%"></div>
                </div>
                <p class="text-xs text-slate-400 mt-3">
                    @if($activeCount >= 2)
                        <span class="text-rose-400 font-semibold">⚠️ Kuota sewa Anda sudah penuh (2 unit).</span> Kembalikan unit untuk dapat menyewa lagi.
                    @else
                        Anda masih dapat menyewa <strong class="text-emerald-400">{{ 2 - $activeCount }} perlengkapan</strong> lagi.
                    @endif
                </p>
            </div>

            <!-- Jatuh Tempo Terdekat -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl relative overflow-hidden">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Jatuh Tempo Terdekat</span>
                    <span class="text-2xl">⏰</span>
                </div>
                @if($nearestRental)
                    @php
                        $isOverdue = now()->startOfDay()->greaterThan($nearestRental->due_at->startOfDay());
                        $daysLeft = (int) now()->startOfDay()->diffInDays($nearestRental->due_at->startOfDay(), false);
                    @endphp
                    <div>
                        <div class="text-lg font-bold text-white flex items-center space-x-2">
                            <span class="font-mono text-amber-400 text-sm">[{{ $nearestRental->gear->unit_code }}]</span>
                            <span>{{ $nearestRental->gear->name }}</span>
                        </div>
                        <div class="mt-2 flex items-baseline space-x-2">
                            <span class="text-2xl font-black {{ $isOverdue ? 'text-rose-400' : 'text-amber-400' }}">
                                {{ $nearestRental->due_at->translatedFormat('d F Y') }}
                            </span>
                        </div>
                        <p class="text-xs mt-2 {{ $isOverdue ? 'text-rose-400 font-semibold' : 'text-slate-400' }}">
                            @if($isOverdue)
                                ⚠️ Terlambat! Segera kembalikan ke Penjaga Gudang (denda Rp 50.000/hari).
                            @elseif($daysLeft === 0)
                                ⚠️ Hari ini adalah batas terakhir pengembalian!
                            @else
                                Sisa waktu peminjaman: {{ $daysLeft }} hari lagi.
                            @endif
                        </p>
                    </div>
                @else
                    <div class="py-3">
                        <span class="text-slate-400 text-sm">Tidak ada sewa aktif saat ini.</span>
                        <p class="text-xs text-slate-500 mt-1">Anda bebas memilih kostum atau persenjataan di katalog.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Daftar Sewa Berjalan Singkat -->
        @if($activeRentals->isNotEmpty())
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-white">Perlengkapan yang Sedang Anda Pegang</h2>
                    <a href="{{ route('member.rentals.index') }}" class="text-xs font-semibold text-amber-400 hover:text-amber-300">
                        Lihat Riwayat &rarr;
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($activeRentals as $rental)
                        <div class="p-4 rounded-xl bg-slate-800/60 border border-slate-700/60 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                @if($rental->gear->photo)
                                    <img src="{{ asset('storage/' . $rental->gear->photo) }}" alt="{{ $rental->gear->name }}" class="w-12 h-12 rounded-xl object-cover border border-slate-700">
                                @else
                                    <div class="w-12 h-12 bg-slate-700 rounded-xl flex items-center justify-center text-xl">🛡️</div>
                                @endif
                                <div>
                                    <span class="font-mono text-xs font-bold text-amber-400">{{ $rental->gear->unit_code }}</span>
                                    <div class="font-bold text-white text-sm">{{ $rental->gear->name }}</div>
                                    <span class="text-xs text-slate-400">Tempo: {{ $rental->due_at->translatedFormat('d M Y') }}</span>
                                </div>
                            </div>
                            <span class="text-xs px-2.5 py-1 rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/30">
                                Sedang Dipinjam
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
