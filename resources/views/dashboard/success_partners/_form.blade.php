@php
    $isEdit = isset($partner);
@endphp

@if (count(LaravelLocalization::getSupportedLocales()) > 1)
    <!-- Tabs for languages -->
    <ul class="nav nav-tabs mb-4" id="partnerLangTabs" role="tablist">
        @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                    id="partner-{{ $localeCode }}-tab" data-bs-toggle="tab"
                    data-bs-target="#partner-{{ $localeCode }}" type="button" role="tab"
                    aria-controls="partner-{{ $localeCode }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    {{ __('admin.' . $properties['name']) }}
                </button>
            </li>
        @endforeach
    </ul>
@endif

<div class="tab-content mb-4" id="partnerLangTabsContent">
    @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
            id="partner-{{ $localeCode }}" role="tabpanel" aria-labelledby="partner-{{ $localeCode }}-tab">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">{{ __('admin.title') }} ({{ $properties['name'] }}) <span class="text-danger">*</span></label>
                    <input type="text" name="title[{{ $localeCode }}]" class="form-control"
                           value="{{ old('title.'.$localeCode, $isEdit ? $partner->getTranslation('title',$localeCode) : '') }}"
                           {{ $localeCode == 'ar' ? 'required' : '' }}>
                    @error('title.'.$localeCode)<span class="text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row">

    <div class="col-md-12 mb-3">
        <label class="form-label">{{ __('admin.image') }}</label>
        <div class="dropzone needsclick" id="dropzone-partner">
            <div class="dz-message needsclick">
                {{ __('admin.Drop files here or click to upload') }}
            </div>
        </div>
        @error('image')
        <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('admin.sort_order') }}</label>
        <input type="number" name="sort_order" class="form-control" min="0"
               value="{{ old('sort_order', $isEdit ? $partner->sort_order : 0) }}">
        @error('sort_order')<span class="text-danger">{{ $message }}</span>@enderror
    </div>

    <div class="col-md-6 mb-3 d-flex align-items-center">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                   id="is_active"
                {{ old('is_active', $isEdit ? $partner->is_active : 1) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">{{ __('admin.active') }}</label>
        </div>
    </div>
</div>
