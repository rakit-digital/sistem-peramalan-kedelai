<?php

namespace App\Http\Controllers;

use App\Models\Forecast; // <-- ADD THIS
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Exception;

class ForecastController extends Controller
{
    /**
     * Menampilkan halaman utama peramalan.
     */
    public function index()
    {
        return view('pages.peramalan');
    }

    public function showResults()
    {
        // Ambil data ramalan untuk masa depan, diurutkan berdasarkan tanggal
        $forecasts = Forecast::where('forecast_date', '>=', today())
            ->orderBy('forecast_date', 'asc')
            ->paginate(14); // Paginate untuk 14 hari per halaman

        // Hitung total prediksi untuk 7 hari ke depan sebagai informasi tambahan
        $sevenDayTotal = Forecast::whereBetween('forecast_date', [today(), today()->addDays(6)])
            ->sum('predicted_usage_kg');

        // Hitung total prediksi untuk 30 hari ke depan
        $thirtyDayTotal = Forecast::whereBetween('forecast_date', [today(), today()->addDays(29)])
            ->sum('predicted_usage_kg');

        return view('pages.forecast-result.index', compact('forecasts', 'sevenDayTotal', 'thirtyDayTotal'));
    }

    /**
     * Menerima permintaan AJAX untuk men-generate peramalan.
     */
    public function generate(Request $request)
    {
        // 1. Validasi input dari frontend
        $validated = $request->validate([
            'days' => 'required|integer|in:7,14,30'
        ]);

        $daysToForecast = $validated['days'];
        $flaskApiUrl = config('tahumelati.flask_api_url') . '/forecast';

        try {
            // 2. Kirim request ke API Python
            $response = Http::timeout(30)->get($flaskApiUrl, [
                'days' => $daysToForecast,
            ]);

            if (!$response->successful()) {
                $errorData = $response->json();
                $errorMessage = $errorData['error'] ?? 'Terjadi kesalahan pada layanan peramalan.';
                return response()->json(['error' => $errorMessage], $response->status());
            }

            // Format the key names to match what the frontend expects, if necessary
            $forecasts = array_map(function ($item) {
                return [
                    'tanggal' => $item['tanggal'], // Already correct from Python
                    'prediksi_stok_kg' => $item['prediksi_stok_kg'] // Already correct
                ];
            }, $response->json());


            return response()->json($forecasts);
        } catch (ConnectionException $e) {
            report($e);
            return response()->json([
                'error' => 'Tidak dapat terhubung ke layanan peramalan. Pastikan layanan Python sudah berjalan.'
            ], 503);
        } catch (Exception $e) {
            report($e);
            return response()->json([
                'error' => 'Terjadi kesalahan tak terduga saat memproses peramalan.'
            ], 500);
        }
    }

    /**
     * Saves or updates forecast results in the database.
     */
    public function save(Request $request)
    {
        // 1. Validate the incoming array of forecasts
        $validated = $request->validate([
            'forecasts' => 'required|array',
            'forecasts.*.tanggal' => 'required|date_format:Y-m-d',
            'forecasts.*.prediksi_stok_kg' => 'required|numeric|min:0',
        ]);

        // 2. Prepare the data for the upsert operation
        $upsertData = [];
        foreach ($validated['forecasts'] as $forecast) {
            $upsertData[] = [
                'forecast_date' => $forecast['tanggal'],
                'predicted_usage_kg' => $forecast['prediksi_stok_kg'],
                'generated_at' => now(),
                'source' => 'manual_forecast_v1', // A source to identify manually saved forecasts
                'created_at' => now(), // Manually set timestamps for upsert
                'updated_at' => now(),
            ];
        }

        // 3. Perform the upsert operation
        Forecast::upsert(
            $upsertData,
            ['forecast_date'], // The unique column to check for existence
            ['predicted_usage_kg', 'generated_at', 'source', 'updated_at'] // Columns to update if the record exists
        );

        // 4. Return a success response
        return response()->json(['message' => 'Hasil peramalan berhasil disimpan ke database.']);
    }
}
