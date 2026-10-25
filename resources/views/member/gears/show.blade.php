<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-black text-white">Detail Perlengkapan</h1>
            <a href="{{ route('member.gears.index') }}" class="text-sm text-slate-400 hover:text-white">
                &larr; Kembali ke Katalog
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-2xl grid grid-cols-1 md:grid-cols-2">
            <!-- Media Area -->
            <div class="bg-slate-800 p-6 flex flex-col justify-between">
                <div>
                    @if($gear->photo)
                        <img src="{{ asset('storage/' . $gear->photo) }}" alt="{{ $gear->name }}" class="w-full h-72 object-cover rounded-xl shadow-lg border border-slate-700">
                    @else
                        <div class="w-full h-72 rounded-xl flex flex-col items-center justify-center bg-slate-900/50 border border-slate-700 text-slate-600">
                            <span class="text-6xl mb-2">🦸</span>
                            <span class="text-xs text-slate-500">Perlengkapan Pahlawan</span>
                        </div>
                    @endif
                </div>

                <div class="mt-6 p-4 rounded-xl bg-slate-900/80 border border-slate-700/60 text-xs space-y-2">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Kode Unit:</span>
                        <span class="font-mono font-bold text-amber-400">{{ $gear->unit_code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Status Ketersediaan:</span>
                        @if($gear->isAvailable())
                            <span class="text-emerald-400 font-bold">● Tersedia di Gudang</span>
                        @else
                            <span class="text-amber-400 font-bold">● Sedang Dipinjam</span>
                        @endif
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Masa Sewa:</span>
                        <span class="text-white font-medium">5 Hari Kalender</span>
                    </div>
                </div>
            </div>

            <!-- Info & Aksi Area -->
            <div class="p-8 flex flex-col justify-between space-y-6">
                <div class="space-y-4">
                    <div>
                        <span class="px-2.5 py-1 text-xs font-mono font-bold rounded-lg bg-amber-500/10 text-amber-400 border border-amber-500/30">
                            {{ $gear->unit_code }}
                        </span>
                        <h2 class="text-2xl font-black text-white mt-2">{{ $gear->name }}</h2>
                    </div>

                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Kategori Kemampuan:</h3>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($gear->categories as $category)
                                <span class="px-3 py-1 bg-slate-800 text-slate-200 rounded-lg text-xs font-medium border border-slate-700">
                                    {{ $category->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Deskripsi & Catatan:</h3>
                        <p class="text-sm text-slate-300 leading-relaxed bg-slate-800/40 p-4 rounded-xl border border-slate-800">
                            {{ $gear->description ?? 'Tidak ada deskripsi khusus untuk perlengkapan ini.' }}
                        </p>
                    </div>

                    <!-- Ketentuan Peminjaman -->
                    <div class="p-3.5 rounded-xl bg-amber-500/5 border border-amber-500/20 text-xs text-amber-300/90 space-y-1">
                        <div class="font-bold text-amber-400">Aturan Peminjaman:</div>
                        <ul class="list-disc list-inside space-y-0.5 text-slate-300">
                            <li>Jatuh tempo otomatis dihitung 5 hari sejak tombol sewa ditekan.</li>
                            <li>Denda keterlambatan sebesar <strong>Rp 50.000 / hari</strong> setelah jatuh tempo.</li>
                            <li>Setiap anggota hanya boleh memegang maksimal 2 perlengkapan aktif sekaligus.</li>
                        </ul>
                    </div>
                </div>

                <!-- Tombol Aksi Sewa -->
                <div class="pt-4 border-t border-slate-800">
                    @if($canRent)
                        <form action="{{ route('member.gears.rent', $gear) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyewa {{ $gear->name }} sekarang?')">
                            @csrf
                            <button type="submit" class="w-full py-3 px-6 bg-amber-500 hover:bg-amber-400 text-slate-950 font-black rounded-xl text-sm transition shadow-xl shadow-amber-500/20 flex items-center justify-center space-x-2">
                                <span>⚡</span>
                                <span>Sewa Perlengkapan Ini Sekarang</span>
                            </button>
                        </form>
                    @else
                        <div class="space-y-2">
                            <button type="button" disabled class="w-full py-3 px-6 bg-slate-800 text-slate-500 font-bold rounded-xl text-sm cursor-not-allowed border border-slate-700">
                                @if(!$gear->isAvailable())
                                    Perlengkapan Sedang Disewa
                                @else
                                    Batas Kuota Tercapai (Maksimal 2 Sewa Aktif)
                                @endif
                            </button>
                            <p class="text-[11px] text-center text-slate-500">
                                @if(!$gear->isAvailable())
                                    Unit ini sedang dipakai oleh anggota lain. Silakan cek kembali nanti.
                                @else
                                    Anda sudah meminjam 2 perlengkapan. Harap kembalikan salah satu unit terlebih dahulu.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
