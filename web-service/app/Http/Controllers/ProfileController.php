<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Validasi file: gambar, maks 2MB
        ]);

        $user = $request->user();

        // Hapus foto lama jika ada, untuk menghemat space
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        // Simpan foto baru di 'storage/app/public/photos' dan dapatkan path-nya
        $path = $request->file('photo')->store('photos', 'public');

        // Simpan path foto baru ke database
        $user->update(['photo' => $path]);

        return redirect()->route('pengaturan')->with('status', 'Foto profil berhasil diperbarui!');
    }

    /**
     * Menghapus foto profil pengguna.
     */
    public function destroyPhoto(Request $request)
    {
        $user = $request->user();

        if ($user->photo) {
            // Hapus file dari storage
            Storage::disk('public')->delete($user->photo);

            // Hapus referensi dari database
            $user->update(['photo' => null]);

            return redirect()->route('pengaturan')->with('status', 'Foto profil berhasil dihapus.');
        }

        return redirect()->route('pengaturan');
    }
}
