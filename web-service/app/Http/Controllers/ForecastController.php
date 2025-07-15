<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Exception;

class ForecastController extends Controller
{
    // Definisikan URL API Python di satu tempat agar mudah diubah
    private const PYTHON_API_URL = 'http://127.0.0.1:5000/forecast';

    /**
     * Menampilkan halaman utama peramalan.
     */
    public function index()
    {
        return view('pages.peramalan');
    }

    /**
     * Menerima permintaan AJAX untuk men-generate peramalan.
     */
    public function generate(Request $request)
    {
        // 1. Validasi input dari frontend
        $validated = $request->validate([
            'days' => 'required|integer|in:7,14,30' // Pastikan hanya nilai ini yang diterima
        ]);

        $daysToForecast = $validated['days'];

        try {
            // 2. Kirim request ke API Python menggunakan HTTP Client Laravel
            // Tambahkan timeout untuk mencegah aplikasi Laravel menunggu terlalu lama
            $response = Http::timeout(30)->get(self::PYTHON_API_URL, [
                'days' => $daysToForecast,
            ]);

            // 3. Periksa apakah request ke API Python berhasil
            if (!$response->successful()) {
                // Jika API Python mengembalikan error (misal: status 500)
                return response()->json([
                    'error' => 'Gagal terhubung ke layanan peramalan. Status: ' . $response->status()
                ], 502); // 502 Bad Gateway
            }

            // 4. Jika berhasil, kirimkan kembali data JSON dari Python ke frontend
            return response()->json($response->json());

        } catch (Exception $e) {
            // 5. Tangani jika API Python tidak berjalan atau terjadi error koneksi
            report($e); // Laporkan error ke log Laravel

            return response()->json([
                'error' => 'Tidak dapat terhubung ke layanan peramalan. Pastikan layanan sudah berjalan.'
            ], 503); // 503 Service Unavailable
        }
    }
}