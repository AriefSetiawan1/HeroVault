<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    /**
     * Tampilkan daftar transaksi sewa milik anggota yang sedang login (Perlengkapan Saya).
     */
    public function index()
    {
        $rentals = Rental::where('user_id', auth()->id())
            ->with('gear')
            ->latest('rented_at')
            ->paginate(10);

        return view('member.rentals.index', compact('rentals'));
    }
}
