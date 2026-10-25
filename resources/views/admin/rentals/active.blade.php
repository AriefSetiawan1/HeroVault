<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-white flex items-center space-x-2">
                    <span>📦</span>
                    <span>Perlengkapan Sedang Disewa</span>
                </h1>
                <p class="text-sm text-slate-400 mt-1">Daftar transaksi penyewaan aktif yang belum dikembalikan</p>
            </div>
            <a href="{{ route('admin.rentals.history') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold rounded-xl text-xs transition border border-slate-700">
                Lihat Riwayat & Cetak
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-800/80 text-xs uppercase font-bold text-slate-400 border-b border-slate-700">
                        <tr>
                            <th class="px-6 py-4">Penyewa (Anggota)</th>
                            <th class="px-6 py-4">Perlengkapan</th>
                            <th class="px-6 py-4">Tanggal Sewa</th>
                            <th class="px-6 py-4">Jatuh Tempo</th>
                            <th class="px-6 py-4">Status & Keterangan</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @forelse($rentals as $rental)
                            @php
                                $isLate = now()->startOfDay()->greaterThan($rental->due_at->startOfDay());
                                $daysLate = $isLate ? (int) $rental->due_at->startOfDay()->diffInDays(now()->startOfDay()) : 0;
                            @endphp
                            <tr class="hover:bg-slate-800/50 transition {{ $isLate ? 'bg-rose-500/5' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-white">{{ $rental->user->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $rental->user->email }}</div>
                                    <div class="text-xs text-amber-400/80 font-mono mt-0.5">{{ $rental->user->profile->phone ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <span class="font-mono text-xs font-bold text-amber-400">{{ $rental->gear->unit_code }}</span>
                                        <span class="font-semibold text-white">{{ $rental->gear->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-300">
                                    {{ $rental->rented_at->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold {{ $isLate ? 'text-rose-400 font-bold' : 'text-slate-300' }}">
                                        {{ $rental->due_at->translatedFormat('d M Y') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($isLate)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-500/10 text-rose-400 border border-rose-500/30 animate-pulse">
                                            ⚠️ Terlambat ({{ $daysLate }} Hari)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-500/10 text-blue-400 border border-blue-500/30">
                                            ● Masa Sewa Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.rentals.return', $rental) }}" class="inline-flex items-center px-3.5 py-1.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl text-xs transition shadow-md shadow-amber-500/20">
                                        Proses Pengembalian
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                                    <span class="text-2xl block mb-2">🎉</span>
                                    Tidak ada perlengkapan yang sedang disewa saat ini. Semua unit berada di gudang!
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
