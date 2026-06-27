@extends('emails.layout')

@section('header_title', __('admin.email_inspection_title'))
@section('header_subtitle', config('app.name'))

@section('content')
    <h2>{{ __('admin.email_hello') }}</h2>
    <p>{!! __('admin.email_inspection_body', ['id' => $serviceRequest->id]) !!}</p>

    @php
        $button_link = route('requests.show', $serviceRequest->id);
        $button_text = __('admin.email_view_request_details');
    @endphp
@endsection

@section('extra_content')
    <div style="background: #f9f9f9; padding: 15px; border-radius: 10px; margin-top: 10px;">
        <p style="margin: 0;"><strong>{{ __('admin.email_inspection_date') }}</strong> {{ \Carbon\Carbon::parse($serviceRequest->inspection_date)->format('Y-m-d H:i') }}</p>
    </div>
    
    <p style="margin-top: 30px;">{{ __('admin.email_thanks') }}</p>
@endsection
