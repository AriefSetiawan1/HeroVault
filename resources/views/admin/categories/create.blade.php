<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-black text-white">Tambah Kategori Baru</h1>
            <a href="{{ route('admin.categories.index') }}" class="text-sm text-slate-400 hover:text-white">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl">
            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-300 mb-1">Nama Kategori</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required maxlength="100" placeholder="cth: Kekuatan Super" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 text-sm">
                    @error('name')
                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-slate-300 mb-1">Deskripsi (Opsional)</label>
                    <textarea id="description" name="description" rows="3" placeholder="Penjelasan singkat mengenai kategori ini" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 text-sm">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-800">
                    <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 text-sm text-slate-400 hover:text-white">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl text-sm transition shadow-lg shadow-amber-500/20">
                        Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
