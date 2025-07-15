<aside id="application-sidebar-brand"
    class="hs-overlay hs-overlay-open:translate-x-0 -translate-x-full xl:rtl:-translate-x-0 rtl:translate-x-full left-0 rtl:left-auto rtl:right-0 transform hidden xl:block xl:translate-x-0 xl:end-auto xl:bottom-0 fixed top-0 with-vertical left-sidebar transition-all duration-300 h-screen xl:z-[2] z-[60] flex-shrink-0 border-r rtl:border-l rtl:border-r-0 w-[270px] border-border bg-white">
    <!-- Logo -->
    <div class="py-5 px-5">
        <div class="brand-logo">
            <a href="{{ route('dashboard') }}" class="text-nowrap logo-img">
                <img src="{{ asset('assets/images/logos/logo-pabrik.png') }}" class="block w-16" alt="Logo" />
            </a>
        </div>
    </div>

    <!-- Navigasi -->
    <div class="overflow-hidden">
        <div class="scroll-sidebar" data-simplebar="">
            <div class="px-6 mt-8 mini-layout" data-te-sidenav-menu-ref>
                <nav class="hs-accordion-group w-full flex flex-col">
                    <ul data-te-sidenav-menu-ref id="sidebarnav">

                        <div class="caption">
                            <i class="ti ti-dots nav-small-cap-icon"></i><span class="hide-menu">Home</span>
                        </div>
                        <li class="sidebar-item">
                            <a class="sidebar-link dark-sidebar-link {{ request()->routeIs('dashboard') ? 'activemenu' : '' }}" href="{{ route('dashboard') }}">
                                <i class="ti ti-layout-dashboard text-xl"></i><span class="hide-menu">Dashboard</span>
                            </a>
                        </li>

                        <div class="caption mt-8">
                            <i class="ti ti-dots nav-small-cap-icon"></i><span class="hide-menu">Manajemen</span>
                        </div>
                        <li class="sidebar-item">
                            <a class="sidebar-link dark-sidebar-link {{ request()->routeIs('data.kedelai.*') ? 'activemenu' : '' }}" href="{{ route('data.kedelai.index') }}">
                                <i class="ti ti-bowl text-xl"></i><span class="hide-menu">Data Kedelai</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link dark-sidebar-link {{ request()->routeIs('peramalan') ? 'activemenu' : '' }}" href="{{ route('peramalan') }}">
                                <i class="ti ti-report-analytics text-xl"></i><span class="hide-menu">Peramalan Kedelai</span>
                            </a>
                        </li>

                        <div class="caption mt-8">
                            <i class="ti ti-dots nav-small-cap-icon"></i><span class="hide-menu">Laporan & Analisis</span>
                        </div>
                        <li class="sidebar-item">
                            <a class="sidebar-link dark-sidebar-link {{ request()->routeIs('laporan') ? 'activemenu' : '' }}" href="{{ route('laporan') }}">
                                <i class="ti ti-file-report text-xl"></i><span class="hide-menu">Laporan</span>
                            </a>
                        </li>

                        <div class="caption mt-8">
                            <i class="ti ti-dots nav-small-cap-icon"></i><span class="hide-menu">Settings</span>
                        </div>
                        <li class="sidebar-item">
                            <a class="sidebar-link dark-sidebar-link {{ request()->routeIs('pengaturan') ? 'activemenu' : '' }}" href="{{ route('pengaturan') }}">
                                <i class="ti ti-settings text-xl"></i><span class="hide-menu">Pengaturan Akun</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</aside>