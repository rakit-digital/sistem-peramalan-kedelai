{{-- Gunakan layout 'guest' yang baru kita buat --}}
@extends('layouts.guest')

{{-- Mulai section 'content' untuk mengisi @yield di layout --}}
@section('content')
<div class="card shadow-lg">
    <div class="card-body p-6">
        <div class="mx-auto text-center mb-6">
            <div class="flex justify-center">
                <div class="brand-logo flex items-center">
                    <a href="/" class="text-nowrap logo-img">
                        {{-- Sesuaikan dengan path logo proyek Anda --}}
                        <img src="{{ asset('assets/images/logos/logo-pabrik.png') }}" class="block w-24" alt="Logo Aplikasi" />
                    </a>
                </div>
            </div>
            <h4 class="font-semibold text-xl text-dark mt-4 mb-1">
                Selamat Datang Kembali
            </h4>
            <p class="text-gray-500 mb-4">Silakan masuk untuk melanjutkan</p>
        </div>
        
        {{-- Tampilkan pesan error validasi jika ada --}}
        @if ($errors->any())
            <div class="bg-lighterror text-error px-4 py-3 rounded relative mb-4" role="alert">
                <p class="font-bold">Oops! Terjadi kesalahan:</p>
                <ul class="list-disc pl-5 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                    <i class="ti ti-x"></i>
                </button>
            </div>
        @endif
        
        {{-- Form login, action menunjuk ke route 'login' dengan method POST --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div class="form-group">
                <label for="email" class="form-label block mb-2 font-medium text-dark">Alamat Email</label>
                {{-- Gunakan old('email') untuk menjaga input jika validasi gagal --}}
                <input type="email" name="email" id="email" class="form-control w-full @error('email') border-error @enderror" value="{{ old('email') }}" required autofocus />
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label block mb-2 font-medium text-dark">Kata Sandi</label>
                <input type="password" name="password" id="password" class="form-control w-full" required />
            </div>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" 
                        class="border-gray-200 rounded text-primary focus:ring-primary" />
                    <label for="remember" class="ms-2 text-sm text-gray-600">Ingat Saya</label>
                </div>
                {{-- Anda bisa menambahkan link 'lupa password' di sini nanti --}}
                {{-- <a href="#" class="text-sm font-medium text-primary hover:underline">Lupa Kata Sandi?</a> --}}
            </div>
            
            <button type="submit" class="btn btn-primary w-full py-3">Masuk</button>
            
            {{-- Bagian untuk login via Google/Facebook bisa di-uncomment nanti jika diperlukan --}}
            {{-- <div class="relative my-4"> ... </div> --}}
            {{-- <div class="grid grid-cols-2 gap-4"> ... </div> --}}
        </form>
    </div>
</div>

{{-- Bagian untuk link registrasi bisa di-uncomment nanti jika diperlukan --}}
{{-- <div class="text-center mt-5">
    <p class="text-gray-600">
        Belum punya akun? <a href="#" class="font-medium text-primary hover:underline">Daftar di sini</a>
    </p>
</div> --}}
@endsection