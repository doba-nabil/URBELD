@extends('emails.layout')

@section('header_title', __('admin.email_payment_title'))
@section('header_subtitle', config('app.name'))

@section('content')
    <h2>{{ __('admin.email_greeting', ['name' => $user->name]) }}</h2>
    <p>{{ __('admin.email_payment_registered') }}</p>

    <div style="background: #f9f9f9; padding: 25px; border-radius: 15px; margin: 25px 0; border: 1px solid #eee;">
        <p style="margin: 10px 0;"><strong>{{ __('admin.email_service_name') }}</strong> {{ $serviceName }}</p>
        @if($price)
        <p style="margin: 10px 0;"><strong>{{ __('admin.email_price') }}</strong> {{ number_format($price, 2) }} {{ __('admin.currency') }}</p>
        @endif
        <div style="margin-top: 15px;">
            <span style="padding: 8px 20px; border-radius: 20px; font-weight: 700; font-size: 14px; 
                {{ $status === 'pending' ? 'background: #fff3cd; color: #856404;' : 'background: #d4edda; color: #155724;' }}">
                {{ $statusText }}
            </span>
        </div>
    </div>

    @if($status === 'pending')
    <p>{{ __('admin.email_payment_pending_note') }}</p>
    @else
    <p>{{ __('admin.email_payment_approved_note') }}</p>
    @endif
@endsection
