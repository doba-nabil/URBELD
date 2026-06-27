@extends('dashboard.layout.master')
@section('title', __('admin.complaints_and_suggestions'))
@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h5 class="card-header d-flex justify-content-between border-b">
                {{ __('admin.contacts_complaints') }}
                <div class="buttons d-flex justify-content-between">
                    @include('dashboard.partials.index.table_btns')
                </div>
            </h5>
            <div class="table-responsive text-nowrap">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>

    <!-- View Contact Modal -->
    <div class="modal fade" id="viewContactModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('admin.message_details') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('admin.name_label') }}</label>
                        <p id="modal-name" class="form-control-plaintext"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('admin.phone_label') }}</label>
                        <p id="modal-phone" class="form-control-plaintext"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('admin.email_label') }}</label>
                        <p id="modal-email" class="form-control-plaintext"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('admin.message_label') }}</label>
                        <p id="modal-message" class="form-control-plaintext" style="white-space: pre-wrap;"></p>
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
            // View Contact Details
            $(document).on('click', '.view-contact', function() {
                const name = $(this).data('name');
                const phone = $(this).data('phone');
                const email = $(this).data('email');
                const message = $(this).data('message');

                $('#modal-name').text(name);
                $('#modal-phone').text(phone || '{{ __('admin.not_available') }}');
                $('#modal-email').text(email || '{{ __('admin.not_available') }}');
                $('#modal-message').text(message);

                $('#viewContactModal').modal('show');
            });
        });
    </script>
@endsection

