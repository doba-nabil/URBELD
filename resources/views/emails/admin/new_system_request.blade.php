@extends('emails.layout')

@section('header_title', __('admin.email_admin_new_request_title'))
@section('header_subtitle', config('app.name'))

@section('content')
    <h2>{{ __('admin.email_hello') }}</h2>
    <p>{!! __('admin.email_admin_new_request_body', ['user' => $serviceRequest->user->name ?? __('admin.email_client')]) !!}</p>

    @php
        $button_link = url('/dashboard');
        $button_text = __('admin.email_go_dashboard');
    @endphp
@endsection

@section('extra_content')
    <div style="background: #f9f9f9; padding: 20px; border-radius: 10px; margin-top: 20px; text-align: right;">
        <strong style="color: #014D40;">{{ __('admin.email_admin_request_details') }}</strong>
        <p style="margin: 5px 0; font-size: 14px;"><strong>{{ __('admin.email_title_label') }}</strong> {{ $serviceRequest->title ?? __('admin.email_new_request') }}</p>
        <p style="margin: 5px 0; font-size: 14px;"><strong>{{ __('admin.email_publish_date') }}</strong> {{ $serviceRequest->created_at->format('Y-m-d H:i') }}</p>
    </div>
    
    <p style="margin-top: 30px;">{{ __('admin.email_thanks') }}</p>
@endsection
