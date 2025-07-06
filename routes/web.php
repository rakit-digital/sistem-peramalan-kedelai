<?php

use Illuminate\Support\Facades\Route;

// Redirect halaman utama ke dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Grup untuk halaman utama (yang memerlukan layout)
Route::middleware('web')->group(function () {
    Route::view('/dashboard', 'pages.dashboard')->name('dashboard');
    Route::view('/data-kedelai', 'pages.data-kedelai')->name('data.kedelai');
    Route::view('/peramalan', 'pages.peramalan')->name('peramalan');
    Route::view('/laporan', 'pages.laporan')->name('laporan');
    Route::view('/pengaturan', 'pages.pengaturan')->name('pengaturan');
});