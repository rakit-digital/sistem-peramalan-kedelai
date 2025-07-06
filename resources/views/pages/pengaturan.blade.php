@extends('layouts.app')

@section('content')
    <!-- Header & Breadcrumb -->
    <div class="card bg-lightprimary dark:bg-darkprimary shadow-none position-relative overflow-hidden mb-6">
        <div class="card-body md:py-3 py-5">
            <div class="flex items-center grid grid-cols-12 gap-6">
                <div class="col-span-12">
                    <h4 class="font-semibold text-xl text-dark mb-3">
                        Pengaturan Akun
                    </h4>
                    <ol class="flex items-center whitespace-nowrap" aria-label="Breadcrumb">
                        <li class="inline-flex items-center">
                            <a class="flex items-center text-sm text-gray-500 hover:text-primary focus:outline-none focus:text-primary" href="{{ route('dashboard') }}">
                                Home
                            </a>
                            <i class="ti ti-slash text-sm mx-2"></i>
                        </li>
                        <li class="inline-flex items-center text-sm font-semibold text-dark truncate" aria-current="page">
                            Pengaturan
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Pengaturan -->
    <div class="card">
        <div class="card-body">
            <form action="#" method="POST">
                @csrf
                <div class="pb-6 border-b border-border dark:border-darkborder">
                    <h5 class="card-title mb-4">Profil Pengguna</h5>
                    <div class="mb-4">
                        <label for="nama" class="block text-sm font-medium mb-1">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" class="form-control" value="Admin Pabrik Tahu">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium mb-1">Alamat Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="admin@pabriktahu.com">
                    </div>
                </div>

                <div class="pt-6">
                    <h5 class="card-title mb-4">Ubah Kata Sandi</h5>
                     <div class="mb-4">
                        <label for="current_password" class="block text-sm font-medium mb-1">Kata Sandi Saat Ini</label>
                        <input type="password" id="current_password" name="current_password" class="form-control" placeholder="Masukkan kata sandi saat ini">
                    </div>
                    <div class="mb-4">
                        <label for="new_password" class="block text-sm font-medium mb-1">Kata Sandi Baru</label>
                        <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Masukkan kata sandi baru">
                    </div>
                    <div class="mb-6">
                        <label for="confirm_password" class="block text-sm font-medium mb-1">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Konfirmasi kata sandi baru">
                    </div>
                </div>

                <div class="flex justify-end mt-4">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection