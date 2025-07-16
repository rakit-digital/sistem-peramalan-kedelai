<header class="sticky top-header top-0 inset-x-0 z-[5] flex flex-wrap md:justify-start md:flex-nowrap text-sm px-0 sm:py-3 py-2 bg-white shadow-md">
    <div class="container container-xl flex items-center">
        <div class="flex-1">
            <div class="brand-logo flex items-center">
                <a href="{{ route('landing.page') }}" class="text-nowrap logo-img">
                    <img src="{{ asset('assets/images/logos/logo-pabrik.png') }}" class="block w-16" alt="Logo Pabrik Tahu Melati" />
                </a>
            </div>
        </div>
        
        <!--- Mobile Toggle Icon --->
        <div class="xl:hidden">
            {{-- Tambahkan fungsionalitas menu mobile jika diperlukan --}}
        </div>

        <!-- Menu Utama -->
        <div>
            <ul class="xl:flex hidden items-center gap-6">
                <li><a href="#tentang" class="hover:text-primary text-link text-sm font-medium">Tentang Kami</a></li>
                <li><a href="#produk" class="hover:text-primary text-link text-sm font-medium">Produk Kami</a></li>
                <li><a href="#proses" class="hover:text-primary text-link text-sm font-medium">Proses Produksi</a></li>
                <li><a href="#lokasi" class="hover:text-primary text-link text-sm font-medium">Lokasi</a></li>
                <li class="ml-4"><a href="{{ route('login') }}" class="btn py-1.5 px-6 text-sm">Login</a></li>
            </ul>
        </div>
    </div>
</header>