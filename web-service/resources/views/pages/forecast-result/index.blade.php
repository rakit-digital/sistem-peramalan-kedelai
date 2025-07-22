@extends('layouts.app')

@section('content')
    <!-- Header & Breadcrumb -->
    <div class="card bg-lightprimary shadow-none position-relative overflow-hidden mb-6">
        <div class="card-body md:py-3 py-5">
            <div class="flex items-center grid grid-cols-12 gap-6">
                <div class="col-span-12">
                    <h4 class="font-semibold text-xl text-dark mb-3">
                        Hasil Peramalan Kebutuhan Kedelai
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
                            Hasil Peramalan
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Info & Summary Cards -->
    <div class="mb-6 p-4 bg-lightinfo border-l-4 border-info rounded-md">
        <div class="flex">
            <div class="flex-shrink-0">
                 <i class="ti ti-info-circle text-info text-2xl"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-bodytext">
                    Tabel ini menampilkan hasil peramalan kebutuhan kedelai untuk hari-hari mendatang. Data ini diperbarui secara otomatis setiap kali ada penambahan atau perubahan data harian, atau ketika peramalan manual dijalankan.
                </p>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="card">
            <div class="card-body">
                <div class="flex items-center gap-4">
                    <div class="size-12 rounded-md bg-lightsuccess flex items-center justify-center">
                        <i class="ti ti-calendar-stats text-success text-2xl"></i>
                    </div>
                    <div>
                        <h5 class="card-title text-success">{{ number_format($sevenDayTotal, 1) }} kg</h5>
                        <p class="card-subtitle">Total Prediksi Kebutuhan 7 Hari</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="flex items-center gap-4">
                    <div class="size-12 rounded-md bg-lightwarning flex items-center justify-center">
                        <i class="ti ti-calendar-time text-warning text-2xl"></i>
                    </div>
                    <div>
                        <h5 class="card-title">{{ number_format($thirtyDayTotal, 1) }} kg</h5>
                        <p class="card-subtitle">Total Prediksi Kebutuhan 30 Hari</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Tabel Hasil Peramalan -->
    <div class="card">
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-lightgray">
                            <th class="px-4 py-3 font-semibold text-sm">Tanggal Ramalan</th>
                            <th class="px-4 py-3 font-semibold text-sm text-center">Prediksi Penggunaan</th>
                            <th class="px-4 py-3 font-semibold text-sm">Waktu Generate</th>
                            <th class="px-4 py-3 font-semibold text-sm">Sumber Model</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($forecasts as $forecast)
                            <tr class="border-b border-border">
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        <span>{{ \Carbon\Carbon::parse($forecast->forecast_date)->isoFormat('dddd, D MMMM Y') }}</span>
                                        @if (\Carbon\Carbon::parse($forecast->forecast_date)->isToday())
                                            <span class="ml-2 text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-primary bg-lightprimary">Hari Ini</span>
                                        @elseif (\Carbon\Carbon::parse($forecast->forecast_date)->isTomorrow())
                                            <span class="ml-2 text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-success bg-lightsuccess">Besok</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="font-bold text-lg text-primary">{{ number_format($forecast->predicted_usage_kg, 1) }}</span>
                                    <span class="text-sm">kg</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-bodytext">{{ \Carbon\Carbon::parse($forecast->generated_at)->diffForHumans() }}</td>
                                <td class="px-4 py-3">
                                    <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-secondary bg-lightsecondary">{{ $forecast->source ?? 'Tidak Diketahui' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-10 text-bodytext">
                                    <div class="flex flex-col items-center">
                                        <i class="ti ti-database-off text-5xl mb-3"></i>
                                        <p class="font-semibold">Belum ada data peramalan yang tersimpan.</p>
                                        <p class="text-sm mt-1">Anda bisa men-generate data baru di halaman <a href="{{ route('peramalan') }}" class="text-primary hover:underline">Peramalan Kedelai</a>.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
             <div class="mt-6">
                {{ $forecasts->links() }}
            </div>
        </div>
    </div>
@endsection