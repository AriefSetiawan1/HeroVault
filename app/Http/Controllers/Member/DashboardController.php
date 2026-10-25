<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard anggota.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Hitung sewa aktif (maksimal 2)
        $activeRentals = Rental::where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->with('gear')
            ->orderBy('due_at', 'asc')
            ->get();

        $activeCount = $activeRentals->count();
        $nearestRental = $activeRentals->first(); // Jatuh tempo terdekat

        return view('member.dashboard', compact('user', 'activeCount', 'nearestRental', 'activeRentals'));
    }
}
