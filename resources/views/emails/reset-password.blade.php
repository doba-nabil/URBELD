<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعادة تعيين كلمة المرور</title>
    <style>
        body {
            font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333333;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            overflow: hidden;
            border: 1px solid #eeeeee;
        }
        .header {
            background-color: #014D40;
            padding: 30px 20px;
            text-align: center;
        }
        .header img {
            max-height: 60px;
        }
        .content {
            padding: 40px 30px;
            text-align: right;
            direction: rtl;
        }
        h1 {
            color: #014D40;
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
            color: #555555;
        }
        .btn-container {
            text-align: center;
            margin: 40px 0;
        }
        .btn {
            background-color: #F8B133;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 35px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 18px;
            display: inline-block;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #888888;
            border-top: 1px solid #eeeeee;
        }
        .footer-link {
            word-break: break-all;
            color: #014D40;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="Logo">
            @else
                <h2 style="color: #ffffff; margin: 0;">ارساء</h2>
            @endif
        </div>
        
        <div class="content">
            <h1>إعادة تعيين كلمة المرور</h1>
            
            <p>مرحباً <strong>{{ $user->name }}</strong>،</p>
            
            <p>لقد تلقينا طلباً لإعادة تعيين كلمة المرور الخاصة بحسابك. يمكنك القيام بذلك من خلال الضغط على الزر أدناه:</p>
            
            <div class="btn-container">
                <a href="{{ $url }}" class="btn">إعادة تعيين كلمة المرور</a>
            </div>
            
            <p>هذا الرابط سيكون صالحاً لمدة {{ $count }} دقيقة.</p>
            
            <p>إذا لم تكن أنت من طلب إعادة تعيين كلمة المرور، فلا داعي لاتخاذ أي إجراء ويمكنك تجاهل هذا البريد بأمان.</p>
            
            <hr style="border: 0; border-top: 1px solid #eee; margin: 30px 0;">
            
            <p style="font-size: 13px; color: #777;">إذا كنت تواجه مشكلة في النقر على زر "إعادة تعيين كلمة المرور"، انسخ والصق الرابط أدناه في متصفحك:</p>
            <p class="footer-link">{{ $url }}</p>
        </div>
        
        <div class="footer">
            <p style="margin: 0;">&copy; {{ date('Y') }} جميع الحقوق محفوظة.</p>
        </div>
    </div>
</body>
</html>
