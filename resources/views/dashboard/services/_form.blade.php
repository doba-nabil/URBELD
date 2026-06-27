@php
    $isEdit = isset($service);
@endphp

@if (count(LaravelLocalization::getSupportedLocales()) > 1)
    <!-- Tabs for languages -->
    <ul class="nav nav-tabs mb-4" id="serviceLangTabs" role="tablist">
        @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                    id="srv-{{ $localeCode }}-tab" data-bs-toggle="tab"
                    data-bs-target="#srv-{{ $localeCode }}" type="button" role="tab"
                    aria-controls="srv-{{ $localeCode }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    {{ __('admin.' . $properties['name']) }}
                </button>
            </li>
        @endforeach
    </ul>
@endif

<div class="tab-content mb-4" id="serviceLangTabsContent">
    @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
            id="srv-{{ $localeCode }}" role="tabpanel" aria-labelledby="srv-{{ $localeCode }}-tab">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">{{ __('admin.title') }} ({{ $properties['name'] }}) <span class="text-danger">*</span></label>
                    <input type="text" name="title[{{ $localeCode }}]" class="form-control"
                        value="{{ old('title.'.$localeCode, $isEdit ? $service->getTranslation('title', $localeCode) : '') }}" {{ $localeCode == 'ar' ? 'required' : '' }}>
                    @error('title.'.$localeCode)
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row">
    <div class="col-md-12 mb-3">
        <label class="form-label">{{ __('admin.provider') }} <span class="text-danger">*</span></label>
        <select name="user_id" id="user_id" class="form-select select2" required>
            <option value="">{{ __('admin.select') }}</option>
            @foreach($providers as $provider)
                <option value="{{ $provider->id }}" {{ old('user_id', $isEdit ? $service->user_id : '') == $provider->id ? 'selected' : '' }}>
                    {{ $provider->name }}
                </option>
            @endforeach
        </select>
        @error('user_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('admin.category') }} <span class="text-danger">*</span></label>
        <select name="category_id" id="category_id" class="form-select select2" required>
            <option value="">{{ __('admin.select_category') }}</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $isEdit ? $service->category_id : '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('admin.subcategory') }}</label>
        <select name="sub_category_id" id="sub_category_id" class="form-select select2">
            <option value="">{{ __('admin.select_sub_categories') }}</option>
            @if($isEdit && $service->category)
                @foreach($service->category->children as $sub)
                    <option value="{{ $sub->id }}" {{ old('sub_category_id', $service->sub_category_id) == $sub->id ? 'selected' : '' }}>
                        {{ $sub->name }}
                    </option>
                @endforeach
            @endif
        </select>
        @error('sub_category_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('admin.icon') }}</label>
        <input type="text" name="icon" id="icon-input" class="form-control"
            value="{{ old('icon', $isEdit ? $service->icon : '') }}" placeholder="ex: ti tabler-building">
        @error('icon')
            <span class="text-danger">{{ $message }}</span>
        @enderror
        <small class="text-muted d-block mt-1">{{ __('admin.icon_picker_hint') }}</small>
        <div class="mt-2" id="icon-preview">
            @if (old('icon', $isEdit ? $service->icon : null))
                <i class="{{ old('icon', $isEdit ? $service->icon : '') }} fs-3"></i>
            @endif
        </div>
    </div>

    <div class="col-md-12 mb-3">
        <label class="form-label">{{ __('admin.content') }}</label>
        <textarea name="content" class="form-control editor" rows="10">{{ old('content', $isEdit ? $service->content : '') }}</textarea>
        @error('content')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-md-12 mb-3">
        <label class="form-label">{{ __('admin.image') }}</label>
        <div class="dropzone needsclick" id="dropzone-service"
             data-image-url="{{ $isEdit && $service->getFirstMediaUrl('services') ? $service->getFirstMediaUrl('services') : '' }}">
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
            value="{{ old('sort_order', $isEdit ? $service->sort_order : 0) }}">
        @error('sort_order')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-md-6 mb-3 d-flex align-items-center">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                {{ old('is_active', $isEdit ? $service->is_active : 1) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">{{ __('admin.active') }}</label>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const allCategories = @json($categories);
        const locale = '{{ app()->getLocale() }}';
        
        // Initialize Select2
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('.select2').each(function() {
                var $this = $(this);
                $this.select2({
                    placeholder: $this.find('option[value=""]').text(),
                    allowClear: !$this.prop('required'),
                    dropdownParent: $this.parent()
                });
            });
        }

        const categorySelect = document.getElementById('category_id');
        if (categorySelect) {
            categorySelect.addEventListener('change', function() {
                const categoryId = this.value;
                const subSelect = document.getElementById('sub_category_id');
                subSelect.innerHTML = '<option value="">{{ __('admin.select_sub_categories') }}</option>';
                
                const selectedCategory = allCategories.find(c => c.id == categoryId);
                if (selectedCategory && selectedCategory.children) {
                    selectedCategory.children.forEach(sub => {
                        const option = document.createElement('option');
                        option.value = sub.id;
                        // Handle translatable name object
                        let name = sub.name;
                        if (typeof name === 'object' && name !== null) {
                            name = name[locale] || name['ar'] || Object.values(name)[0];
                        }
                        option.textContent = name;
                        subSelect.appendChild(option);
                    });
                }
                
                // Refresh Select2 for subcategories
                if (typeof $ !== 'undefined' && $.fn.select2) {
                    $(subSelect).trigger('change');
                }
            });
        }
    });
</script>
