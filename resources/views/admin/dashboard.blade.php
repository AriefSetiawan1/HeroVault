<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-white flex items-center space-x-2">
                    <span>👑</span>
                    <span>Dashboard Penjaga Gudang</span>
                </h1>
                <p class="text-sm text-slate-400 mt-1">Ringkasan status gudang persenjataan superhero pensiunan Kota Metrodana</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.gears.create') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl text-sm transition shadow-lg shadow-amber-500/20">
                    + Tambah Perlengkapan
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Total Perlengkapan -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-lg relative overflow-hidden">
                <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total Perlengkapan</div>
                <div class="text-3xl font-black text-white mt-2">{{ $totalGears }}</div>
                <div class="text-xs text-slate-500 mt-1">Unit dalam database</div>
                <div class="absolute right-3 bottom-3 text-3xl opacity-20">🛡️</div>
            </div>

            <!-- Perlengkapan Tersedia -->
            <div class="bg-slate-900 border border-emerald-500/30 rounded-2xl p-5 shadow-lg relative overflow-hidden">
                <div class="text-emerald-400 text-xs font-semibold uppercase tracking-wider">Tersedia</div>
                <div class="text-3xl font-black text-emerald-400 mt-2">{{ $availableGears }}</div>
                <div class="text-xs text-slate-500 mt-1">Siap dipinjamkan</div>
                <div class="absolute right-3 bottom-3 text-3xl opacity-20">✅</div>
            </div>

            <!-- Perlengkapan Disewa -->
            <div class="bg-slate-900 border border-amber-500/30 rounded-2xl p-5 shadow-lg relative overflow-hidden">
                <div class="text-amber-400 text-xs font-semibold uppercase tracking-wider">Sedang Disewa</div>
                <div class="text-3xl font-black text-amber-400 mt-2">{{ $rentedGears }}</div>
                <div class="text-xs text-slate-500 mt-1">Sedang dipakai warga</div>
                <div class="absolute right-3 bottom-3 text-3xl opacity-20">📦</div>
            </div>

            <!-- Jumlah Anggota -->
            <div class="bg-slate-900 border border-indigo-500/30 rounded-2xl p-5 shadow-lg relative overflow-hidden">
                <div class="text-indigo-400 text-xs font-semibold uppercase tracking-wider">Total Anggota</div>
                <div class="text-3xl font-black text-indigo-400 mt-2">{{ $totalMembers }}</div>
                <div class="text-xs text-slate-500 mt-1">Warga terdaftar</div>
                <div class="absolute right-3 bottom-3 text-3xl opacity-20">👥</div>
            </div>

            <!-- Sewa Terlambat -->
            <div class="bg-slate-900 border border-rose-500/40 rounded-2xl p-5 shadow-lg relative overflow-hidden {{ $lateRentals > 0 ? 'ring-2 ring-rose-500/50' : '' }}">
                <div class="text-rose-400 text-xs font-semibold uppercase tracking-wider">Sewa Terlambat</div>
                <div class="text-3xl font-black text-rose-500 mt-2">{{ $lateRentals }}</div>
                <div class="text-xs text-slate-500 mt-1">Melewati jatuh tempo</div>
                <div class="absolute right-3 bottom-3 text-3xl opacity-20">⏰</div>
            </div>
        </div>

        <!-- Quick Links / Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-lg">
                <div class="flex items-center space-x-3 mb-4">
                    <span class="text-2xl">⚡</span>
                    <h2 class="text-lg font-bold text-white">Sewa Aktif & Pengembalian</h2>
                </div>
                <p class="text-sm text-slate-400 mb-4">Periksa barang yang sedang dipinjam dan proses pengembalian beserta hitungan dendanya.</p>
                <a href="{{ route('admin.rentals.active') }}" class="inline-flex items-center text-sm font-semibold text-amber-400 hover:text-amber-300">
                    Lihat {{ $activeRentals }} Sewa Berjalan &rarr;
                </a>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-lg">
                <div class="flex items-center space-x-3 mb-4">
                    <span class="text-2xl">📊</span>
                    <h2 class="text-lg font-bold text-white">Riwayat & Laporan Cetak</h2>
                </div>
                <p class="text-sm text-slate-400 mb-4">Filter seluruh transaksi rental berdasarkan tanggal, anggota, atau status, lalu cetak laporan.</p>
                <a href="{{ route('admin.rentals.history') }}" class="inline-flex items-center text-sm font-semibold text-amber-400 hover:text-amber-300">
                    Buka Riwayat & Cetak &rarr;
                </a>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-lg">
                <div class="flex items-center space-x-3 mb-4">
                    <span class="text-2xl">⚙️</span>
                    <h2 class="text-lg font-bold text-white">Manajemen Master Data</h2>
                </div>
                <p class="text-sm text-slate-400 mb-4">Kelola perlengkapan superhero, kategori kemampuan, dan data akun para anggota.</p>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.gears.index') }}" class="text-xs px-3 py-1.5 bg-slate-800 hover:bg-slate-700 rounded-lg text-slate-300">Perlengkapan</a>
                    <a href="{{ route('admin.categories.index') }}" class="text-xs px-3 py-1.5 bg-slate-800 hover:bg-slate-700 rounded-lg text-slate-300">Kategori</a>
                    <a href="{{ route('admin.members.index') }}" class="text-xs px-3 py-1.5 bg-slate-800 hover:bg-slate-700 rounded-lg text-slate-300">Anggota</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
