<nav x-data="{ open: false }" class="bg-slate-900 border-b border-slate-800 text-white shadow-md sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center space-x-6">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('member.dashboard') }}" class="flex items-center space-x-2 text-xl font-black tracking-tight text-white hover:text-amber-400 transition">
                        <span class="text-2xl">🦸</span>
                        <span class="bg-gradient-to-r from-amber-400 to-red-500 bg-clip-text text-transparent">HeroVault</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 md:-my-px md:ms-6 md:flex">
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-amber-500 text-slate-950 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.gears.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.gears.*') ? 'bg-amber-500 text-slate-950 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Perlengkapan
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.categories.*') ? 'bg-amber-500 text-slate-950 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Kategori
                        </a>
                        <a href="{{ route('admin.members.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.members.*') ? 'bg-amber-500 text-slate-950 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Anggota
                        </a>
                        <a href="{{ route('admin.rentals.active') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.rentals.active') || request()->routeIs('admin.rentals.return') ? 'bg-amber-500 text-slate-950 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Sewa Aktif
                        </a>
                        <a href="{{ route('admin.rentals.history') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.rentals.history') ? 'bg-amber-500 text-slate-950 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Riwayat
                        </a>
                    @else
                        <a href="{{ route('member.dashboard') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('member.dashboard') ? 'bg-amber-500 text-slate-950 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('member.gears.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('member.gears.*') ? 'bg-amber-500 text-slate-950 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Katalog Perlengkapan
                        </a>
                        <a href="{{ route('member.rentals.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('member.rentals.*') ? 'bg-amber-500 text-slate-950 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Perlengkapan Saya
                        </a>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden md:flex md:items-center md:ms-6">
                <!-- Role Badge -->
                <span class="me-3 px-2.5 py-1 text-xs font-semibold rounded-full {{ auth()->user()->isAdmin() ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 'bg-cyan-500/20 text-cyan-400 border border-cyan-500/30' }}">
                    {{ auth()->user()->isAdmin() ? 'Penjaga Gudang (Admin)' : 'Anggota Superhero' }}
                </span>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-slate-700 text-sm leading-4 font-medium rounded-lg text-slate-200 bg-slate-800 hover:bg-slate-700 hover:text-white focus:outline-none transition">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 text-xs text-slate-400 border-b border-slate-100">
                            Masuk sebagai: <strong>{{ Auth::user()->email }}</strong>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profil Saya') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="text-red-600 hover:bg-red-50">
                                {{ __('Keluar (Log Out)') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center md:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-800 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden border-t border-slate-800 bg-slate-900 px-4 pt-2 pb-4 space-y-1">
        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-amber-500 text-slate-950 font-bold' : 'text-slate-300 hover:bg-slate-800' }}">Dashboard Admin</a>
            <a href="{{ route('admin.gears.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.gears.*') ? 'bg-amber-500 text-slate-950 font-bold' : 'text-slate-300 hover:bg-slate-800' }}">Data Perlengkapan</a>
            <a href="{{ route('admin.categories.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.categories.*') ? 'bg-amber-500 text-slate-950 font-bold' : 'text-slate-300 hover:bg-slate-800' }}">Data Kategori</a>
            <a href="{{ route('admin.members.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.members.*') ? 'bg-amber-500 text-slate-950 font-bold' : 'text-slate-300 hover:bg-slate-800' }}">Data Anggota</a>
            <a href="{{ route('admin.rentals.active') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.rentals.active') ? 'bg-amber-500 text-slate-950 font-bold' : 'text-slate-300 hover:bg-slate-800' }}">Perlengkapan Disewa</a>
            <a href="{{ route('admin.rentals.history') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.rentals.history') ? 'bg-amber-500 text-slate-950 font-bold' : 'text-slate-300 hover:bg-slate-800' }}">Riwayat Penyewaan</a>
        @else
            <a href="{{ route('member.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('member.dashboard') ? 'bg-amber-500 text-slate-950 font-bold' : 'text-slate-300 hover:bg-slate-800' }}">Dashboard Anggota</a>
            <a href="{{ route('member.gears.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('member.gears.*') ? 'bg-amber-500 text-slate-950 font-bold' : 'text-slate-300 hover:bg-slate-800' }}">Katalog Perlengkapan</a>
            <a href="{{ route('member.rentals.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('member.rentals.*') ? 'bg-amber-500 text-slate-950 font-bold' : 'text-slate-300 hover:bg-slate-800' }}">Perlengkapan Saya</a>
        @endif

        <div class="pt-3 border-t border-slate-800">
            <div class="px-3 text-sm text-slate-400">Login: {{ Auth::user()->name }} ({{ Auth::user()->role }})</div>
            <a href="{{ route('profile.edit') }}" class="block mt-2 px-3 py-2 rounded-md text-base text-slate-300 hover:bg-slate-800">Profil Saya</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 rounded-md text-base text-red-400 hover:bg-slate-800">Keluar (Log Out)</button>
            </form>
        </div>
    </div>
</nav>
