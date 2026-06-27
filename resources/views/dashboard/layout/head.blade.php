<meta charset="utf-8" />
<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<meta name="robots" content="noindex, nofollow" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ __('admin.admin-panel') }} | @yield('title')</title>

<meta name="description" content="" />

<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="{{ \App\Models\Setting::getMediaUrl('favicon') ?: asset('dashboard/assets/img/favicon/fav-icon.png') }}" />

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Tajawal:wght@400;500;700&display=swap"
    rel="stylesheet">

<link rel="stylesheet" href="{{ asset('dashboard') }}/assets/vendor/fonts/iconify-icons.css" />
<link rel="stylesheet" href="{{ asset('dashboard') }}/assets/vendor/fonts/fontawesome.css" />

<!-- Core CSS -->
<!-- build:css assets/vendor/css/theme.css  -->

<link rel="stylesheet" href="{{ asset('dashboard') }}/assets/vendor/libs/node-waves/node-waves.css" />

<link rel="stylesheet" href="{{ asset('dashboard') }}/assets/vendor/libs/pickr/pickr-themes.css" />

<link rel="stylesheet" href="{{ asset('dashboard') }}/assets/vendor/css/core.css" />
<link rel="stylesheet" href="{{ asset('dashboard') }}/assets/css/demo.css" />
<link rel="stylesheet" href="{{ asset('dashboard') }}/assets/css/custom.css?ver=1.1.3" />

<!-- Vendors CSS -->

<link rel="stylesheet" href="{{ asset('dashboard') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

<!-- endbuild -->

<link rel="stylesheet" href="{{ asset('dashboard') }}/assets/vendor/libs/apex-charts/apex-charts.css" />
<link rel="stylesheet" href="{{ asset('dashboard') }}/assets/vendor/libs/swiper/swiper.css" />
<link rel="stylesheet" href="{{ asset('dashboard') }}/assets/vendor/fonts/flag-icons.css" />

<!-- Page CSS -->
<link rel="stylesheet" href="{{ asset('dashboard') }}/assets/vendor/css/pages/cards-advance.css" />
@section('dashboard-head')

@show

<!-- Helpers -->
<script src="{{ asset('dashboard') }}/assets/vendor/js/helpers.js"></script>
<!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

<!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->

<script src="{{ asset('dashboard') }}/assets/js/config.js"></script>

