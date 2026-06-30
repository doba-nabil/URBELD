@php
    $isEdit = isset($package);
@endphp

@if (count(LaravelLocalization::getSupportedLocales()) > 1)
    <!-- Tabs for languages -->
    <ul class="nav nav-tabs mb-4" id="packageLangTabs" role="tablist">
        @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                    id="pkg-{{ $localeCode }}-tab" data-bs-toggle="tab"
                    data-bs-target="#pkg-{{ $localeCode }}" type="button" role="tab"
                    aria-controls="pkg-{{ $localeCode }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    {{ __('admin.' . $properties['name']) }}
                </button>
            </li>
        @endforeach
    </ul>
@endif

<div class="tab-content mb-4" id="packageLangTabsContent">
    @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
            id="pkg-{{ $localeCode }}" role="tabpanel" aria-labelledby="pkg-{{ $localeCode }}-tab">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('admin.name') }} ({{ $properties['name'] }}) <span class="text-danger">*</span></label>
                    <input type="text" name="name[{{ $localeCode }}]" class="form-control"
                           value="{{ old('name.'.$localeCode, $isEdit ? $package->getTranslation('name',$localeCode) : '') }}"
                           {{ $localeCode == 'ar' ? 'required' : '' }}>
                    @error('name.'.$localeCode)<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('admin.badge_name') }} ({{ $properties['name'] }})</label>
                    <input type="text" name="badge_name[{{ $localeCode }}]" class="form-control"
                           value="{{ old('badge_name.'.$localeCode, $isEdit ? $package->getTranslation('badge_name',$localeCode) : '') }}">
                    @error('badge_name.'.$localeCode)<span class="text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>
    @endforeach
</div>

 

<div class="row">

    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('admin.description') }}</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $isEdit ? $package->description : '') }}</textarea>
        @error('description')<span class="text-danger">{{ $message }}</span>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('admin.price') }}</label>
        <input type="number" name="price" class="form-control" step="0.01" min="0"
               value="{{ old('price', $isEdit ? $package->price : '') }}" required>
        @error('price')<span class="text-danger">{{ $message }}</span>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('admin.duration') }} ({{ __('admin.days') }})</label>
        <input type="number" name="duration_days" class="form-control" min="1"
               value="{{ old('duration_days', $isEdit ? $package->duration_days : '30') }}" required>
        @error('duration_days')<span class="text-danger">{{ $message }}</span>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('admin.sort_order_package') }}</label>
        <input type="number" name="sort_order" class="form-control" min="0"
               value="{{ old('sort_order', $isEdit ? $package->sort_order : '0') }}">
        @error('sort_order')<span class="text-danger">{{ $message }}</span>@enderror
    </div>

    <div class="col-12 mb-3">
        <label class="form-label">{{ __('admin.features') }}</label>
        <div id="features-container">
            @php
                $features = old('features', $isEdit && $package->features ? (is_array($package->features) ? $package->features : json_decode($package->features, true)) : []);
                if (empty($features)) {
                    $features = [''];
                }
            @endphp
            @foreach($features as $index => $feature)
                <div class="input-group mb-2 feature-item">
                    <input type="text" name="features[]" class="form-control" value="{{ $feature }}" placeholder="{{ __('admin.feature') }}">
                    <button type="button" class="btn btn-danger remove-feature">
                        <i class="icon-base ti tabler-trash"></i>
                    </button>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-sm btn-primary mt-2" id="add-feature">
            <i class="icon-base ti tabler-plus"></i> {{ __('admin.add_feature') }}
        </button>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">{{ __('admin.works_limit') }}</label>
        <input type="number" name="works_limit" class="form-control" min="0"
               value="{{ old('works_limit', $isEdit ? $package->works_limit : '0') }}">
        <small class="text-muted">{{ __('admin.works_limit_hint') }}</small>
        @error('works_limit')<span class="text-danger">{{ $message }}</span>@enderror
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">{{ __('admin.max_services') }}</label>
        <input type="number" name="max_services" class="form-control" min="0"
               value="{{ old('max_services', $isEdit ? $package->max_services : '0') }}">
        <small class="text-muted">{{ __('admin.max_services_hint') }}</small>
        @error('max_services')<span class="text-danger">{{ $message }}</span>@enderror
    </div>

    <div class="col-md-2 mb-3">
        <label class="form-label">{{ __('admin.badge_color') ?? 'لون الشارة' }}</label>
        <input type="color" name="color" class="form-control form-control-color w-100" 
               value="{{ old('color', $isEdit ? ($package->color ?: '#014D40') : '#014D40') }}" title="{{ __('admin.choose_color') ?? 'اختر اللون' }}">
        @error('color')<span class="text-danger">{{ $message }}</span>@enderror
    </div>

    <div class="col-md-2 mb-3 d-flex align-items-center">
        <div class="form-check form-switch pt-3">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                   {{ old('is_active', $isEdit ? $package->is_active : true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">{{ __('admin.is_active') }}</label>
        </div>
    </div>

    <div class="col-md-2 mb-3 d-flex align-items-center">
        <div class="form-check form-switch pt-3">
            <input class="form-check-input" type="checkbox" name="is_recommended" value="1" id="is_recommended"
                   {{ old('is_recommended', $isEdit ? $package->is_recommended : false) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_recommended">موصى به</label>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add feature
    document.getElementById('add-feature').addEventListener('click', function() {
        const container = document.getElementById('features-container');
        const newItem = document.createElement('div');
        newItem.className = 'input-group mb-2 feature-item';
        newItem.innerHTML = `
            <input type="text" name="features[]" class="form-control" placeholder="{{ __('admin.feature') }}">
            <button type="button" class="btn btn-danger remove-feature">
                <i class="icon-base ti tabler-trash"></i>
            </button>
        `;
        container.appendChild(newItem);
    });

    // Remove feature
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-feature')) {
            const container = document.getElementById('features-container');
            if (container.children.length > 1) {
                e.target.closest('.feature-item').remove();
            }
        }
    });
});
</script>
