<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <title>{{ __('website.reset_password') }} - {{ $settings['site_name'] ?? 'ارساء' }}</title>
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
                        <img src="{{ asset('website/assets/img/logo.png') }}" alt="ERSAA Logo" class="login-logo-img">
                    </a>
                </div>

                <!-- Heading -->
                <div class="login-heading">
                    <h1 class="login-title">{{ __('website.reset_password') }}</h1>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Form -->
                <form class="login-form" method="POST" action="{{ route('password.store') }}">
                    @csrf
                    
                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email Field -->
                    <div class="form-group login-form-group">
                        <label for="email" class="form-label">{{ __('website.email') }}</label>
                        <input type="email" 
                               class="form-control login-input @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $request->email) }}"
                               placeholder="{{ __('website.email') }}" 
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

                    <!-- Password Confirmation Field -->
                    <div class="form-group login-form-group">
                        <label for="password_confirmation" class="form-label">{{ __('website.confirm_password') }}</label>
                        <input type="password" 
                               class="form-control login-input @error('password_confirmation') is-invalid @enderror" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               placeholder="{{ __('website.confirm_password') }}" 
                               required>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Reset Button -->
                    <button type="submit" class="auth btn btn-icon py-3 px-5 mb-4">
                        <span>{{ __('website.reset_password') }}</span>
                        <i class="icon-btn bi bi-arrow-left"></i>
                    </button>

                </form>
            </div>
        </div>
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
