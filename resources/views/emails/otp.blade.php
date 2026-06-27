@extends('emails.layout')

@section('header_title', 'رمز التحقق - أوربلد')
@section('header_subtitle', config('app.name'))

@section('content')
    <h2>{{ __('admin.email_greeting', ['name' => $user->name ?? $user->email]) }}</h2>
    <p>رمز التحقق الخاص بك هو:</p>
    <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; border: 1px dashed #014D40; margin: 30px 0; font-size: 24px; font-weight: bold; color: #014D40;">
        {{ $otpCode }}
    </div>
    <p>هذا الرمز صالح لمدة 5 دقائق.</p>
    <p>إذا لم تطلب هذا الرمز، يرجى تجاهل هذا البريد الإلكتروني.</p>
@endsection

@section('extra_content')
    <p style="margin-top: 30px;">مع تحيات فريق أوربلد</p>
@endsection
