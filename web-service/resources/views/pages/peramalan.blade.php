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
    {{-- Chart.js juga akan kita gunakan di sini --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.getElementById('btn-ramal').addEventListener('click', async function() {
            // 1. Setup & Loading State
            const button = this;
            const icon = document.getElementById('icon-ramal');
            const text = document.getElementById('text-ramal');
            const hasilContainer = document.getElementById('hasil-peramalan-container');
            const periodeSelect = document.getElementById('periode');
            const days = parseInt(periodeSelect.value);

            button.disabled = true;
            icon.className = 'ti ti-loader animate-spin';
            text.textContent = 'Menghubungi layanan peramalan...';
            
            // Hapus hasil lama
            hasilContainer.innerHTML = `<div class="text-center py-10"><i class="ti ti-loader animate-spin text-3xl"></i><p class="mt-2">Sedang memproses...</p></div>`;

            try {
                // 2. Kirim AJAX Request menggunakan Fetch API
                const response = await fetch("{{ route('peramalan.generate') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}", // Penting untuk keamanan
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ days: days })
                });

                const data = await response.json();

                if (!response.ok) {
                    // Jika ada error dari backend (validasi, server error, dll)
                    throw new Error(data.error || `Terjadi kesalahan: ${response.statusText}`);
                }
                
                // 3. Jika berhasil, render hasil ke halaman
                renderHasil(data, days);

            } catch (error) {
                // 4. Tangani jika ada error jaringan atau dari backend
                console.error('Error saat peramalan:', error);
                hasilContainer.innerHTML = `
                    <div class="text-center text-error py-10">
                        <i class="ti ti-alert-triangle text-5xl mb-2"></i>
                        <p class="font-bold">Gagal Melakukan Peramalan</p>
                        <p class="text-sm mt-1">${error.message}</p>
                    </div>`;
            } finally {
                // 5. Kembalikan tombol ke state semula
                button.disabled = false;
                icon.className = 'ti ti-player-play';
                text.textContent = 'Mulai Peramalan';
            }
        });

        function renderHasil(data, days) {
            const hasilContainer = document.getElementById('hasil-peramalan-container');

            // Buat baris tabel
            let tableRows = '';
            data.forEach(item => {
                const date = new Date(item.tanggal).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                tableRows += `
                    <tr class="border-b border-border">
                        <td class="px-4 py-3">${date}</td>
                        <td class="px-4 py-3 text-right font-medium">${item.prediksi_stok_kg.toFixed(1)}</td>
                    </tr>
                `;
            });

            // Siapkan data untuk grafik
            const chartLabels = data.map(item => new Date(item.tanggal).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' }));
            const chartValues = data.map(item => item.prediksi_stok_kg);

            // Render seluruh kontainer hasil
            hasilContainer.innerHTML = `
                <h5 class="card-title mb-4">Hasil Peramalan untuk ${days} Hari ke Depan</h5>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-lightgray"><tr>
                                <th class="px-4 py-3 font-semibold text-sm">Tanggal</th>
                                <th class="px-4 py-3 font-semibold text-sm text-right">Prediksi Kebutuhan (kg)</th>
                            </tr></thead>
                            <tbody>${tableRows}</tbody>
                        </table>
                    </div>
                    <div class="h-80"><canvas id="grafikHasil"></canvas></div>
                </div>
            `;
            
            // Render grafik
            renderGrafik(chartLabels, chartValues);
        }

        function renderGrafik(labels, values) {
            const ctx = document.getElementById('grafikHasil');
            if(ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Prediksi Kebutuhan (kg)',
                            data: values,
                            backgroundColor: getComputedStyle(document.documentElement).getPropertyValue('--color-lightprimary').trim(),
                            borderColor: getComputedStyle(document.documentElement).getPropertyValue('--color-primary').trim(),
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: { y: { beginAtZero: true } },
                        plugins: { legend: { display: false } }
                    }
                });
            }
        }
    </script>
@endpush
