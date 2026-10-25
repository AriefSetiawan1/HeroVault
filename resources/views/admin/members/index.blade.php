<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-white">Data Anggota Superhero</h1>
                <p class="text-sm text-slate-400 mt-1">Kelola akun dan profil warga terdaftar di HeroVault</p>
            </div>
            <a href="{{ route('admin.members.create') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl text-sm transition self-start sm:self-auto shadow-lg shadow-amber-500/20">
                + Tambah Anggota
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
        <!-- Search Bar -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 shadow-lg">
            <form method="GET" action="{{ route('admin.members.index') }}" class="flex gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email anggota..." class="flex-1 bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 text-sm">
                <button type="submit" class="px-5 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-sm font-semibold transition border border-slate-700">
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.members.index') }}" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white rounded-xl text-sm flex items-center transition border border-slate-700">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-800/80 text-xs uppercase font-bold text-slate-400 border-b border-slate-700">
                        <tr>
                            <th class="px-6 py-4">Foto</th>
                            <th class="px-6 py-4">Nama Anggota</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">No. HP</th>
                            <th class="px-6 py-4">Alamat</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @forelse($members as $member)
                            <tr class="hover:bg-slate-800/50 transition">
                                <td class="px-6 py-4">
                                    @if($member->profile && $member->profile->photo)
                                        <img src="{{ asset('storage/' . $member->profile->photo) }}" alt="{{ $member->name }}" class="w-10 h-10 object-cover rounded-full border border-slate-700">
                                    @else
                                        <div class="w-10 h-10 bg-slate-800 rounded-full flex items-center justify-center text-sm font-bold text-amber-400 border border-slate-700">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-bold text-white">{{ $member->name }}</td>
                                <td class="px-6 py-4 text-slate-300">{{ $member->email }}</td>
                                <td class="px-6 py-4 font-mono text-xs text-amber-400">
                                    {{ $member->profile->phone ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-slate-400 text-xs truncate max-w-xs">
                                    {{ $member->profile->address ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('admin.members.edit', $member) }}" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-amber-400 rounded-lg text-xs font-semibold transition">
                                        Ubah
                                    </a>
                                    <form action="{{ route('admin.members.destroy', $member) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota ini?')">
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
                                <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                                    Belum ada data anggota.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($members->hasPages())
                <div class="px-6 py-4 border-t border-slate-800 bg-slate-900/50">
                    {{ $members->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
