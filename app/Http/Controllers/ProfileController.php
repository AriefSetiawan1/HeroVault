<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman Profil Saya.
     */
    public function edit(Request $request): View
    {
        $user = $request->user()->load('profile');
        return view('profile.edit', compact('user'));
    }

    /**
     * Update profil pengguna: nama, no HP, alamat, foto, dan password (opsional).
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['required', 'regex:/^[0-9]{10,13}$/'],
            'address' => ['required', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 100 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'phone.regex' => 'Nomor HP harus berupa angka 10 sampai 13 digit.',
            'address.required' => 'Alamat wajib diisi.',
            'photo.image' => 'File foto harus berupa gambar.',
            'photo.mimes' => 'Format foto harus jpg, jpeg, atau png.',
            'photo.max' => 'Ukuran foto maksimal 2 MB.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        $profileData = [
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        if ($request->hasFile('photo')) {
            if ($user->profile && $user->profile->photo) {
                Storage::disk('public')->delete($user->profile->photo);
            }
            $profileData['photo'] = $request->file('photo')->store('profiles', 'public');
        }

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        return redirect()->route('profile.edit')->with('success', 'Profil Anda berhasil diperbarui.');
    }
}
