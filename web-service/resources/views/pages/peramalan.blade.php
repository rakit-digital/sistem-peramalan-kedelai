@extends('layouts.app')

@section('content')
    <!-- Header & Breadcrumb -->
    <div class="card bg-lightprimary shadow-none position-relative overflow-hidden mb-6">
        <div class="card-body md:py-3 py-5">
            <div class="flex items-center grid grid-cols-12 gap-6">
                <div class="col-span-12">
                    <h4 class="font-semibold text-xl text-dark mb-3">
                        Peramalan Kebutuhan Kedelai
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
            <div class="pb-6 border-b border-border">
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
                        <button id="btn-ramal"
                            class="btn btn-primary w-full md:w-auto flex items-center gap-2 justify-center">
                            <i id="icon-ramal" class="ti ti-player-play"></i>
                            <span id="text-ramal">Mulai Peramalan</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Hasil Peramalan -->
            <div id="hasil-peramalan-container" class="pt-6">
                <h5 class="card-title mb-4">Hasil Peramalan</h5>
                <div class="text-center text-bodytext py-10">
                    <i class="ti ti-chart-infographic text-5xl mb-2"></i>
                    <p>Hasil peramalan akan muncul di sini setelah proses dijalankan.</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('btn-ramal').addEventListener('click', function() {
    // 1. Tampilkan Loading State
    const button = this;
    const icon = document.getElementById('icon-ramal');
    const text = document.getElementById('text-ramal');
    
    button.disabled = true;
    icon.className = 'ti ti-loader animate-spin'; // Ganti ikon dengan spinner
    text.textContent = 'Sedang memproses...';

    // 2. Simulasi Proses Backend (nantinya ini adalah call AJAX)
    setTimeout(() => {
        // 3. Tampilkan Hasil (Contoh dengan data statis)
        const hasilContainer = document.getElementById('hasil-peramalan-container');
        hasilContainer.innerHTML = `
            <h5 class="card-title mb-4">Hasil Peramalan untuk 7 Hari ke Depan</h5>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-lightgray"><tr>
                        <th class="px-4 py-3 font-semibold text-sm">Tanggal</th>
                        <th class="px-4 py-3 font-semibold text-sm text-right">Prediksi Kebutuhan (kg)</th>
                    </tr></thead>
                    <tbody>
                        <tr class="border-b border-border"><td class="px-4 py-3">27 Mei 2024</td><td class="px-4 py-3 text-right font-medium">25.1</td></tr>
                        <tr class="border-b border-border"><td class="px-4 py-3">28 Mei 2024</td><td class="px-4 py-3 text-right font-medium">24.8</td></tr>
                        {{-- ... data lainnya ... --}}
                    </tbody>
                </table>
            </div>
            <div class="h-80 mt-6"><canvas id="grafikHasil"></canvas></div>
        `;
        
        // (Tambahkan logika untuk render grafik di sini)

        // 4. Kembalikan Tombol ke State Semula
        button.disabled = false;
        icon.className = 'ti ti-player-play';
        text.textContent = 'Mulai Peramalan';

    }, 2500); // Simulasi delay 2.5 detik
});
</script>
@endpush
