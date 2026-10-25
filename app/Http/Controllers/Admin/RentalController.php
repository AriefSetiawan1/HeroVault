<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    /**
     * Tampilkan daftar perlengkapan yang sedang disewa (sewa aktif).
     */
    public function activeRentals()
    {
        $rentals = Rental::where('status', 'dipinjam')
            ->with(['user', 'gear'])
            ->latest('rented_at')
            ->paginate(10);

        return view('admin.rentals.active', compact('rentals'));
    }

    /**
     * Tampilkan form proses pengembalian perlengkapan.
     */
    public function returnForm(Rental $rental)
    {
        if ($rental->status !== 'dipinjam') {
            return redirect()->route('admin.rentals.active')
                ->with('error', 'Sewa ini sudah selesai diproses sebelumnya.');
        }

        $rental->load(['user', 'gear']);
        $today = Carbon::today()->toDateString();

        return view('admin.rentals.return', compact('rental', 'today'));
    }

    /**
     * Eksekusi proses pengembalian, hitung hari terlambat & denda otomatis.
     */
    public function processReturn(Request $request, Rental $rental)
    {
        if ($rental->status !== 'dipinjam') {
            return redirect()->route('admin.rentals.active')
                ->with('error', 'Sewa ini sudah dikembalikan.');
        }

        $rentedAtString = $rental->rented_at->format('Y-m-d');

        $request->validate([
            'returned_at' => [
                'required',
                'date',
                'after_or_equal:' . $rentedAtString,
            ],
            'return_condition' => ['required', 'in:baik,kotor,rusak'],
        ], [
            'returned_at.required' => 'Tanggal kembali wajib diisi.',
            'returned_at.after_or_equal' => 'Tanggal kembali tidak boleh sebelum tanggal sewa (' . $rental->rented_at->format('d/m/Y') . ').',
            'return_condition.required' => 'Kondisi perlengkapan wajib dipilih.',
            'return_condition.in' => 'Kondisi hanya boleh baik, kotor, atau rusak.',
        ]);

        $returnedDate = Carbon::parse($request->returned_at)->startOfDay();
        $dueDate = Carbon::parse($rental->due_at)->startOfDay();

        // Hitung hari terlambat: jika returnedDate > dueDate maka selisih hari, jika tidak maka 0
        $lateDays = 0;
        if ($returnedDate->greaterThan($dueDate)) {
            $lateDays = (int) $dueDate->diffInDays($returnedDate);
        }

        $fine = $lateDays * 50000;

        // Update transaksi rental
        $rental->update([
            'returned_at' => $returnedDate->toDateString(),
            'return_condition' => $request->return_condition,
            'late_days' => $lateDays,
            'fine' => $fine,
            'status' => 'dikembalikan',
            'processed_by' => auth()->id(),
        ]);

        // Kembalikan status perlengkapan menjadi tersedia
        $rental->gear->update([
            'status' => 'tersedia',
        ]);

        $pesan = "Pengembalian berhasil diproses.";
        if ($lateDays > 0) {
            $pesan .= " Terlambat {$lateDays} hari. Denda: Rp " . number_format($fine, 0, ',', '.');
        }

        return redirect()->route('admin.rentals.active')->with('success', $pesan);
    }

    /**
     * Tampilkan riwayat penyewaan lengkap dengan filter.
     */
    public function history(Request $request)
    {
        $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ], [
            'end_date.after_or_equal' => 'Tanggal akhir tidak boleh sebelum tanggal awal.',
        ]);

        $query = Rental::with(['user', 'gear', 'processor']);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('rented_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('rented_at', '<=', $request->end_date);
        }

        // Hitung total denda dari hasil filter
        $totalFine = (clone $query)->sum('fine');

        $rentals = $query->latest('rented_at')->paginate(10)->withQueryString();
        $members = User::where('role', 'user')->orderBy('name')->get();

        return view('admin.rentals.history', compact('rentals', 'members', 'totalFine'));
    }

    /**
     * Tampilan cetak riwayat sewa tanpa menu navigasi.
     */
    public function printHistory(Request $request)
    {
        $query = Rental::with(['user', 'gear', 'processor']);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('rented_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('rented_at', '<=', $request->end_date);
        }

        $totalFine = (clone $query)->sum('fine');
        $rentals = $query->latest('rented_at')->get();
        $selectedMember = $request->filled('user_id') ? User::find($request->user_id) : null;

        return view('admin.rentals.print', compact('rentals', 'totalFine', 'selectedMember'));
    }
}
