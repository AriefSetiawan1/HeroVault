<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-black text-slate-800">Daftar Anggota Baru</h2>
        <p class="text-sm text-slate-500 mt-1">Sewa kostum & perlengkapan superhero untuk keperluan Anda</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Nama Lengkap" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="cth: Budi Santoso" />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Alamat Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="cth: budi@herovault.test" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- No HP (Phone) -->
        <div>
            <x-input-label for="phone" value="Nomor HP (10 - 13 digit)" />
            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required placeholder="cth: 081234567890" />
            <x-input-error :messages="$errors->get('phone')" class="mt-1" />
        </div>

        <!-- Address -->
        <div>
            <x-input-label for="address" value="Alamat Tempat Tinggal" />
            <textarea id="address" name="address" rows="2" required class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" placeholder="cth: Jl. Merdeka No. 10, Kota Metrodana">{{ old('address') }}</textarea>
            <x-input-error :messages="$errors->get('address')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Kata Sandi (Minimal 8 karakter)" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="flex items-center justify-between pt-2">
            <a class="text-sm text-indigo-600 hover:text-indigo-800 font-medium underline" href="{{ route('login') }}">
                Sudah punya akun? Masuk
            </a>

            <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-sm rounded-xl shadow-md transition">
                Daftar Sekarang
            </button>
        </div>
    </form>
</x-guest-layout>
