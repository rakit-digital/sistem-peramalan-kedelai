<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" data-color-theme="Soybean_Harvest_Theme" class="light selected">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Pabrik Tahu Melati, produsen tahu berkualitas tinggi di Kota Batu sejak 1980. Tradisi rasa, kualitas terjamin.">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/logos/favicon.ico') }}" />
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.44.0/tabler-icons.min.css">
    
    <!-- Core Css -->
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}" />
    
    <title>Pabrik Tahu Melati - Kualitas & Tradisi dari Kota Batu</title>
</head>

<body class="bg-white">
    <main>
        <div id="main-wrapper" class="flex landingpage">
            <div class="w-full">
                <!-- Header -->
                @include('pages.landing.layouts.header')

                <!-- Main Content -->
                <div class="max-w-full pt-6">
                    @yield('content')
                </div>

                <!-- Footer -->
                @include('pages.landing.layouts.footer')
            </div>
        </div>
    </main>

    {{-- Script untuk smooth scroll --}}
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>