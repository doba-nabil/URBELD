@extends('emails.layout')

@section('header_title', $subject)
@section('header_subtitle', config('app.name'))

@section('content')
    <h2>{{ __('admin.email_greeting', ['name' => $user->name]) }}</h2>
    
    <p>
    @if($type === 'new_response')
    {!! __('admin.email_new_response', ['title' => $serviceRequest->title ?? $serviceRequest->category->name, 'provider' => $response->user->name]) !!}
    @elseif($type === 'accepted')
    {!! __('admin.email_response_accepted', ['title' => $serviceRequest->title ?? $serviceRequest->category->name]) !!}
    @elseif($type === 'rejected')
    {!! __('admin.email_response_rejected', ['title' => $serviceRequest->title ?? $serviceRequest->category->name]) !!}
    @endif
    </p>

    @php
        $button_link = $requestUrl;
        $button_text = __('admin.email_view_request_btn');
    @endphp
@endsection

@section('extra_content')
    <p style="margin-top: 30px;">{{ __('admin.email_thanks') }}</p>
@endsection
