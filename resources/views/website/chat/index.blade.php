@extends('website.layouts.master')

@section('title', __('website.messages') ?? 'الرسائل')

@section('content')
    <!-- Header Start -->
    <div class="services-header-section without-search">
        <div class="container p-md-5 p-4 mb-md-5">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <h1 class="display-5 text-white fw-bold mb-3">{{ __('website.messages') ?? 'الرسائل' }}</h1>
                    <p class="lead text-white-50 mb-0">تابع كافة المحادثات الخاصة بطلباتك وعروضك هنا</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Chats Section Start -->
    <div class="chat-page-container py-5" style="min-height: 70vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-white border-bottom p-4">
                            <h4 class="mb-0 fw-bold"><i class="bi bi-chat-dots me-2 text-primary"></i> المحادثات السابقة</h4>
                        </div>
                        <div class="card-body p-0">
                            @if($chats->count() > 0)
                                <div class="list-group list-group-flush rounded-bottom-4">
                                    @foreach($chats as $chat)
                                        @php
                                            $otherUser = $chat->participants->first();
                                            $lastMessage = $chat->messages->first();
                                            $isUnread = $lastMessage && $lastMessage->sender_id !== auth()->id() && is_null($lastMessage->read_at);
                                        @endphp
                                        <a href="{{ route('dashboard.chat.show', $chat->id) }}" class="list-group-item list-group-item-action p-4 {{ $isUnread ? 'bg-light' : '' }}">
                                            <div class="d-flex w-100 justify-content-between align-items-center">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="position-relative">
                                                        <img src="{{ $otherUser ? ($otherUser->getFirstMediaUrl('personal_photo') ?: $otherUser->getFirstMediaUrl('users') ?: asset('website/assets/img/logo.png')) : asset('website/assets/img/logo.png') }}" alt="Avatar" class="rounded-circle object-fit-cover" width="50" height="50">
                                                        @if($isUnread)
                                                            <span class="position-absolute bottom-0 start-0 p-1 bg-danger border border-light rounded-circle">
                                                                <span class="visually-hidden">New alerts</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1 fw-bold {{ $isUnread ? 'text-dark' : 'text-secondary' }}">{{ $otherUser->name ?? 'مستخدم' }}</h6>
                                                        <small class="text-muted d-block text-truncate" style="max-width: 200px;">
                                                            @if($lastMessage)
                                                                {{ $lastMessage->sender_id === auth()->id() ? 'أنت: ' : '' }}
                                                                {{ $lastMessage->message ?: 'مرفق' }}
                                                            @else
                                                                لا توجد رسائل بعد
                                                            @endif
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <small class="text-muted d-block mb-1">{{ $chat->updated_at->diffForHumans() }}</small>
                                                    @if($chat->serviceRequest)
                                                        <span class="badge bg-primary-subtle text-primary rounded-pill">طلب #{{ $chat->serviceRequest->id }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-5 text-center">
                                    <i class="bi bi-chat-slash display-1 text-muted opacity-50 mb-3 d-block"></i>
                                    <h5 class="text-muted">لا توجد محادثات حتى الآن</h5>
                                    <p class="text-secondary small mb-0">ستظهر هنا أي رسائل تتعلق بطلباتك</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Chats Section End -->
@endsection
