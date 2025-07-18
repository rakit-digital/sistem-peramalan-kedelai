@extends('layouts.app')

@section('content')
    <!-- Header & Breadcrumb -->
    <div class="card bg-lightprimary shadow-none position-relative overflow-hidden mb-6">
        <div class="card-body md:py-3 py-5">
            <div class="flex items-center grid grid-cols-12 gap-6">
                <div class="col-span-12 md:col-span-8">
                    <h4 class="font-semibold text-xl text-dark mb-3">
                        Laporan
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
                <div class="col-span-12 md:col-span-4">
                    <div class="flex justify-end">
                        <form action="{{ route('laporan.export') }}" method="GET" id="export-form">
                            <!-- Input tersembunyi untuk membawa nilai filter saat ini -->
                            <input type="hidden" name="report_type" value="{{ $reportType }}">
                            <input type="hidden" name="start_date"
                                value="{{ $startDate ? $startDate->format('Y-m-d') : '' }}">
                            <input type="hidden" name="end_date" value="{{ $endDate ? $endDate->format('Y-m-d') : '' }}">

                            <!-- Tombol hanya aktif jika ada hasil -->
                            <button type="submit" class="btn btn-secondary flex items-center gap-2"
                                {{ !$results || $results->isEmpty() ? 'disabled' : '' }}>
                                <i class="ti ti-download"></i>
                                <span>Ekspor ke PDF</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Konten Utama -->
    <div class="card">
        <div class="card-body">
            <!-- Filter Laporan -->
            <div class="pb-6 border-b border-border">
                <h5 class="card-title">Filter Laporan</h5>
                <p class="card-subtitle mb-4">Pilih parameter untuk men-generate laporan.</p>

                {{-- Form untuk filter, method GET --}}
                <form action="{{ route('laporan') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        <div class="md:col-span-2">
                            <label for="report_type" class="block text-sm font-medium mb-1">Jenis Laporan</label>
                            {{-- Gunakan 'report_type' sebagai nama input --}}
                            <select id="report_type" name="report_type" class="form-control" required>
                                {{-- Gunakan old() atau $request->input() untuk menjaga nilai terpilih --}}
                                <option value="usage" {{ $reportType == 'usage' ? 'selected' : '' }}>Laporan Penggunaan
                                    Harian</option>
                                <option value="stock" {{ $reportType == 'stock' ? 'selected' : '' }}>Laporan Stok</option>
                                <option value="purchase" {{ $reportType == 'purchase' ? 'selected' : '' }}>Laporan Pembelian
                                </option>
                            </select>
                        </div>
                        <div>
                            <label for="start_date" class="block text-sm font-medium mb-1">Tanggal Mulai</label>
                            <input type="date" id="start_date" name="start_date" class="form-control"
                                value="{{ isset($startDate) ? $startDate->format('Y-m-d') : now()->startOfMonth()->format('Y-m-d') }}"
                                required>
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium mb-1">Tanggal Akhir</label>
                            <input type="date" id="end_date" name="end_date" class="form-control"
                                value="{{ isset($endDate) ? $endDate->format('Y-m-d') : now()->format('Y-m-d') }}"
                                required>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-2">
                        <button type="submit" class="btn btn-primary">Tampilkan Laporan</button>
                        <a href="{{ route('laporan') }}" class="btn btn-outline-primary">Reset</a>
                    </div>
                </form>
            </div>

            <!-- Area Hasil Laporan -->
            <div class="pt-6">
                @if (isset($results) && $results->isNotEmpty())
                    @php
                        $reportTitleText = '';
                        $columnHeaderText = '';
                        if ($reportType == 'usage') {
                            $reportTitleText = 'Laporan Penggunaan Harian';
                            $columnHeaderText = 'Penggunaan (kg)';
                        } elseif ($reportType == 'stock') {
                            $reportTitleText = 'Laporan Stok Harian';
                            $columnHeaderText = 'Stok Akhir (kg)';
                        } elseif ($reportType == 'purchase') {
                            $reportTitleText = 'Laporan Pembelian';
                            $columnHeaderText = 'Pembelian (kg)';
                        }
                    @endphp

                    <h5 class="card-title mb-4">Hasil: {{ $reportTitleText }}</h5>
                    <p class="card-subtitle mb-4">Periode: {{ $startDate->isoFormat('D MMMM Y') }} -
                        {{ $endDate->isoFormat('D MMMM Y') }}</p>

                    <!-- ======================= KODE TABEL YANG HILANG DITAMBAHKAN DI SINI ======================= -->
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
                                        <td class="px-4 py-3">{{ $result->date->isoFormat('dddd, D MMMM Y') }}</td>
                                        <td class="px-4 py-3 text-right font-medium">
                                            @if ($reportType == 'usage')
                                                {{ number_format($result->usage_kg, 2) }}
                                            @elseif ($reportType == 'stock')
                                                {{ number_format($result->closing_stock_kg, 2) }}
                                            @elseif ($reportType == 'purchase')
                                                {{ number_format($result->purchase_kg, 2) }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- =================================== AKHIR KODE TABEL =================================== -->
                @elseif(request()->has('report_type'))
                    <div class="text-center text-bodytext py-10">
                        <i class="ti ti-search text-5xl mb-2"></i>
                        <p>Tidak ada data ditemukan untuk periode dan jenis laporan yang dipilih.</p>
                    </div>
                @else
                    <div class="text-center text-bodytext py-10">
                        <i class="ti ti-file-report text-5xl mb-2"></i>
                        <p>Silakan pilih parameter di atas dan klik "Tampilkan Laporan".</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
