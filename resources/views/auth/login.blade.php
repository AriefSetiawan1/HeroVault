<x-guest-layout>
    <!-- Header -->
    <div class="mb-6 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-amber-500/10 border-2 border-amber-500/40 rounded-2xl text-3xl mb-3 shadow-inner">
            🦸
        </div>
        <h2 class="text-2xl font-black text-slate-800 tracking-tight">Masuk ke HeroVault</h2>
        <p class="text-xs text-slate-500 mt-1">Sistem Peminjaman Kostum dan Senjata Superhero Pensiunan</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Alamat Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="cth: admin@herovault.test" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Kata Sandi" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between text-sm">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-amber-500 shadow-sm focus:ring-amber-400" name="remember">
                <span class="ms-2 text-xs text-slate-600">Ingat Saya</span>
            </label>
        </div>

        <div>
            <button type="submit" class="w-full flex justify-center items-center py-2.5 px-4 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl shadow-md transition">
                Masuk
            </button>
        </div>

        <div class="text-center pt-2">
            <span class="text-xs text-slate-500">Belum punya akun?</span>
            <a href="{{ route('register') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold underline ms-1">
                Daftar sebagai Anggota Baru
            </a>
        </div>
    </form>

    <!-- Akun Demo Info -->
    <div class="mt-6 pt-4 border-t border-slate-200">
        <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-2 text-center">Akun Demo (Password: <code>password</code>)</p>
        <div class="grid grid-cols-2 gap-2 text-xs">
            <button type="button" onclick="document.getElementById('email').value='admin@herovault.test';document.getElementById('password').value='password';" class="p-2 text-left bg-slate-50 hover:bg-amber-50 border border-slate-200 hover:border-amber-300 rounded-lg transition">
                <span class="font-bold block text-red-600">👑 Admin (Penjaga)</span>
                <span class="text-[10px] text-slate-500">admin@herovault.test</span>
            </button>
            <button type="button" onclick="document.getElementById('email').value='budi@herovault.test';document.getElementById('password').value='password';" class="p-2 text-left bg-slate-50 hover:bg-amber-50 border border-slate-200 hover:border-amber-300 rounded-lg transition">
                <span class="font-bold block text-indigo-600">⚡ Anggota (Budi)</span>
                <span class="text-[10px] text-slate-500">budi@herovault.test</span>
            </button>
        </div>
    </div>
</x-guest-layout>
