<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <title>{{ __('website.register_title') }} - {{ $settings['site_name'] ?? 'اوربلد' }}</title>
<meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="إنشاء حساب، تسجيل، اوربلد" name="keywords">
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
    <div class="login-container">
        <!-- Left Side - Background Image -->
        <div class="login-left-section">
            <div class="login-background-overlay">
                <img src="{{ asset('website/assets/img/auth-img.png') }}" alt="Background" class="login-background-image">
                <div class="login-text-overlay">URBELD</div>
            </div>
        </div>

        <!-- Right Side - Register Form -->
        <div class="login-right-section">
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
                    <h1 class="login-title">{{ __('website.register_title') }}</h1>
                </div>

                <!-- Register Form -->
                <form class="login-form mt-4" method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf
                    
                    <!-- Account Type Selection -->
                    <div class="account-type-selection mb-4">
                        <label class="form-label text-center d-block mb-3 fw-bold">{{ __('website.choose_account_type') }}</label>
                        <div class="account-type-buttons d-flex gap-2">
                            <button type="button" class="account-type-btn flex-fill {{ old('account_type', 'seeker') == 'seeker' ? 'active' : '' }}" data-type="seeker">
                                {{ __('website.account_types.seeker') }}
                            </button>
                            <button type="button" class="account-type-btn flex-fill {{ old('account_type') == 'company' ? 'active' : '' }}" data-type="company">
                                {{ __('website.account_types.company') }}
                            </button>
                            <button type="button" class="account-type-btn flex-fill {{ old('account_type') == 'supplier' ? 'active' : '' }}" data-type="supplier">
                                {{ __('website.account_types.supplier') }}
                            </button>
                            <button type="button" class="account-type-btn flex-fill {{ old('account_type') == 'individual' ? 'active' : '' }}" data-type="individual">
                                {{ __('website.account_types.individual') }}
                            </button>
                        </div>
                        <input type="hidden" name="account_type" id="account_type" value="{{ old('account_type', 'seeker') }}">
                        @error('account_type')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Full Name Field -->
                    <div class="form-group login-form-group">
                        <label for="fullName" class="form-label">{{ __('website.full_name') }}</label>
                        <input type="text" 
                               class="form-control login-input @error('name') is-invalid @enderror" 
                               id="fullName" 
                               name="name" 
                               value="{{ old('name') }}"
                               placeholder="{{ __('website.full_name') }}" 
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="form-group login-form-group">
                        <label for="email" class="form-label">{{ __('website.email') }}</label>
                        <input type="email" 
                               class="form-control login-input @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               placeholder="{{ __('website.email') }}" 
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Phone Field -->
                    <div class="form-group login-form-group">
                        <label for="phone" class="form-label">{{ __('website.phone') }}</label>
                        <input type="tel" 
                               class="form-control login-input @error('phone') is-invalid @enderror" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}"
                               placeholder="{{ __('website.phone') }}" 
                               required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- ID Number Field (Hidden for Seekers initially) -->
                    <div class="form-group login-form-group" id="idNumberGroup" style="display: none;">
                        <label for="idNumber" class="form-label" id="idNumberLabel">{{ __('website.id_or_iqama') }}</label>
                        <input type="text" 
                               class="form-control login-input @error('id_number') is-invalid @enderror" 
                               id="idNumber" 
                               name="id_number" 
                               value="{{ old('id_number') }}"
                               placeholder="{{ __('website.id_placeholder') }}">
                        @error('id_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Representative Name Field (Hidden for Seekers/Individuals initially) -->
                    <div class="form-group login-form-group" id="representativeNameGroup" style="display: none;">
                        <label for="representativeName" class="form-label">{{ __('website.representative_name') }}</label>
                        <input type="text" 
                               class="form-control login-input @error('representative_name') is-invalid @enderror" 
                               id="representativeName" 
                               name="representative_name" 
                               value="{{ old('representative_name') }}"
                               placeholder="{{ __('website.representative_name') }}">
                        @error('representative_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Classification Field -->
                    <div class="form-group login-form-group" id="classificationGroup" style="display: none;">
                        <label for="classification_id" class="form-label" id="classificationLabel">{{ __('website.classification') }}</label>
                        <select class="form-control login-input @error('classification_id') is-invalid @enderror" id="classification_id" name="classification_id">
                            <option value="">{{ __('website.choose') }}</option>
                            <!-- Options will be populated via JS -->
                        </select>
                        @error('classification_id')
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



                    <!-- Terms and Conditions -->
                    <div class="terms-checkbox">
                        <label class="custom-checkbox">
                            <input type="checkbox" id="agreeTerms" name="agreeTerms" required>
                            <span class="checkmark"></span>
                            <span class="checkbox-text">{!! __('website.agree_terms_checkbox') !!}</span>
                        </label>
                    </div>

                    <!-- Sign Up Button -->
                    <button type="submit" class="auth btn btn-icon py-3 px-5" id="signUpBtn">
                        <span>{{ __('website.register_btn') }}</span>
                        <i class="icon-btn bi bi-arrow-left"></i>
                    </button>
                </form>

                <!-- Login Link -->
                <div class="login-link-section">
                    <p>{{ __('website.have_account') }} <a href="{{ route('login') }}">{{ __('website.login_link') }}</a></p>
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

        document.addEventListener('DOMContentLoaded', function() {
            const accountTypeButtons = document.querySelectorAll('.account-type-btn');
            const accountTypeInput = document.getElementById('account_type');
            const idNumberGroup = document.getElementById('idNumberGroup');
            const idNumberInput = document.getElementById('idNumber');
            const representativeNameGroup = document.getElementById('representativeNameGroup');
            const representativeNameInput = document.getElementById('representativeName');
            const classificationGroup = document.getElementById('classificationGroup');
            const classificationSelect = document.getElementById('classification_id');
            const classificationLabel = document.getElementById('classificationLabel');

            const companyClassifications = @json($companyClassifications ?? []);
            const supplierVolumes = @json($supplierVolumes ?? []);

            function populateClassifications(items) {
                classificationSelect.innerHTML = '<option value="">{{ __('website.choose') }}</option>';
                items.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.name;
                    if (item.id == '{{ old('classification_id') }}') {
                        option.selected = true;
                    }
                    classificationSelect.appendChild(option);
                });
            }

            function toggleFields(type) {
                const idLabel = document.getElementById('idNumberLabel');
                if (type === 'company') {
                    idNumberGroup.style.display = 'block';
                    if (idLabel) idLabel.innerText = '{{ __('website.commercial_record') }}';
                    idNumberInput.placeholder = '{{ __('website.commercial_record') }}';
                    representativeNameGroup.style.display = 'block';
                    classificationGroup.style.display = 'block';
                    classificationLabel.innerText = '{{ __('website.company_classification') }}';
                    populateClassifications(companyClassifications);
                } else if (type === 'supplier') {
                    idNumberGroup.style.display = 'block';
                    if (idLabel) idLabel.innerText = '{{ __('website.commercial_record') }}';
                    idNumberInput.placeholder = '{{ __('website.commercial_record') }}';
                    representativeNameGroup.style.display = 'block';
                    classificationGroup.style.display = 'block';
                    classificationLabel.innerText = '{{ __('website.supplier_volume') }}';
                    populateClassifications(supplierVolumes);
                } else if (type === 'individual') {
                    idNumberGroup.style.display = 'block';
                    if (idLabel) idLabel.innerText = '{{ __('website.id_or_iqama') }}';
                    idNumberInput.placeholder = '{{ __('website.id_or_iqama') }}';
                    representativeNameGroup.style.display = 'none';
                    representativeNameInput.value = ''; // clear value
                    classificationGroup.style.display = 'none';
                    classificationSelect.value = '';
                } else {
                    idNumberGroup.style.display = 'none';
                    idNumberInput.value = ''; // clear value
                    representativeNameGroup.style.display = 'none';
                    representativeNameInput.value = ''; // clear value
                    classificationGroup.style.display = 'none';
                    classificationSelect.value = '';
                }
            }

            // Initial toggle based on default/old value
            toggleFields(accountTypeInput.value);

            // Handle Account Type Selection
            accountTypeButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    accountTypeButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    const selectedType = this.getAttribute('data-type');
                    accountTypeInput.value = selectedType;
                    toggleFields(selectedType);
                });
            });
        });
    </script>
</body>

</html>
