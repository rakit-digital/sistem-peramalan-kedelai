@extends('layouts.app')

@section('title', 'Pengaturan Akun')

@section('content')
    <!-- Header & Breadcrumb -->
    <div class="card bg-lightprimary shadow-none position-relative overflow-hidden mb-6">
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
                            Pengaturan Akun
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Notifikasi Status/Error --}}
    @if (session('status'))
        <div class="bg-lightsuccess text-success px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('status') }}</span>
        </div>
    @endif
    
    {{-- Menampilkan error validasi umum di bagian atas --}}
    @if ($errors->any())
        <div class="bg-lighterror text-error px-4 py-3 rounded relative mb-4" role="alert">
            <p class="font-bold">Oops! Terjadi kesalahan:</p>
            <ul class="list-disc pl-5 mt-2 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Foto Profil -->
        <div class="lg:col-span-1">
            <div class="card">
                <div class="card-body flex flex-col items-center text-center">
                    <h5 class="card-title mb-2">Foto Profil</h5>
                    <p class="card-subtitle mb-4">JPG, GIF atau PNG. Ukuran maks 2MB.</p>

                    <div class="relative w-32 h-32 rounded-full overflow-hidden mb-4 group">
                        <img src="{{ $user->photo ? asset('storage/' . $user->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7F9CF5&background=EBF4FF' }}"
                            alt="Foto Profil" class="w-full h-full object-cover">
                        <label for="photo-input"
                            class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 cursor-pointer">
                            <i class="ti ti-camera text-white text-2xl"></i>
                        </label>
                    </div>

                    <h4 class="text-lg font-medium text-dark">{{ $user->name }}</h4>
                    <p class="text-sm text-bodytext">{{ $user->email }}</p>
                    <p class="text-sm text-bodytext capitalize">{{ $user->role }}</p>

                    <!-- Form untuk Upload Foto (tersembunyi) -->
                    <form action="{{ route('pengaturan.photo.update') }}" method="POST" enctype="multipart/form-data" id="photo-upload-form" class="hidden">
                        @csrf
                        <input type="file" name="photo" id="photo-input" accept="image/*"
                            onchange="document.getElementById('photo-upload-form').submit();">
                    </form>

                    <!-- Grup Tombol Aksi Foto -->
                    <div class="flex gap-2 mt-4">
                        <button type="button" onclick="document.getElementById('photo-input').click()" class="btn btn-sm btn-primary">
                            Ubah Foto
                        </button>
                        
                        @if($user->photo)
                        <!-- Form untuk Hapus Foto -->
                        <form action="{{ route('pengaturan.photo.destroy') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto profil?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-error">
                                Hapus Foto
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Informasi & Password -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-body">
                    <!-- Navigasi Tabs -->
                    <div class="border-b border-border mb-6">
                        <nav class="flex space-x-4" aria-label="Tabs">
                            <button type="button" class="tab-button active" data-tab-target="#profile-tab-content">
                                <i class="ti ti-user-circle mr-2"></i> Informasi Profil
                            </button>
                            <button type="button" class="tab-button" data-tab-target="#password-tab-content">
                                <i class="ti ti-lock mr-2"></i> Ubah Kata Sandi
                            </button>
                        </nav>
                    </div>

                    <!-- Konten Tabs -->
                    <div>
                        <!-- Tab Konten Informasi Profil -->
                        <div id="profile-tab-content" class="tab-content">
                            <form action="{{ route('pengaturan.profile.update') }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PATCH')
                                <div class="form-group">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control @error('name') border-error @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div class="form-group">
                                    <label for="email" class="form-label">Alamat Email</label>
                                    <input type="email" class="form-control @error('email') border-error @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div class="flex justify-end mt-6">
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan Profil</button>
                                </div>
                            </form>
                        </div>

                        <!-- Tab Konten Ubah Password -->
                        <div id="password-tab-content" class="tab-content hidden">
                            <form action="{{ route('pengaturan.password.update') }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PATCH')
                                <div class="form-group">
                                    <label for="current_password" class="form-label">Kata Sandi Saat Ini</label>
                                    <input type="password" class="form-control @error('current_password') border-error @enderror" id="current_password" name="current_password" required>
                                    @error('current_password')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div class="form-group">
                                    <label for="password" class="form-label">Kata Sandi Baru</label>
                                    <input type="password" class="form-control @error('password') border-error @enderror" id="password" name="password" required>
                                    @error('password')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>
                                <div class="flex justify-end mt-6">
                                    <button type="submit" class="btn btn-primary">Perbarui Kata Sandi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    tabContents.forEach(content => content.classList.add('hidden'));
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    const target = document.querySelector(button.dataset.tabTarget);
                    if (target) {
                        target.classList.remove('hidden');
                    }
                    button.classList.add('active');
                });
            });
        });
    </script>
    <style>
        .tab-button {
            @apply py-3 px-1 inline-flex items-center gap-2 border-b-2 border-transparent text-sm whitespace-nowrap text-bodytext hover:text-primary;
        }
        .tab-button.active {
            @apply font-semibold border-primary text-primary;
        }
    </style>
@endpush