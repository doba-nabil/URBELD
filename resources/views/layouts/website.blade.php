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
            <style>
                .custom-toast {
                    background: #ffffff;
                    color: #1f2937;
                    border-radius: 12px;
                    box-shadow: 0 10px 40px -10px rgba(0,0,0,0.15);
                    overflow: hidden;
                    border: none;
                    opacity: 0.98;
                }
                .custom-toast.toast-success { border-inline-start: 6px solid #10b981; }
                .custom-toast.toast-error { border-inline-start: 6px solid #ef4444; }
                .custom-toast.toast-warning { border-inline-start: 6px solid #f59e0b; }

                .toast-icon-wrap {
                    min-width: 36px;
                    height: 36px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 50%;
                    margin-inline-end: 12px;
                    font-size: 1.1rem;
                }
                .toast-success .toast-icon-wrap { background: #d1fae5; color: #10b981; }
                .toast-error .toast-icon-wrap { background: #fee2e2; color: #ef4444; }
                .toast-warning .toast-icon-wrap { background: #fef3c7; color: #f59e0b; }
            </style>
            
            <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 10000; margin-top: 70px;">
                @if (session('success'))
                    <div class="toast custom-toast toast-success show mb-3" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-body d-flex align-items-center py-3 pe-3 ps-2">
                            <div class="toast-icon-wrap">
                                <i class="bi bi-check-lg"></i>
                            </div>
                            <div class="flex-grow-1 fw-bold" style="font-size: 0.95rem;">
                                {{ session('success') }}
                            </div>
                            <button type="button" class="btn-close ms-2 me-auto" data-bs-dismiss="toast" aria-label="Close" onclick="this.closest('.toast').remove()"></button>
                        </div>
                    </div>
                @endif
                @if (session('error'))
                    <div class="toast custom-toast toast-error show mb-3" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-body d-flex align-items-center py-3 pe-3 ps-2">
                            <div class="toast-icon-wrap">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <div class="flex-grow-1 fw-bold" style="font-size: 0.95rem;">
                                {{ session('error') }}
                            </div>
                            <button type="button" class="btn-close ms-2 me-auto" data-bs-dismiss="toast" aria-label="Close" onclick="this.closest('.toast').remove()"></button>
                        </div>
                    </div>
                @endif
                @if (session('warning'))
                    <div class="toast custom-toast toast-warning show mb-3" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-body d-flex align-items-center py-3 pe-3 ps-2">
                            <div class="toast-icon-wrap">
                                <i class="bi bi-exclamation-circle"></i>
                            </div>
                            <div class="flex-grow-1 fw-bold" style="font-size: 0.95rem;">
                                {{ session('warning') }}
                            </div>
                            <button type="button" class="btn-close ms-2 me-auto" data-bs-dismiss="toast" aria-label="Close" onclick="this.closest('.toast').remove()"></button>
                        </div>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="toast custom-toast toast-error show mb-3" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-body d-flex align-items-center py-3 pe-3 ps-2">
                            <div class="toast-icon-wrap">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <div class="flex-grow-1 fw-bold" style="font-size: 0.95rem;">
                                {{ __('website.form_error_hint') }}
                            </div>
                            <button type="button" class="btn-close ms-2 me-auto" data-bs-dismiss="toast" aria-label="Close" onclick="this.closest('.toast').remove()"></button>
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
