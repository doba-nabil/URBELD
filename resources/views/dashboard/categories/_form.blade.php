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

                <!-- Bulk Request Fields (Only for Main Categories) -->
                <div class="col-md-12 mb-3 bulk-request-field">
                    <label class="form-label">{{ __('admin.bulk_request_title') ?? 'عنوان الطلب الجماعي' }} ({{ $properties['name'] }})</label>
                    <input type="text" name="bulk_request_title[{{ $localeCode }}]" class="form-control"
                           value="{{ old('bulk_request_title.'.$localeCode, $isEdit ? $category->getTranslation('bulk_request_title', $localeCode) : '') }}">
                    @error('bulk_request_title.'.$localeCode)<span class="text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="col-md-12 mb-3 bulk-request-field">
                    <label class="form-label">{{ __('admin.bulk_request_subtitle') ?? 'النص الفرعي للطلب الجماعي' }} ({{ $properties['name'] }})</label>
                    <textarea name="bulk_request_subtitle[{{ $localeCode }}]" class="form-control" rows="2">{{ old('bulk_request_subtitle.'.$localeCode, $isEdit ? $category->getTranslation('bulk_request_subtitle', $localeCode) : '') }}</textarea>
                    @error('bulk_request_subtitle.'.$localeCode)<span class="text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="col-md-12 mb-3 bulk-request-field">
                    <label class="form-label">{{ __('admin.bulk_request_button_text') ?? 'نص زر الطلب الجماعي' }} ({{ $properties['name'] }})</label>
                    <input type="text" name="bulk_request_button_text[{{ $localeCode }}]" class="form-control"
                           value="{{ old('bulk_request_button_text.'.$localeCode, $isEdit ? $category->getTranslation('bulk_request_button_text', $localeCode) : '') }}">
                    @error('bulk_request_button_text.'.$localeCode)<span class="text-danger">{{ $message }}</span>@enderror
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

    <div class="col-md-6 mb-3" id="color-input-container">
        <label class="form-label">{{ __('admin.color') ?? 'اللون' }}</label>
        <input type="color" name="color" class="form-control form-control-color w-100"
               value="{{ old('color', $isEdit ? $category->color : '#064B3B') }}"
               title="Choose category color">
        @error('color')<span class="text-danger">{{ $message }}</span>@enderror
        <small class="text-muted d-block mt-1">يستخدم هذا اللون لتمييز التصنيف الرئيسي في صفحة الطلبات.</small>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const parentSelect = document.getElementById('parent_id');
            const colorContainer = document.getElementById('color-input-container');
            
            function toggleColorInput() {
                if(parentSelect.value) {
                    colorContainer.style.display = 'none';
                } else {
                    colorContainer.style.display = 'block';
                }
            }
            
            if(parentSelect && colorContainer) {
                parentSelect.addEventListener('change', toggleColorInput);
                toggleColorInput();
            }
        });
    </script>


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

    <div class="col-md-6 mb-3 d-flex flex-column gap-3">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                   id="is_active"
                {{ old('is_active', $isEdit ? $category->is_active : 1) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">{{ __('admin.active') }}</label>
        </div>

        <div id="sub-settings-container" class="d-flex flex-column gap-3" style="display: none;">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="supports_tenders" value="1"
                       id="supports_tenders"
                    {{ old('supports_tenders', $isEdit ? $category->supports_tenders : 0) ? 'checked' : '' }}>
                <label class="form-check-label" for="supports_tenders">{{ __('admin.supports_tenders') }}</label>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-3 d-flex flex-column gap-3" id="home-settings-container">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="show_in_home" value="1"
                   id="show_in_home"
                {{ old('show_in_home', $isEdit ? $category->show_in_home : 1) ? 'checked' : '' }}>
            <label class="form-check-label" for="show_in_home">{{ __('admin.show_in_home') }}</label>
        </div>

        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="supports_supply_requests" value="1"
                   id="supports_supply_requests"
                {{ old('supports_supply_requests', $isEdit ? $category->supports_supply_requests : 0) ? 'checked' : '' }}>
            <label class="form-check-label" for="supports_supply_requests">{{ __('admin.supports_supply_requests') ?? 'تصنيف للموردين' }}</label>
            <small class="text-muted d-block mt-1">عند التفعيل، سيظهر هذا التصنيف للموردين فقط في الموقع والتطبيق.</small>
        </div>

        <div class="form-check form-switch" id="full-width-container">
            <input class="form-check-input" type="checkbox" name="is_full_width" value="1"
                   id="is_full_width"
                {{ old('is_full_width', $isEdit ? $category->is_full_width : 0) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_full_width">{{ __('admin.is_full_width') }}</label>
        </div>

        <div id="sort-order-container">
            <label class="form-label">{{ __('admin.sort_order') }}</label>
            <input type="number" name="sort_order" class="form-control"
                   value="{{ old('sort_order', $isEdit ? $category->sort_order : 0) }}">
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const parentSelectElement = document.getElementById('parent_id');
        const homeSettingsContainer = document.getElementById('home-settings-container');
        const subSettingsContainer = document.getElementById('sub-settings-container');
        const bulkRequestFields = document.querySelectorAll('.bulk-request-field');
        
        const supportsSupplyRequestsCheckbox = document.getElementById('supports_supply_requests');
        const fullWidthContainer = document.getElementById('full-width-container');
        const sortOrderContainer = document.getElementById('sort-order-container');
        
        function toggleCategorySettings() {
            if(parentSelectElement) {
                if(parentSelectElement.value) {
                    // It is a subcategory (Hide Home, Hide Bulk, Show Sub)
                    if (homeSettingsContainer) {
                        homeSettingsContainer.classList.add('d-none');
                        homeSettingsContainer.classList.remove('d-flex');
                    }
                    if (subSettingsContainer) {
                        subSettingsContainer.classList.remove('d-none');
                        subSettingsContainer.classList.add('d-flex');
                    }
                    bulkRequestFields.forEach(el => {
                        el.classList.add('d-none');
                    });
                } else {
                    // It is a main category (Show Home, Show Bulk, Hide Sub)
                    if (homeSettingsContainer) {
                        homeSettingsContainer.classList.remove('d-none');
                        homeSettingsContainer.classList.add('d-flex');
                    }
                    if (subSettingsContainer) {
                        subSettingsContainer.classList.add('d-none');
                        subSettingsContainer.classList.remove('d-flex');
                    }
                    bulkRequestFields.forEach(el => {
                        el.classList.remove('d-none');
                    });
                }
            }
        }
        
        function toggleSupplyRequestSettings() {
            if(supportsSupplyRequestsCheckbox) {
                if(supportsSupplyRequestsCheckbox.checked) {
                    if(fullWidthContainer) fullWidthContainer.classList.add('d-none');
                    if(sortOrderContainer) sortOrderContainer.classList.add('d-none');
                } else {
                    if(fullWidthContainer) fullWidthContainer.classList.remove('d-none');
                    if(sortOrderContainer) sortOrderContainer.classList.remove('d-none');
                }
            }
        }
        
        if(parentSelectElement) {
            parentSelectElement.addEventListener('change', toggleCategorySettings);
            toggleCategorySettings();
        }
        
        if(supportsSupplyRequestsCheckbox) {
            supportsSupplyRequestsCheckbox.addEventListener('change', toggleSupplyRequestSettings);
            toggleSupplyRequestSettings();
        }
    });
</script>
