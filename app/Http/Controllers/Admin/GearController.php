<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Gear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GearController extends Controller
{
    /**
     * Tampilkan daftar perlengkapan.
     */
    public function index()
    {
        $gears = Gear::with('categories')->paginate(10);
        return view('admin.gears.index', compact('gears'));
    }

    /**
     * Tampilkan form tambah perlengkapan.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.gears.create', compact('categories'));
    }

    /**
     * Simpan perlengkapan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'unit_code' => ['required', 'string', 'max:10', 'unique:gears,unit_code'],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'],
        ], [
            'unit_code.required' => 'Kode unit wajib diisi.',
            'unit_code.max' => 'Kode unit maksimal 10 karakter.',
            'unit_code.unique' => 'Kode unit sudah digunakan.',
            'name.required' => 'Nama perlengkapan wajib diisi.',
            'name.max' => 'Nama perlengkapan maksimal 100 karakter.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format foto harus jpg, jpeg, atau png.',
            'photo.max' => 'Ukuran foto maksimal 2 MB.',
            'categories.required' => 'Pilih minimal satu kategori.',
            'categories.min' => 'Pilih minimal satu kategori.',
        ]);

        $data = $request->only('unit_code', 'name', 'description');

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('gears', 'public');
        }

        $gear = Gear::create($data);
        $gear->categories()->attach($request->categories);

        return redirect()->route('admin.gears.index')
            ->with('success', 'Perlengkapan berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail perlengkapan.
     */
    public function show(Gear $gear)
    {
        $gear->load('categories', 'rentals.user');
        return view('admin.gears.show', compact('gear'));
    }

    /**
     * Tampilkan form edit perlengkapan.
     */
    public function edit(Gear $gear)
    {
        $categories = Category::all();
        $gear->load('categories');
        return view('admin.gears.edit', compact('gear', 'categories'));
    }

    /**
     * Update perlengkapan.
     */
    public function update(Request $request, Gear $gear)
    {
        $request->validate([
            'unit_code' => ['required', 'string', 'max:10', 'unique:gears,unit_code,' . $gear->id],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'],
        ], [
            'unit_code.required' => 'Kode unit wajib diisi.',
            'unit_code.max' => 'Kode unit maksimal 10 karakter.',
            'unit_code.unique' => 'Kode unit sudah digunakan.',
            'name.required' => 'Nama perlengkapan wajib diisi.',
            'name.max' => 'Nama perlengkapan maksimal 100 karakter.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format foto harus jpg, jpeg, atau png.',
            'photo.max' => 'Ukuran foto maksimal 2 MB.',
            'categories.required' => 'Pilih minimal satu kategori.',
            'categories.min' => 'Pilih minimal satu kategori.',
        ]);

        $data = $request->only('unit_code', 'name', 'description');

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($gear->photo) {
                Storage::disk('public')->delete($gear->photo);
            }
            $data['photo'] = $request->file('photo')->store('gears', 'public');
        }

        $gear->update($data);
        $gear->categories()->sync($request->categories);

        return redirect()->route('admin.gears.index')
            ->with('success', 'Perlengkapan berhasil diperbarui.');
    }

    /**
     * Hapus perlengkapan.
     */
    public function destroy(Gear $gear)
    {
        // Cek apakah ada sewa aktif
        if ($gear->rentals()->where('status', 'dipinjam')->exists()) {
            return redirect()->route('admin.gears.index')
                ->with('error', 'Tidak dapat menghapus perlengkapan yang masih disewa.');
        }

        // Hapus foto jika ada
        if ($gear->photo) {
            Storage::disk('public')->delete($gear->photo);
        }

        $gear->delete();

        return redirect()->route('admin.gears.index')
            ->with('success', 'Perlengkapan berhasil dihapus.');
    }
}
