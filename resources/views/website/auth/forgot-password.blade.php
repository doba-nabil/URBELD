<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <title>{{ __('website.forgot_password') }} - {{ $settings['site_name'] ?? 'اوربلد' }}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <!-- Favicon -->
    <link href="{{ asset('website/assets/img/fav.png') }}" rel="icon">

    <!-- Google Web Fonts - Arabic Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">

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
                <div class="login-logo">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('website/assets/img/logo.png') }}" alt="Urbeld Logo" class="login-logo-img">
                    </a>
                </div>

                <!-- Heading -->
                <div class="login-heading">
                    <h1 class="login-title">{{ __('website.forgot_password') }}</h1>
                    <p class="login-subtitle">
                        {{ __('website.forgot_password_desc') }}
                    </p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Form -->
                <form class="login-form" method="POST" action="{{ route('password.email') }}">
                    @csrf
                    
                    <!-- Email Field -->
                    <div class="form-group login-form-group">
                        <label for="email" class="form-label">{{ __('website.email') }}</label>
                        <input type="email" 
                               class="form-control login-input @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               placeholder="{{ __('website.email') }}" 
                               required 
                               autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Reset Button -->
                    <button type="submit" class="auth btn btn-icon py-3 px-5 mb-4">
                        <span>{{ __('website.send_password_reset_link') }}</span>
                        <i class="icon-btn bi bi-arrow-left"></i>
                    </button>
                    
                    <div class="login-link-section">
                        <p><a href="{{ route('login') }}">{{ __('website.login_btn') }}</a></p>
                    </div>

                </form>
            </div>
        </div>
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
