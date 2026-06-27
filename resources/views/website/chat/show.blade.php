@extends('website.layouts.master')

@section('title', __('website.chat_title'))

@section('content')
    <!-- Header Start -->
    <div class="services-header-section without-search">
        <div class="container p-md-5 p-4 mb-md-5">
            <div class="row align-items-center">
                <div class="col-lg-12">
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Chat Section Start -->
    <div class="chat-page-container py-5" style="min-height: 70vh;">
        <div class="container">
            <div class="row justify-content-center">
                <!-- Chat Main Area -->
                <div class="col-lg-12 chat-main-area rounded shadow-sm overflow-hidden">
                    <!-- Chat Header -->
                    <div class="chat-header">
                        <div class="chat-header-user">
                            @if (auth()->user()->isServiceProvider())
                                <a href="{{ route('provider.requests.index') }}" class="chat-back-btn"
                                    title="{{ __('website.back_to_requests') }}">
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            @else
                                <a href="{{ route('requests.show', $chat->service_request_id) }}" class="chat-back-btn"
                                    title="{{ __('website.back_to_requests') }}">
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            @endif
                            <div class="chat-header-avatar">
                                <img src="{{ $otherUser->getFirstMediaUrl(' personal_photo') ?: $otherUser->getFirstMediaUrl('users') ?: asset('website/assets/img/logo.png') }}"
                                    alt="{{ $otherUser->name }}">
                                <span class="online-status"></span>
                            </div>
                            <div class="chat-header-info">
                                <h4 class="chat-header-name">{{ $otherUser->name }}</h4>
                                <span class="chat-header-status">{{ __('website.online_now') }}</span>
                            </div>
                        </div>
                        <div class="chat-header-actions">
                            <div class="text-end">
                                <small class="text-muted d-block">{{ __('website.request_number') }} #{{ $chat->serviceRequest->id }}</small>
                                <span
                                    class="badge bg-primary text-white">{{ $chat->serviceRequest->category->name ?? __('website.service') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Chat Messages -->
                    <div class="chat-messages" id="chatMessages" style="min-height: 500px; background-color: #f9f9f9;">
                        @foreach ($chat->messages as $msg)
                            @if ($msg->sender_id == auth()->id())
                                <!-- Message from Current User (Sent) -->
                                <div class="message-wrapper message-sent">
                                    <div class="message-content">
                                        <div class="message-bubble sent">
                                            <p>{{ $msg->message }}</p>
                                            @if ($msg->hasMedia('chat_attachments'))
                                                <div class="mt-2">
                                                    <a href="{{ $msg->getFirstMediaUrl('chat_attachments') }}"
                                                        target="_blank" class="text-white text-decoration-underline small">
                                                        <i class="bi bi-paperclip"></i> {{ __('website.attachment') }}
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                        <span class="message-time">{{ $msg->created_at->format('h:i A') }}</span>
                                    </div>
                                    <div class="message-avatar">
                                        <img src="{{ auth()->user()->getFirstMediaUrl('personal_photo') ?: auth()->user()->getFirstMediaUrl('users') ?: asset('website/assets/img/logo.png') }}"
                                            alt="You">
                                    </div>
                                </div>
                            @else
                                <!-- Message from Other User (Received) -->
                                <div class="message-wrapper message-received">
                                    <div class="message-avatar">
                                        <img src="{{ $otherUser->getFirstMediaUrl('personal_photo') ?: $otherUser->getFirstMediaUrl('users') ?: asset('website/assets/img/logo.png') }}"
                                            alt="{{ $otherUser->name }}">
                                    </div>
                                    <div class="message-content">
                                        <div class="message-bubble received">
                                            <p>{{ $msg->message }}</p>
                                            @if ($msg->hasMedia('chat_attachments'))
                                                <div class="mt-2">
                                                    <a href="{{ $msg->getFirstMediaUrl('chat_attachments') }}"
                                                        target="_blank"
                                                        class="text-primary text-decoration-underline small">
                                                        <i class="bi bi-paperclip"></i> {{ __('website.attachment') }}
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                        <span class="message-time">{{ $msg->created_at->format('h:i A') }}</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Chat Input -->
                    <div class="chat-input-wrapper">
                        <form id="chatForm" action="{{ route('chat.send', $chat->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="chat-input-container">
                                <label class="chat-attach-btn" for="attachmentFile" style="cursor: pointer;">
                                    <i class="bi bi-paperclip"></i>
                                </label>
                                <input type="file" name="attachment" id="attachmentFile" class="d-none">
                                <input type="text" name="message" class="chat-input" id="messageInput"
                                    placeholder="{{ __('website.type_message') }}" autocomplete="off">
                                <button type="submit" class="chat-send-btn" id="sendBtn">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                            <div id="fileNameDisplay" class="text-muted small mt-1 d-none px-3">{{ __('website.file_selected') }}: <span
                                    class="fw-bold"></span>
                                <button type="button" class="btn btn-sm text-danger" id="removeFileBtn"><i
                                        class="bi bi-x"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Chat Section End -->
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatMessages = document.getElementById('chatMessages');
            const fileInput = document.getElementById('attachmentFile');
            const fileNameDisplay = document.getElementById('fileNameDisplay');
            const removeFileBtn = document.getElementById('removeFileBtn');
            const form = document.getElementById('chatForm');
            const messageInput = document.getElementById('messageInput');

            // Scroll to bottom on load
            chatMessages.scrollTop = chatMessages.scrollHeight;

            // Display selected file name
            fileInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    fileNameDisplay.classList.remove('d-none');
                    fileNameDisplay.querySelector('span').textContent = this.files[0].name;
                } else {
                    fileNameDisplay.classList.add('d-none');
                }
            });

            // Remove selected file
            removeFileBtn.addEventListener('click', function() {
                fileInput.value = '';
                fileNameDisplay.classList.add('d-none');
            });

            // Track last message ID
            let lastMessageId = {{ $chat->messages->last() ? $chat->messages->last()->id : 0 }};

            // AJAX submit
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const sendBtn = document.getElementById('sendBtn');

                if (!messageInput.value.trim() && !fileInput.files.length) {
                    return;
                }

                sendBtn.disabled = true;

                fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (data.message.id > lastMessageId) {
                                lastMessageId = data.message.id;
                            }
                            
                            let attachmentHtml = '';
                            if (data.attachment_url) {
                                attachmentHtml = `
                                    <div class="mt-2">
                                        <a href="${data.attachment_url}" target="_blank" class="text-white text-decoration-underline small">
                                            <i class="bi bi-paperclip"></i> {{ __('website.attachment') }}
                                        </a>
                                    </div>`;
                            }

                            const messageWrapper = document.createElement('div');
                            messageWrapper.className = 'message-wrapper message-sent';
                            messageWrapper.innerHTML = `
                                <div class="message-content">
                                    <div class="message-bubble sent">
                                        <p>${data.message.message || ''}</p>
                                        ${attachmentHtml}
                                    </div>
                                    <span class="message-time">${data.time}</span>
                                </div>
                                <div class="message-avatar">
                                    <img src="${data.avatar}" alt="You">
                                </div>
                            `;
                            chatMessages.appendChild(messageWrapper);
                            chatMessages.scrollTop = chatMessages.scrollHeight;

                            form.reset();
                            fileNameDisplay.classList.add('d-none');
                        }
                    })
                    .catch(err => console.error(err))
                    .finally(() => {
                        sendBtn.disabled = false;
                        messageInput.focus();
                    });
            });

            // Polling for new messages
            function pollMessages() {
                fetch(`{{ route('chat.messages', $chat->id) }}?last_id=${lastMessageId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.messages.length > 0) {
                        data.messages.forEach(msg => {
                            if (msg.id > lastMessageId) {
                                lastMessageId = msg.id;
                                
                                let attachmentHtml = '';
                                if (msg.attachment_url) {
                                    attachmentHtml = `
                                        <div class="mt-2">
                                            <a href="${msg.attachment_url}" target="_blank" class="text-primary text-decoration-underline small">
                                                <i class="bi bi-paperclip"></i> {{ __('website.attachment') }}
                                            </a>
                                        </div>`;
                                }

                                const messageWrapper = document.createElement('div');
                                messageWrapper.className = 'message-wrapper message-received';
                                messageWrapper.innerHTML = `
                                    <div class="message-avatar">
                                        <img src="${msg.avatar}" alt="User">
                                    </div>
                                    <div class="message-content">
                                        <div class="message-bubble received">
                                            <p>${msg.message || ''}</p>
                                            ${attachmentHtml}
                                        </div>
                                        <span class="message-time">${msg.time}</span>
                                    </div>
                                `;
                                chatMessages.appendChild(messageWrapper);
                            }
                        });
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    }
                })
                .catch(err => console.error('Polling error:', err));
            }

            // Start polling every 5 seconds
            setInterval(pollMessages, 5000);
        });
    </script>
@endpush
