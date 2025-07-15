@extends('layouts.app')

@section('content')
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Stok Kedelai -->
        <div class="card">
            <div class="card-body">
                <div class="flex items-center gap-4">
                    <div class="size-12 rounded-md bg-lightprimary flex items-center justify-center">
                        <i class="ti ti-bowl text-primary text-2xl"></i>
                    </div>
                    <div>
                        <h5 class="card-title">{{ number_format($currentStock, 1) }} kg</h5>
                        <p class="card-subtitle">Stok Kedelai Saat Ini</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Prediksi Besok -->
        <div class="card">
            <div class="card-body">
                <div class="flex items-center gap-4">
                    <div class="size-12 rounded-md bg-lightsuccess flex items-center justify-center">
                        <i class="ti ti-trending-up text-success text-2xl"></i>
                    </div>
                    <div>
                        <h5 class="card-title text-success">{{ number_format($predictedUsageTomorrow, 1) }} kg</h5>
                        <p class="card-subtitle">Prediksi Kebutuhan Besok</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Rata-rata Penggunaan -->
        <div class="card">
            <div class="card-body">
                <div class="flex items-center gap-4">
                    <div class="size-12 rounded-md bg-lightwarning flex items-center justify-center">
                        <i class="ti ti-chart-pie-2 text-warning text-2xl"></i>
                    </div>
                    <div>
                        <h5 class="card-title">{{ number_format($avgUsage, 1) }} kg</h5>
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
            <div class="h-80">
                <canvas id="trenChart"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Ambil nilai warna dari variabel CSS tema Anda
            const rootStyles = getComputedStyle(document.documentElement);
            const colorPrimary = rootStyles.getPropertyValue('--color-primary').trim();
            const colorSecondary = rootStyles.getPropertyValue('--color-secondary').trim();
            const colorLightPrimary = rootStyles.getPropertyValue('--color-lightprimary').trim();
            const colorLightSecondary = rootStyles.getPropertyValue('--color-lightsecondary').trim();

            const ctx = document.getElementById('trenChart');
            if (ctx) {
                new window.Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                        datasets: [{
                            label: 'Penggunaan Aktual (kg)',
                            data: [22, 24, 21, 25, 23, 26, 24],
                            // 2. Gunakan variabel JavaScript yang sudah berisi nilai warna
                            borderColor: colorSecondary, // Contoh: #4CAF50
                            backgroundColor: colorLightSecondary, // Contoh: #E9F7EA
                            tension: 0.3,
                            fill: true,
                            pointBackgroundColor: colorSecondary,
                            pointBorderColor: '#fff',
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: colorSecondary,
                        }, {
                            label: 'Hasil Peramalan (kg)',
                            data: [23, 23.5, 22, 24, 24.5, 25, 25.5],
                            // 3. Gunakan variabel JavaScript yang sudah berisi nilai warna
                            borderColor: colorPrimary, // Contoh: #D4A017
                            backgroundColor: colorLightPrimary, // Contoh: #FDF4D7
                            tension: 0.3,
                            fill: true,
                            pointBackgroundColor: colorPrimary,
                            pointBorderColor: '#fff',
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: colorPrimary,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: getComputedStyle(document.body).getPropertyValue(
                                        '--color-border') // Warna grid sesuai tema
                                },
                                ticks: {
                                    color: getComputedStyle(document.body).getPropertyValue(
                                        '--color-bodytext') // Warna teks sumbu Y
                                }
                            },
                            x: {
                                grid: {
                                    display: false // Sembunyikan grid vertikal
                                },
                                ticks: {
                                    color: getComputedStyle(document.body).getPropertyValue(
                                        '--color-bodytext') // Warna teks sumbu X
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                labels: {
                                    color: getComputedStyle(document.body).getPropertyValue(
                                        '--color-bodytext') // Warna teks legenda
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush
