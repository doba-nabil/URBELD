@extends('dashboard.layout.master')
@section('title', __('admin.favorites'))
@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h5 class="card-header d-flex justify-content-between border-b">
                {{ __('admin.favorites_list') }}
                <div class="buttons d-flex justify-content-between">
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
            // Delete Favorite
            $(document).on('click', '.delete-favorite', function() {
                const id = $(this).data('id');
                const userName = $(this).data('user');
                const favoriteName = $(this).data('favorite');
                
                Swal.fire({
                    title: '{{ __('admin.confirm_delete') }}',
                    html: `{{ __('admin.confirm_delete_favorite') }}`.replace(':user', userName).replace(':favorite', favoriteName),
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: '{{ __('admin.yes_delete') }}',
                    cancelButtonText: '{{ __('admin.cancel') }}'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('/') }}/admin-panel/favorites/${id}`,
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
                                        $('#favorites-table').DataTable().ajax.reload();
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

