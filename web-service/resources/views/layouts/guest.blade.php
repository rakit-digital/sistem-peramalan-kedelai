<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" data-color-theme="Soybean_Harvest_Theme" class="light selected">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/logos/favicon.ico') }}" />
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.44.0/tabler-icons.min.css">
    
    <!-- Core Css (sesuaikan dengan path di proyek Anda) -->
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}" />

    <title>Login | {{ config('app.name', 'Peramalan Kedelai') }}</title>
</head>

<body class="DEFAULT_THEME bg-white dark:bg-dark">	
    <main>
        <div id="main-wrapper" class="flex">
            <!-- Main Content -->
            <div class="h-screen w-full bg-lightsuccess dark:bg-darkinfo">
                <div class="h-full w-full flex justify-center items-center">
                    <div class="flex justify-center w-full">
                        <div class="xl:w-2/6 lg:w-2/5 md:w-1/2 sm:w-3/4 w-11/12">
                            <div class="max-w-[500px] px-5 mx-auto">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Main Content End -->
        </div>
    </main>
    
    <!-- Scripts (sesuaikan dengan path di proyek Anda) -->
    {{-- <script src="{{asset('assets/js/vendor.min.js')}}"></script>
    <script src="{{asset('assets/js/theme/app.init.js')}}"></script> --}}
</body>
</html>