<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-white">Data Kategori Perlengkapan</h1>
                <p class="text-sm text-slate-400 mt-1">Kelola kategori kemampuan atau fungsi perlengkapan</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl text-sm transition">
                + Tambah Kategori
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-800/80 text-xs uppercase font-bold text-slate-400 border-b border-slate-700">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Nama Kategori</th>
                            <th class="px-6 py-4">Deskripsi</th>
                            <th class="px-6 py-4">Jumlah Perlengkapan</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @forelse($categories as $category)
                            <tr class="hover:bg-slate-800/50 transition">
                                <td class="px-6 py-4 text-slate-500">{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                                <td class="px-6 py-4 font-semibold text-white">{{ $category->name }}</td>
                                <td class="px-6 py-4 text-slate-400 text-xs">{{ $category->description ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-xs rounded-full bg-slate-800 text-amber-400 font-semibold border border-slate-700">
                                        {{ $category->gears_count }} item
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-amber-400 rounded-lg text-xs font-semibold transition">
                                        Ubah
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 rounded-lg text-xs font-semibold border border-rose-500/30 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                    Belum ada data kategori.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
                <div class="px-6 py-4 border-t border-slate-800 bg-slate-900/50">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
