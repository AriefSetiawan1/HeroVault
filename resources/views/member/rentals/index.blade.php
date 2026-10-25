<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-white flex items-center space-x-2">
                    <span>🎒</span>
                    <span>Perlengkapan Saya</span>
                </h1>
                <p class="text-sm text-slate-400 mt-1">Daftar transaksi penyewaan kostum dan senjata milik Anda</p>
            </div>
            <a href="{{ route('member.gears.index') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl text-xs transition shadow-lg shadow-amber-500/20 self-start sm:self-auto">
                + Sewa Lagi
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Informasi Pengembalian PENTING sesuai PRD F-10 -->
        <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 flex items-start space-x-3 text-sm text-amber-200">
            <span class="text-xl">ℹ️</span>
            <div>
                <span class="font-bold text-amber-300 block">Ingin Mengembalikan Perlengkapan?</span>
                <p class="text-xs text-amber-200/90 mt-0.5">
                    <strong>Hubungi Admin (Penjaga Gudang)</strong> di loket gudang Kota Metrodana untuk mengembalikan perlengkapan dan pengecekan kondisi barang fisik. Halaman ini tidak menyediakan tombol pengembalian mandiri.
                </p>
            </div>
        </div>

        <!-- Tabel Riwayat Sewa Saya -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-800/80 text-xs uppercase font-bold text-slate-400 border-b border-slate-700">
                        <tr>
                            <th class="px-6 py-4">Perlengkapan</th>
                            <th class="px-6 py-4">Tanggal Sewa</th>
                            <th class="px-6 py-4">Jatuh Tempo</th>
                            <th class="px-6 py-4">Tanggal Kembali</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Denda</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @forelse($rentals as $rental)
                            @php
                                $isLate = ($rental->status === 'dipinjam') && now()->startOfDay()->greaterThan($rental->due_at->startOfDay());
                                $daysLate = $isLate ? (int) $rental->due_at->startOfDay()->diffInDays(now()->startOfDay()) : 0;
                            @endphp
                            <tr class="hover:bg-slate-800/50 transition {{ $isLate ? 'bg-rose-500/5' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        @if($rental->gear->photo)
                                            <img src="{{ asset('storage/' . $rental->gear->photo) }}" alt="{{ $rental->gear->name }}" class="w-10 h-10 object-cover rounded-lg border border-slate-700">
                                        @else
                                            <div class="w-10 h-10 bg-slate-800 rounded-lg flex items-center justify-center text-lg border border-slate-700">
                                                🛡️
                                            </div>
                                        @endif
                                        <div>
                                            <span class="font-mono text-xs font-bold text-amber-400">[{{ $rental->gear->unit_code }}]</span>
                                            <div class="font-bold text-white">{{ $rental->gear->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-300">
                                    {{ $rental->rented_at->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold {{ $isLate ? 'text-rose-400' : 'text-slate-300' }}">
                                        {{ $rental->due_at->translatedFormat('d M Y') }}
                                    </span>
                                    @if($isLate)
                                        <span class="block text-[10px] text-rose-400 font-bold uppercase mt-0.5">⚠️ Terlambat {{ $daysLate }} Hari</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-400 text-xs">
                                    {{ $rental->returned_at ? $rental->returned_at->translatedFormat('d M Y') : '-' }}
                                    @if($rental->return_condition)
                                        <span class="block text-[11px] text-slate-500 capitalize">Kondisi: {{ $rental->return_condition }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($rental->status === 'dipinjam')
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/30">
                                            Sedang Dipinjam
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                            Sudah Dikembalikan
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-bold {{ $rental->fine > 0 ? 'text-rose-400' : 'text-slate-400' }}">
                                    Rp {{ number_format($rental->fine, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                                    Anda belum pernah menyewa perlengkapan.
                                    <div class="mt-3">
                                        <a href="{{ route('member.gears.index') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl text-xs transition inline-block">
                                            Lihat Katalog Perlengkapan
                                        </a>
                                    </div>
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
