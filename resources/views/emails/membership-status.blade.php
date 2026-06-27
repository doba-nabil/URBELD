@extends('emails.layout')

@section('header_title', $header_title)
@section('header_subtitle', $header_subtitle)

@section('content')
    <h2>{{ __('admin.email_hello', ['name' => $user->name]) }}</h2>
    <p>{{ $message_text }}</p>
@endsection

@section('extra_content')
    <div style="text-align: right; background: #f9f9f9; padding: 20px; border-radius: 10px; margin-top: 20px;">
        <strong style="color: #014D40; display: block; margin-bottom: 10px;">{{ __('admin.membership_details') ?? 'تفاصيل العضوية' }}</strong>
        <p style="margin: 5px 0; font-size: 14px;"><strong>حالة العضوية:</strong> {{ $statusText }}</p>
        @if($notes)
            <p style="margin: 5px 0; font-size: 14px; margin-top: 10px;">
                <strong>{{ __('admin.admin_notes') ?? 'ملاحظات الإدارة' }}:</strong><br>
                {{ $notes }}
            </p>
        @endif
    </div>
    
    <p style="margin-top: 30px;">{{ __('admin.email_thanks') ?? 'شكراً لتواجدكم معنا.' }}</p>
@endsection
