@extends('dashboard.layout.master')
@section('title', __('admin.notifications'))
@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h5 class="card-header d-flex justify-content-between border-b">
                {{ __('admin.notifications') }}
                <div class="buttons d-flex justify-content-between gap-2">
                    <button class="btn btn-danger delete-all-notifications">
                        <i class="ti tabler-trash me-1"></i> حذف الكل
                    </button>
                    @include('dashboard.partials.index.table_btns')
                </div>
            </h5>
            <div class="table-responsive text-nowrap">
                {{ $dataTable->table() }}
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
            // Mark single notification as read
            $(document).on('click', '.mark-read', function() {
                const id = $(this).data('id');
                
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                
                $.ajax({
                    url: `{{ url('/') }}/admin-panel/notifications/${id}/mark-read`,
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#notifications-table').DataTable().ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: '{{ __('admin.error') }}!',
                            text: '{{ __('admin.error_updating_notification') }}',
                            icon: 'error'
                        });
                    }
                });
            });
            
            // Mark notification as read when clicking its link
            $(document).on('click', '.notification-link', function(e) {
                const id = $(this).data('id');
                const href = $(this).attr('href');
                
                if (href && href !== 'javascript:void(0)' && href !== '#') {
                    e.preventDefault();
                    
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    
                    $.ajax({
                        url: `{{ url('/') }}/admin-panel/notifications/${id}/mark-read`,
                        type: 'POST',
                        success: function() {
                            window.location.href = href;
                        },
                        error: function() {
                            // Proceed to link even if it fails
                            window.location.href = href;
                        }
                    });
                }
            });
            
            // Delete notification
            $(document).on('click', '.delete-notification', function() {
                const id = $(this).data('id');
                
                Swal.fire({
                    title: '{{ __('admin.confirm_delete') }}',
                    text: '{{ __('admin.confirm_delete_notification') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: '{{ __('admin.yes_delete') }}',
                    cancelButtonText: '{{ __('admin.cancel') }}'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        
                        $.ajax({
                            url: `{{ url('/') }}/admin-panel/notifications/${id}`,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: '{{ __('admin.deleted') }}',
                                    text: response.message,
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    $('#notifications-table').DataTable().ajax.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: '{{ __('admin.error') }}!',
                                    text: '{{ __('admin.error_deleting_notification') }}',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });
            
            // Delete all notifications
            $(document).on('click', '.delete-all-notifications', function() {
                Swal.fire({
                    title: '{{ __('admin.confirm_delete') }}',
                    text: 'هل أنت متأكد من حذف جميع إشعاراتك؟ لا يمكن التراجع عن هذا الإجراء.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: '{{ __('admin.yes_delete') }}',
                    cancelButtonText: '{{ __('admin.cancel') }}'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        
                        $.ajax({
                            url: `{{ url('/') }}/admin-panel/notifications/delete-all`,
                            type: 'DELETE',
                            success: function(response) {
                                Swal.fire({
                                    title: '{{ __('admin.deleted') }}',
                                    text: response.message,
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    $('#notifications-table').DataTable().ajax.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: '{{ __('admin.error') }}!',
                                    text: 'حدث خطأ أثناء محاولة الحذف.',
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

