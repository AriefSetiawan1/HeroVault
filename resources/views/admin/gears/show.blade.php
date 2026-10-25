<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-white">Detail Perlengkapan</h1>
                <p class="text-sm text-slate-400 mt-1">Informasi lengkap unit {{ $gear->unit_code }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.gears.edit', $gear) }}" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-amber-400 font-semibold rounded-xl text-xs transition">
                    Ubah
                </a>
                <a href="{{ route('admin.gears.index') }}" class="text-sm text-slate-400 hover:text-white ms-3">
                    &larr; Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-1">
                @if($gear->photo)
                    <img src="{{ asset('storage/' . $gear->photo) }}" alt="{{ $gear->name }}" class="w-full h-64 object-cover rounded-xl border border-slate-700 shadow-lg">
                @else
                    <div class="w-full h-64 bg-slate-800 rounded-xl flex flex-col items-center justify-center text-slate-500 border border-slate-700">
                        <span class="text-5xl mb-2">🛡️</span>
                        <span class="text-xs">Tidak ada foto</span>
                    </div>
                @endif

                <div class="mt-4 text-center">
                    @if($gear->status === 'tersedia')
                        <span class="inline-block px-4 py-1.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 text-xs font-bold uppercase tracking-wider">
                            ● Status: Tersedia
                        </span>
                    @else
                        <span class="inline-block px-4 py-1.5 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/30 text-xs font-bold uppercase tracking-wider">
                            ● Status: Sedang Disewa
                        </span>
                    @endif
                </div>
            </div>

            <div class="md:col-span-2 space-y-4">
                <div>
                    <span class="text-xs font-mono font-bold text-amber-400 uppercase tracking-widest">{{ $gear->unit_code }}</span>
                    <h2 class="text-2xl font-black text-white mt-1">{{ $gear->name }}</h2>
                </div>

                <div>
                    <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Kategori:</h3>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($gear->categories as $category)
                            <span class="px-3 py-1 bg-slate-800 text-slate-300 rounded-lg text-xs font-medium border border-slate-700">
                                {{ $category->name }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Deskripsi:</h3>
                    <p class="text-sm text-slate-300 leading-relaxed bg-slate-800/40 p-4 rounded-xl border border-slate-800">
                        {{ $gear->description ?? 'Tidak ada deskripsi.' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Riwayat Peminjaman Unit Ini -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
            <div class="px-6 py-4 bg-slate-800/50 border-b border-slate-800 flex items-center justify-between">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">Riwayat Peminjaman Unit Ini</h3>
                <span class="text-xs text-slate-400">{{ $gear->rentals->count() }} Kali Dipinjam</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-300">
                    <thead class="bg-slate-800/30 text-slate-400 border-b border-slate-800 uppercase">
                        <tr>
                            <th class="px-6 py-3">Penyewa</th>
                            <th class="px-6 py-3">Tanggal Sewa</th>
                            <th class="px-6 py-3">Jatuh Tempo</th>
                            <th class="px-6 py-3">Tanggal Kembali</th>
                            <th class="px-6 py-3">Kondisi</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3 text-right">Denda</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @forelse($gear->rentals as $rental)
                            <tr class="hover:bg-slate-800/40">
                                <td class="px-6 py-3 font-semibold text-white">{{ $rental->user->name ?? '-' }}</td>
                                <td class="px-6 py-3">{{ $rental->rented_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-3">{{ $rental->due_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-3">{{ $rental->returned_at ? $rental->returned_at->format('d/m/Y') : '-' }}</td>
                                <td class="px-6 py-3 capitalize">{{ $rental->return_condition ?? '-' }}</td>
                                <td class="px-6 py-3">
                                    @if($rental->status === 'dipinjam')
                                        <span class="text-amber-400 font-semibold">Aktif</span>
                                    @else
                                        <span class="text-emerald-400">Kembali</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-right font-mono">
                                    Rp {{ number_format($rental->fine, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-6 text-center text-slate-500">
                                    Belum ada transaksi sewa untuk unit ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
