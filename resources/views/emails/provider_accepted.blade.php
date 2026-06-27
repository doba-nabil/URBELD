@extends('emails.layout')

@section('header_title', __('admin.email_provider_accepted_title'))
@section('header_subtitle', config('app.name'))

@section('content')
    <h2>{{ __('admin.email_hello') }}</h2>
    <p>{!! __('admin.email_provider_accepted_body', ['id' => $response->service_request_id]) !!}</p>

    @php
        $button_link = route('provider.requests.index');
        $button_text = __('admin.email_view_request_chat');
    @endphp
@endsection

@section('extra_content')
    <p style="margin-top: 30px;">{{ __('admin.email_best_wishes') }}</p>
@endsection
