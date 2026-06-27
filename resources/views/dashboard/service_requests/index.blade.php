@extends('dashboard.layout.master')
@section('title', __('admin.service_requests'))
@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Bootstrap Table -->
        <div class="card">
            <h5 class="card-header d-flex justify-content-between border-b">
                {{__('admin.service_requests')}}
                <div class="buttons d-flex justify-content-between">
                    @include('dashboard.partials.index.table_btns')
                    <a class="btn btn-primary" href="{{ route('service-requests.create') }}"><i
                            class="menu-icon icon-base ti tabler-plus"></i> {{ __('admin.add_new') }}</a>
                    <button class="btn btn-warning ms-2" id="update-expired-btn">
                        <i class="menu-icon icon-base ti tabler-refresh"></i> تحديث المنتهية
                    </button>
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
            $('#update-expired-btn').on('click', function() {
                $.ajax({
                    url: '{{ route('service-requests.update-expired') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'تم التحديث',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            $('#table').DataTable().ajax.reload();
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: 'حدث خطأ أثناء التحديث'
                        });
                    }
                });
            });
        });
    </script>
@endsection
