@extends('pages.landing.layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section overflow-hidden items-center mb-12 bg-lightprimary py-16">
        <div class="container container-xl">
            <div class="grid grid-cols-12 gap-6 items-center">
                <div class="xl:col-span-6 col-span-12">
                    <div class="xl:pt-0 pt-8">
                        <h6 class="flex items-center gap-2 text-base mb-3 text-secondary">
                            <i class="ti ti-plant-2"></i> Sejak 1980, Kualitas Turun-temurun
                        </h6>
                        <h1 class="font-bold mb-7 lg:text-[55px] lg:leading-[66px] text-4xl">
                            Pabrik Tahu Melati
                            <br>
                            <span class="text-primary">Kota Batu</span>
                        </h1>
                        <p class="text-lg mb-10">
                            Menghadirkan tahu berkualitas terbaik yang dibuat dari kedelai pilihan dan resep tradisional.
                            Rasakan kelembutan dan cita rasa otentik di setiap gigitan.
                        </p>
                        <div class="md:flex items-center gap-3.5">
                            <a class="btn py-3 px-12 mb-3 sm:mb-0 flex justify-center" href="{{ route('login') }}">
                                Login
                            </a>
                            <a class="btn btn-outline-primary scroll-link px-6 py-3 flex justify-center" href="#produk">
                                Lihat Produk Kami
                            </a>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-6 col-span-12 xl:block hidden">
                    <div class="p-4 rounded overflow-hidden">
                        <img src="{{ asset('assets/images/backgrounds/tahu-kedelai.jpg') }}" alt="Produk Tahu Melati"
                            class="rounded-lg shadow-xl" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tentang Kami Section -->
    <section id="tentang" class="py-16 mb-6">
        <div class="container container-xl">
            <div class="grid grid-cols-12 gap-8 items-center">
                <div class="xl:col-span-5 col-span-12">
                    <div class="rounded-lg overflow-hidden shadow-lg">
                        {{-- Ganti dengan foto suasana pabrik atau pemilik --}}
                        <img src="{{ asset('assets/images/backgrounds/pabrik-suasana.png') }}"
                            alt="Suasana Pabrik Tahu Melati" class="w-full h-auto" />
                    </div>
                </div>
                <div class="md:col-span-7 col-span-12">
                    <h2 class="font-bold mb-5 text-4xl">
                        Warisan Rasa, <span class="text-primary">Kualitas Terjaga</span>
                    </h2>
                    <p class="text-lg mb-6">
                        Pabrik Tahu Melati didirikan pada tahun 1980 di jantung Kota Batu. Berawal dari usaha keluarga
                        sederhana, kami berkomitmen untuk menjaga resep tradisional dan proses pembuatan tahu yang higienis
                        untuk menghasilkan produk yang tidak hanya lezat, tetapi juga sehat dan bergizi.
                    </p>
                    <p class="text-lg">
                        Misi kami adalah menjadi produsen tahu terpercaya bagi masyarakat, dengan selalu menggunakan kedelai
                        lokal pilihan dan tanpa bahan pengawet.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Produk Section -->
    <section id="produk" class="py-16 bg-lightgray mb-6">
        <div class="container container-xl">
            <div class="text-center lg:w-3/5 w-full mx-auto">
                <h2 class="font-bold md:text-4xl text-2xl mb-6">Produk Unggulan Kami</h2>
                <p class="text-lg mb-10">Dibuat dengan cinta dan dedikasi, setiap produk kami menjanjikan kualitas terbaik
                    untuk setiap masakan Anda.</p>
            </div>
            <div class="grid grid-cols-12 gap-6 mt-6">
                <!-- Produk 1: Tahu Putih Klasik -->
                <div class="lg:col-span-4 md:col-span-6 col-span-12">
                    <div class="card bg-white rounded-lg shadow-md h-full">
                        <div class="card-body p-8">
                            <div class="mb-6 text-yellow-500">
                                <i class="ti ti-box text-5xl"></i>
                            </div>
                            <h3 class="font-bold text-2xl mb-4">Tahu Putih Klasik</h3>
                            <p class="mb-6 text-gray-600">
                                Lembut, padat, dan segar. Sempurna untuk digoreng, ditumis, atau dibuat sup. Produk andalan
                                kami sejak dulu.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Produk 2: Tahu Kuning Khas -->
                <div class="lg:col-span-4 md:col-span-6 col-span-12">
                    <div class="card bg-white rounded-lg shadow-md h-full">
                        <div class="card-body p-8">
                            <div class="mb-6 text-primary">
                                <i class="ti ti-cube text-5xl"></i>
                            </div>
                            <h3 class="font-bold text-2xl mb-4">Tahu Kuning Khas</h3>
                            <p class="mb-6 text-gray-600">
                                Dengan pewarna alami dari kunyit, memberikan aroma khas dan rasa yang lebih gurih. Cocok
                                untuk tahu takwa.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Produk 3: Tahu Goreng Siap Saji -->
                <div class="lg:col-span-4 md:col-span-6 col-span-12">
                    <div class="card bg-white rounded-lg shadow-md h-full">
                        <div class="card-body p-8">
                            <div class="mb-6 text-orange-500">
                                <i class="ti ti-flame text-5xl"></i>
                            </div>
                            <h3 class="font-bold text-2xl mb-4">Tahu Goreng Siap Saji</h3>
                            <p class="mb-6 text-gray-600">
                                Krispi di luar, lembut di dalam. Sudah dibumbui dengan resep rahasia kami, tinggal nikmati.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Proses Produksi Section -->
    <section id="proses" class="py-16 mb-6">
        <div class="container container-xl">
            <div class="text-center lg:w-3/5 w-full mx-auto">
                <h2 class="font-bold md:text-4xl text-2xl mb-6">Bagaimana Tahu Kami Dibuat?</h2>
                <p class="text-lg mb-10">Kami menjaga setiap langkah proses produksi untuk memastikan kualitas terbaik
                    sampai ke tangan Anda.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="mx-auto bg-lightprimary h-20 w-20 rounded-full flex items-center justify-center mb-4"><i
                            class="ti ti-droplet text-primary text-4xl"></i></div>
                    <h4 class="font-bold text-lg mb-2">1. Pemilihan Kedelai</h4>
                    <p>Hanya kedelai lokal berkualitas tinggi yang kami pilih dan rendam.</p>
                </div>
                <div>
                    <div class="mx-auto bg-lightprimary h-20 w-20 rounded-full flex items-center justify-center mb-4"><i
                            class="ti ti-flame text-primary text-4xl"></i></div>
                    <h4 class="font-bold text-lg mb-2">2. Penggilingan & Pemasakan</h4>
                    <p>Kedelai digiling halus dan dimasak hingga menjadi sari kedelai murni.</p>
                </div>
                <div>
                    <div class="mx-auto bg-lightprimary h-20 w-20 rounded-full flex items-center justify-center mb-4"><i
                            class="ti ti-filter text-primary text-4xl"></i></div>
                    <h4 class="font-bold text-lg mb-2">3. Penyaringan</h4>
                    <p>Sari kedelai disaring untuk memisahkan ampas, menghasilkan susu kedelai.</p>
                </div>
                <div>
                    <div class="mx-auto bg-lightprimary h-20 w-20 rounded-full flex items-center justify-center mb-4"><i
                            class="ti ti-box text-primary text-4xl"></i></div>
                    <h4 class="font-bold text-lg mb-2">4. Pencetakan</h4>
                    <p>Susu kedelai digumpalkan dan dicetak menjadi tahu yang padat dan lembut.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Lokasi Section -->
    <section id="lokasi" class="py-12 bg-lightgray">
        <div class="container container-xl">
            <div class="text-center lg:w-3/5 w-full mx-auto mb-10">
                <h2 class="font-bold md:text-4xl text-2xl mb-6">Kunjungi Pabrik Kami</h2>
                <p class="text-lg text-center">Lihat langsung proses produksi kami dan dapatkan produk tahu segar setiap
                    hari.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div class="card p-0 overflow-hidden shadow-lg rounded-lg">
                    <div class="h-96 w-full">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3951.916895318721!2d112.50640877402633!3d-7.867916278061452!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7887351d9d5f1d%3A0xee977acda0d939ec!2sTAHU%20MELATI%20BATU!5e0!3m2!1sen!2sid!4v1678886543210"
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
                <div>
                    <h3 class="text-2xl font-bold mb-4">Informasi Kontak</h3>
                    <div class="space-y-4">
                        <div class="flex items-start"><i class="ti ti-map-pin text-primary text-xl mt-1"></i>
                            <p class="ml-4">Jl. Lahor No.87, RT.02/RW.07, Pesanggrahan, Kec. Batu, Kota Batu, Jawa Timur
                                65313</p>
                        </div>
                        <div class="flex items-center"><i class="ti ti-phone text-primary text-xl"></i>
                            <p class="ml-4">(0341) 59xxxx</p>
                        </div>
                        <div class="space-y-2 mt-4">
                            <h4 class="font-bold text-lg">Jam Buka:</h4>
                            <p>Setiap Hari: 06:00 - 16:00 WIB</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection