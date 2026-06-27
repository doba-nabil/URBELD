@extends('dashboard.layout.master')
@section('title', __('admin.search_logs'))
@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h5 class="card-header d-flex justify-content-between border-b">
                {{ __('admin.search_operations') }}
                <div class="buttons d-flex justify-content-between">
                    @include('dashboard.partials.index.table_btns')
                </div>
            </h5>
            <div class="table-responsive text-nowrap">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>

    <!-- View Search Details Modal -->
    <div class="modal fade" id="viewSearchModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('admin.search_details_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="search-details-content">
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('admin.search_criteria') }}:</label>
                            <pre id="modal-filters" class="bg-light p-3 rounded" style="max-height: 300px; overflow-y: auto;"></pre>
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
            // View Search Details
            $(document).on('click', '.view-search-details', function() {
                const filters = $(this).data('filters');
                
                if (filters && Object.keys(filters).length > 0) {
                    let filtersHtml = '<div class="table-responsive">';
                    filtersHtml += '<table class="table table-sm">';
                    filtersHtml += '<thead><tr><th>{{ __('admin.search_criterion') }}</th><th>{{ __('admin.value') }}</th></tr></thead>';
                    filtersHtml += '<tbody>';
                    
                    for (const [key, value] of Object.entries(filters)) {
                        if (value && value !== 'all') {
                            const label = key.replace(/_/g, ' ');
                            filtersHtml += `<tr><td>${label}</td><td>${value}</td></tr>`;
                        }
                    }
                    
                    filtersHtml += '</tbody></table></div>';
                    $('#modal-filters').html(filtersHtml);
                } else {
                    $('#modal-filters').html('<p class="text-muted text-center">{{ __('admin.no_search_criteria') }}</p>');
                }

                $('#viewSearchModal').modal('show');
            });
        });
    </script>
@endsection

