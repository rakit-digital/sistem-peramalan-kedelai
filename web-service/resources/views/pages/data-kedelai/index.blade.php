@extends('layouts.app')

@section('content')
    <!-- Header & Breadcrumb -->
    <div class="card bg-lightprimary shadow-none position-relative overflow-hidden mb-6">
        <div class="card-body md:py-3 py-5">
            <div class="flex items-center grid grid-cols-12 gap-6">
                <div class="col-span-12 md:col-span-7">
                    <h4 class="font-semibold text-xl text-dark mb-3">
                        Manajemen Data Kedelai
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
                            Data Kedelai
                        </li>
                    </ol>
                </div>
                <div class="col-span-12 md:col-span-5">
                    <div class="flex justify-end">
                        <a href="{{ route('data.kedelai.create') }}" class="btn btn-primary flex items-center gap-2">
                            <i class="ti ti-plus"></i>
                            <span>Tambah Data Harian</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('status'))
        <div class="bg-lightsuccess text-success px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('status') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-lighterror text-error px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Tabel Data -->
    <div class="card">
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-lightgray">
                            <th class="px-4 py-3 font-semibold text-sm">Tanggal</th>
                            <th class="px-4 py-3 font-semibold text-sm">Stok Awal</th>
                            <th class="px-4 py-3 font-semibold text-sm">Pembelian</th>
                            <th class="px-4 py-3 font-semibold text-sm">Penggunaan</th>
                            <th class="px-4 py-3 font-semibold text-sm">Stok Akhir</th>
                            <th class="px-4 py-3 font-semibold text-sm text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stocks as $stock)
                            <tr class="border-b border-border">
                                <td class="px-4 py-3">{{ $stock->date->format('d M Y') }}</td>
                                <td class="px-4 py-3">{{ number_format($stock->opening_stock, 1) }}</td>
                                <td class="px-4 py-3 text-success font-medium">+{{ number_format($stock->purchase_kg, 1) }}
                                </td>
                                <td class="px-4 py-3 text-error font-medium">-{{ number_format($stock->usage_kg, 1) }}</td>
                                <td class="px-4 py-3 font-semibold">{{ number_format($stock->closing_stock_kg, 1) }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-2 justify-center">
                                        {{-- Link ke halaman edit --}}
                                        <a href="{{ route('data.kedelai.edit', $stock->id) }}"
                                            class="p-2 bg-lightsecondary rounded-full hover:bg-secondary hover:text-white transition-all"
                                            title="Edit">
                                            <i class="ti ti-edit text-secondary hover:text-white"></i>
                                        </a>
                                        @if (auth()->user()->role === 'admin')
                                            <form action="{{ route('data.kedelai.destroy', $stock->id) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 bg-lighterror rounded-full hover:bg-error hover:text-white transition-all"
                                                    title="Hapus">
                                                    <i class="ti ti-trash text-error hover:text-white"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 text-bodytext">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $stocks->links() }}</div>
        </div>
    </div>
@endsection
