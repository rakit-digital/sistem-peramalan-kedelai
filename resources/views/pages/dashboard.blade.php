@extends('layouts.app')

@section('content')
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Stok Kedelai -->
        <div class="card">
            <div class="card-body">
                <div class="flex items-center gap-4">
                    <div class="size-12 rounded-md bg-lightprimary dark:bg-darkprimary flex items-center justify-center">
                        <i class="ti ti-bowl text-primary text-2xl"></i>
                    </div>
                    <div>
                        <h5 class="card-title">150.5 kg</h5>
                        <p class="card-subtitle">Stok Kedelai Saat Ini</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Prediksi Besok -->
        <div class="card">
            <div class="card-body">
                <div class="flex items-center gap-4">
                    <div class="size-12 rounded-md bg-lightsuccess dark:bg-darksuccess flex items-center justify-center">
                        <i class="ti ti-trending-up text-success text-2xl"></i>
                    </div>
                    <div>
                        <h5 class="card-title text-success">25.0 kg</h5>
                        <p class="card-subtitle">Prediksi Kebutuhan Besok</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Rata-rata Penggunaan -->
        <div class="card">
            <div class="card-body">
                <div class="flex items-center gap-4">
                    <div class="size-12 rounded-md bg-lightwarning dark:bg-darkwarning flex items-center justify-center">
                        <i class="ti ti-chart-pie-2 text-warning text-2xl"></i>
                    </div>
                    <div>
                        <h5 class="card-title">22.8 kg</h5>
                        <p class="card-subtitle">Rata-rata Penggunaan</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tombol Aksi Cepat -->
        <a href="{{ route('peramalan') }}" class="card bg-primary hover:bg-primaryemphasis transition-all duration-300">
            <div class="card-body flex items-center justify-center text-white text-center">
                <div>
                    <i class="ti ti-report-analytics text-3xl"></i>
                    <p class="font-semibold mt-1">Lakukan Peramalan</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Chart -->
    <div class="card mt-6">
        <div class="card-body">
            <h5 class="card-title mb-4">Tren Penggunaan vs Peramalan (7 Hari Terakhir)</h5>
            <div class="h-80 bg-lightgray dark:bg-darkgray rounded-md flex items-center justify-center">
                <p class="text-gray-500">[ Placeholder untuk Grafik Chart.js/ApexCharts ]</p>
            </div>
        </div>
    </div>
@endsection