@extends('layouts.app')

@section('content')
    <!-- Header & Breadcrumb -->
    <div class="card bg-lightprimary dark:bg-darkprimary shadow-none position-relative overflow-hidden mb-6">
        <div class="card-body md:py-3 py-5">
            <div class="flex items-center grid grid-cols-12 gap-6">
                <div class="col-span-12">
                    <h4 class="font-semibold text-xl text-dark mb-3">
                        Peramalan Kebutuhan Kedelai
                    </h4>
                    <ol class="flex items-center whitespace-nowrap" aria-label="Breadcrumb">
                        <li class="inline-flex items-center">
                            <a class="flex items-center text-sm text-gray-500 hover:text-primary focus:outline-none focus:text-primary" href="{{ route('dashboard') }}">
                                Home
                            </a>
                            <i class="ti ti-slash text-sm mx-2"></i>
                        </li>
                        <li class="inline-flex items-center text-sm font-semibold text-dark truncate" aria-current="page">
                            Peramalan
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Konten Utama -->
    <div class="card">
        <div class="card-body">
            <!-- Form Peramalan -->
            <div class="pb-6 border-b border-border dark:border-darkborder">
                <h5 class="card-title">Parameter Peramalan</h5>
                <p class="card-subtitle mb-4">Pilih periode untuk melakukan peramalan kebutuhan kedelai.</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div>
                        <label for="periode" class="block text-sm font-medium mb-1">Periode Peramalan</label>
                        <select id="periode" name="periode" class="form-control">
                            <option>7 Hari ke Depan</option>
                            <option>14 Hari ke Depan</option>
                            <option>30 Hari ke Depan</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <button class="btn btn-primary w-full md:w-auto flex items-center gap-2 justify-center">
                            <i class="ti ti-player-play"></i>
                            <span>Mulai Peramalan</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Hasil Peramalan -->
            <div class="pt-6">
                <h5 class="card-title mb-4">Hasil Peramalan</h5>
                <div class="text-center text-bodytext py-10">
                    <i class="ti ti-chart-infographic text-5xl mb-2"></i>
                    <p>Hasil peramalan akan muncul di sini setelah proses dijalankan.</p>
                </div>
            </div>
        </div>
    </div>
@endsection