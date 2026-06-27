@php
    $isEdit = isset($type);
@endphp

@if (count(LaravelLocalization::getSupportedLocales()) > 1)
    <!-- Tabs for languages -->
    <ul class="nav nav-tabs mb-4" id="typeLangTabs" role="tablist">
        @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                    id="type-{{ $localeCode }}-tab" data-bs-toggle="tab"
                    data-bs-target="#type-{{ $localeCode }}" type="button" role="tab"
                    aria-controls="type-{{ $localeCode }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    {{ __('admin.' . $properties['name']) }}
                </button>
            </li>
        @endforeach
    </ul>
@endif

<div class="tab-content mb-4" id="typeLangTabsContent">
    @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
            id="type-{{ $localeCode }}" role="tabpanel" aria-labelledby="type-{{ $localeCode }}-tab">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">{{ __('admin.name') }} ({{ $properties['name'] }}) <span class="text-danger">*</span></label>
                    <input type="text" name="name[{{ $localeCode }}]" class="form-control"
                           value="{{ old('name.'.$localeCode, $isEdit ? $type->getTranslation('name',$localeCode) : '') }}"
                           {{ $localeCode == 'ar' ? 'required' : '' }}>
                    @error('name.'.$localeCode)<span class="text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row">

    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('admin.description') }}</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $isEdit ? $type->description : '') }}</textarea>
        @error('description')<span class="text-danger">{{ $message }}</span>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('admin.sort_order') }}</label>
        <input type="number" name="sort_order" class="form-control" min="0"
               value="{{ old('sort_order', $isEdit ? $type->sort_order : '0') }}">
        @error('sort_order')<span class="text-danger">{{ $message }}</span>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                   {{ old('is_active', $isEdit ? $type->is_active : true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">{{ __('admin.is_active') }}</label>
        </div>
    </div>
</div>
