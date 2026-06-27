@extends('dashboard.layout.master')
@section('title', __('admin.chats'))
@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h5 class="card-header d-flex justify-content-between border-b">
                {{ __('admin.chats_title') }}
                <div class="buttons d-flex justify-content-between">
                    @include('dashboard.partials.index.table_btns')
                </div>
            </h5>
            <div class="table-responsive text-nowrap">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>

    <!-- View Chat Messages Modal -->
    <div class="modal fade" id="viewChatModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('admin.chat_messages_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="chat-messages-content">
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('dashboard-head')
    @include('dashboard.partials.index.css')
@endsection

@section('dashboard-footer')
    {{ $dataTable->scripts() }}
    @include('dashboard.partials.index.js')
    
    <script>
        $(document).ready(function() {
            // View Chat Messages
            $(document).on('click', '.view-chat-messages', function() {
                const uuid = $(this).data('uuid');
                const fromUser = $(this).data('from');
                const toUser = $(this).data('to');
                
                $('#viewChatModal .modal-title').text(`{{ __('admin.chat_messages_between') }}`.replace(':from', fromUser).replace(':to', toUser));
                $('#chat-messages-content').html(`
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `);
                
                $('#viewChatModal').modal('show');
                
                // Load messages
                $.ajax({
                    url: `/admin-panel/chats/${uuid}/messages`,
                    type: 'GET',
                    success: function(response) {
                        if (response.status === 'success') {
                            let messagesHtml = '';
                            
                            if (response.messages && response.messages.length > 0) {
                                messagesHtml = '<div class="chat-messages p-3" style="max-height: 500px; overflow-y: auto;">';
                                
                                response.messages.forEach(function(message) {
                                    const isFromSender = message.sender_id == response.chat.from_user_id;
                                    
                                    // Handle cases where the user might have been deleted (null)
                                    let senderName = '{{ __('admin.not_specified') }}';
                                    if (isFromSender && response.chat.from_user) {
                                        senderName = response.chat.from_user.name;
                                    } else if (!isFromSender && response.chat.to_user) {
                                        senderName = response.chat.to_user.name;
                                    }
                                    
                                    const messageClass = isFromSender ? 'text-end' : 'text-start';
                                    const bgClass = isFromSender ? 'bg-primary text-white' : 'bg-light text-dark';
                                    const dateObj = new Date(message.created_at);
                                    const dateStr = dateObj.toLocaleDateString('en-GB') + ' ' + dateObj.toLocaleTimeString('en-GB', {hour: '2-digit', minute:'2-digit'});
                                    
                                    messagesHtml += `
                                        <div class="mb-3 ${messageClass}">
                                            <div class="d-inline-block p-2 rounded ${bgClass}" style="max-width: 70%; text-align: right;">
                                                <div class="small mb-1 fw-bold" style="opacity: 0.8">${senderName}</div>
                                                <div class="fs-6">${message.message}</div>
                                                <div class="small mt-1" style="opacity: 0.7; font-size: 0.75rem" dir="ltr">${dateStr}</div>
                                            </div>
                                        </div>
                                    `;
                                });
                                
                                messagesHtml += '</div>';
                            } else {
                                messagesHtml = '<div class="text-center text-muted p-4">{{ __('admin.no_messages') }}</div>';
                            }
                            
                            $('#chat-messages-content').html(messagesHtml);
                        } else {
                            $('#chat-messages-content').html('<div class="text-center text-danger">{{ __('admin.error_fetching_messages') }}</div>');
                        }
                    },
                    error: function() {
                        $('#chat-messages-content').html('<div class="text-center text-danger">{{ __('admin.error_fetching_messages') }}</div>');
                    }
                });
            });

            // Delete Chat
            $(document).on('click', '.delete-chat', function() {
                const id = $(this).data('id');
                const fromUser = $(this).data('from');
                const toUser = $(this).data('to');
                
                Swal.fire({
                    title: '{{ __('admin.confirm_delete') }}',
                    html: `{{ __('admin.confirm_delete_chat') }}`.replace(':from', fromUser).replace(':to', toUser),
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: '{{ __('admin.yes_delete') }}',
                    cancelButtonText: '{{ __('admin.cancel') }}'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('/') }}/admin-panel/chats/${id}`,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    Swal.fire({
                                        title: '{{ __('admin.deleted') }}',
                                        text: response.message,
                                        icon: 'success',
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        $('#chats-table').DataTable().ajax.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: '{{ __('admin.error') }}!',
                                        text: response.message,
                                        icon: 'error'
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    title: '{{ __('admin.error') }}!',
                                    text: '{{ __('admin.error_deleting') }}',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
