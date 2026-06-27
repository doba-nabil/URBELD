<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
        }
        .email-wrapper {
            width: 100%;
            background-color: #f4f7f6;
            padding: 40px 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .email-header {
            background: linear-gradient(135deg, #014D40 0%, #01332a 100%);
            padding: 60px 40px;
            text-align: center;
            color: #ffffff;
        }
        .email-header h1 {
            font-size: 28px;
            margin: 0 0 15px 0;
            font-weight: 700;
            line-height: 1.3;
        }
        .email-header p {
            font-size: 16px;
            margin: 0;
            opacity: 0.9;
            line-height: 1.6;
        }
        .email-body {
            padding: 50px 40px;
            color: #333333;
            text-align: center;
        }
        .email-body h2 {
            font-size: 22px;
            color: #014D40;
            margin: 0 0 20px 0;
            font-weight: 700;
        }
        .email-body p {
            font-size: 16px;
            line-height: 1.8;
            margin: 0 0 25px 0;
            color: #555555;
        }
        .button-container {
            margin: 35px 0;
        }
        .primary-button {
            display: inline-block;
            padding: 16px 45px;
            background-color: #f1c40f;
            background: linear-gradient(135deg, #f1c40f 0%, #e1b400 100%);
            color: #014D40 !important;
            text-decoration: none;
            border-radius: 12px;
            font-size: 17px;
            font-weight: 700;
            box-shadow: 0 5px 15px rgba(241, 196, 15, 0.3);
            transition: all 0.3s ease;
        }
        .email-footer {
            background-color: #ffffff;
            padding: 0 40px 40px 40px;
            text-align: center;
        }
        .footer-divider {
            border-top: 1px solid #eeeeee;
            margin-bottom: 30px;
        }
        .footer-logo {
            font-weight: 700;
            color: #014D40;
            font-size: 18px;
            margin-bottom: 5px;
            display: block;
        }
        .footer-text {
            font-size: 13px;
            color: #999999;
            margin: 0;
        }
        /* RTL Adjustments */
        [dir="rtl"] .email-header h1,
        [dir="rtl"] .email-header p,
        [dir="rtl"] .email-body h2,
        [dir="rtl"] .email-body p {
            font-family: 'Cairo', 'Tajawal', sans-serif;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <div class="email-header">
                <h1>@yield('header_title')</h1>
                <p>@yield('header_subtitle')</p>
            </div>
            
            <div class="email-body">
                @yield('content')
                
                @if(isset($button_link) && isset($button_text))
                <div class="button-container">
                    <a href="{{ $button_link }}" class="primary-button">{{ $button_text }}</a>
                </div>
                @endif
                
                @yield('extra_content')
            </div>
            
            <div class="email-footer">
                <div class="footer-divider"></div>
                <span class="footer-logo">{{ config('app.name') }}</span>
                <p class="footer-text">© {{ date('Y') }} {{ config('app.name') }}. {{ __('website.footer_copyright_text') ?? 'All rights reserved.' }}</p>
            </div>
        </div>
    </div>
</body>
</html>
