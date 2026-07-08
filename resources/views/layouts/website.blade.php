<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
   @include('website.layouts.head')
</head>

<body class="@yield('body_class')">
    <div class="site-scale">
        <div class="container-fluid p-0">
             <!-- Spinner Start -->
            <div id="spinner"
                class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
                @php
                    $loaderLogoUrl = app()->getLocale() == 'ar' 
                                ? \App\Models\Setting::getMediaUrl('logo_ar') 
                                : \App\Models\Setting::getMediaUrl('logo_en');
                    $loaderLogoUrl = $loaderLogoUrl ?: asset('website/assets/img/logo.png');
                @endphp
                <div class="logo-loader-wrapper" style="position: relative; width: 120px; height: 120px;">
                    <!-- Faded Background Logo -->
                    <img src="{{ $loaderLogoUrl }}" alt="Loading" 
                         style="position: absolute; width: 100%; height: 100%; object-fit: contain; opacity: 0.15; filter: grayscale(100%);">
                    <!-- Animated Foreground Logo -->
                    <img src="{{ $loaderLogoUrl }}" alt="Loading" class="logo-loader-animated" 
                         style="position: absolute; width: 100%; height: 100%; object-fit: contain;">
                </div>
                <style>
                    .logo-loader-animated {
                        clip-path: inset(100% 0 0 0);
                        animation: fillLogo 1.5s cubic-bezier(0.4, 0, 0.2, 1) infinite alternate;
                    }
                    @keyframes fillLogo {
                        0% { clip-path: inset(100% 0 0 0); }
                        100% { clip-path: inset(0 0 0 0); }
                    }
                </style>
            </div>
            <!-- Spinner End -->
            <!-- Navbar Start -->
            @include('website.layouts.navbar')
            <!-- Navbar End -->

            <!-- Alerts -->
            <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 10000; margin-top: 70px;">
                @if (session('success'))
                    <div class="toast align-items-center text-white border-0 show mb-2 shadow-lg" role="alert"
                        aria-live="assertive" aria-atomic="true"
                        style="background-color: var(--primary); border-radius: 10px;">
                        <div class="d-flex">
                            <div class="toast-body fw-bold">
                                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                                aria-label="Close" onclick="this.closest('.toast').remove()"></button>
                        </div>
                    </div>
                @endif
                @if (session('error'))
                    <div class="toast align-items-center text-white border-0 show mb-2 shadow-lg" role="alert"
                        aria-live="assertive" aria-atomic="true"
                        style="background-color: #dc3545; border-radius: 10px;">
                        <div class="d-flex">
                            <div class="toast-body fw-bold">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto"
                                data-bs-dismiss="toast" aria-label="Close"
                                onclick="this.closest('.toast').remove()"></button>
                        </div>
                    </div>
                @endif
                @if (session('warning'))
                    <div class="toast align-items-center text-dark border-0 show mb-2 shadow-lg" role="alert"
                        aria-live="assertive" aria-atomic="true"
                        style="background-color: #ffc107; border-radius: 10px;">
                        <div class="d-flex">
                            <div class="toast-body fw-bold">
                                <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('warning') }}
                            </div>
                            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
                                aria-label="Close" onclick="this.closest('.toast').remove()"></button>
                        </div>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="toast align-items-center text-white border-0 show mb-2 shadow-lg" role="alert"
                        aria-live="assertive" aria-atomic="true"
                        style="background-color: #dc3545; border-radius: 10px;">
                        <div class="d-flex">
                            <div class="toast-body fw-bold">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ __('website.form_error_hint') }}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto"
                                data-bs-dismiss="toast" aria-label="Close"
                                onclick="this.closest('.toast').remove()"></button>
                        </div>
                    </div>
                @endif
            </div>

            @include('website.layouts.profile_banner')

            <!-- Main Content -->
            <div class="content-wrap">
                @yield('content')
            </div>

            <!-- Footer Start -->
            @include('website.layouts.footer')
            <!-- Footer End -->
            <!-- Back to Top -->
            <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i
                    class="bi bi-arrow-up"></i></a>
        </div>
    </div>

    <!-- JavaScript Libraries -->
     @include('website.layouts.scripts')


    @stack('js')
</body>

</html>
