<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SoybeanStock;

class SoybeanStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama
        SoybeanStock::truncate();

        // Ambil semua ID user yang ada untuk di-assign secara acak
        $userIds = User::pluck('id');

        // Tentukan kondisi awal
        $currentClosingStock = 500.00; // Stok awal 90 hari yang lalu
        $daysOfData = 10; // Jumlah hari data historis yang ingin dibuat

        // Loop untuk membuat data dari 90 hari yang lalu hingga kemarin
        for ($i = $daysOfData; $i >= 1; $i--) {
            $date = now()->subDays($i);

            // Stok awal hari ini adalah stok akhir kemarin
            $openingStock = $currentClosingStock;

            // Simulasikan pembelian (tidak setiap hari ada pembelian)
            $purchase = (rand(1, 4) == 1) ? rand(100, 250) + (rand(0, 99) / 100) : 0;
            
            // Simulasikan penggunaan harian
            $usage = rand(20, 50) + (rand(0, 99) / 100);

            // Hitung stok akhir, pastikan tidak negatif
            $closingStock = max(0, $openingStock + $purchase - $usage);
            
            SoybeanStock::create([
                'user_id' => $userIds->random(), // Pilih user secara acak
                'date' => $date,
                'purchase_kg' => $purchase,
                'usage_kg' => $usage,
                'closing_stock_kg' => $closingStock,
                'notes' => (rand(1, 20) == 1) ? 'Pengecekan stok fisik' : null, // Sesekali tambahkan catatan
            ]);

            // Update stok akhir untuk iterasi berikutnya
            $currentClosingStock = $closingStock;
        }
    }
}