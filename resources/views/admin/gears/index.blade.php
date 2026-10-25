<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-white">Data Perlengkapan Superhero</h1>
                <p class="text-sm text-slate-400 mt-1">Daftar inventaris kostum dan persenjataan di HeroVault</p>
            </div>
            <a href="{{ route('admin.gears.create') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl text-sm transition self-start sm:self-auto shadow-lg shadow-amber-500/20">
                + Tambah Perlengkapan
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-800/80 text-xs uppercase font-bold text-slate-400 border-b border-slate-700">
                        <tr>
                            <th class="px-6 py-4">Kode Unit</th>
                            <th class="px-6 py-4">Nama Perlengkapan</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @forelse($gears as $gear)
                            <tr class="hover:bg-slate-800/50 transition">
                                <td class="px-6 py-4 font-mono font-bold text-amber-400">
                                    {{ $gear->unit_code }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        @if($gear->photo)
                                            <img src="{{ asset('storage/' . $gear->photo) }}" alt="{{ $gear->name }}" class="w-10 h-10 object-cover rounded-lg border border-slate-700">
                                        @else
                                            <div class="w-10 h-10 bg-slate-800 rounded-lg flex items-center justify-center text-lg border border-slate-700">
                                                🛡️
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-bold text-white">{{ $gear->name }}</div>
                                            <div class="text-xs text-slate-400 truncate max-w-xs">{{ $gear->description ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($gear->categories as $cat)
                                            <span class="px-2 py-0.5 text-xs rounded bg-slate-800 text-slate-300 border border-slate-700">
                                                {{ $cat->name }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-slate-500">-</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($gear->status === 'tersedia')
                                        <span class="px-2.5 py-1 text-xs rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 font-semibold">
                                            ● Tersedia
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/30 font-semibold">
                                            ● Disewa
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('admin.gears.show', $gear) }}" class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-semibold transition">
                                        Detail
                                    </a>
                                    <a href="{{ route('admin.gears.edit', $gear) }}" class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-amber-400 rounded-lg text-xs font-semibold transition">
                                        Ubah
                                    </a>
                                    <form action="{{ route('admin.gears.destroy', $gear) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus perlengkapan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1.5 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 rounded-lg text-xs font-semibold border border-rose-500/30 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                    Belum ada data perlengkapan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($gears->hasPages())
                <div class="px-6 py-4 border-t border-slate-800 bg-slate-900/50">
                    {{ $gears->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
