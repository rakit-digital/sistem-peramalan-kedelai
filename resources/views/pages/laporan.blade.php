@extends('layouts.app')

@section('content')
    <!-- Header & Breadcrumb -->
    <div class="card bg-lightprimary dark:bg-darkprimary shadow-none position-relative overflow-hidden mb-6">
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
            <div class="pb-6 border-b border-border dark:border-darkborder">
                <h5 class="card-title">Filter Laporan</h5>
                <p class="card-subtitle mb-4">Pilih parameter untuk men-generate laporan.</p>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div class="md:col-span-2">
                        <label for="jenis_laporan" class="block text-sm font-medium mb-1">Jenis Laporan</label>
                        <select id="jenis_laporan" name="jenis_laporan" class="form-control">
                            <option>Laporan Penggunaan Harian</option>
                            <option>Laporan Stok</option>
                            <option>Laporan Pembelian</option>
                        </select>
                    </div>
                    <div>
                        <label for="tanggal_mulai" class="block text-sm font-medium mb-1">Tanggal Mulai</label>
                        <input type="date" id="tanggal_mulai" class="form-control">
                    </div>
                    <div>
                        <label for="tanggal_akhir" class="block text-sm font-medium mb-1">Tanggal Akhir</label>
                        <input type="date" id="tanggal_akhir" class="form-control">
                    </div>
                </div>
                <div class="mt-4">
                    <button class="btn btn-primary">Tampilkan Laporan</button>
                </div>
            </div>

            <!-- Area Hasil Laporan -->
            <div class="pt-6">
                 <h5 class="card-title mb-4">Hasil Laporan</h5>
                 <div class="text-center text-bodytext py-10">
                    <i class="ti ti-file-report text-5xl mb-2"></i>
                    <p>Hasil laporan akan ditampilkan di sini.</p>
                </div>
            </div>
        </div>
    </div>
@endsection