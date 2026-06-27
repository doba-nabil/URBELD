@extends('website.layouts.master')

@section('content')
<div class="chat-page-container py-5">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar: Conversations List -->
            <div class="col-lg-3 col-md-4 border-end">
                <div class="chat-sidebar h-100 bg-white shadow-sm p-3 rounded">
                    <h5 class="mb-4">المحادثات</h5>
                    <div class="list-group list-group-flush">
                        @forelse($chats as $c)
                            @php
                                $otherUser = ($c->from_user_id == Auth::id()) ? $c->toUser : $c->fromUser;
                                $isActive = (isset($chat) && $chat->id == $c->id) ? 'active' : '';
                            @endphp
                            <a href="{{ route('dashboard.chat.show', $c->id) }}" class="list-group-item list-group-item-action d-flex align-items-center {{ $isActive }}">
                                <div class="position-relative me-3">
                                    <img src="{{ $otherUser->getFirstMediaUrl('users') ?: asset('website/assets/img/logo.png') }}" class="rounded-circle" width="50" height="50" alt="{{ $otherUser->name }}">
                                    @if($otherUser->is_online) 
                                        <span class="position-absolute bottom-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle"></span>
                                    @endif
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="mb-0 text-truncate">{{ $otherUser->name }}</h6>
                                        <small class="text-muted">{{ $c->lastMessage ? $c->lastMessage->created_at->diffForHumans() : '' }}</small>
                                    </div>
                                    <p class="mb-0 text-muted small text-truncate">
                                        {{ $c->lastMessage ? $c->lastMessage->message : 'ابدأ المحادثة الآن' }}
                                    </p>
                                </div>
                            </a>
                        @empty
                            <p class="text-muted text-center py-4">لا توجد محادثات حالياً</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Main Chat Area -->
            <div class="col-lg-9 col-md-8">
                @if(isset($chat))
                    @php
                        $chatPartner = ($chat->from_user_id == Auth::id()) ? $chat->toUser : $chat->fromUser;
                    @endphp
                    <div class="chat-main-area bg-white shadow-sm rounded h-100 d-flex flex-column">
                        <!-- Chat Header -->
                        <div class="chat-header p-3 border-bottom d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <img src="{{ $chatPartner->getFirstMediaUrl('users') ?: asset('website/assets/img/logo.png') }}" class="rounded-circle me-3" width="55" height="55" alt="{{ $chatPartner->name }}">
                                <div>
                                    <h5 class="mb-0">{{ $chatPartner->name }}</h5>
                                    <small class="text-muted">{{ $chatPartner->email }}</small>
                                </div>
                            </div>
                            <a href="{{ route('profile.requests') }}" class="btn btn-outline-secondary btn-sm">العودة للطلبات</a>
                        </div>

                        <!-- Chat Messages -->
                        <div class="chat-messages p-4 flex-grow-1 overflow-auto" id="chatMessages" style="height: 500px; background-color: #f8f9fa;">
                            @forelse($chat->messages as $message)
                                <div class="message-wrapper d-flex mb-3 {{ $message->sender_id == Auth::id() ? 'justify-content-end' : 'justify-content-start' }}">
                                    @if($message->sender_id != Auth::id())
                                        <img src="{{ $message->sender->getFirstMediaUrl('users') ?: asset('website/assets/img/logo.png') }}" class="rounded-circle align-self-end me-2" width="40" height="40">
                                    @endif
                                    
                                    <div class="message-content {{ $message->sender_id == Auth::id() ? 'bg-primary text-white' : 'bg-white text-dark border' }} p-3 rounded" style="max-width: 70%;">
                                        <p class="mb-1">{{ $message->message }}</p>
                                        <small class="{{ $message->sender_id == Auth::id() ? 'text-white-50' : 'text-muted' }}" style="font-size: 0.7em;">
                                            {{ $message->created_at->format('h:i A') }}
                                        </small>
                                    </div>
                                    
                                    @if($message->sender_id == Auth::id())
                                        <img src="{{ Auth::user()->getFirstMediaUrl('users') ?: asset('website/assets/img/logo.png') }}" class="rounded-circle align-self-end ms-2" width="40" height="40">
                                    @endif
                                </div>
                            @empty
                                <div class="text-center text-muted py-5">ابدأ المحادثة بإرسال رسالة ترحيبية!</div>
                            @endforelse
                        </div>

                        <!-- Chat Input -->
                        <div class="chat-input-wrapper p-3 border-top bg-white">
                            <form id="chatForm" action="{{ route('dashboard.chat.send', $chat->id) }}" method="POST" class="d-flex gap-2">
                                @csrf
                                <input type="text" name="message" class="form-control" id="messageInput" placeholder="اكتب رسالتك هنا..." autocomplete="off">
                                <button type="submit" class="btn btn-primary px-4" id="sendBtn">
                                    <i class="bi bi-send-fill"></i> إرسال
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="d-flex align-items-center justify-content-center h-100 bg-white shadow-sm rounded p-5 text-center">
                        <div>
                            <img src="{{ asset('website/assets/img/chat_placeholder.svg') }}" class="mb-4" width="200" alt="Select Chat"> <!-- Placeholder image -->
                            <h4>اختر محادثة للبدء</h4>
                            <p class="text-muted">تواصل مع مقدمي الخدمة أو العملاء بسهولة.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatMessages = document.getElementById('chatMessages');
        if(chatMessages) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        const chatForm = document.getElementById('chatForm');
        if(chatForm) {
            chatForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const input = document.getElementById('messageInput');
                const message = input.value.trim();
                const btn = document.getElementById('sendBtn');
                
                if(!message) return;

                btn.disabled = true;

                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message: message })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.status === 'success') {
                        // Append message
                        const html = `
                            <div class="message-wrapper d-flex mb-3 justify-content-end">
                                <div class="message-content bg-primary text-white p-3 rounded" style="max-width: 70%;">
                                    <p class="mb-1">${message}</p>
                                    <small class="text-white-50" style="font-size: 0.7em;">الآن</small>
                                </div>
                                <img src="${data.avatar}" class="rounded-circle align-self-end ms-2" width="40" height="40">
                            </div>
                        `;
                        chatMessages.insertAdjacentHTML('beforeend', html);
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                        input.value = '';
                    }
                })
                .catch(error => console.error('Error:', error))
                .finally(() => {
                    btn.disabled = false;
                    input.focus();
                });
            });
        }
    });
</script>
@endpush
@endsection
