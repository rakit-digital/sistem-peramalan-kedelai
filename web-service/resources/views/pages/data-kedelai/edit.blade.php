@extends('layouts.app')

@section('content')
    <!-- Header & Breadcrumb -->
    <div class="card bg-lightprimary shadow-none position-relative overflow-hidden mb-6">
        <div class="card-body md:py-3 py-5">
            <div class="flex items-center grid grid-cols-12 gap-6">
                <div class="col-span-12 md:col-span-7">
                    <h4 class="font-semibold text-xl text-dark mb-3">
                        Edit Data Kedelai Tanggal {{ $stock->date->format('d M Y') }}
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
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('data.kedelai.update', $stock->id) }}" method="POST">
                @csrf
                @method('PATCH')
                @include('pages.data-kedelai.partials.form', ['isEdit' => true])
                <div class="flex justify-end gap-3 mt-6">
                    <a href="{{ route('data.kedelai.index') }}" class="btn btn-outline-primary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
