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

Route::view('/', 'pages.landing.index')->name('landing.page');
// --- RUTE UNTUK AUTENTIKASI ---
// Rute untuk pengguna yang belum login (Guest)
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

// Rute untuk logout (harus sudah login)
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Data Kedelai
    Route::resource('data-kedelai', SoybeanStockController::class)->names('data.kedelai');

    // Peramalan
    Route::get('/peramalan', [ForecastController::class, 'index'])->name('peramalan');
    Route::post('/peramalan/generate', [ForecastController::class, 'generate'])->name('peramalan.generate');

    // Laporan
    Route::get('/laporan', [ReportController::class, 'index'])->name('laporan');

    // Pengaturan Akun
    Route::get('/pengaturan', [ProfileController::class, 'edit'])->name('pengaturan');
    Route::patch('/pengaturan/profile', [ProfileController::class, 'updateProfile'])->name('pengaturan.profile.update');
    Route::patch('/pengaturan/password', [ProfileController::class, 'updatePassword'])->name('pengaturan.password.update');
    Route::post('/pengaturan/photo', [ProfileController::class, 'updatePhoto'])->name('pengaturan.photo.update');
    Route::delete('/pengaturan/photo', [ProfileController::class, 'destroyPhoto'])->name('pengaturan.photo.destroy');
});
