@extends('dashboard.layout.master')
@section('title', __('admin.service_providers'))
@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Bootstrap Table -->
        <div class="card">
            <h5 class="card-header d-flex justify-content-between border-b">
                {{__('admin.service_providers')}}
                <div class="buttons d-flex justify-content-between gap-2">
                    @include('dashboard.partials.index.table_btns')
                    <div class="btn-group" role="group">
                        <a href="{{ route('memberships.index') }}" 
                           class="btn btn-{{ !request()->has('type') ? 'primary' : 'outline-primary' }}">
                            <i class="menu-icon icon-base ti tabler-list"></i> {{ __('admin.all') }}
                        </a>
                        <a href="{{ route('memberships.index', ['type' => 'individual']) }}" 
                           class="btn btn-{{ request()->get('type') == 'individual' ? 'primary' : 'outline-primary' }}">
                            <i class="menu-icon icon-base ti tabler-user"></i> {{ __('admin.individual') }}
                        </a>
                        <a href="{{ route('memberships.index', ['type' => 'company']) }}" 
                           class="btn btn-{{ request()->get('type') == 'company' ? 'primary' : 'outline-primary' }}">
                            <i class="menu-icon icon-base ti tabler-building"></i> {{ __('admin.company') }}
                        </a>
                    </div>
                    <a class="btn btn-primary" href="{{ route('memberships.create', ['type' => request()->get('type')]) }}">
                        <i class="menu-icon icon-base ti tabler-plus"></i> {{ __('admin.add_new') }}
                    </a>
                </div>
            </h5>
            <div class="table-responsive text-nowrap">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>

    <!-- Modal for Sub Categories -->
    <div class="modal fade" id="subCategoriesModal" tabindex="-1" aria-labelledby="subCategoriesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="subCategoriesModalLabel">{{ __('admin.sub_categories') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="subCategoriesContent">
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Certificates -->
    <div class="modal fade" id="certificatesModal" tabindex="-1" aria-labelledby="certificatesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="certificatesModalLabel">{{ __('admin.certificates') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="certificatesContent">
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
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
            // Handle click on sub categories count
            $(document).on('click', '.view-sub-categories', function() {
                const membershipId = $(this).data('membership-id');
                const modal = new bootstrap.Modal(document.getElementById('subCategoriesModal'));
                const content = $('#subCategoriesContent');
                
                content.html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                modal.show();
                
                $.ajax({
                    url: '{{ url("admin-panel/memberships") }}/' + membershipId + '/sub-categories',
                    method: 'GET',
                    success: function(response) {
                        if (response.status === 'success' && response.data.length > 0) {
                            let html = '<div class="mb-3"><strong>{{ __("admin.membership") }}:</strong> ' + response.membership_name + '</div>';
                            html += '<div class="row">';
                            response.data.forEach(function(category) {
                                html += '<div class="col-md-6 mb-3">';
                                html += '<div class="card">';
                                html += '<div class="card-body">';
                                if (category.icon) {
                                    html += '<i class="' + category.icon + ' me-2"></i>';
                                }
                                html += '<strong>' + category.name + '</strong>';
                                html += '</div>';
                                html += '</div>';
                                html += '</div>';
                            });
                            html += '</div>';
                            content.html(html);
                        } else {
                            content.html('<div class="alert alert-info text-center">{{ __("admin.no_data") }}</div>');
                        }
                    },
                    error: function() {
                        content.html('<div class="alert alert-danger text-center">{{ __("admin.error_loading_data") }}</div>');
                    }
                });
            });
            
            // Handle click on certificates count
            $(document).on('click', '.view-certificates', function() {
                const membershipId = $(this).data('membership-id');
                const modal = new bootstrap.Modal(document.getElementById('certificatesModal'));
                const content = $('#certificatesContent');
                
                content.html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                modal.show();
                
                $.ajax({
                    url: '{{ url("admin-panel/memberships") }}/' + membershipId + '/certificates',
                    method: 'GET',
                    success: function(response) {
                        if (response.status === 'success' && response.data.length > 0) {
                            let html = '<div class="mb-3"><strong>{{ __("admin.membership") }}:</strong> ' + response.membership_name + '</div>';
                            html += '<div class="row">';
                            response.data.forEach(function(certificate) {
                                html += '<div class="col-md-6 mb-3">';
                                html += '<div class="card">';
                                html += '<div class="card-body text-center">';
                                if (certificate.image) {
                                    html += '<img src="' + certificate.image + '" alt="' + certificate.name + '" class="img-fluid mb-2" style="max-height: 150px; border-radius: 8px;">';
                                }
                                html += '<div><strong>' + certificate.name + '</strong></div>';
                                html += '</div>';
                                html += '</div>';
                                html += '</div>';
                            });
                            html += '</div>';
                            content.html(html);
                        } else {
                            content.html('<div class="alert alert-info text-center">{{ __("admin.no_data") }}</div>');
                        }
                    },
                    error: function() {
                        content.html('<div class="alert alert-danger text-center">{{ __("admin.error_loading_data") }}</div>');
                    }
                });
            });
        });
    </script>
@endsection
