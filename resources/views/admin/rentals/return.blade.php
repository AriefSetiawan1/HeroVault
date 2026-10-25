<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-white">Proses Pengembalian Perlengkapan</h1>
                <p class="text-sm text-slate-400 mt-1">Konfirmasi pengembalian unit barang, catat kondisi, dan hitung denda keterlambatan</p>
            </div>
            <a href="{{ route('admin.rentals.active') }}" class="text-sm text-slate-400 hover:text-white">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-6">
            <!-- Informasi Transaksi Sewa -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 rounded-xl bg-slate-800/50 border border-slate-800 text-sm">
                <div>
                    <span class="text-xs text-slate-400 block">Penyewa:</span>
                    <span class="font-bold text-white text-base">{{ $rental->user->name }}</span>
                    <span class="text-xs text-slate-400 block">{{ $rental->user->email }}</span>
                    <span class="text-xs text-amber-400/80 font-mono">{{ $rental->user->profile->phone ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block">Perlengkapan yang Disewa:</span>
                    <span class="font-mono text-xs font-bold text-amber-400">[{{ $rental->gear->unit_code }}]</span>
                    <span class="font-bold text-white text-base">{{ $rental->gear->name }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block">Tanggal Sewa:</span>
                    <span class="font-semibold text-slate-200">{{ $rental->rented_at->translatedFormat('d F Y') }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block">Jatuh Tempo:</span>
                    <span class="font-bold text-amber-400" id="due-date-text">{{ $rental->due_at->translatedFormat('d F Y') }}</span>
                </div>
            </div>

            <!-- Form Proses Pengembalian -->
            <form action="{{ route('admin.rentals.process_return', $rental) }}" method="POST" class="space-y-5">
                @csrf

                <!-- Tanggal Pengembalian -->
                <div>
                    <label for="returned_at" class="block text-sm font-semibold text-slate-300 mb-1">
                        Tanggal Pengembalian <span class="text-rose-400">*</span>
                    </label>
                    <input type="date" id="returned_at" name="returned_at" value="{{ old('returned_at', $today) }}" min="{{ $rental->rented_at->format('Y-m-d') }}" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-amber-400 text-sm">
                    <p class="text-xs text-slate-500 mt-1">Default hari ini (tidak boleh sebelum tanggal sewa: {{ $rental->rented_at->format('d/m/Y') }}).</p>
                    @error('returned_at')
                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kondisi Perlengkapan -->
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">
                        Kondisi Saat Kembali <span class="text-rose-400">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="flex flex-col items-center justify-center p-3 rounded-xl border border-slate-700 bg-slate-800/60 cursor-pointer hover:bg-slate-800 transition">
                            <input type="radio" name="return_condition" value="baik" {{ old('return_condition', 'baik') === 'baik' ? 'checked' : '' }} class="text-amber-500 focus:ring-amber-400 mb-1">
                            <span class="text-sm font-bold text-emerald-400">Baik</span>
                            <span class="text-[10px] text-slate-400">Tanpa cacat</span>
                        </label>
                        <label class="flex flex-col items-center justify-center p-3 rounded-xl border border-slate-700 bg-slate-800/60 cursor-pointer hover:bg-slate-800 transition">
                            <input type="radio" name="return_condition" value="kotor" {{ old('return_condition') === 'kotor' ? 'checked' : '' }} class="text-amber-500 focus:ring-amber-400 mb-1">
                            <span class="text-sm font-bold text-amber-400">Kotor</span>
                            <span class="text-[10px] text-slate-400">Butuh laundry</span>
                        </label>
                        <label class="flex flex-col items-center justify-center p-3 rounded-xl border border-slate-700 bg-slate-800/60 cursor-pointer hover:bg-slate-800 transition">
                            <input type="radio" name="return_condition" value="rusak" {{ old('return_condition') === 'rusak' ? 'checked' : '' }} class="text-amber-500 focus:ring-amber-400 mb-1">
                            <span class="text-sm font-bold text-rose-400">Rusak</span>
                            <span class="text-[10px] text-slate-400">Perlu perbaikan</span>
                        </label>
                    </div>
                    <p class="text-xs text-slate-500 mt-1.5">* Catatan: Kondisi hanya dicatat untuk rekam medis inventaris dan tidak menambah denda.</p>
                    @error('return_condition')
                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kalkulasi Denda Otomatis (Live Preview) -->
                <div class="p-4 rounded-xl bg-slate-800/80 border border-slate-700 space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-400">Keterlambatan:</span>
                        <span id="preview-late-days" class="font-bold text-white">0 Hari</span>
                    </div>
                    <div class="flex items-center justify-between text-sm border-t border-slate-700/60 pt-2">
                        <span class="text-slate-400">Tarif Denda (Rp 50.000 / hari terlambat):</span>
                        <span id="preview-fine" class="font-bold text-amber-400 text-lg">Rp 0</span>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-800">
                    <a href="{{ route('admin.rentals.active') }}" class="px-4 py-2 text-sm text-slate-400 hover:text-white">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl text-sm transition shadow-lg shadow-amber-500/20">
                        Konfirmasi Pengembalian
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const dueDate = new Date("{{ $rental->due_at->format('Y-m-d') }}");
        const returnInput = document.getElementById('returned_at');
        const previewDays = document.getElementById('preview-late-days');
        const previewFine = document.getElementById('preview-fine');

        function updateFine() {
            if (!returnInput.value) return;
            const returnDate = new Date(returnInput.value);
            
            // Set both dates to UTC midnight for accurate diff
            const diffTime = returnDate - dueDate;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            const lateDays = diffDays > 0 ? diffDays : 0;
            const fine = lateDays * 50000;

            previewDays.innerText = lateDays + ' Hari';
            previewFine.innerText = 'Rp ' + fine.toLocaleString('id-ID');
            
            if (lateDays > 0) {
                previewDays.className = 'font-bold text-rose-400';
                previewFine.className = 'font-bold text-rose-400 text-lg';
            } else {
                previewDays.className = 'font-bold text-emerald-400';
                previewFine.className = 'font-bold text-amber-400 text-lg';
            }
        }

        returnInput.addEventListener('change', updateFine);
        updateFine();
    </script>
</x-app-layout>
