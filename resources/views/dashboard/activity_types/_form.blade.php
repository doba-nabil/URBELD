@php
    $isEdit = isset($activityType);
@endphp

<div class="row">
    <div class="{{ count(LaravelLocalization::getSupportedLocales()) > 1 && isset(LaravelLocalization::getSupportedLocales()['en']) ? 'col-md-6' : 'col-md-12' }} mb-3">
        <label class="form-label">{{ __('admin.name_ar') }}</label>
        <input type="text" name="name[ar]" class="form-control"
               value="{{ old('name.ar', $isEdit ? $activityType->getTranslation('name','ar') : '') }}"
               required>
        @error('name.ar')<span class="text-danger">{{ $message }}</span>@enderror
    </div>
    @if(count(LaravelLocalization::getSupportedLocales()) > 1 && isset(LaravelLocalization::getSupportedLocales()['en']))
    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('admin.name_en') }}</label>
        <input type="text" name="name[en]" class="form-control"
               value="{{ old('name.en', $isEdit ? $activityType->getTranslation('name','en') : '') }}">
        @error('name.en')<span class="text-danger">{{ $message }}</span>@enderror
    </div>
    @endif

    <div class="col-md-12 mb-3">
        <label class="form-label">{{ __('admin.description') }}</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $isEdit ? $activityType->description : '') }}</textarea>
        @error('description')<span class="text-danger">{{ $message }}</span>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('admin.sort_order') }}</label>
        <input type="number" name="sort_order" class="form-control" min="0"
               value="{{ old('sort_order', $isEdit ? $activityType->sort_order : 0) }}">
        @error('sort_order')<span class="text-danger">{{ $message }}</span>@enderror
    </div>

    <div class="col-md-6 mb-3 d-flex align-items-center">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                   id="is_active"
                {{ old('is_active', $isEdit ? $activityType->is_active : 1) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">{{ __('admin.active') }}</label>
        </div>
    </div>
</div>
