<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Gear;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GearController extends Controller
{
    /**
     * Tampilkan katalog perlengkapan dengan pencarian nama & filter kategori.
     */
    public function index(Request $request)
    {
        $query = Gear::with('categories');

        // Pencarian berdasarkan nama perlengkapan (misal: "Jubah")
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        // Filter kategori jika ada
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        $gears = $query->latest()->paginate(9)->withQueryString();
        $categories = Category::all();

        // Hitung kuota aktif penyewa saat ini
        $activeRentalsCount = Rental::where('user_id', auth()->id())
            ->where('status', 'dipinjam')
            ->count();

        return view('member.gears.index', compact('gears', 'categories', 'activeRentalsCount'));
    }

    /**
     * Tampilkan detail perlengkapan.
     */
    public function show(Gear $gear)
    {
        $gear->load('categories');

        $activeRentalsCount = Rental::where('user_id', auth()->id())
            ->where('status', 'dipinjam')
            ->count();

        // Tombol sewa hanya aktif jika perlengkapan tersedia DAN kuota sewa < 2
        $canRent = $gear->isAvailable() && ($activeRentalsCount < 2);

        return view('member.gears.show', compact('gear', 'activeRentalsCount', 'canRent'));
    }

    /**
     * Proses transaksi penyewaan oleh anggota.
     */
    public function rent(Gear $gear)
    {
        $user = auth()->user();

        // 1. Validasi: perlengkapan harus berstatus tersedia
        if (!$gear->isAvailable()) {
            return redirect()->back()->with('error', 'Perlengkapan ini sedang disewa atau tidak tersedia.');
        }

        // 2. Validasi: kuota anggota tidak boleh >= 2 sewa aktif
        $activeCount = Rental::where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->count();

        if ($activeCount >= 2) {
            return redirect()->back()->with('error', 'Anda telah mencapai batas maksimal 2 sewa aktif. Harap kembalikan perlengkapan yang sedang disewa terlebih dahulu.');
        }

        $rentedAt = Carbon::today();
        $dueAt = Carbon::today()->addDays(5);

        // Buat record rental baru
        Rental::create([
            'user_id' => $user->id,
            'gear_id' => $gear->id,
            'rented_at' => $rentedAt->toDateString(),
            'due_at' => $dueAt->toDateString(),
            'status' => 'dipinjam',
            'late_days' => 0,
            'fine' => 0,
        ]);

        // Update status perlengkapan menjadi disewa
        $gear->update([
            'status' => 'disewa',
        ]);

        return redirect()->route('member.rentals.index')
            ->with('success', "Berhasil menyewa {$gear->name} ({$gear->unit_code})! Batas pengembalian adalah tanggal {$dueAt->translatedFormat('d F Y')}.");
    }
}
