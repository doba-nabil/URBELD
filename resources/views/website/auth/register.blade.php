<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <title>{{ __('website.register_title') ?? 'تسجيل حساب جديد' }} - {{ $settings['site_name'] ?? 'ارساء' }}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <!-- Favicon -->
    <link href="{{ \App\Models\Setting::getMediaUrl('favicon') ?: asset('website/assets/img/fav.png') }}" rel="icon">

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
        <div class="card shadow-lg border-0 rounded-4 w-100" style="max-width: 650px;">
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
                    <h1 class="login-title">{{ __('website.register_title') ?? 'تسجيل حساب جديد' }}</h1>
                    <p class="login-subtitle">{{ __('website.create_account') ?? 'انشئ حسابك الآن' }}</p>
                </div>

                <!-- Session Status -->
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Register Form -->
                <form class="login-form" method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf
                    
                    <div class="row">
                        <!-- Account Type -->
                        <div class="col-md-12 mb-3 form-group login-form-group">
                            <label for="account_type" class="form-label">{{ __('website.account_type') ?? 'نوع الحساب' }}</label>
                            <select name="account_type" id="account_type" class="form-control login-input @error('account_type') is-invalid @enderror" required onchange="toggleFields()">
                                <option value="seeker" {{ old('account_type') == 'seeker' ? 'selected' : '' }}>{{ __('website.seeker') ?? 'طالب خدمة' }}</option>
                                <option value="individual" {{ old('account_type') == 'individual' ? 'selected' : '' }}>{{ __('website.individual') ?? 'مقدم خدمة (فرد)' }}</option>
                                <option value="company" {{ old('account_type') == 'company' ? 'selected' : '' }}>{{ __('website.company') ?? 'مقدم خدمة (شركة)' }}</option>
                                <option value="supplier" {{ old('account_type') == 'supplier' ? 'selected' : '' }}>{{ __('website.supplier') ?? 'مورد' }}</option>
                            </select>
                        </div>
                        
                        <!-- Name -->
                        <div class="col-md-6 mb-3 form-group login-form-group">
                            <label for="name" class="form-label">{{ __('website.name') ?? 'الاسم' }}</label>
                            <input type="text" class="form-control login-input @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="{{ __('website.name') ?? 'الاسم' }}" required>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-3 form-group login-form-group">
                            <label for="email" class="form-label">{{ __('website.email') ?? 'البريد الإلكتروني' }}</label>
                            <input type="email" class="form-control login-input @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="{{ __('website.email') ?? 'البريد الإلكتروني' }}" required>
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6 mb-3 form-group login-form-group">
                            <label for="phone" class="form-label">{{ __('website.phone') ?? 'رقم الجوال' }}</label>
                            <input type="text" class="form-control login-input @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="{{ __('website.phone') ?? 'رقم الجوال' }}" required>
                        </div>
                        
                        <!-- ID Number -->
                        <div class="col-md-6 mb-3 form-group login-form-group">
                            <label for="id_number" class="form-label">{{ __('website.id_number') ?? 'رقم الهوية / السجل التجاري' }}</label>
                            <input type="text" class="form-control login-input @error('id_number') is-invalid @enderror" id="id_number" name="id_number" value="{{ old('id_number') }}" placeholder="{{ __('website.id_number') ?? 'رقم الهوية / السجل التجاري' }}">
                        </div>

                        <!-- Representative Name (For Company) -->
                        <div class="col-md-6 mb-3 form-group login-form-group company-field" style="display: none;">
                            <label for="representative_name" class="form-label">{{ __('website.representative_name') ?? 'اسم الممثل' }}</label>
                            <input type="text" class="form-control login-input @error('representative_name') is-invalid @enderror" id="representative_name" name="representative_name" value="{{ old('representative_name') }}" placeholder="{{ __('website.representative_name') ?? 'اسم الممثل' }}">
                        </div>
                        
                        <!-- Classification (For Company / Supplier) -->
                        <div class="col-md-6 mb-3 form-group login-form-group classification-field" style="display: none;">
                            <label for="classification_id" class="form-label">{{ __('website.classification') ?? 'التصنيف' }}</label>
                            <select name="classification_id" id="classification_id" class="form-control login-input @error('classification_id') is-invalid @enderror">
                                <option value="">{{ __('website.select_classification') ?? 'اختر التصنيف' }}</option>
                                <optgroup label="Company" class="company-opt" style="display:none;">
                                    @foreach($companyClassifications as $class)
                                        <option value="{{ $class->id }}" {{ old('classification_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Supplier" class="supplier-opt" style="display:none;">
                                    @foreach($supplierVolumes as $vol)
                                        <option value="{{ $vol->id }}" {{ old('classification_id') == $vol->id ? 'selected' : '' }}>{{ $vol->name }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>

                        <!-- Password -->
                        <div class="col-md-6 mb-3 form-group login-form-group">
                            <label for="password" class="form-label">{{ __('website.password') ?? 'كلمة المرور' }}</label>
                            <input type="password" class="form-control login-input @error('password') is-invalid @enderror" id="password" name="password" placeholder="{{ __('website.password') ?? 'كلمة المرور' }}" required>
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-md-6 mb-3 form-group login-form-group">
                            <label for="password_confirmation" class="form-label">{{ __('website.password_confirmation') ?? 'تأكيد كلمة المرور' }}</label>
                            <input type="password" class="form-control login-input" id="password_confirmation" name="password_confirmation" placeholder="{{ __('website.password_confirmation') ?? 'تأكيد كلمة المرور' }}" required>
                        </div>
                    </div>

                    <!-- Sign Up Button -->
                    <button type="submit" class="auth btn btn-icon py-3 px-5 w-100 mt-3" id="signUpBtn">
                        <span>{{ __('website.register_btn') ?? 'إنشاء حساب' }}</span>
                        <i class="icon-btn bi bi-arrow-left"></i>
                    </button>
                </form>

                <!-- Login Link -->
                <div class="login-link-section mt-4 text-center">
                    <p>{{ __('website.already_have_account') ?? 'لديك حساب بالفعل؟' }} <a href="{{ route('login') }}">{{ __('website.login') ?? 'تسجيل الدخول' }}</a></p>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function toggleFields() {
            var type = document.getElementById('account_type').value;
            var companyFields = document.querySelectorAll('.company-field');
            var classificationFields = document.querySelectorAll('.classification-field');
            var compOpt = document.querySelectorAll('.company-opt');
            var suppOpt = document.querySelectorAll('.supplier-opt');
            
            // hide all first
            companyFields.forEach(el => el.style.display = 'none');
            classificationFields.forEach(el => el.style.display = 'none');
            compOpt.forEach(el => el.style.display = 'none');
            suppOpt.forEach(el => el.style.display = 'none');
            
            if (type === 'company') {
                companyFields.forEach(el => el.style.display = 'block');
                classificationFields.forEach(el => el.style.display = 'block');
                compOpt.forEach(el => el.style.display = 'block');
            } else if (type === 'supplier') {
                classificationFields.forEach(el => el.style.display = 'block');
                suppOpt.forEach(el => el.style.display = 'block');
            }
        }
        
        // run on load
        window.addEventListener('load', function() {
            toggleFields();
        });
    </script>
</body>

</html>
