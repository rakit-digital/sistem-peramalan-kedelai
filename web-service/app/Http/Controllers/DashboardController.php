<?php

namespace App\Http\Controllers;

use App\Models\SoybeanStock;
use App\Models\Forecast;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Card 1: Stok Kedelai Saat Ini
        $latestStock = SoybeanStock::latest('date')->first();
        $currentStock = $latestStock ? $latestStock->closing_stock_kg : 0;

        // Card 2: Prediksi Kebutuhan Besok
        $tomorrowPrediction = Forecast::where('forecast_date', today()->addDay())->first();
        $predictedUsageTomorrow = $tomorrowPrediction ? $tomorrowPrediction->predicted_usage_kg : 0;
        
        // Card 3: Rata-rata Penggunaan 30 Hari Terakhir
        $avgUsage = SoybeanStock::where('date', '>=', now()->subDays(30))->avg('usage_kg');

        // Data untuk Grafik Tren (7 hari terakhir)
        $chartData = $this->prepareChartData();

        return view('pages.dashboard', [
            'currentStock' => $currentStock,
            'predictedUsageTomorrow' => $predictedUsageTomorrow,
            'avgUsage' => $avgUsage,
            'chartData' => $chartData
        ]);
    }

    private function prepareChartData()
    {
        $labels = [];
        $actualData = [];
        $predictedData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $labels[] = $date->isoFormat('dddd, D MMM'); // Format hari dan tanggal (misal: Senin, 15 Jan)

            // Ambil data penggunaan aktual
            $actual = SoybeanStock::whereDate('date', $date)->value('usage_kg');
            $actualData[] = $actual ?? 0;
            
            // Ambil data peramalan untuk hari yang sama
            // Kita asumsikan peramalan sudah ada di DB.
            // Jika tidak, kita bisa mengambil dari data historis
            $predicted = Forecast::whereDate('forecast_date', $date)->value('predicted_usage_kg');
            // Jika tidak ada data ramalan, kita bisa gunakan data aktual sebagai placeholder
            $predictedData[] = $predicted ?? $actual ?? 0;
        }

        return [
            'labels' => $labels,
            'actual' => $actualData,
            'predicted' => $predictedData,
        ];
    }
}