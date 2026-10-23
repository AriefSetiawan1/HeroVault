# 🦸 HeroVault

**Sistem Peminjaman Kostum dan Senjata Superhero Pensiunan**

![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-11%2B-FF2D20?logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-MariaDB-4479A1?logo=mysql&logoColor=white)
![Status](https://img.shields.io/badge/versi-1.0-blue)

HeroVault adalah aplikasi web untuk mengelola penyewaan kostum dan senjata milik superhero yang sudah pensiun di Kota Metrodana. Warga bisa menyewa jubah, palu, topeng, dan perisai untuk cosplay, syuting film, lomba 17-an, atau sekadar gaya.

Aplikasi ini menggantikan pencatatan manual di buku yang sering bermasalah: perlengkapan tercatat disewa dua orang, denda tidak dihitung, dan riwayat sulit dicari.

---

## 📑 Daftar Isi

- [Fitur Utama](#-fitur-utama)
- [Peran Pengguna](#-peran-pengguna)
- [Aturan Sewa dan Denda](#-aturan-sewa-dan-denda)
- [Teknologi](#-teknologi)
- [Struktur Database](#-struktur-database)
- [Instalasi](#-instalasi)
- [Akun Demo](#-akun-demo)
- [Skenario Pengujian (UAT)](#-skenario-pengujian-uat)
- [Alur Kerja Git](#-alur-kerja-git)
- [Tim](#-tim)

---

## ✨ Fitur Utama

| Kode | Fitur               | Keterangan                                                         |
| ---- | ------------------- | ------------------------------------------------------------------ |
| F-01 | Registrasi anggota  | Calon anggota mendaftar sendiri, akun dan profil dibuat sekaligus  |
| F-02 | Login dan logout    | Dibawa ke dashboard sesuai role                                    |
| F-03 | Profil saya         | Ubah nama, no HP, alamat, foto, dan password                       |
| F-04 | Dashboard           | Ringkasan untuk Admin dan Anggota                                  |
| F-05 | Kelola perlengkapan | CRUD, kode unit unik, satu perlengkapan bisa punya banyak kategori |
| F-06 | Kelola kategori     | CRUD kategori                                                      |
| F-07 | Kelola anggota      | CRUD anggota beserta profilnya                                     |
| F-08 | Cari perlengkapan   | Pencarian berdasarkan nama                                         |
| F-09 | Sewa perlengkapan   | Langsung aktif tanpa persetujuan Admin                             |
| F-10 | Perlengkapan saya   | Anggota hanya melihat sewa miliknya                                |
| F-11 | Perlengkapan disewa | Daftar sewa aktif dengan label **Terlambat**                       |
| F-12 | Proses pengembalian | Hitung denda otomatis, catat kondisi barang                        |
| F-13 | Riwayat dan cetak   | Filter tanggal, anggota, status, lalu cetak                        |
| F-14 | Validasi form       | Validasi di server, pesan error berbahasa Indonesia                |

---

## 👥 Peran Pengguna

| Pengguna                   | Yang bisa dilakukan                                                                                 | Batasan                                                                    |
| -------------------------- | --------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------- |
| **Tamu**                   | Membuka halaman login dan registrasi                                                                | Halaman lain diarahkan ke login                                            |
| **Anggota**                | Mencari dan menyewa perlengkapan (maks. 2), melihat sewa miliknya, mengubah profil                  | Tidak bisa membuka halaman Admin dan tidak bisa memproses pengembalian     |
| **Admin (Penjaga Gudang)** | Mengelola perlengkapan, kategori, dan anggota, memproses pengembalian, melihat dan mencetak riwayat | Tidak bisa menghapus perlengkapan atau anggota yang masih punya sewa aktif |

Semua route Admin berada di bawah awalan `/admin` dan dilindungi middleware role. Anggota yang mencoba membukanya akan melihat halaman **403 Akses Ditolak**.

---

## 📜 Aturan Sewa dan Denda

- Status perlengkapan hanya **tersedia** atau **disewa**.
- Satu transaksi hanya untuk satu perlengkapan.
- Satu anggota paling banyak punya **2 sewa aktif**.
- Jatuh tempo otomatis: **tanggal sewa + 5 hari**.
- Denda **Rp50.000 per hari** mulai hari ke-6, dihitung saat Admin memproses pengembalian.
- Hari terlambat = tanggal kembali − jatuh tempo (minimal 0).
- Kondisi saat kembali (baik, kotor, rusak) hanya dicatat dan **tidak** menambah denda.
- Hanya Admin yang bisa memproses pengembalian.

**Contoh** (sewa 1 Oktober 2026, jatuh tempo 6 Oktober 2026):

| Tanggal kembali | Hari terlambat |     Denda |
| --------------- | :------------: | --------: |
| 6 Oktober 2026  |       0        |       Rp0 |
| 7 Oktober 2026  |       1        |  Rp50.000 |
| 8 Oktober 2026  |       2        | Rp100.000 |

---

## 🛠 Teknologi

| Bagian            | Teknologi                                                      |
| ----------------- | -------------------------------------------------------------- |
| Bahasa            | PHP 8.2+                                                       |
| Framework         | Laravel 11+                                                    |
| Database          | MySQL / MariaDB (Migration dan Seeder)                         |
| ORM               | Eloquent                                                       |
| Tampilan          | Blade, Bootstrap atau Tailwind CSS, Vite                       |
| Autentikasi       | Laravel Breeze (Blade)                                         |
| Hak akses         | Middleware role (`admin` / `user`)                             |
| Validasi          | Form Request Laravel                                           |
| Upload foto       | Laravel Storage (disk `public`)                                |
| Tanggal dan denda | Carbon                                                         |
| Cetak             | Halaman Blade khusus cetak, opsional `barryvdh/laravel-dompdf` |
| Version control   | Git                                                            |

---

## 🗄 Struktur Database

Database terdiri dari enam tabel dengan foreign key dan unique key.

| Tabel           | Fungsi                                 |
| --------------- | -------------------------------------- |
| `users`         | Akun pengguna (`role`: admin / user)   |
| `profiles`      | Data profil, relasi 1:1 dengan `users` |
| `categories`    | Kategori perlengkapan                  |
| `gears`         | Perlengkapan dengan `unit_code` unik   |
| `category_gear` | Tabel pivot gears dan categories (M:N) |
| `rentals`       | Transaksi sewa                         |

**Relasi:**

- `users` 1:1 `profiles`
- `users` 1:N `rentals` (lewat `user_id` sebagai penyewa dan `processed_by` sebagai Admin pemroses)
- `gears` 1:N `rentals`
- `gears` M:N `categories` (lewat `category_gear`)

---

## 🚀 Instalasi

### Prasyarat

- PHP 8.2 atau lebih baru
- Composer
- MySQL atau MariaDB
- Node.js dan npm
- Git

### Langkah

```bash
# 1. Clone repositori
git clone <url-repositori>
cd herovault

# 2. Pasang dependensi PHP dan JavaScript
composer install
npm install

# 3. Salin file environment dan buat app key
cp .env.example .env
php artisan key:generate
```

Buat database kosong (misalnya `herovault`), lalu atur koneksi di file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=herovault
DB_USERNAME=root
DB_PASSWORD=
```

Lanjutkan dengan:

```bash
# 4. Jalankan migration dan seeder
php artisan migrate --seed

# 5. Hubungkan folder storage untuk foto
php artisan storage:link

# 6. Jalankan aplikasi (gunakan dua terminal)
php artisan serve
npm run dev
```

Buka `http://localhost:8000` di browser.

---

## 🔑 Akun Demo

Seeder membuat akun berikut. Password semuanya `password`.

| Role    | Email                  |
| ------- | ---------------------- |
| Admin   | `admin@herovault.test` |
| Anggota | `budi@herovault.test`  |
| Anggota | `siti@herovault.test`  |

**Data awal lain:**

- 4 kategori: Kekuatan Super, Pertahanan, Gaya Doang, Butuh Pelatihan
- 6 perlengkapan: Jubah Terbang (JB-01), Jubah Terbang (JB-02), Palu Petir KW (PL-01), Topeng Kelelawar (TP-01), Perisai Bundar (PR-01), Sarung Tangan Laser (ST-01)

> ⚠️ Akun dan password di atas hanya untuk demo di localhost. Jangan dipakai di lingkungan produksi.

---

## ✅ Skenario Pengujian (UAT)

Pengujian memakai data seeder. Beberapa skenario penting:

| No  | Skenario                                               | Hasil yang diharapkan                 |
| --- | ------------------------------------------------------ | ------------------------------------- |
| 1   | Login sebagai Admin lalu Anggota                       | Masuk ke dashboard masing-masing      |
| 3   | Anggota membuka `/admin`                               | Muncul halaman akses ditolak          |
| 4   | Simpan perlengkapan dengan kode `JB-01` yang sudah ada | Ditolak, kode unit sudah dipakai      |
| 5   | Simpan Jubah Terbang baru dengan kode `JB-03`          | Tersimpan, nama boleh sama            |
| 7   | Cari kata kunci "Jubah"                                | JB-01 dan JB-02 tampil                |
| 8   | Anggota dengan 2 sewa aktif menyewa lagi               | Tombol Sewa nonaktif dan sewa ditolak |
| 9   | Anggota lain menyewa perlengkapan yang sedang disewa   | Sewa ditolak                          |
| 10  | Pengembalian 7 hari setelah tanggal sewa               | Terlambat 2 hari, denda Rp100.000     |
| 13  | Hapus anggota atau perlengkapan yang masih disewa      | Ditolak dengan pesan alasan           |

Daftar lengkap 15 skenario ada di dokumen PRD (bagian 10).

---

## 🌿 Alur Kerja Git

- Setiap fitur dikerjakan di **branch sendiri**.
- Kode dicek anggota lain sebelum di-merge.
- Contoh nama branch: `feature/registrasi`, `feature/sewa-perlengkapan`, `feature/pengembalian-denda`.

---

## 🚫 Di Luar Ruang Lingkup

- Pembayaran online (Admin mencatat sendiri jika denda sudah dibayar)
- Notifikasi email atau WhatsApp
- Aplikasi mobile

---

## 👨‍💻 Tim

| Nama                     | Peran               |
| ------------------------ | ------------------- |
| Muhammad Azka Azkiyaa    | Frontend            |
| Arief Setiawan           | Backend             |
| Avanindra Wira Wicaksana | PPT dan Dokumentasi |

**Nama kelompok:** Kelompok 5
**Nama team:** HeroVault

---

## 📄 Dokumen Pendukung

BRD, flowchart, ERD, dan PRD HeroVault versi 1.0 (22 Oktober 2026) tersedia di folder `Dokumentasi/`.
