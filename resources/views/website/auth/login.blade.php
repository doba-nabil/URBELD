<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <title>{{ __('website.login_title') }} - {{ $settings['site_name'] ?? 'اوربلد' }}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="تسجيل الدخول، حسابي، اوربلد" name="keywords">
    <meta content="اوربلد - نبدع في تصميم المساحات التي تُلهم وتُبتكر وتدوم" name="description">

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

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('website/assets/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('website/assets/css/style.css') }}" rel="stylesheet">
</head>

<body class="login-page">
    <div class="site-scale">
    <div class="container d-flex align-items-center justify-content-center min-vh-100 py-5">
        <div class="card shadow-lg border-0 rounded-4 w-100" style="max-width: 450px;">
            <div class="card-body p-5">
                <div class="login-form-wrapper">
                <!-- Logo -->
                <div class="login-logo text-center mb-4">
                    <a href="{{ url('/') }}">
                        @php
                            $currentLogo = app()->getLocale() == 'ar' 
                                            ? \App\Models\Setting::getMediaUrl('logo_ar') 
                                            : \App\Models\Setting::getMediaUrl('logo_en');
                            $currentLogo = $currentLogo ?: (\App\Models\Setting::getMediaUrl('favicon') ?: asset('website/assets/img/logo.png'));
                        @endphp
                        <img src="{{ $currentLogo }}" alt="Logo" class="login-logo-img" style="max-height: 80px;">
                    </a>
                </div>

                <!-- Heading -->
                <div class="login-heading">
                    <h1 class="login-title">{{ __('website.login_title') }}</h1>
                    <p class="login-subtitle">{{ __('website.welcome_back') }}</p>
                </div>

                <!-- Session Status -->
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Login Form -->
                <form class="login-form" method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf
                    
                    <!-- Email Field -->
                    <div class="form-group login-form-group" id="emailFieldGroup">
                        <label for="email" class="form-label">{{ __('website.email') . ' / ' . __('website.commercial_record') }}</label>
                        <input type="text" 
                               class="form-control login-input @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               placeholder="{{ __('website.email') . ' / ' . __('website.commercial_record') }}" 
                               required 
                               autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="form-group login-form-group">
                        <label for="password" class="form-label">{{ __('website.password') }}</label>
                        <input type="password" 
                               class="form-control login-input @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               placeholder="{{ __('website.password') }}" 
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Forget Password Link -->
                    <div class="login-form-actions">
                        <a href="{{ route('password.request') }}" class="forget-password-link">{{ __('website.forgot_password') }}</a>
                    </div>

                    <!-- Sign In Button -->
                    <button type="submit" class="auth btn btn-icon py-3 px-5" id="signInBtn">
                        <span>{{ __('website.login_btn') }}</span>
                        <i class="icon-btn bi bi-arrow-left"></i>
                    </button>
                </form>

                <!-- Register Link -->
                <div class="login-link-section">
                    <p>{{ __('website.no_account') }} <a href="{{ route('register') }}">{{ __('website.create_account') }}</a></p>
                </div>

                <!-- Legal Text -->
                <div class="login-legal-text">
                    <p>{!! __('website.agree_terms') !!}</p>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Scroll to top on page load
        window.addEventListener('load', function() {
            const rightSection = document.querySelector('.login-right-section');
            if (rightSection) {
                rightSection.scrollTop = 0;
            }
            window.scrollTo(0, 0);
        });
    </script>
</body>

</html>
