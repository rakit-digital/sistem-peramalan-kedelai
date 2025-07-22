<?php

namespace App\Http\Controllers;

use App\Models\Forecast;
use App\Models\SoybeanStock; // <-- Tambahkan model ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Pagination\LengthAwarePaginator; // <-- Tambahkan ini untuk paginasi manual
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

    /**
     * Menampilkan halaman hasil peramalan yang tersimpan di database.
     */
    public function showResults(Request $request)
    {
        $latestDate = SoybeanStock::latest('date')->value('date');
        // 1. Ambil SEMUA data ramalan masa depan (tidak dipaginasi dulu)
        $allFutureForecasts = Forecast::where('forecast_date', '>=', $latestDate)
                                    ->orderBy('forecast_date', 'asc')
                                    ->get();

        // 2. Ambil data penting untuk kalkulasi
        $latestStock = SoybeanStock::latest('date')->first();
        $runningStock = $latestStock ? $latestStock->closing_stock_kg : 0;
        $avgUsage = SoybeanStock::where('date', '>=', now()->subDays(30))->avg('usage_kg') ?: 30; // Default 30kg jika tidak ada data

        // 3. Lakukan iterasi untuk menghitung estimasi stok
        $forecastsWithEstimates = $allFutureForecasts->map(function ($forecast) use (&$runningStock) {
            // Asumsi tidak ada pembelian di masa depan untuk skenario "jika tidak melakukan apa-apa"
            $runningStock -= $forecast->predicted_usage_kg;
            $forecast->estimated_closing_stock = $runningStock;
            return $forecast;
        });

        // 4. Buat Paginator secara manual dari koleksi yang sudah dihitung
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentPageItems = $forecastsWithEstimates->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedForecasts = new LengthAwarePaginator($currentPageItems, count($forecastsWithEstimates), $perPage);
        $paginatedForecasts->setPath($request->url());

        // 5. Hitung total prediksi (bisa dari koleksi sebelum di-map)
        $sevenDayTotal = $allFutureForecasts->where('forecast_date', '<=', today()->addDays(6))->sum('predicted_usage_kg');
        $thirtyDayTotal = $allFutureForecasts->where('forecast_date', '<=', today()->addDays(29))->sum('predicted_usage_kg');

        return view('pages.forecast-result.index', [
            'forecasts' => $paginatedForecasts,
            'sevenDayTotal' => $sevenDayTotal,
            'thirtyDayTotal' => $thirtyDayTotal,
            'latestStock' => $latestStock,
            'warningThreshold' => $avgUsage * 3, // Stok dianggap 'rendah' jika kurang dari kebutuhan 3 hari
        ]);
    }

    /**
     * Menerima permintaan AJAX untuk men-generate peramalan.
     */
    public function generate(Request $request)
    {
        // ... (kode ini tidak berubah)
        $validated = $request->validate([
            'days' => 'required|integer|in:7,14,30'
        ]);

        $daysToForecast = $validated['days'];
        $flaskApiUrl = config('tahumelati.flask_api_url') . '/forecast';

        try {
            $response = Http::timeout(30)->get($flaskApiUrl, [
                'days' => $daysToForecast,
            ]);

            if (!$response->successful()) {
                $errorData = $response->json();
                $errorMessage = $errorData['error'] ?? 'Terjadi kesalahan pada layanan peramalan.';
                return response()->json(['error' => $errorMessage], $response->status());
            }
            
            $forecasts = array_map(function($item) {
                return [
                    'tanggal' => $item['tanggal'],
                    'prediksi_stok_kg' => $item['prediksi_stok_kg']
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
        // ... (kode ini tidak berubah)
        $validated = $request->validate([
            'forecasts' => 'required|array',
            'forecasts.*.tanggal' => 'required|date_format:Y-m-d',
            'forecasts.*.prediksi_stok_kg' => 'required|numeric|min:0',
        ]);

        $upsertData = [];
        foreach ($validated['forecasts'] as $forecast) {
            $upsertData[] = [
                'forecast_date' => $forecast['tanggal'],
                'predicted_usage_kg' => $forecast['prediksi_stok_kg'],
                'generated_at' => now(),
                'source' => 'manual_forecast_v1',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Forecast::upsert(
            $upsertData,
            ['forecast_date'],
            ['predicted_usage_kg', 'generated_at', 'source', 'updated_at']
        );

        return response()->json(['message' => 'Hasil peramalan berhasil disimpan ke database.']);
    }
}