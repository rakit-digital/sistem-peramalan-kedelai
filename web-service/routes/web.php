<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SoybeanStockController;
use App\Http\Controllers\ForecastController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController; // Ganti dengan controller Anda jika berbeda
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- RUTE UNTUK AUTENTIKASI ---
// Rute untuk pengguna yang belum login (Guest)
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

// Rute untuk logout (harus sudah login)
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');


// --- RUTE UTAMA APLIKASI ---
// Rute Halaman Utama, akan diarahkan ke login atau dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Grup untuk semua halaman yang memerlukan autentikasi (setelah login)
Route::middleware('auth')->group(function () {

    // Dashboard (dari sidebar: route('dashboard'))
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Data Kedelai (dari sidebar: route('data.kedelai'))
    // Perhatikan: request()->routeIs('data.kedelai') akan false.
    // Kita harus menggunakan wildcard: request()->routeIs('data.kedelai.*')
    Route::resource('data-kedelai', SoybeanStockController::class)->names('data.kedelai');

    // Peramalan (dari sidebar: route('peramalan'))
    Route::get('/peramalan', [ForecastController::class, 'index'])->name('peramalan');
    Route::post('/peramalan/generate', [ForecastController::class, 'generate'])->name('peramalan.generate');

    // Laporan (dari sidebar: route('laporan'))
    Route::get('/laporan', [ReportController::class, 'index'])->name('laporan');

    // Pengaturan Akun (dari sidebar: route('pengaturan'))
    Route::get('/pengaturan', [ProfileController::class, 'edit'])->name('pengaturan');

    // Rute khusus untuk update informasi profil (nama, email, dll)
    Route::patch('/pengaturan/profile', [ProfileController::class, 'updateProfile'])->name('pengaturan.profile.update');

    // Rute khusus untuk update password
    Route::patch('/pengaturan/password', [ProfileController::class, 'updatePassword'])->name('pengaturan.password.update');
});
