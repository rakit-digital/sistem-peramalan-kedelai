@extends('layouts.app')

@section('content')
    <!-- Header & Breadcrumb -->
    <div class="card bg-lightprimary dark:bg-darkprimary shadow-none position-relative overflow-hidden mb-6">
        <div class="card-body md:py-3 py-5">
            <div class="flex items-center grid grid-cols-12 gap-6">
                <div class="col-span-12 md:col-span-7">
                    <h4 class="font-semibold text-xl text-dark mb-3">
                        Manajemen Data Kedelai
                    </h4>
                    <ol class="flex items-center whitespace-nowrap" aria-label="Breadcrumb">
                        <li class="inline-flex items-center">
                            <a class="flex items-center text-sm text-gray-500 hover:text-primary focus:outline-none focus:text-primary" href="{{ route('dashboard') }}">
                                Home
                            </a>
                            <i class="ti ti-slash text-sm mx-2"></i>
                        </li>
                        <li class="inline-flex items-center text-sm font-semibold text-dark truncate" aria-current="page">
                            Data Kedelai
                        </li>
                    </ol>
                </div>
                <div class="col-span-12 md:col-span-5">
                    <div class="flex justify-end">
                        <a href="#" class="btn btn-primary flex items-center gap-2">
                            <i class="ti ti-plus"></i>
                            <span>Tambah Data Harian</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="card">
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-lightgray dark:bg-darkgray">
                            <th class="px-4 py-3 border-b font-semibold text-sm text-dark">Tanggal</th>
                            <th class="px-4 py-3 border-b font-semibold text-sm text-dark">Stok Awal (kg)</th>
                            <th class="px-4 py-3 border-b font-semibold text-sm text-dark">Pembelian (kg)</th>
                            <th class="px-4 py-3 border-b font-semibold text-sm text-dark">Penggunaan (kg)</th>
                            <th class="px-4 py-3 border-b font-semibold text-sm text-dark">Stok Akhir (kg)</th>
                            <th class="px-4 py-3 border-b font-semibold text-sm text-dark text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Contoh Data Statis - Loop data dari database di sini --}}
                        <tr class="border-b border-border dark:border-darkborder">
                            <td class="px-4 py-3 text-bodytext dark:text-darklink">25 Mei 2024</td>
                            <td class="px-4 py-3 text-bodytext dark:text-darklink">150.0</td>
                            <td class="px-4 py-3 text-success font-medium">+ 50.0</td>
                            <td class="px-4 py-3 text-error font-medium">- 24.5</td>
                            <td class="px-4 py-3 font-semibold text-dark">175.5</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2 justify-center">
                                    <a href="#" class="p-2 bg-lightsecondary dark:bg-darksecondary rounded-full hover:bg-secondary hover:text-white transition-all" title="Edit">
                                        <i class="ti ti-edit text-secondary hover:text-white"></i>
                                    </a>
                                    <a href="#" class="p-2 bg-lighterror dark:bg-darkerror rounded-full hover:bg-error hover:text-white transition-all" title="Hapus">
                                        <i class="ti ti-trash text-error hover:text-white"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr class="border-b border-border dark:border-darkborder">
                            <td class="px-4 py-3 text-bodytext dark:text-darklink">24 Mei 2024</td>
                            <td class="px-4 py-3 text-bodytext dark:text-darklink">172.0</td>
                            <td class="px-4 py-3 text-bodytext dark:text-darklink">0.0</td>
                            <td class="px-4 py-3 text-error font-medium">- 22.0</td>
                            <td class="px-4 py-3 font-semibold text-dark">150.0</td>
                             <td class="px-4 py-3">
                                <div class="flex gap-2 justify-center">
                                    <a href="#" class="p-2 bg-lightsecondary dark:bg-darksecondary rounded-full hover:bg-secondary hover:text-white transition-all" title="Edit">
                                        <i class="ti ti-edit text-secondary hover:text-white"></i>
                                    </a>
                                    <a href="#" class="p-2 bg-lighterror dark:bg-darkerror rounded-full hover:bg-error hover:text-white transition-all" title="Hapus">
                                        <i class="ti ti-trash text-error hover:text-white"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection