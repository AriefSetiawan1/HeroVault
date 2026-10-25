<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class MemberController extends Controller
{
    /**
     * Tampilkan daftar anggota.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'user')->with('profile');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $members = $query->latest()->paginate(10);

        return view('admin.members.index', compact('members'));
    }

    /**
     * Tampilkan form tambah anggota.
     */
    public function create()
    {
        return view('admin.members.create');
    }

    /**
     * Simpan anggota baru beserta profilnya.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'regex:/^[0-9]{10,13}$/'],
            'address' => ['required', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 100 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'phone.regex' => 'Nomor HP harus berupa angka 10 sampai 13 digit.',
            'address.required' => 'Alamat wajib diisi.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format foto harus jpg, jpeg, atau png.',
            'photo.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('profiles', 'public');
        }

        $user->profile()->create([
            'phone' => $request->phone,
            'address' => $request->address,
            'photo' => $photoPath,
        ]);

        return redirect()->route('admin.members.index')
            ->with('success', 'Anggota berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit anggota.
     */
    public function edit(User $member)
    {
        if ($member->role !== 'user') {
            abort(404);
        }

        $member->load('profile');
        return view('admin.members.edit', compact('member'));
    }

    /**
     * Update data anggota dan profil.
     */
    public function update(Request $request, User $member)
    {
        if ($member->role !== 'user') {
            abort(404);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $member->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'regex:/^[0-9]{10,13}$/'],
            'address' => ['required', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 100 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'phone.regex' => 'Nomor HP harus berupa angka 10 sampai 13 digit.',
            'address.required' => 'Alamat wajib diisi.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format foto harus jpg, jpeg, atau png.',
            'photo.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $member->update($userData);

        $profileData = [
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        if ($request->hasFile('photo')) {
            if ($member->profile && $member->profile->photo) {
                Storage::disk('public')->delete($member->profile->photo);
            }
            $profileData['photo'] = $request->file('photo')->store('profiles', 'public');
        }

        $member->profile()->updateOrCreate(
            ['user_id' => $member->id],
            $profileData
        );

        return redirect()->route('admin.members.index')
            ->with('success', 'Data anggota berhasil diperbarui.');
    }

    /**
     * Hapus anggota beserta profilnya (Proteksi: jika ada sewa aktif, tolak!).
     */
    public function destroy(User $member)
    {
        if ($member->role !== 'user') {
            abort(404);
        }

        // Cek apakah anggota masih punya sewa aktif (status: dipinjam)
        if ($member->rentals()->where('status', 'dipinjam')->exists()) {
            return redirect()->route('admin.members.index')
                ->with('error', 'Tidak dapat menghapus anggota yang masih memiliki sewa aktif.');
        }

        if ($member->profile && $member->profile->photo) {
            Storage::disk('public')->delete($member->profile->photo);
        }

        $member->delete();

        return redirect()->route('admin.members.index')
            ->with('success', 'Anggota berhasil dihapus.');
    }
}
