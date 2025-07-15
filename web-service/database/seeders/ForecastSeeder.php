<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Forecast;

class ForecastSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data ramalan lama untuk diisi dengan yang baru
        // Ini penting karena kolom tanggal bersifat unik
        Forecast::truncate();
        
        $daysToForecast = 30; // Jumlah hari peramalan ke depan

        for ($i = 1; $i <= $daysToForecast; $i++) {
            Forecast::create([
                'forecast_date' => now()->addDays($i),
                'predicted_usage_kg' => rand(25, 55) + (rand(0, 99) / 100), // Prediksi penggunaan
                'generated_at' => now(),
                'source' => 'holt_winters_v1_dummy',
            ]);
        }
    }
}