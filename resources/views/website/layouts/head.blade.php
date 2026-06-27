 <meta charset="utf-8">
    <title>@yield('title', ($settings['site_name'] ?? config('app.name')))</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="{{ __('website.meta_keywords') }}" name="keywords">
    <meta content="{{ __('website.meta_description') }}" name="description">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link href="{{ \App\Models\Setting::getMediaUrl('favicon') ?: asset('website/assets/img/fav.png') }}" rel="icon">

    <!-- Google Web Fonts - Arabic Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Tajawal:wght@400;500;700&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('website/assets/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('website/assets/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('website/assets/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('website/assets/css/style.css') }}" rel="stylesheet">

@stack('css')

    <style>
        .site-scale {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
        .card-ribbon {
            position: absolute;
            top: 0;
            right: 0;
            width: 130px;
            height: 130px;
            overflow: hidden;
            z-index: 10;
            pointer-events: none;
        }

        .card-ribbon span {
            position: absolute;
            display: block;
            width: 225px;
            padding: 8px 0;
            background-color: var(--bs-primary);
            box-shadow: 0 5px 10px rgba(0,0,0,.1);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            text-shadow: 0 1px 1px rgba(0,0,0,.2);
            text-align: center;
            right: -55px;
            top: 30px;
            transform: rotate(45deg);
            z-index: 11;
        }
    </style>
    @stack('css')