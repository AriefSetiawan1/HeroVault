<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GearController as AdminGearController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\RentalController as AdminRentalController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\GearController as MemberGearController;
use App\Http\Controllers\Member\RentalController as MemberRentalController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root ke dashboard masing-masing jika login, atau ke login jika belum
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('member.dashboard');
    }
    return redirect()->route('login');
});

// Route Profil Saya (bisa diakses oleh semua user yang login: Admin & Anggota)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// ==========================================
// ROUTE ADMIN (Prefix /admin, Role admin)
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // CRUD Kategori
    Route::resource('categories', AdminCategoryController::class)->except(['show']);

    // CRUD Perlengkapan
    Route::resource('gears', AdminGearController::class);

    // CRUD Anggota
    Route::resource('members', AdminMemberController::class)->except(['show']);

    // Perlengkapan Disewa & Proses Pengembalian
    Route::get('/rentals/active', [AdminRentalController::class, 'activeRentals'])->name('rentals.active');
    Route::get('/rentals/{rental}/return', [AdminRentalController::class, 'returnForm'])->name('rentals.return');
    Route::post('/rentals/{rental}/return', [AdminRentalController::class, 'processReturn'])->name('rentals.process_return');

    // Riwayat Penyewaan & Cetak Laporan
    Route::get('/rentals/history', [AdminRentalController::class, 'history'])->name('rentals.history');
    Route::get('/rentals/history/print', [AdminRentalController::class, 'printHistory'])->name('rentals.print');
});

// ==========================================
// ROUTE ANGGOTA (Prefix /member, Role user)
// ==========================================
Route::middleware(['auth', 'role:user'])->prefix('member')->name('member.')->group(function () {
    // Dashboard Anggota
    Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');

    // Katalog & Pencarian Perlengkapan
    Route::get('/gears', [MemberGearController::class, 'index'])->name('gears.index');
    Route::get('/gears/{gear}', [MemberGearController::class, 'show'])->name('gears.show');

    // Transaksi Sewa
    Route::post('/gears/{gear}/rent', [MemberGearController::class, 'rent'])->name('gears.rent');

    // Perlengkapan Saya (Daftar Sewa)
    Route::get('/my-rentals', [MemberRentalController::class, 'index'])->name('rentals.index');
});

require __DIR__ . '/auth.php';
