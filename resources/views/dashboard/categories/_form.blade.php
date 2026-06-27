@php
    $isEdit = isset($category);
@endphp

@if (count(LaravelLocalization::getSupportedLocales()) > 1)
    <!-- Tabs for languages -->
    <ul class="nav nav-tabs mb-4" id="categoryLangTabs" role="tablist">
        @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                    id="cat-{{ $localeCode }}-tab" data-bs-toggle="tab"
                    data-bs-target="#cat-{{ $localeCode }}" type="button" role="tab"
                    aria-controls="cat-{{ $localeCode }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    {{ __('admin.' . $properties['name']) }}
                </button>
            </li>
        @endforeach
    </ul>
@endif

<div class="tab-content mb-4" id="categoryLangTabsContent">
    @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
            id="cat-{{ $localeCode }}" role="tabpanel" aria-labelledby="cat-{{ $localeCode }}-tab">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">{{ __('admin.name') }} ({{ $properties['name'] }})</label>
                    <input type="text" name="name[{{ $localeCode }}]" class="form-control"
                           value="{{ old('name.'.$localeCode, $isEdit ? $category->getTranslation('name', $localeCode) : '') }}"
                           {{ $localeCode == 'ar' ? 'required' : '' }}>
                    @error('name.'.$localeCode)<span class="text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">{{ __('admin.desc') }} ({{ $properties['name'] }})</label>
                    <textarea name="description[{{ $localeCode }}]" class="form-control" rows="3">{{ old('description.'.$localeCode, $isEdit ? $category->getTranslation('description', $localeCode) : '') }}</textarea>
                    @error('description.'.$localeCode)<span class="text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row">
        <label class="form-label">
            {{ __('admin.parent_category') }}
            <small class="text-muted">{{ __('admin.optional_main_category') }}</small>
        </label>
        <select name="parent_id" class="form-select" id="parent_id">
            <option value="">{{ __('admin.main_category') }} - {{ __('admin.main_category_label') }}</option>
            @foreach($parents as $parent)
                <option value="{{ $parent->id }}"
                    {{ old('parent_id', $isEdit ? $category->parent_id : null) == $parent->id ? 'selected' : '' }}>
                    {{ $parent->name }} - {{ __('admin.sub_category_label') }}
                </option>
            @endforeach
        </select>
        @error('parent_id')<span class="text-danger">{{ $message }}</span>@enderror
        <small class="text-muted d-block mt-1">
            <i class="ti tabler-info-circle"></i> 
            {{ __('admin.category_selection_hint') }}
        </small>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('admin.icon') }}</label>
        <input type="text" name="icon" id="icon-input" class="form-control"
               value="{{ old('icon', $isEdit ? $category->icon : '') }}"
               placeholder="ex: ti tabler-building">
        @error('icon')<span class="text-danger">{{ $message }}</span>@enderror
        <small class="text-muted d-block mt-1">{{ __('admin.icon_picker_hint') }}</small>
        <div class="mt-2" id="icon-preview">
            @if(old('icon', $isEdit ? $category->icon : null))
                <i class="{{ old('icon', $isEdit ? $category->icon : '') }} fs-3"></i>
            @endif
        </div>
    </div>


    <div class="col-md-12 mb-3">
        <label class="form-label">{{ __('admin.image') }}</label>
        <div class="dropzone needsclick" id="dropzone-category">
            <div class="dz-message needsclick">
                {{ __('admin.Drop files here or click to upload') }}
            </div>
        </div>
        @error('image')
        <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3 d-flex align-items-center">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                   id="is_active"
                {{ old('is_active', $isEdit ? $category->is_active : 1) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">{{ __('admin.active') }}</label>
        </div>
    </div>
</div>

