@extends('layouts.website')
@section('title', __('tenders.apply_title'))
@section('content')
<!-- Header Start -->
<div class="category-header-section text-center services-header-section without-search pb-4">
    <div class="container">
        <h1 class="fw-bold mb-3">{{ __('tenders.apply_title') }}: {{ $tender->title }}</h1>
        <p class="mb-0">{{ __('tenders.apply_subtitle') }}</p>
    </div>
</div>
<div class="container py-5" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="row justify-content-center">
        <!-- Tender Details Summary -->
        <div class="col-lg-4 mb-4 mb-lg-0">
            <div class="card shadow-sm border-0" style="border-radius: 16px; position: sticky; top: 100px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold border-bottom pb-2 mb-3"><i class="bi bi-info-circle text-primary me-2"></i> {{ __('tenders.summary') }}</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">{{ __('tenders.budget') }}:</span>
                        <span class="fw-bold text-success">{{ $tender->budget ? number_format($tender->budget) . ' ' . __('tenders.sar') : __('tenders.not_specified') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">{{ __('tenders.location') }}:</span>
                        <span class="fw-bold">{{ $tender->city ? $tender->city->name : __('tenders.not_specified') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">{{ __('tenders.end_date') }}:</span>
                        <span class="fw-bold text-danger">{{ $tender->ends_at ? $tender->ends_at->format('Y-m-d') : __('tenders.not_specified') }}</span>
                    </div>
                    @if($tender->qualification_requirements && count($tender->qualification_requirements) > 0)
                    <h6 class="fw-bold mt-4 mb-2" style="font-size: 14px;">{{ __('tenders.basic_reqs') }}</h6>
                    <ul class="text-muted small ps-3 mb-0" style="list-style-type: disc;">
                        @foreach(array_slice($tender->qualification_requirements, 0, 3) as $req)
                            <li>{{ $req }}</li>
                        @endforeach
                        @if(count($tender->qualification_requirements) > 3)
                            <li>{{ __('tenders.and_more') }}</li>
                        @endif
                    </ul>
                    @endif
                    <a href="{{ route('website.tenders.show', $tender->id) }}" class="btn btn-outline-secondary btn-sm w-100 mt-4">{{ __('tenders.back_to_tender') }}</a>
                </div>
            </div>
        </div>
        <!-- Application Form -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-body p-4 p-md-5">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <form action="{{ route('website.tenders.storeApplication', $tender->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('tenders.financial_offer') }} <span class="text-danger">*</span></label>
                                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" min="0" step="100" placeholder="{{ __('tenders.price_ph') }}" required>
                                @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('tenders.execution_days') }} <span class="text-danger">*</span></label>
                                <input type="number" name="delivery_days" class="form-control @error('delivery_days') is-invalid @enderror" value="{{ old('delivery_days') }}" min="1" placeholder="{{ __('tenders.days_ph') }}" required>
                                @error('delivery_days')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold">{{ __('tenders.technical_offer') }} <span class="text-danger">*</span></label>
                                <textarea name="notes" rows="6" class="form-control @error('notes') is-invalid @enderror" placeholder="{{ __('tenders.notes_ph') }}" required>{{ old('notes') }}</textarea>
                                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="form-text">{{ __('tenders.notes_hint') }}</div>
                            </div>
                            <div class="col-md-12 border-top pt-4 mt-4">
                                <label class="form-label fw-bold">{{ __('tenders.offer_files') }}</label>
                                <p class="text-muted small mb-2">{{ __('tenders.offer_files_hint') }}</p>
                                <div id="files-container">
                                    <div class="row mb-2 file-row align-items-center">
                                        <div class="col-md-5">
                                            <input type="text" name="file_titles[]" class="form-control form-control-sm" placeholder="{{ __('tenders.offer_file_ph') }}">
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
                            <div class="col-md-12 mt-5">
                                <div class="form-check bg-light p-3 rounded" style="border: 1px solid #e5e7eb; {{ app()->getLocale() == 'ar' ? 'padding-right: 2.5em; padding-left: 0;' : 'padding-left: 2.5em; padding-right: 0;' }}">
                                    <input class="form-check-input mt-1 {{ app()->getLocale() == 'ar' ? 'float-end me-n4 ms-2' : 'float-start ms-n4 me-2' }}" type="checkbox" id="agreeTerms" required>
                                    <label class="form-check-label fw-bold" for="agreeTerms" style="font-size: 14px;">
                                        {{ __('tenders.agree_terms') }}
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-12 mt-4 text-center">
                                <button type="submit" class="btn btn-success px-5 py-2 fw-bold" style="border-radius: 8px;">
                                    <i class="bi bi-check-circle me-1"></i> {{ __('tenders.submit_offer') }}
                                </button>
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
    // Dynamic Files
    const filesContainer = document.getElementById('files-container');
    const addFileBtn = document.getElementById('addFileBtn');
    addFileBtn.addEventListener('click', function() {
        const row = document.createElement('div');
        row.className = 'row mb-2 file-row align-items-center';
        row.innerHTML = `
            <div class="col-md-5">
                <input type="text" name="file_titles[]" class="form-control form-control-sm" placeholder="{{ __('tenders.offer_file_ph') }}">
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
