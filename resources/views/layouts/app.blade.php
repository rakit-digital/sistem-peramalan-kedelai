<!DOCTYPE html>
<html lang="en" dir="ltr" data-color-theme="Soybean_Harvest_Theme" class="light selected" data-layout="vertical"
    data-boxed-layout="boxed" data-card="shadow">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('assets/images/logos/favicon.ico')}}" />
    <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.44.0/tabler-icons.min.css">
    <!-- Core Css -->
    <link rel="stylesheet" href="{{asset('assets/css/theme.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}" />
    <title>Peramalan Kedelai</title>
    @vite(['resources/css/app.css', 'resources/js/app.js']) 

    @stack('css')
</head>

<body class="DEFAULT_THEME bg-white dark:bg-dark">
    <main>
        <!--start the project-->
        <div id="main-wrapper" class="flex">
            @include('layouts.sidebar')
            <div class="page-wrapper w-full" role="main">
                <!--  Header Start -->
                @include('layouts.header')
                <!--  Header End -->

                <!-- Main Content -->
                <div class="max-w-full pt-6">
                    <div class="container full-container py-5">
                        @yield('content')
                    </div>
                </div>
                <!-- Main Content End -->
            </div>
        </div>
        <!--end of project-->

    </main>

    <div id="hs-focus-management-modal"
        class="hs-overlay hs-overlay-open:opacity-100 hs-overlay-open:duration-500 hidden w-full h-full fixed top-0 start-0 z-[60] opacity-0 overflow-x-hidden transition-all overflow-y-auto pointer-events-none">
        <div
            class="hs-overlay-open:opacity-100 hs-overlay-open:duration-500 opacity-0 transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
            <div
                class="flex flex-col bg-white dark:bg-dark  shadow-md dark:shadow-dark-md rounded-md pointer-events-auto">
                <div class="p-4 overflow-y-auto">
                    <input type="email" class="w-full form-control" placeholder="you@site.com" autofocus>
                </div>
                <div class="items-center gap-x-2 py-3 px-4 border-t border-border dark:border-darkborder">

                    <div class="overflow-hidden">
                        <h5 class="text-lg  mb-4 px-4">Quick Page Links</h5>
                        <div class="message-body max-h-[300px]" data-simplebar="">
                            <ul>
                                <li class="light-dark-hoverbg rounded-md  mb-2 px-4 py-2">
                                    <a href="#" class="font-semibold text-sm text-link dark:text-darklink ">
                                        Modern
                                        <p class="text-sm text-link dark:text-darklink opacity-50 font-normal">
                                            /dashboards/modern</p>
                                    </a>
                                </li>
                                <li class="light-dark-hoverbg rounded-md  mb-2 px-4 py-2">
                                    <a href="#" class="font-semibold text-sm text-link dark:text-darklink">
                                        eCommerce
                                        <p class="text-sm text-link dark:text-darklink opacity-50 font-normal">
                                            /dashboards/ecommerce</p>
                                    </a>
                                </li>
                                <li class="light-dark-hoverbg rounded-md  mb-2 px-4 py-2">
                                    <a href="#" class="font-semibold text-sm text-link dark:text-darklink">
                                        Contacts
                                        <p class="text-sm text-link dark:text-darklink opacity-50 font-normal">
                                            /apps/contacts</p>
                                    </a>
                                </li>
                                <li class="light-dark-hoverbg rounded-md  mb-2 px-4 py-2">
                                    <a href="#" class="font-semibold text-sm text-link dark:text-darklink">
                                        Shop
                                        <p class="text-sm text-link dark:text-darklink opacity-50 font-normal">
                                            /ecommerce/products</p>
                                    </a>
                                </li>
                                <li class="light-dark-hoverbg rounded-md  mb-2 px-4 py-2">
                                    <a href="#" class="font-semibold text-sm text-link dark:text-darklink">
                                        Checkout
                                        <p class="text-sm text-link dark:text-darklink opacity-50 font-normal">
                                            /ecommerce/checkout</p>
                                    </a>
                                </li>
                                <li class="light-dark-hoverbg rounded-md  mb-2 px-4 py-2">
                                    <a href="#" class="font-semibold text-sm text-link dark:text-darklink">
                                        Chats
                                        <p class="text-sm text-link dark:text-darklink opacity-50 font-normal">
                                            /apps/chats</p>
                                    </a>
                                </li>
                                <li class="light-dark-hoverbg rounded-md  mb-2 px-4 py-2">
                                    <a href="#" class="font-semibold text-sm text-link dark:text-darklink">
                                        Notes
                                        <p class="text-sm text-link dark:text-darklink opacity-50 font-normal">
                                            /apps/notes</p>
                                    </a>
                                </li>
                                <li class="light-dark-hoverbg rounded-md  mb-2 px-4 py-2">
                                    <a href="#" class="font-semibold text-sm text-link dark:text-darklink">
                                        Pricing
                                        <p class="text-sm text-link dark:text-darklink opacity-50 font-normal">
                                            /pages/pricing</p>
                                    </a>
                                </li>
                                <li class="light-dark-hoverbg rounded-md  mb-2 px-4 py-2">
                                    <a href="#" class="font-semibold text-sm text-link dark:text-darklink">
                                        Account Setting
                                        <p class="text-sm text-link dark:text-darklink opacity-50 font-normal">
                                            /pages/account-settings</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function handleColorTheme(e) {
            document.documentElement.setAttribute("data-color-theme", e);
        }
    </script>
    <script src="{{asset('assets/js/vendor.min.js')}}"></script>

    <script src="{{asset('assets/js/theme/app.init.js')}}"></script>
    <script src="{{asset('assets/js/theme/app.min.js')}}"></script>
    <script src="{{asset('assets/js/theme.js')}}"></script>
    <script src="{{asset('assets/libs/simplebar/dist/simplebar.min.js')}}"></script>
    <script src="{{asset('assets/libs/preline/dist/preline.js')}}"></script>
    <script src="{{asset('assets/libs/@preline/input-number/index.js')}}"></script>
    <script src="{{asset('assets/libs/@preline/tooltip/index.js')}}"></script>
    <script src="{{asset('assets/libs/@preline/stepper/index.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('scripts')

</body>

</html>
