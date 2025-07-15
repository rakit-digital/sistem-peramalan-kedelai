<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forecasts', function (Blueprint $table) {
            $table->id();
            
            // Tanggal yang diramalkan. Dibuat unik agar hanya ada 1 ramalan resmi per hari.
            $table->date('forecast_date')->unique();
            
            // Nilai yang diramalkan. Kita fokus meramal 'penggunaan' karena lebih actionable.
            $table->decimal('predicted_usage_kg', 8, 2);
            
            // Kapan ramalan ini dibuat? Penting untuk melacak histori & performa model.
            $table->dateTime('generated_at');
            
            // Sumber ramalan. Berguna jika ada >1 model atau ada input manual.
            // Contoh: 'holt_winters_v1', 'manual_override'
            $table->string('source')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forecasts');
    }
};