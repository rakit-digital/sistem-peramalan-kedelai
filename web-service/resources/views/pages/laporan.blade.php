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
                            <a class="flex items-center text-sm text-gray-500 hover:text-primary focus:outline-none focus:text-primary" href="{{ route('dashboard') }}">
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
                        <a href="#" class="btn btn-secondary flex items-center gap-2">
                            <i class="ti ti-download"></i>
                            <span>Ekspor ke PDF</span>
                        </a>
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
                                <option value="usage" {{ $request->input('report_type') == 'usage' ? 'selected' : '' }}>Laporan Penggunaan Harian</option>
                                <option value="stock" {{ $request->input('report_type') == 'stock' ? 'selected' : '' }}>Laporan Stok</option>
                                <option value="purchase" {{ $request->input('report_type') == 'purchase' ? 'selected' : '' }}>Laporan Pembelian</option>
                            </select>
                        </div>
                        <div>
                            <label for="start_date" class="block text-sm font-medium mb-1">Tanggal Mulai</label>
                            <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $request->input('start_date', now()->startOfMonth()->format('Y-m-d')) }}" required>
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium mb-1">Tanggal Akhir</label>
                            <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $request->input('end_date', now()->format('Y-m-d')) }}" required>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Tampilkan Laporan</button>
                    </div>
                </form>
            </div>

            <!-- Area Hasil Laporan -->
            <div class="pt-6">
                 {{-- Tampilkan hasil jika ada --}}
                 @if ($results)
                    {{-- Tentukan judul berdasarkan jenis laporan --}}
                    @php
                        $reportTitle = '';
                        if ($reportType == 'usage') $reportTitle = 'Laporan Penggunaan Harian';
                        elseif ($reportType == 'stock') $reportTitle = 'Laporan Stok';
                        elseif ($reportType == 'purchase') $reportTitle = 'Laporan Pembelian';
                    @endphp
                    
                    <h5 class="card-title mb-4">Hasil: {{ $reportTitle }}</h5>
                    <p class="card-subtitle mb-4">Periode: {{ \Carbon\Carbon::parse($request->start_date)->isoFormat('D MMMM Y') }} - {{ \Carbon\Carbon::parse($request->end_date)->isoFormat('D MMMM Y') }}</p>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-lightgray">
                                <tr>
                                    <th class="px-4 py-3 font-semibold text-sm">Tanggal</th>
                                    @if ($reportType == 'usage')
                                        <th class="px-4 py-3 font-semibold text-sm text-right">Penggunaan (kg)</th>
                                    @elseif ($reportType == 'stock')
                                        <th class="px-4 py-3 font-semibold text-sm text-right">Stok Akhir (kg)</th>
                                    @elseif ($reportType == 'purchase')
                                        <th class="px-4 py-3 font-semibold text-sm text-right">Pembelian (kg)</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($results as $result)
                                <tr class="border-b border-border">
                                    <td class="px-4 py-3">{{ $result->date->isoFormat('dddd, D MMMM Y') }}</td>
                                     @if ($reportType == 'usage')
                                        <td class="px-4 py-3 text-right font-medium">{{ number_format($result->usage_kg, 2) }}</td>
                                    @elseif ($reportType == 'stock')
                                        <td class="px-4 py-3 text-right font-medium">{{ number_format($result->closing_stock_kg, 2) }}</td>
                                    @elseif ($reportType == 'purchase')
                                        <td class="px-4 py-3 text-right font-medium">{{ number_format($result->purchase_kg, 2) }}</td>
                                    @endif
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center py-10 text-bodytext">
                                        Tidak ada data yang ditemukan untuk periode dan jenis laporan yang dipilih.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                 @else
                    {{-- Tampilkan placeholder jika belum ada filter --}}
                    <div class="text-center text-bodytext py-10">
                        <i class="ti ti-file-report text-5xl mb-2"></i>
                        <p>Hasil laporan akan ditampilkan di sini.</p>
                    </div>
                 @endif
            </div>
        </div>
    </div>
@endsection