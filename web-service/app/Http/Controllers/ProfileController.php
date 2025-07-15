<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman form untuk mengedit profil.
     */
    public function edit()
    {
        return view('pages.pengaturan', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Memperbarui informasi profil pengguna (Nama & Email).
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            // Tambahkan validasi untuk field lain jika ada (phone_number, gender, dll)
        ]);

        $user->fill($request->only(['name', 'email']));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('pengaturan')->with('status', 'Informasi profil berhasil diperbarui!');
    }

    /**
     * Memperbarui password pengguna.
     */
    public function updatePassword(Request $request)
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);
        
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('pengaturan')->with('status', 'Kata sandi berhasil diperbarui!');
    }

    // Anda bisa menambahkan method untuk update foto di sini nanti
    // public function updatePhoto(Request $request) { ... }
}