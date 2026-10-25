<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gear;
use App\Models\Rental;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard admin dengan ringkasan statistik.
     */
    public function index()
    {
        $totalGears = Gear::count();
        $availableGears = Gear::where('status', 'tersedia')->count();
        $rentedGears = Gear::where('status', 'disewa')->count();
        $totalMembers = User::where('role', 'user')->count();
        $lateRentals = Rental::where('status', 'dipinjam')
            ->where('due_at', '<', now())
            ->count();
        $activeRentals = Rental::where('status', 'dipinjam')->count();

        return view('admin.dashboard', compact(
            'totalGears',
            'availableGears',
            'rentedGears',
            'totalMembers',
            'lateRentals',
            'activeRentals'
        ));
    }
}
