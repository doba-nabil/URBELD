@extends('dashboard.layout.master')

@section('dashboard-main')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">{{ __('admin.home') }} /</span> {{ __('admin.landing_page') }} / {{ __('admin.edit') }}
    </h4>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('admin.edit') }} {{ __('admin.features') }}</h5>
            <a href="{{ route('admin.landing-page.index') }}" class="btn btn-label-secondary">
                <i class="ti tabler-arrow-left me-1"></i> {{ __('admin.back') }}
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.landing-page.features.update', $feature->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <!-- Title AR -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('admin.title_ar') }} <span class="text-danger">*</span></label>
                        <input type="text" name="title[ar]" class="form-control" required value="{{ $feature->getTranslation('title', 'ar') }}">
                    </div>

                    <!-- Title EN -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('admin.title_en') }} <span class="text-danger">*</span></label>
                        <input type="text" name="title[en]" class="form-control" required value="{{ $feature->getTranslation('title', 'en') }}">
                    </div>

                    <!-- Description AR -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('admin.description_ar') }} <span class="text-danger">*</span></label>
                        <textarea name="description[ar]" class="form-control" rows="3" required>{{ $feature->getTranslation('description', 'ar') }}</textarea>
                    </div>

                    <!-- Description EN -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('admin.description_en') }} <span class="text-danger">*</span></label>
                        <textarea name="description[en]" class="form-control" rows="3" required>{{ $feature->getTranslation('description', 'en') }}</textarea>
                    </div>

                    <!-- Image -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('admin.image') }}</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        @if($feature->hasMedia('image'))
                            <div class="mt-2 text-center border p-2 rounded" style="width: 120px;">
                                <img src="{{ $feature->getFirstMediaUrl('image') }}" width="100" class="rounded">
                            </div>
                        @endif
                    </div>

                    <!-- Order -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">{{ __('admin.order') }}</label>
                        <input type="number" name="order" class="form-control" value="{{ $feature->order }}">
                    </div>

                    <!-- Status -->
                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ $feature->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">{{ __('admin.active') }}</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti tabler-check me-1"></i> {{ __('admin.save') }}
                    </button>
                    <a href="{{ route('admin.landing-page.index') }}" class="btn btn-label-secondary ms-2">
                        {{ __('admin.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
