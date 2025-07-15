<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema; // <-- Tambahkan ini

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Nonaktifkan pemeriksaan foreign key untuk sementara
        Schema::disableForeignKeyConstraints();

        // 1. Panggil Seeder untuk User
        // Truncate di dalam UserSeeder akan berjalan dengan aman sekarang
        $this->call(UserSeeder::class);
        
        // 2. Panggil Seeder untuk Stok Kedelai
        // Truncate di dalam SoybeanStockSeeder juga akan berjalan aman
        $this->call(SoybeanStockSeeder::class);
        
        // 3. Panggil Seeder untuk Peramalan
        $this->call(ForecastSeeder::class);

        // Aktifkan kembali pemeriksaan foreign key setelah semua seeder selesai
        Schema::enableForeignKeyConstraints();
    }
}