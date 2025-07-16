<footer class="pt-16 pb-8 bg-lightgray">
    <div class="container container-xl">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">

            <!-- Kolom 1: Tentang Perusahaan (Lebih Dominan) -->
            <div class="lg:col-span-2 md:col-span-2 col-span-1">
                <h4 class="text-xl font-bold mt-4 mb-4">Pabrik Tahu Melati</h4>
                <p class="opacity-80 pr-4">
                    Menjaga tradisi rasa dan kualitas tahu terbaik dari Kota Batu sejak tahun 1980. Kami berkomitmen menyajikan produk sehat tanpa bahan pengawet dari kedelai lokal pilihan.
                </p>
            </div>

            <!-- Kolom 2: Navigasi Cepat -->
            <div>
                <h4 class="text-lg font-bold mb-4">Navigasi</h4>
                <ul class="space-y-3">
                    <li><a href="#tentang" class="opacity-80 hover:opacity-100 hover:text-primary transition-colors duration-300">Tentang Kami</a></li>
                    <li><a href="#produk" class="opacity-80 hover:opacity-100 hover:text-primary transition-colors duration-300">Produk</a></li>
                    <li><a href="#proses" class="opacity-80 hover:opacity-100 hover:text-primary transition-colors duration-300">Proses</a></li>
                    <li><a href="#lokasi" class="opacity-80 hover:opacity-100 hover:text-primary transition-colors duration-300">Lokasi</a></li>
                </ul>
            </div>

            <!-- Kolom 3: Kontak & Info (Ringkas dengan Ikon) -->
            <div>
                <h4 class="text-lg font-bold mb-4">Kontak & Info</h4>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <i class="ti ti-map-pin text-primary text-xl mt-1"></i>
                        <p class="ml-3 opacity-80">Jl. Lahor No.87, Pesanggrahan, Kec. Batu, Kota Batu</p>
                    </div>
                    <div class="flex items-center">
                        <i class="ti ti-phone text-primary text-xl"></i>
                        <p class="ml-3 opacity-80">(0341) 59xxxx</p>
                    </div>
                    <div class="flex items-center">
                        <i class="ti ti-clock text-primary text-xl"></i>
                        <p class="ml-3 opacity-80">06:00 - 16:00 WIB</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Bagian Bawah Footer: Copyright dan Media Sosial -->
        <div class="border-t border-gray-600/50 pt-6 flex flex-col md:flex-row justify-between items-center text-center md:text-left">
            <p class="text-sm opacity-60 mb-4 md:mb-0">
                © {{ date('Y') }} Pabrik Tahu Melati. All rights reserved.
            </p>
            {{-- <div class="flex space-x-5">
                <a href="#" aria-label="Instagram" class="opacity-60 hover:opacity-100 hover:text-primary transition-colors duration-300">
                    <i class="ti ti-brand-instagram text-2xl"></i>
                </a>
                <a href="#" aria-label="Facebook" class="opacity-60 hover:opacity-100 hover:text-primary transition-colors duration-300">
                    <i class="ti ti-brand-facebook text-2xl"></i>
                </a>
                <a href="#" aria-label="WhatsApp" class="opacity-60 hover:opacity-100 hover:text-primary transition-colors duration-300">
                    <i class="ti ti-brand-whatsapp text-2xl"></i>
                </a>
            </div> --}}
        </div>
    </div>
</footer>