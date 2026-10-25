<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Gear;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ===== USERS & PROFILES =====

        // Admin
        $admin = User::create([
            'name' => 'Admin HeroVault',
            'email' => 'admin@herovault.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        Profile::create([
            'user_id' => $admin->id,
            'phone' => '081200000001',
            'address' => 'Gudang Utama HeroVault, Jl. Pahlawan No. 1, Kota Metrodana',
        ]);

        // Anggota 1 - Budi
        $budi = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@herovault.test',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
        Profile::create([
            'user_id' => $budi->id,
            'phone' => '081300000002',
            'address' => 'Jl. Merdeka No. 10, Kota Metrodana',
        ]);

        // Anggota 2 - Siti
        $siti = User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@herovault.test',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
        Profile::create([
            'user_id' => $siti->id,
            'phone' => '081400000003',
            'address' => 'Jl. Cendrawasih No. 5, Kota Metrodana',
        ]);

        // ===== CATEGORIES =====

        $kekuatanSuper = Category::create([
            'name' => 'Kekuatan Super',
            'description' => 'Perlengkapan yang memberikan atau mensimulasikan kekuatan super.',
        ]);

        $pertahanan = Category::create([
            'name' => 'Pertahanan',
            'description' => 'Perlengkapan untuk bertahan dari serangan.',
        ]);

        $gayaDoang = Category::create([
            'name' => 'Gaya Doang',
            'description' => 'Perlengkapan yang terlihat keren tapi tidak terlalu berguna.',
        ]);

        $butuhPelatihan = Category::create([
            'name' => 'Butuh Pelatihan',
            'description' => 'Perlengkapan yang memerlukan pelatihan khusus untuk digunakan.',
        ]);

        // ===== GEARS =====

        $jubah1 = Gear::create([
            'unit_code' => 'JB-01',
            'name' => 'Jubah Terbang',
            'description' => 'Jubah merah legendaris milik Captain Metrodana. Bisa terbang kalau lagi mood.',
            'status' => 'tersedia',
        ]);
        $jubah1->categories()->attach([$kekuatanSuper->id, $gayaDoang->id]);

        $jubah2 = Gear::create([
            'unit_code' => 'JB-02',
            'name' => 'Jubah Terbang',
            'description' => 'Jubah terbang cadangan. Warnanya agak pudar tapi masih fungsional.',
            'status' => 'tersedia',
        ]);
        $jubah2->categories()->attach([$kekuatanSuper->id]);

        $palu = Gear::create([
            'unit_code' => 'PL-01',
            'name' => 'Palu Petir KW',
            'description' => 'Replika palu petir. Tidak bisa memanggil petir, tapi lumayan buat paku.',
            'status' => 'tersedia',
        ]);
        $palu->categories()->attach([$kekuatanSuper->id, $butuhPelatihan->id]);

        $topeng = Gear::create([
            'unit_code' => 'TP-01',
            'name' => 'Topeng Kelelawar',
            'description' => 'Topeng hitam bergaya kelelawar. Cocok untuk acara malam.',
            'status' => 'tersedia',
        ]);
        $topeng->categories()->attach([$gayaDoang->id]);

        $perisai = Gear::create([
            'unit_code' => 'PR-01',
            'name' => 'Perisai Bundar',
            'description' => 'Perisai bundar anti peluru. Sudah agak penyok tapi masih kokoh.',
            'status' => 'tersedia',
        ]);
        $perisai->categories()->attach([$pertahanan->id, $butuhPelatihan->id]);

        $sarungTangan = Gear::create([
            'unit_code' => 'ST-01',
            'name' => 'Sarung Tangan Laser',
            'description' => 'Sarung tangan yang bisa menembakkan laser. Baterainya tinggal 20%.',
            'status' => 'tersedia',
        ]);
        $sarungTangan->categories()->attach([$kekuatanSuper->id, $butuhPelatihan->id]);
    }
}
