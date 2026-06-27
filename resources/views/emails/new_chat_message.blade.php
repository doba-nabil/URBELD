@extends('emails.layout')

@section('header_title', __('admin.email_new_message_title'))
@section('header_subtitle', config('app.name'))

@section('content')
    <h2>{{ __('admin.email_hello') }}</h2>
    <p>{!! __('admin.email_new_message_body', ['sender' => $sender->name ?? __('admin.email_client')]) !!}</p>

    <div style="background: #f9f9f9; padding: 20px; border-radius: 10px; border-right: 4px solid #014D40; margin: 20px 0; font-style: italic;">
        {{ \Illuminate\Support\Str::limit($messageText, 100) ?: __('admin.email_attachment_in_conversation') }}
    </div>

    @php
        $button_link = $chatUrl ?? '#';
        $button_text = __('admin.email_view_conversation');
    @endphp
@endsection

@section('extra_content')
    <p style="margin-top: 30px;">{{ __('admin.email_thanks') }}</p>
@endsection
