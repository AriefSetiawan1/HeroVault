<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-white">Ubah Perlengkapan</h1>
                <p class="text-sm text-slate-400 mt-1">Perbarui data unit {{ $gear->unit_code }} ({{ $gear->name }})</p>
            </div>
            <a href="{{ route('admin.gears.index') }}" class="text-sm text-slate-400 hover:text-white">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl">
            <form action="{{ route('admin.gears.update', $gear) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Kode Unit -->
                <div>
                    <label for="unit_code" class="block text-sm font-semibold text-slate-300 mb-1">
                        Kode Unit <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" id="unit_code" name="unit_code" value="{{ old('unit_code', $gear->unit_code) }}" required maxlength="10" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 text-sm font-mono uppercase">
                    @error('unit_code')
                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Perlengkapan -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-300 mb-1">
                        Nama Perlengkapan <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $gear->name) }}" required maxlength="100" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 text-sm">
                    @error('name')
                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pilihan Kategori -->
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">
                        Kategori <span class="text-rose-400">* (Pilih minimal satu)</span>
                    </label>
                    @php
                        $selectedCategories = old('categories', $gear->categories->pluck('id')->toArray());
                    @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 bg-slate-800/60 p-3 rounded-xl border border-slate-700/60">
                        @foreach($categories as $category)
                            <label class="flex items-center space-x-2 text-sm text-slate-300 cursor-pointer p-1.5 rounded hover:bg-slate-700/50 transition">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}" {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }} class="rounded border-slate-600 bg-slate-700 text-amber-500 focus:ring-amber-400">
                                <span>{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('categories')
                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-slate-300 mb-1">Deskripsi</label>
                    <textarea id="description" name="description" rows="3" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 text-sm">{{ old('description', $gear->description) }}</textarea>
                    @error('description')
                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Foto Perlengkapan -->
                <div>
                    <label for="photo" class="block text-sm font-semibold text-slate-300 mb-1">Foto Perlengkapan</label>
                    @if($gear->photo)
                        <div class="mb-2 flex items-center space-x-3">
                            <img src="{{ asset('storage/' . $gear->photo) }}" alt="{{ $gear->name }}" class="w-16 h-16 object-cover rounded-lg border border-slate-700">
                            <span class="text-xs text-slate-400">Foto saat ini terpasang. Unggah baru jika ingin mengganti.</span>
                        </div>
                    @endif
                    <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/jpg" class="w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-amber-400 hover:file:bg-slate-700 file:cursor-pointer">
                    @error('photo')
                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-800">
                    <a href="{{ route('admin.gears.index') }}" class="px-4 py-2 text-sm text-slate-400 hover:text-white">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl text-sm transition shadow-lg shadow-amber-500/20">
                        Perbarui Perlengkapan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
