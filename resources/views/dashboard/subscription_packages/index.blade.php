@extends('dashboard.layout.master')
@section('title', __('admin.subscription_packages'))
@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h5 class="card-header d-flex justify-content-between border-b">
                {{__('admin.subscription_packages')}}
                <div class="buttons d-flex justify-content-between">
                    @include('dashboard.partials.index.table_btns')
                    <a class="btn btn-primary" href="{{ route('subscription-packages.create') }}">
                        <i class="menu-icon icon-base ti tabler-plus"></i> {{ __('admin.add_new') }}
                    </a>
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
        $(document).ready(function () {
            $(document).on('click', '.toggle-recommended-btn', function (e) {
                e.preventDefault();
                var url = $(this).data('url');
                var table = $(this).data('table');
                
                Swal.fire({
                    title: '{{ __("admin.sure") }}',
                    text: '{{ __("admin.sure_change_recommendation") }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'نعم، تأكيد',
                    cancelButtonText: '{{ __("admin.cancel") }}'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                if (response.status === 'success') {
                                    Swal.fire('{{ __("admin.update_success") }}', response.message, 'success');
                                    if (typeof window.LaravelDataTables !== 'undefined') {
                                        let tableId = Object.keys(window.LaravelDataTables)[0];
                                        window.LaravelDataTables[tableId].ajax.reload(null, false);
                                    } else {
                                        $(table).DataTable().ajax.reload(null, false);
                                    }
                                } else {
                                    Swal.fire('خطأ', response.message, 'error');
                                }
                            },
                            error: function (xhr) {
                                Swal.fire('خطأ', '{{ __("admin.error_executing_request") }}', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
