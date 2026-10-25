<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-white flex items-center space-x-2">
                    <span>👤</span>
                    <span>Profil Saya</span>
                </h1>
                <p class="text-sm text-slate-400 mt-1">Perbarui informasi data pribadi dan kredensial akun Anda</p>
            </div>
            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $user->isAdmin() ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 'bg-cyan-500/20 text-cyan-400 border border-cyan-500/30' }}">
                Peran: {{ $user->isAdmin() ? 'Admin (Penjaga Gudang)' : 'Anggota Superhero' }}
            </span>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 md:p-8 shadow-xl space-y-6">
            <!-- Header User Card -->
            <div class="flex items-center space-x-4 pb-6 border-b border-slate-800">
                @if($user->profile && $user->profile->photo)
                    <img src="{{ asset('storage/' . $user->profile->photo) }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full object-cover border-2 border-amber-400 shadow-md">
                @else
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-400 to-amber-600 rounded-full flex items-center justify-center text-slate-950 text-2xl font-black shadow-md">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h2 class="text-xl font-bold text-white">{{ $user->name }}</h2>
                    <p class="text-xs text-slate-400">{{ $user->email }}</p>
                    <span class="inline-block mt-1 text-[11px] font-mono text-amber-400">
                        {{ $user->profile->phone ?? 'Belum ada nomor HP' }}
                    </span>
                </div>
            </div>

            <!-- Form Edit Profil -->
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-300 mb-1">
                        Nama Lengkap <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required maxlength="100" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 text-sm">
                    @error('name')
                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-300 mb-1">
                        Alamat Email <span class="text-rose-400">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 text-sm">
                    @error('email')
                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor HP -->
                <div>
                    <label for="phone" class="block text-sm font-semibold text-slate-300 mb-1">
                        Nomor HP (10 sampai 13 digit) <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->profile->phone ?? '') }}" required placeholder="081234567890" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 text-sm">
                    @error('phone')
                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alamat -->
                <div>
                    <label for="address" class="block text-sm font-semibold text-slate-300 mb-1">
                        Alamat <span class="text-rose-400">*</span>
                    </label>
                    <textarea id="address" name="address" rows="2" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 text-sm">{{ old('address', $user->profile->address ?? '') }}</textarea>
                    @error('address')
                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Foto Profil -->
                <div>
                    <label for="photo" class="block text-sm font-semibold text-slate-300 mb-1">Ganti Foto Profil (Opsional, JPG/PNG, Maks. 2 MB)</label>
                    <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/jpg" class="w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-amber-400 hover:file:bg-slate-700 file:cursor-pointer">
                    @error('photo')
                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bagian Ubah Password (Opsional) -->
                <div class="p-4 rounded-xl bg-slate-800/50 border border-slate-800 space-y-3">
                    <div>
                        <h3 class="text-xs font-bold text-amber-400 uppercase tracking-wider">Ubah Kata Sandi (Opsional)</h3>
                        <p class="text-[11px] text-slate-400">Kosongkan kolom sandi jika Anda tidak ingin mengubah password akun.</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-semibold text-slate-300 mb-1">Kata Sandi Baru</label>
                            <input type="password" id="password" name="password" placeholder="••••••••" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 text-sm">
                            @error('password')
                                <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-xs font-semibold text-slate-300 mb-1">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 text-sm">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end pt-4 border-t border-slate-800">
                    <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl text-sm transition shadow-lg shadow-amber-500/20">
                        Simpan Perubahan Profil
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
