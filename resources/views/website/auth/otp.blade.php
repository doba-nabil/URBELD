<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <title>التحقق من الرمز - {{ $settings['site_name'] ?? 'ارساء' }}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="التحقق، رمز التحقق، OTP، ارساء" name="keywords">
    <meta content="ارساء - نبدع في تصميم المساحات التي تُلهم وتُبتكر وتدوم" name="description">

    <!-- Favicon -->
    <link href="{{ asset('website/assets/img/fav.png') }}" rel="icon">

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
                <div class="login-text-overlay">{{ \App\Models\Setting::getValue('site_name', app()->getLocale(), config('app.name')) }}</div>
            </div>
        </div>

        <!-- Right Side - OTP Form -->
        <div class="login-right-section">
            <div class="login-form-wrapper">
                <!-- Logo -->
                <div class="login-logo">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('website/assets/img/logo.png') }}" alt="ERSAA Logo" class="login-logo-img">
                    </a>
                </div>

                <!-- Heading -->
                <div class="login-heading">
                    <h1 class="login-title">التحقق من الرمز</h1>
                    <p class="login-subtitle">أدخل رمز التحقق الذي أرسلناه إلى</p>
                    <p class="login-email-display">{{ session('otp_email', 'example@email.com') }}</p>
                </div>

                <!-- Success Message -->
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- OTP Form -->
                <form class="login-form" method="POST" action="{{ route('otp.verify') }}" id="otpForm">
                    @csrf
                    
                    <!-- OTP Input Fields -->
                    <div class="otp-container" dir="ltr">
                        <div class="otp-input-group">
                            <input type="text" class="otp-input" id="otp1" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                            <input type="text" class="otp-input" id="otp2" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                            <input type="text" class="otp-input" id="otp3" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                            <input type="text" class="otp-input" id="otp4" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                            <input type="text" class="otp-input" id="otp5" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                            <input type="text" class="otp-input" id="otp6" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                        </div>
                        <input type="hidden" name="otp" id="otpValue">
                    </div>

                    <!-- Error Display -->
                    @error('otp')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror

                    <!-- Resend Code -->
                    <div class="otp-resend-section">
                        <p class="otp-resend-text">
                            لم تستلم الرمز؟ 
                            <span id="resendTimer" class="otp-timer">إعادة الإرسال (<span id="timerSeconds">60</span>)</span>
                            <a href="#" class="otp-resend-link" id="resendLink" style="display: none;">إعادة الإرسال</a>
                        </p>
                    </div>

                    <!-- Verify Button -->
                    <button type="submit" class="auth btn btn-icon py-3 px-5" id="verifyBtn">
                        <span>التحقق</span>
                        <i class="icon-btn bi bi-arrow-left"></i>
                    </button>
                </form>

                <!-- Back Link -->
                <div class="login-link-section">
                    <p><a href="{{ route('login') }}"><i class="bi bi-arrow-right"></i> العودة لتسجيل الدخول</a></p>
                </div>

                <!-- Legal Text -->
                <div class="login-legal-text">
                    <p>بالمتابعة، أنت توافق على <a href="#">شروط الاستخدام</a> و <a href="#">سياسة الخصوصية</a> الخاصة بارساء</p>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            const otpInputs = document.querySelectorAll('.otp-input');
            const verifyBtn = document.getElementById('verifyBtn');
            const resendTimer = document.getElementById('resendTimer');
            const resendLink = document.getElementById('resendLink');
            const timerSeconds = document.getElementById('timerSeconds');
            const otpValue = document.getElementById('otpValue');
            
            let timerInterval;
            let secondsLeft = 60;

            // Focus first input on load
            otpInputs[0].focus();

            // Handle OTP input
            otpInputs.forEach((input, index) => {
                input.addEventListener('input', function(e) {
                    // Only allow numbers
                    this.value = this.value.replace(/[^0-9]/g, '');
                    
                    // Auto-focus next input
                    if (this.value && index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                    
                    checkOTPComplete();
                });

                input.addEventListener('keydown', function(e) {
                    // Handle backspace
                    if (e.key === 'Backspace' && !this.value && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                });

                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const paste = (e.clipboardData || window.clipboardData).getData('text');
                    const numbers = paste.replace(/[^0-9]/g, '').slice(0, 6);
                    
                    numbers.split('').forEach((char, i) => {
                        if (otpInputs[i]) {
                            otpInputs[i].value = char;
                        }
                    });
                    
                    // Focus last filled input or next empty
                    const nextEmptyIndex = Math.min(numbers.length, otpInputs.length - 1);
                    otpInputs[nextEmptyIndex].focus();
                    
                    checkOTPComplete();
                });
            });

            function checkOTPComplete() {
                const allFilled = Array.from(otpInputs).every(input => input.value.length === 1);
                verifyBtn.disabled = !allFilled;
                
                if (allFilled) {
                    verifyBtn.style.opacity = '1';
                    verifyBtn.style.cursor = 'pointer';
                    // Set the hidden input value
                    otpValue.value = Array.from(otpInputs).map(input => input.value).join('');
                } else {
                    verifyBtn.style.opacity = '0.6';
                    verifyBtn.style.cursor = 'not-allowed';
                    otpValue.value = '';
                }
            }

            // Timer countdown
            function startTimer() {
                secondsLeft = 60;
                timerSeconds.textContent = secondsLeft;
                resendTimer.style.display = 'inline';
                resendLink.style.display = 'none';
                verifyBtn.disabled = false;

                timerInterval = setInterval(function() {
                    secondsLeft--;
                    timerSeconds.textContent = secondsLeft;

                    if (secondsLeft <= 0) {
                        clearInterval(timerInterval);
                        resendTimer.style.display = 'none';
                        resendLink.style.display = 'inline';
                    }
                }, 1000);
            }

            // Start timer on load
            startTimer();

            // Handle resend link
            resendLink.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Send resend request
                fetch('{{ route("otp.resend") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    startTimer();
                    Swal.fire({
                        title: 'تم الإرسال',
                        text: 'تم إعادة إرسال الرمز بنجاح',
                        icon: 'success',
                        confirmButtonText: 'حسناً',
                        confirmButtonColor: '#014D40'
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });

            // Initial check
            checkOTPComplete();
        });
    </script>
    </div>
</body>

</html>
