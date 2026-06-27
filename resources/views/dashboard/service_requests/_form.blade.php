@php
    $isEdit = isset($serviceRequest);
    $currentCategory = $isEdit ? $serviceRequest->category : null;
@endphp

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('admin.service_seeker') }} <span class="text-danger">*</span></label>
        <select name="user_id" class="form-select" required>
            <option value="">{{ __('admin.choose_service_seeker') }}</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}"
                    {{ old('user_id', $isEdit ? $serviceRequest->user_id : '') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }} ({{ $user->email }})
                </option>
            @endforeach
        </select>
        @error('user_id')<span class="text-danger">{{ $message }}</span>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('admin.service_provider') }} ({{ __('admin.optional') }})</label>
        <select name="provider_id" id="provider_id" class="form-select">
            <option value="">{{ __('admin.choose_service_provider') ?? 'اختر مقدم الخدمة' }}</option>
            @foreach($providers as $provider)
                <option value="{{ $provider->id }}"
                    {{ old('provider_id', $isEdit ? $serviceRequest->provider_id : '') == $provider->id ? 'selected' : '' }}>
                    {{ $provider->name }} ({{ $provider->email }})
                </option>
            @endforeach
        </select>
        @error('provider_id')<span class="text-danger">{{ $message }}</span>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('admin.category') }} <span class="text-danger">*</span></label>
        <select name="category_id" id="category_id" class="form-select" required>
            <option value="">{{ __('admin.choose_category') }}</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" 
                    data-slug="{{ $category->slug }}"
                    {{ old('category_id', $isEdit ? $serviceRequest->category_id : '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')<span class="text-danger">{{ $message }}</span>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('admin.sub_category') ?? 'القسم الفرعي' }}</label>
        <select name="sub_category_id" id="sub_category_id" class="form-select">
            <option value="">{{ __('admin.choose_sub_category') ?? 'اختر القسم الفرعي' }}</option>
            @if($isEdit && $serviceRequest->subCategory)
                <option value="{{ $serviceRequest->sub_category_id }}" selected>{{ $serviceRequest->subCategory->name }}</option>
            @endif
        </select>
        @error('sub_category_id')<span class="text-danger">{{ $message }}</span>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('admin.city') }}</label>
        <select name="city_id" id="city_id" class="form-select">
            <option value="">{{ __('admin.choose_city') }}</option>
            @foreach(\App\Models\City::orderBy('name')->get() as $city)
                <option value="{{ $city->id }}"
                    {{ old('city_id', $isEdit ? $serviceRequest->city_id : '') == $city->id ? 'selected' : '' }}>
                    {{ $city->name }}
                </option>
            @endforeach
        </select>
        @error('city_id')<span class="text-danger">{{ $message }}</span>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('admin.neighborhood') ?? 'الحي' }}</label>
        <input type="text" name="neighborhood" id="neighborhood" class="form-control"
               placeholder="{{ __('admin.enter_neighborhood') ?? 'أدخل الحي' }}"
               value="{{ old('neighborhood', $isEdit ? $serviceRequest->neighborhood : '') }}">
        @error('neighborhood')<span class="text-danger">{{ $message }}</span>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('admin.location') }}</label>
        <input type="text" name="location" class="form-control"
               value="{{ old('location', $isEdit ? $serviceRequest->location : '') }}">
        @error('location')<span class="text-danger">{{ $message }}</span>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('admin.latitude') }}</label>
        <input type="text" name="latitude" class="form-control"
               value="{{ old('latitude', $isEdit ? $serviceRequest->latitude : '') }}">
        @error('latitude')<span class="text-danger">{{ $message }}</span>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('admin.longitude') }}</label>
        <input type="text" name="longitude" class="form-control"
               value="{{ old('longitude', $isEdit ? $serviceRequest->longitude : '') }}">
        @error('longitude')<span class="text-danger">{{ $message }}</span>@enderror
    </div>

    <div class="col-md-12 mb-3">
        <label class="form-label">{{ __('admin.description') }}</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $isEdit ? $serviceRequest->description : '') }}</textarea>
        @error('description')<span class="text-danger">{{ $message }}</span>@enderror
    </div>

    {{-- Contracting Fields --}}
    <div id="contracting-fields" style="display: none;">
        <div class="col-md-12 mb-3">
            <label class="form-label">{{ __('admin.blueprint_description') }}</label>
            <textarea name="blueprint_description" class="form-control" rows="2">{{ old('blueprint_description', $isEdit ? $serviceRequest->blueprint_description : '') }}</textarea>
            @error('blueprint_description')<span class="text-danger">{{ $message }}</span>@enderror
        </div>

        <div class="col-md-12 mb-3">
            <label class="form-label">{{ __('admin.blueprints_label') }}</label>
            <input type="file" name="blueprints[]" class="form-control" multiple accept="image/*,application/pdf">
            @error('blueprints')<span class="text-danger">{{ $message }}</span>@enderror
            @if($isEdit && $serviceRequest->getMedia('blueprints')->count() > 0)
                <div class="mt-2">
                    @foreach($serviceRequest->getMedia('blueprints') as $media)
                        <div class="d-inline-block me-2 mb-2">
                            <a href="{{ $media->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="ti tabler-file"></i> {{ $media->name }}
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Engineering Consulting Fields --}}
    <div id="engineering-fields" style="display: none;">
        <div class="col-md-12 mb-3">
            <label class="form-label">{{ __('admin.site_photos_description') }}</label>
            <textarea name="site_photos_description" class="form-control" rows="2">{{ old('site_photos_description', $isEdit ? $serviceRequest->site_photos_description : '') }}</textarea>
            @error('site_photos_description')<span class="text-danger">{{ $message }}</span>@enderror
        </div>

        <div class="col-md-12 mb-3">
            <label class="form-label">{{ __('admin.site_photos_label') }}</label>
            <input type="file" name="site_photos[]" class="form-control" multiple accept="image/*">
            @error('site_photos')<span class="text-danger">{{ $message }}</span>@enderror
            @if($isEdit && $serviceRequest->getMedia('site_photos')->count() > 0)
                <div class="mt-2">
                    @foreach($serviceRequest->getMedia('site_photos') as $media)
                        <div class="d-inline-block me-2 mb-2">
                            <img src="{{ $media->getUrl() }}" width="80" class="rounded">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Environment Fields --}}
    <div id="environment-fields" style="display: none;">
        <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('admin.activity_type') }}</label>
            <select name="activity_type_id" class="form-select">
                <option value="">{{ __('admin.choose_activity_type') }}</option>
                @foreach($activityTypes as $activityType)
                    <option value="{{ $activityType->id }}"
                        {{ old('activity_type_id', $isEdit ? $serviceRequest->activity_type_id : '') == $activityType->id ? 'selected' : '' }}>
                        {{ $activityType->name }}
                    </option>
                @endforeach
            </select>
            @error('activity_type_id')<span class="text-danger">{{ $message }}</span>@enderror
        </div>

        <div class="col-md-12 mb-3">
            <label class="form-label">وصف الجيران من الجهات الأربعة</label>
            <textarea name="neighbors_description" class="form-control" rows="3">{{ old('neighbors_description', $isEdit ? $serviceRequest->neighbors_description : '') }}</textarea>
            @error('neighbors_description')<span class="text-danger">{{ $message }}</span>@enderror
        </div>
    </div>

    @if($isEdit)
    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('admin.status') }}</label>
        <select name="status" class="form-select">
            <option value="under_review" {{ old('status', $serviceRequest->status) == 'under_review' ? 'selected' : '' }}>{{ __('admin.status_under_review') }}</option>
            <option value="pending" {{ old('status', $serviceRequest->status) == 'pending' ? 'selected' : '' }}>{{ __('admin.status_pending') }}</option>
            <option value="provider_accepted" {{ old('status', $serviceRequest->status) == 'provider_accepted' ? 'selected' : '' }}>{{ __('admin.status_provider_accepted') }}</option>
            <option value="seeker_confirmed_provider" {{ old('status', $serviceRequest->status) == 'seeker_confirmed_provider' ? 'selected' : '' }}>{{ __('admin.status_seeker_confirmed_provider') }}</option>
            <option value="inspection_scheduled" {{ old('status', $serviceRequest->status) == 'inspection_scheduled' ? 'selected' : '' }}>{{ __('admin.status_inspection_scheduled') }}</option>
            <option value="inspection_done" {{ old('status', $serviceRequest->status) == 'inspection_done' ? 'selected' : '' }}>{{ __('admin.status_inspection_done') }}</option>
            <option value="work_completed" {{ old('status', $serviceRequest->status) == 'work_completed' ? 'selected' : '' }}>{{ __('admin.status_work_completed') }}</option>
            <option value="completed" {{ old('status', $serviceRequest->status) == 'completed' ? 'selected' : '' }}>{{ __('admin.status_completed') }}</option>
            <option value="time_expired" {{ old('status', $serviceRequest->status) == 'time_expired' ? 'selected' : '' }}>{{ __('admin.status_time_expired') }}</option>
            <option value="cancelled" {{ old('status', $serviceRequest->status) == 'cancelled' ? 'selected' : '' }}>{{ __('admin.status_cancelled') }}</option>
        </select>
        @error('status')<span class="text-danger">{{ $message }}</span>@enderror
    </div>
    @endif
</div>
