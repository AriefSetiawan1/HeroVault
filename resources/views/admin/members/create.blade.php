<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-black text-white">Tambah Anggota Baru</h1>
            <a href="{{ route('admin.members.index') }}" class="text-sm text-slate-400 hover:text-white">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl">
            <form action="{{ route('admin.members.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-300 mb-1">
                        Nama Lengkap <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required maxlength="100" placeholder="cth: Budi Santoso" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 text-sm">
                    @error('name')
                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-300 mb-1">
                        Email <span class="text-rose-400">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="cth: budi@herovault.test" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 text-sm">
                    @error('email')
                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- No HP -->
                <div>
                    <label for="phone" class="block text-sm font-semibold text-slate-300 mb-1">
                        Nomor HP (10 - 13 digit) <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="cth: 081234567890" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 text-sm">
                    @error('phone')
                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alamat -->
                <div>
                    <label for="address" class="block text-sm font-semibold text-slate-300 mb-1">
                        Alamat <span class="text-rose-400">*</span>
                    </label>
                    <textarea id="address" name="address" rows="2" required placeholder="cth: Jl. Merdeka No. 10, Kota Metrodana" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 text-sm">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-300 mb-1">
                            Password (Min. 8 karakter) <span class="text-rose-400">*</span>
                        </label>
                        <input type="password" id="password" name="password" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 text-sm">
                        @error('password')
                            <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Konfirmasi Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-slate-300 mb-1">
                            Konfirmasi Password <span class="text-rose-400">*</span>
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 text-sm">
                    </div>
                </div>

                <!-- Foto Profil -->
                <div>
                    <label for="photo" class="block text-sm font-semibold text-slate-300 mb-1">Foto Profil (Opsional, Maks 2 MB)</label>
                    <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/jpg" class="w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-amber-400 hover:file:bg-slate-700 file:cursor-pointer">
                    @error('photo')
                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-800">
                    <a href="{{ route('admin.members.index') }}" class="px-4 py-2 text-sm text-slate-400 hover:text-white">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl text-sm transition shadow-lg shadow-amber-500/20">
                        Simpan Anggota
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
