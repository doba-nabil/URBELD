@extends('layouts.website')
@section('title', __('tenders.create_title'))
@section('content')
<div class="category-header-section text-center services-header-section without-search">
    <div class="container">
        <h1 class="fw-bold mb-3">{{ __('tenders.create_title') }}</h1>
        <p class="mb-0">{{ __('tenders.create_subtitle') }}</p>
    </div>
</div>
<div class="container py-5" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-body p-4 p-md-5">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <form action="{{ route('website.tenders.store') }}" method="POST" enctype="multipart/form-data" id="createTenderForm">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">{{ __('tenders.tender_title') }} <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="{{ __('tenders.tender_title_ph') }}" required>
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('tenders.category') }} <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">{{ __('tenders.all_categories') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('tenders.city') }} <span class="text-danger">*</span></label>
                                <select name="city_id" class="form-select @error('city_id') is-invalid @enderror" required>
                                    <option value="">{{ __('tenders.all_cities') }}</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                    @endforeach
                                </select>
                                @error('city_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('tenders.budget_opt') }}</label>
                                <input type="number" name="budget" class="form-control @error('budget') is-invalid @enderror" value="{{ old('budget') }}" min="0" step="100">
                                @error('budget')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('tenders.end_date') }} <span class="text-danger">*</span></label>
                                <input type="date" name="ends_at" class="form-control @error('ends_at') is-invalid @enderror" value="{{ old('ends_at') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                @error('ends_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold">{{ __('tenders.project_type_opt') }}</label>
                                <input type="text" name="project_type" class="form-control @error('project_type') is-invalid @enderror" value="{{ old('project_type') }}" placeholder="{{ __('tenders.project_type_ph') }}">
                                @error('project_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold">{{ __('tenders.description') }} <span class="text-danger">*</span></label>
                                <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror" placeholder="{{ __('tenders.desc_ph') }}" required>{{ old('description') }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold">{{ __('tenders.req_opt') }}</label>
                                <p class="text-muted small mb-2">{{ __('tenders.req_hint') }}</p>
                                <div id="requirements-container">
                                    <div class="input-group mb-2 req-row">
                                        <input type="text" name="qualification_requirements[]" class="form-control" placeholder="{{ __('tenders.req_ph') }}">
                                        <button class="btn btn-outline-danger remove-req" type="button"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="addReqBtn"><i class="bi bi-plus"></i> {{ __('tenders.add_req') }}</button>
                            </div>
                            <div class="col-md-12 border-top pt-4 mt-4">
                                <label class="form-label fw-bold">{{ __('tenders.files_opt') }}</label>
                                <p class="text-muted small mb-2">{{ __('tenders.files_hint') }}</p>
                                <div id="files-container">
                                    <div class="row mb-2 file-row align-items-center">
                                        <div class="col-md-5">
                                            <input type="text" name="file_titles[]" class="form-control form-control-sm" placeholder="{{ __('tenders.file_name_ph') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="file" name="files[]" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png,.zip,.doc,.docx">
                                        </div>
                                        <div class="col-md-1 text-center">
                                            <button class="btn btn-sm btn-outline-danger remove-file" type="button"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-secondary mt-2" id="addFileBtn"><i class="bi bi-plus"></i> {{ __('tenders.add_file') }}</button>
                            </div>
                            <div class="col-md-12 border-top pt-4 mt-4">
                                <div class="form-check form-switch" style="{{ app()->getLocale() == 'ar' ? 'padding-right: 2.5em; padding-left: 0;' : 'padding-left: 2.5em; padding-right: 0;' }}">
                                    <input class="form-check-input {{ app()->getLocale() == 'ar' ? 'float-end me-n4 ms-2' : 'float-start ms-n4 me-2' }}" type="checkbox" name="is_urgent" id="is_urgent" value="1" {{ old('is_urgent') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-danger" for="is_urgent">
                                        <i class="bi bi-lightning-charge-fill"></i> {{ __('tenders.mark_urgent') }}
                                    </label>
                                    <p class="text-muted small mb-0 mt-1">{{ __('tenders.urgent_hint') }}</p>
                                </div>
                            </div>
                            <div class="col-md-12 mt-5 text-center">
                                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold" style="border-radius: 8px;">
                                    <i class="bi bi-send me-1"></i> {{ __('tenders.publish_btn') }}
                                </button>
                                <p class="text-muted small mt-2">{{ __('tenders.publish_note') }}</p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dynamic Requirements
    const reqContainer = document.getElementById('requirements-container');
    const addReqBtn = document.getElementById('addReqBtn');
    addReqBtn.addEventListener('click', function() {
        const row = document.createElement('div');
        row.className = 'input-group mb-2 req-row';
        row.innerHTML = `
            <input type="text" name="qualification_requirements[]" class="form-control" placeholder="{{ __('tenders.req_ph') }}">
            <button class="btn btn-outline-danger remove-req" type="button"><i class="bi bi-trash"></i></button>
        `;
        reqContainer.appendChild(row);
    });
    reqContainer.addEventListener('click', function(e) {
        if(e.target.closest('.remove-req')) {
            const row = e.target.closest('.req-row');
            if(document.querySelectorAll('.req-row').length > 1) {
                row.remove();
            } else {
                row.querySelector('input').value = '';
            }
        }
    });
    // Dynamic Files
    const filesContainer = document.getElementById('files-container');
    const addFileBtn = document.getElementById('addFileBtn');
    addFileBtn.addEventListener('click', function() {
        const row = document.createElement('div');
        row.className = 'row mb-2 file-row align-items-center';
        row.innerHTML = `
            <div class="col-md-5">
                <input type="text" name="file_titles[]" class="form-control form-control-sm" placeholder="{{ __('tenders.file_name_ph') }}">
            </div>
            <div class="col-md-6">
                <input type="file" name="files[]" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png,.zip,.doc,.docx">
            </div>
            <div class="col-md-1 text-center">
                <button class="btn btn-sm btn-outline-danger remove-file" type="button"><i class="bi bi-trash"></i></button>
            </div>
        `;
        filesContainer.appendChild(row);
    });
    filesContainer.addEventListener('click', function(e) {
        if(e.target.closest('.remove-file')) {
            const row = e.target.closest('.file-row');
            if(document.querySelectorAll('.file-row').length > 1) {
                row.remove();
            } else {
                row.querySelectorAll('input').forEach(input => input.value = '');
            }
        }
    });
});
</script>
@endpush
