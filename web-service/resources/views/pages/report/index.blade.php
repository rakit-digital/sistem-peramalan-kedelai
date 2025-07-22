@extends('layouts.app')

@section('content')
    <!-- Header & Breadcrumb -->
    <div class="card bg-lightprimary shadow-none position-relative overflow-hidden mb-6">
        <div class="card-body md:py-3 py-5">
            <div class="flex items-center grid grid-cols-12 gap-6">
                <div class="col-span-12 md:col-span-8">
                    <h4 class="font-semibold text-xl text-dark mb-3">
                        Laporan & Analisis
                    </h4>
                    <ol class="flex items-center whitespace-nowrap" aria-label="Breadcrumb">
                        <li class="inline-flex items-center">
                            <a class="flex items-center text-sm text-gray-500 hover:text-primary focus:outline-none focus:text-primary"
                                href="{{ route('dashboard') }}">
                                Home
                            </a>
                            <i class="ti ti-slash text-sm mx-2"></i>
                        </li>
                        <li class="inline-flex items-center text-sm font-semibold text-dark truncate" aria-current="page">
                            Laporan
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    @if (session('error'))
        <div class="bg-lighterror text-error px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Card Filter -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Filter Laporan</h5>
            <p class="card-subtitle mb-4">Pilih parameter untuk men-generate laporan.</p>

            <form action="{{ route('laporan') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div class="md:col-span-2">
                        <label for="report_type" class="block text-sm font-medium mb-1">Jenis Laporan</label>
                        <select id="report_type" name="report_type" class="form-control" required>
                            <option value="">-- Pilih Jenis Laporan --</option>
                            <option value="usage" {{ $reportType == 'usage' ? 'selected' : '' }}>Laporan Penggunaan Harian</option>
                            <option value="stock" {{ $reportType == 'stock' ? 'selected' : '' }}>Laporan Stok Harian</option>
                            <option value="purchase" {{ $reportType == 'purchase' ? 'selected' : '' }}>Laporan Pembelian</option>
                        </select>
                    </div>
                    <div>
                        <label for="start_date" class="block text-sm font-medium mb-1">Tanggal Mulai</label>
                        <input type="date" id="start_date" name="start_date" class="form-control"
                               value="{{ $startDate->format('Y-m-d') }}" required>
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium mb-1">Tanggal Akhir</label>
                        <input type="date" id="end_date" name="end_date" class="form-control"
                               value="{{ $endDate->format('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <button type="submit" class="btn btn-primary flex items-center gap-2">
                        <i class="ti ti-search"></i> Tampilkan Laporan
                    </button>
                    <a href="{{ route('laporan') }}" class="btn btn-outline-primary">Reset Filter</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Area Hasil Laporan -->
    @if ($reportType && $results->isNotEmpty())
        @php
            $reportTitleText = '';
            $columnHeaderText = '';
            $colorClass = 'primary';
            if ($reportType == 'usage') {
                $reportTitleText = 'Laporan Penggunaan Harian';
                $columnHeaderText = 'Penggunaan (kg)';
                $colorClass = 'secondary';
            } elseif ($reportType == 'stock') {
                $reportTitleText = 'Laporan Stok Harian';
                $columnHeaderText = 'Stok Akhir (kg)';
                $colorClass = 'primary';
            } elseif ($reportType == 'purchase') {
                $reportTitleText = 'Laporan Pembelian';
                $columnHeaderText = 'Pembelian (kg)';
                $colorClass = 'success';
            }
        @endphp

        <!-- Header Hasil -->
        <div class="flex justify-between items-center mt-6 mb-4">
            <div>
                <h4 class="font-semibold text-xl text-dark">Hasil: {{ $reportTitleText }}</h4>
                <p class="text-sm text-bodytext">Periode: {{ $startDate->isoFormat('D MMM Y') }} - {{ $endDate->isoFormat('D MMM Y') }}</p>
            </div>
            <form action="{{ route('laporan.export') }}" method="GET">
                <input type="hidden" name="report_type" value="{{ $reportType }}">
                <input type="hidden" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                <input type="hidden" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                <button type="submit" class="btn btn-secondary flex items-center gap-2">
                    <i class="ti ti-download"></i> Ekspor PDF
                </button>
            </form>
        </div>

        <!-- Kartu Statistik -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
            @if (isset($stats['total']))
            <div class="card"><div class="card-body text-center">
                <h5 class="card-title text-{{$colorClass}}">{{ number_format($stats['total'], 1) }} kg</h5>
                <p class="card-subtitle">Total</p>
            </div></div>
            @endif
            <div class="card"><div class="card-body text-center">
                <h5 class="card-title">{{ number_format($stats['average'], 1) }} kg</h5>
                <p class="card-subtitle">Rata-rata</p>
            </div></div>
            <div class="card"><div class="card-body text-center">
                <h5 class="card-title">{{ number_format($stats['highest'], 1) }} kg</h5>
                <p class="card-subtitle">Tertinggi</p>
            </div></div>
            <div class="card"><div class="card-body text-center">
                <h5 class="card-title">{{ number_format($stats['lowest'], 1) }} kg</h5>
                <p class="card-subtitle">Terendah</p>
            </div></div>
        </div>

        <!-- Tabel & Grafik -->
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            <div class="lg:col-span-3 card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Data Rinci</h5>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-lightgray">
                                <tr>
                                    <th class="px-4 py-3 font-semibold text-sm">Tanggal</th>
                                    <th class="px-4 py-3 font-semibold text-sm text-right">{{ $columnHeaderText }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($results as $result)
                                    <tr class="border-b border-border">
                                        <td class="px-4 py-3">{{ $result->date->isoFormat('dddd, D MMM Y') }}</td>
                                        <td class="px-4 py-3 text-right font-medium">
                                            @if ($reportType == 'purchase')
                                                <span class="text-success">+{{ number_format($result->purchase_kg, 1) }}</span>
                                            @else
                                                {{ number_format($result->{$reportType == 'usage' ? 'usage_kg' : 'closing_stock_kg'}, 1) }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $results->appends(request()->query())->links() }}</div>
                </div>
            </div>
            <div class="lg:col-span-2 card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Grafik Tren</h5>
                    <div class="h-96">
                        <canvas id="reportChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    @elseif (request()->has('report_type'))
        <div class="card mt-6">
            <div class="card-body text-center py-10">
                <i class="ti ti-search text-5xl mb-2 text-gray-400"></i>
                <p class="font-semibold">Tidak Ada Data Ditemukan</p>
                <p class="text-sm text-bodytext mt-1">Tidak ada data untuk periode dan jenis laporan yang Anda pilih.</p>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    {{-- Chart.js diperlukan untuk grafik --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (isset($chartData) && !empty($chartData['values']))
                const chartData = @json($chartData);
                const reportType = @json($reportType);
                
                const rootStyles = getComputedStyle(document.documentElement);
                let chartColor = rootStyles.getPropertyValue('--color-primary').trim();
                let chartBgColor = rootStyles.getPropertyValue('--color-lightprimary').trim();

                if(reportType === 'usage') {
                    chartColor = rootStyles.getPropertyValue('--color-secondary').trim();
                    chartBgColor = rootStyles.getPropertyValue('--color-lightsecondary').trim();
                } else if (reportType === 'purchase') {
                    chartColor = rootStyles.getPropertyValue('--color-success').trim();
                    chartBgColor = rootStyles.getPropertyValue('--color-lightsuccess').trim();
                }

                const ctx = document.getElementById('reportChart');
                if (ctx) {
                    new window.Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: chartData.labels, 
                            datasets: [{
                                label: 'Nilai (kg)',
                                data: chartData.values,
                                borderColor: chartColor,
                                backgroundColor: chartBgColor, 
                                tension: 0.3,
                                fill: true,
                                pointBackgroundColor: chartColor,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: { beginAtZero: true }
                            },
                            plugins: {
                                legend: { display: false }
                            }
                        }
                    });
                }
            @endif
        });
    </script>
@endpush