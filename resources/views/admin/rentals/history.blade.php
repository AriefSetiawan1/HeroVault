<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-white flex items-center space-x-2">
                    <span>📜</span>
                    <span>Riwayat Penyewaan & Denda</span>
                </h1>
                <p class="text-sm text-slate-400 mt-1">Seluruh arsip transaksi peminjaman perlengkapan superhero di HeroVault</p>
            </div>
            <a href="{{ route('admin.rentals.print', request()->query()) }}" target="_blank" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-sm transition flex items-center space-x-2 shadow-lg shadow-indigo-600/20 self-start md:self-auto">
                <span>🖨️</span>
                <span>Cetak Laporan</span>
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Filter Form -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-lg">
            <form method="GET" action="{{ route('admin.rentals.history') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
                <!-- Filter Tanggal Awal -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Tanggal Mulai Sewa</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white text-xs focus:outline-none focus:border-amber-400">
                </div>

                <!-- Filter Tanggal Akhir -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white text-xs focus:outline-none focus:border-amber-400">
                </div>

                <!-- Filter Anggota -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Anggota Penyewa</label>
                    <select name="user_id" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white text-xs focus:outline-none focus:border-amber-400">
                        <option value="">-- Semua Anggota --</option>
                        @foreach($members as $m)
                            <option value="{{ $m->id }}" {{ request('user_id') == $m->id ? 'selected' : '' }}>
                                {{ $m->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Status -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Status Sewa</label>
                    <select name="status" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white text-xs focus:outline-none focus:border-amber-400">
                        <option value="">-- Semua Status --</option>
                        <option value="dipinjam" {{ request('status') === 'dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                        <option value="dikembalikan" {{ request('status') === 'dikembalikan' ? 'selected' : '' }}>Sudah Dikembalikan</option>
                    </select>
                </div>

                <!-- Action Button -->
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl text-xs transition">
                        Filter Data
                    </button>
                    @if(request()->hasAny(['start_date', 'end_date', 'user_id', 'status']))
                        <a href="{{ route('admin.rentals.history') }}" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white rounded-xl text-xs flex items-center transition border border-slate-700">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
            @error('end_date')
                <p class="text-rose-400 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Total Denda Banner -->
        <div class="bg-gradient-to-r from-slate-900 to-slate-800 border border-slate-800 rounded-2xl p-4 flex items-center justify-between shadow-md">
            <span class="text-sm text-slate-300 font-medium">Total Denda dari Hasil Filter:</span>
            <span class="text-xl font-black font-mono text-amber-400">Rp {{ number_format($totalFine, 0, ',', '.') }}</span>
        </div>

        <!-- Tabel Riwayat -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-800/80 text-xs uppercase font-bold text-slate-400 border-b border-slate-700">
                        <tr>
                            <th class="px-6 py-4">Penyewa</th>
                            <th class="px-6 py-4">Perlengkapan</th>
                            <th class="px-6 py-4">Tgl Sewa</th>
                            <th class="px-6 py-4">Jatuh Tempo</th>
                            <th class="px-6 py-4">Tgl Kembali</th>
                            <th class="px-6 py-4">Kondisi</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Denda</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @forelse($rentals as $rental)
                            <tr class="hover:bg-slate-800/50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-white">{{ $rental->user->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $rental->user->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-mono text-xs text-amber-400 font-bold">{{ $rental->gear->unit_code }}</div>
                                    <div class="text-sm font-semibold text-slate-200">{{ $rental->gear->name }}</div>
                                </td>
                                <td class="px-6 py-4 text-xs">{{ $rental->rented_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-xs font-medium">{{ $rental->due_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-xs">
                                    {{ $rental->returned_at ? $rental->returned_at->format('d/m/Y') : '-' }}
                                    @if($rental->late_days > 0)
                                        <span class="block text-[11px] text-rose-400">({{ $rental->late_days }} hari telat)</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs capitalize">
                                    {{ $rental->return_condition ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($rental->status === 'dipinjam')
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/30">
                                            Dipinjam
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                            Dikembalikan
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-bold {{ $rental->fine > 0 ? 'text-rose-400' : 'text-slate-400' }}">
                                    Rp {{ number_format($rental->fine, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-slate-500">
                                    Tidak ada data riwayat yang sesuai dengan kriteria filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($rentals->hasPages())
                <div class="px-6 py-4 border-t border-slate-800 bg-slate-900/50">
                    {{ $rentals->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
