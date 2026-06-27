<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('admin.error_503_title') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('website/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('website/assets/css/style.css') }}" rel="stylesheet">
    <style>
        body {
            background-color: #f6f3ec;
            font-family: 'Cairo', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .error-container {
            text-align: center;
            padding: 50px 40px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            max-width: 500px;
            width: 90%;
            border-top: 5px solid #014D40;
        }
        .error-code {
            font-size: 100px;
            font-weight: 800;
            color: #014D40;
            line-height: 1;
            margin-bottom: 15px;
        }
        .error-message {
            font-size: 24px;
            color: #333;
            margin-bottom: 30px;
            font-weight: 600;
        }
        .error-desc {
            color: #777;
            margin-bottom: 40px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">503</div>
        <div class="error-message">{{ __('admin.error_503_message') }}</div>
        <p class="error-desc">{{ __('admin.error_503_desc') }}</p>
        <a href="{{ url('/') }}" class="btn btn-icon py-3 px-5 animated fadeIn" style="background-color: #014D40; color: #fff; width: auto; display: inline-flex;">
            <span>{{ __('admin.error_back_home') }}</span>
            <i class="icon-btn bi bi-house-door text-white" style="background-color: rgba(255,255,255,0.2);"></i>
        </a>
    </div>
</body>
</html>
