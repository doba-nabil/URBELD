<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $siteName ?? 'ERSAA' }} - {{ __('admin.landing_page') }}</title>
    
    <!-- Favicon -->
    @php
        $favicon = \App\Models\Setting::getMediaUrl('favicon');
    @endphp
    @if($favicon)
        <link href="{{ $favicon }}" rel="icon">
    @endif

    <!-- Google Web Fonts - Arabic Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Tajawal:wght@400;500;700&family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('website/assets/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('website/assets/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('website/assets/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('website/assets/css/style.css') }}" rel="stylesheet">

    <!-- Custom Landing CSS (Overlaying premium touches) -->
    <link href="{{ asset('website/assets/css/landing.css') }}" rel="stylesheet">
    
    @stack('css')
    
    <style>
        /* Ensuring home styles don't conflict with our glass navbar */
        .navbar-glass { border-bottom: 1px solid transparent !important; }
        .hero-section { background: var(--lp-dark); }
        .hero-overlay { z-index: 1; }
        .hero-content { z-index: 2; position: relative; }
    </style>
</head>
<body class="landing-page">

    @yield('content')

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('website/assets/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('website/assets/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('website/assets/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('website/assets/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        // Initiate the wowjs
        new WOW().init();
        
        // Initiate AOS
        AOS.init({
            duration: 1000,
            once: true,
            easing: 'ease-out-quart'
        });

    </script>
    
    <!-- Template Javascript -->
    <script src="{{ asset('website/assets/js/main.js') }}"></script>
    @stack('js')
</body>
</html>
