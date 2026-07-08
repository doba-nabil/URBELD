@php
    $isEdit = isset($membership) || isset($provider);
    $item = $provider ?? $membership;
@endphp

<!-- Section 1: Basic Information -->
<div class="mb-4">
    <h5 class="mb-3 text-primary"><i class="ti tabler-user me-2"></i>{{ __('admin.basic_info') }}</h5>
    <div class="row">
        <!-- Personal Photo on the left or top -->
        <div class="col-md-4 mb-3 text-center">
            <label class="form-label d-block text-start">{{ __('admin.personal_photo') }}</label>
            <div class="dropzone needsclick border-dashed" id="dropzone-personal" style="min-height: 200px;" 
                 data-image-url="{{ (isset($provider) && $provider->getFirstMediaUrl('personal_photo')) ? $provider->getFirstMediaUrl('personal_photo') : '' }}">
                <div class="dz-message needsclick">
                    <i class="ti tabler-upload fs-1"></i>
                    <p class="mt-2 text-muted">{{ __('admin.Drop files here or click to upload') }}</p>
                </div>
            </div>
            @error('personal_photo')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>

        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">{{ __('admin.name_ar') }} <span class="text-danger">*</span></label>
                    <input type="text" name="name[ar]" class="form-control"
                        value="{{ old('name.ar', ($membership && $membership->getTranslation('name', 'ar')) ? $membership->getTranslation('name', 'ar') : (isset($provider) ? $provider->name : '')) }}" required>
                    @error('name.ar')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('admin.email') }} <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control"
                        value="{{ old('email', isset($provider) ? $provider->email : '') }}" required>
                    @error('email')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('admin.phone') }} <span class="text-danger">*</span></label>
                    <input type="text" name="phone" class="form-control"
                        value="{{ old('phone', isset($provider) ? $provider->phone : '') }}" required>
                    @error('phone')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">{{ __('admin.bio') }}</label>
                    <textarea name="bio" class="form-control" rows="2" placeholder="{{ __('admin.bio_placeholder') ?? '' }}">{{ old('bio', isset($provider) ? $provider->bio : '') }}</textarea>
                    @error('bio')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
                <!-- Password Fields -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('admin.password') }} {{ !$isEdit ? '*' : '' }}</label>
                    <input type="password" name="password" class="form-control"
                        placeholder="{{ $isEdit ? __('admin.leave_blank_keep_password') : '' }}" {{ !$isEdit ? 'required' : '' }}>
                    @error('password')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('admin.confirm_password') }} {{ !$isEdit ? '*' : '' }}</label>
                    <input type="password" name="password_confirmation" class="form-control" {{ !$isEdit ? 'required' : '' }}>
                </div>
            </div>
        </div>
    </div>
</div>

<hr class="my-4">

<!-- Section 2: Account & Location -->
<div class="mb-4">
    <h5 class="mb-3 text-primary"><i class="ti tabler-settings me-2"></i>{{ __('admin.account_and_location') }}</h5>
    <div class="row">
        @if(isset($type) && $type)
            <input type="hidden" name="type" value="{{ $type }}" id="membership_type">
        @elseif(isset($membership))
            <input type="hidden" name="type" value="{{ $membership->type }}" id="membership_type">
        @else
            <div class="col-md-3 mb-3">
                <label class="form-label">{{ __('admin.membership_type') }} <span class="text-danger">*</span></label>
                <select name="type" class="form-select" id="membership_type" required>
                    <option value="individual" {{ old('type', ($membership ? $membership->type : 'individual')) == 'individual' ? 'selected' : '' }}>
                        {{ __('admin.individual') }}
                    </option>
                    <option value="company" {{ old('type', ($membership ? $membership->type : '')) == 'company' ? 'selected' : '' }}>
                        {{ __('admin.company') }}
                    </option>
                    <option value="supplier" {{ old('type', ($membership ? $membership->type : '')) == 'supplier' ? 'selected' : '' }}>
                        {{ __('admin.supplier') ?? 'مورد' }}
                    </option>
                </select>
                @error('type')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="col-md-3 mb-3">
                <label class="form-label">{{ __('admin.company_classification') ?? 'التصنيف / الحجم' }}</label>
                <select name="classification_id" class="form-select">
                    <option value="">{{ __('admin.none') ?? 'لا يوجد' }}</option>
                    @foreach($classifications ?? [] as $class)
                        <option value="{{ $class->id }}" {{ old('classification_id', (isset($provider) ? $provider->classification_id : '')) == $class->id ? 'selected' : '' }}>
                            {{ $class->name }} ({{ $class->type == 'company' ? 'شركة' : 'مورد' }})
                        </option>
                    @endforeach
                </select>
                @error('classification_id')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-3 mb-3 d-flex align-items-end">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="is_trusted" value="1" id="is_trusted"
                        {{ old('is_trusted', (isset($provider) && $provider->is_trusted)) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_trusted">{{ __('admin.is_trusted') ?? 'عضو موثوق' }}</label>
                </div>
            </div>
        @endif
        <div class="col-md-3 mb-3">
            <label class="form-label">{{ __('admin.years_of_experience') }}</label>
            <input type="number" name="years_of_experience" class="form-control" min="0"
                value="{{ old('years_of_experience', isset($provider) ? $provider->years_of_experience : 0) }}">
            @error('years_of_experience')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">{{ __('admin.sort_order') }}</label>
            <input type="number" name="sort_order" class="form-control" min="0"
                value="{{ old('sort_order', ($membership ? $membership->sort_order : 0) ) }}">
            @error('sort_order')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">{{ __('admin.account_status') }}</label>
            <select name="active" class="form-select">
                <option value="active" {{ old('active', (isset($provider) ? $provider->active : 'active')) == 'active' ? 'selected' : '' }}>
                    {{ __('admin.active') }}
                </option>
                <option value="pending" {{ old('active', (isset($provider) ? $provider->active : '')) == 'pending' ? 'selected' : '' }}>
                    {{ __('admin.pending') }}
                </option>
                <option value="blocked" {{ old('active', (isset($provider) ? $provider->active : '')) == 'blocked' ? 'selected' : '' }}>
                    {{ __('admin.blocked') }}
                </option>
            </select>
            @error('active')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-md-3 mb-3 d-flex align-items-end">
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                    {{ old('is_active', ($membership ? $membership->is_active : 1)) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">{{ __('admin.active_status') }}</label>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('admin.country') }}</label>
            <select name="country_id" class="form-select select2" id="membership_country_id">
                <option value="">{{ __('admin.select_country') }}</option>
                @foreach ($countries ?? [] as $country)
                    <option value="{{ $country->id }}"
                        {{ old('country_id', ($membership && $membership->country_id) ? $membership->country_id : (($provider && $provider->city) ? $provider->city->country_id : null)) == $country->id ? 'selected' : '' }}>
                        {{ $country->name }}
                    </option>
                @endforeach
            </select>
            @error('country_id')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('admin.city') }}</label>
            <select name="city_id" class="form-select select2" id="membership_city_id">
                <option value="">{{ __('admin.select_city') }}</option>
                @if ($isEdit && (($membership && $membership->country_id) || ($provider && $provider->city)))
                    @foreach ($cities ?? [] as $city)
                        <option value="{{ $city->id }}"
                            {{ old('city_id', ($membership && $membership->city_id) ? $membership->city_id : ($provider ? $provider->city_id : null)) == $city->id ? 'selected' : '' }}>
                            {{ $city->name }}
                        </option>
                    @endforeach
                @endif
            </select>
            @error('city_id')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<hr class="my-4">

<!-- Section Subscription -->
<div class="mb-4">
    <h5 class="mb-3 text-primary"><i class="ti tabler-package me-2"></i>{{ __('admin.subscription_details') ?? 'تفاصيل الاشتراك' }}</h5>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">{{ __('admin.subscription_package') }}</label>
            <select name="subscription_package_id" class="form-select select2">
                <option value="">{{ __('admin.no_package') ?? 'بدون باقة' }}</option>
                @foreach ($packages ?? [] as $package)
                    <option value="{{ $package->id }}"
                        {{ old('subscription_package_id', (isset($provider) && $provider->subscription_package_id == $package->id) ? 'selected' : '') }}>
                        {{ $package->name }} ({{ $package->badge_name }})
                    </option>
                @endforeach
            </select>
            @error('subscription_package_id')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">{{ __('admin.subscription_start') }}</label>
            <input type="date" name="subscription_start_at" class="form-control"
                value="{{ old('subscription_start_at', (isset($provider) && $provider->subscription_start_at) ? $provider->subscription_start_at->format('Y-m-d') : '') }}">
            @error('subscription_start_at')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">{{ __('admin.subscription_end') }}</label>
            <input type="date" name="subscription_end_at" class="form-control"
                value="{{ old('subscription_end_at', (isset($provider) && $provider->subscription_end_at) ? $provider->subscription_end_at->format('Y-m-d') : '') }}">
            @error('subscription_end_at')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<hr class="my-4">

<!-- Section 3: Professional Documents & Categories (Dynamic) -->
<div class="mb-4">
    <div id="individual-fields" style="display: {{ old('type', ($membership ? $membership->type : 'individual')) == 'individual' ? 'block' : 'none' }};">
        <h5 class="mb-3 text-primary"><i class="ti tabler-id me-2"></i>{{ __('admin.individual_professional_info') }}</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">{{ __('admin.id_front') }}</label>
                <div class="dropzone needsclick border-dashed" id="dropzone-id-front" style="min-height: 150px;" 
                     data-image-url="{{ ($membership && $membership->getFirstMediaUrl('id_front')) ? $membership->getFirstMediaUrl('id_front') : (($provider && $provider->getFirstMediaUrl('id_front')) ? $provider->getFirstMediaUrl('id_front') : '') }}">
                    <div class="dz-message needsclick">
                        <i class="ti tabler-upload pe-2"></i>{{ __('admin.id_front_hint') ?? __('admin.Drop files here or click to upload') }}
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">{{ __('admin.id_back') }}</label>
                <div class="dropzone needsclick border-dashed" id="dropzone-id-back" style="min-height: 150px;" 
                     data-image-url="{{ ($membership && $membership->getFirstMediaUrl('id_back')) ? $membership->getFirstMediaUrl('id_back') : (($provider && $provider->getFirstMediaUrl('id_back')) ? $provider->getFirstMediaUrl('id_back') : '') }}">
                    <div class="dz-message needsclick">
                        <i class="ti tabler-upload pe-2"></i>{{ __('admin.id_back_hint') ?? __('admin.Drop files here or click to upload') }}
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label">{{ __('admin.main_category') }}</label>
                <select name="main_category_id" class="form-select select2" id="individual_main_category_id" {{ (isset($provider) && $provider->active === 'active') ? 'disabled' : '' }}>
                    <option value="">{{ __('admin.select_main_category') }}</option>
                    @foreach ($categories ?? [] as $category)
                        <option value="{{ $category->id }}"
                            {{ old('main_category_id', ($membership && $membership->main_category_id) ? $membership->main_category_id : (($provider && $provider->categories->count() > 0) ? $provider->categories->first()->id : null)) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">{{ __('admin.sub_categories') }}</label>
                <select name="sub_categories[]" class="form-select select2" id="individual_sub_categories" multiple {{ (isset($provider) && $provider->active === 'active') ? 'disabled' : '' }}>
                    @if ($isEdit && ($membership && $membership->main_category_id || $provider && $provider->categories->count() > 0))
                        @php
                            $selectedMainCat = ($membership && $membership->main_category_id) ? $membership->mainCategory : ($provider ? $provider->categories->first() : null);
                            $existingSubCatIds = ($membership && $membership->subCategories->count() > 0) ? $membership->subCategories->pluck('id')->toArray() : ($provider ? $provider->categories->pluck('id')->toArray() : []);
                        @endphp
                        @if ($selectedMainCat)
                            @foreach ($selectedMainCat->children ?? [] as $subCategory)
                                <option value="{{ $subCategory->id }}"
                                    {{ in_array($subCategory->id, old('sub_categories', $existingSubCatIds)) ? 'selected' : '' }}>
                                    {{ $subCategory->name }}
                                </option>
                            @endforeach
                        @endif
                    @endif
                </select>
            </div>
        </div>
    </div>

    <div id="company-fields" style="display: {{ old('type', ($membership ? $membership->type : '')) == 'company' ? 'block' : 'none' }};">
        <h5 class="mb-3 text-primary"><i class="ti tabler-building me-2"></i>{{ __('admin.company_professional_info') }}</h5>
        <div class="row">
            <div class="col-md-7 mb-3">
                <label class="form-label">{{ __('admin.commercial_registration') }}</label>
                <div class="dropzone needsclick border-dashed" id="dropzone-commercial" style="min-height: 150px;" 
                     data-image-url="{{ ($membership && $membership->getFirstMediaUrl('commercial_registration')) ? $membership->getFirstMediaUrl('commercial_registration') : (($provider && $provider->getFirstMediaUrl('commercial_registration')) ? $provider->getFirstMediaUrl('commercial_registration') : '') }}">
                    <div class="dz-message needsclick">
                        <i class="ti tabler-upload pe-2"></i>{{ __('admin.cr_hint') ?? __('admin.Drop files here or click to upload') }}
                    </div>
                </div>
            </div>
            <div class="col-md-5 mb-3">
                <label class="form-label">{{ __('admin.employees_count') }}</label>
                <input type="number" name="employees_count" class="form-control" min="1"
                    value="{{ old('employees_count', ($membership ? $membership->employees_count : '')) }}">
                
                <div class="mt-4">
                    <label class="form-label">{{ __('admin.main_category') }}</label>
                    <select name="main_category_id" class="form-select select2" id="main_category_id" {{ (isset($provider) && $provider->active === 'active') ? 'disabled' : '' }}>
                        <option value="">{{ __('admin.select_main_category') }}</option>
                        @foreach ($categories ?? [] as $category)
                            <option value="{{ $category->id }}"
                                {{ old('main_category_id', ($membership && $membership->main_category_id) ? $membership->main_category_id : (($provider && $provider->categories->count() > 0) ? $provider->categories->first()->id : null)) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-label">{{ __('admin.sub_categories') }}</label>
                <select name="sub_categories[]" class="form-select select2" id="sub_categories" multiple {{ (isset($provider) && $provider->active === 'active') ? 'disabled' : '' }}>
                    @if ($isEdit && ($membership && $membership->main_category_id || $provider && $provider->categories->count() > 0))
                        @php
                            $selectedMainCat = ($membership && $membership->main_category_id) ? $membership->mainCategory : ($provider ? $provider->categories->first() : null);
                            $existingSubCatIds = ($membership && $membership->subCategories->count() > 0) ? $membership->subCategories->pluck('id')->toArray() : ($provider ? $provider->categories->pluck('id')->toArray() : []);
                        @endphp
                        @if ($selectedMainCat)
                            @foreach ($selectedMainCat->children ?? [] as $subCategory)
                                <option value="{{ $subCategory->id }}"
                                    {{ in_array($subCategory->id, old('sub_categories', $existingSubCatIds)) ? 'selected' : '' }}>
                                    {{ $subCategory->name }}
                                </option>
                            @endforeach
                        @endif
                    @endif
                </select>
            </div>
        </div>
    </div>
</div>

<hr class="my-4">

<!-- Section 4: Certificates -->
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="text-primary mb-0"><i class="ti tabler-certificate me-2"></i>{{ __('admin.certificates') }}</h5>
        @if(!(isset($provider) && $provider->active === 'active'))
        <button type="button" class="btn btn-sm btn-label-primary" id="add-certificate">
            <i class="ti tabler-plus me-1"></i> {{ __('admin.add_certificate') }}
        </button>
        @endif
    </div>
    
    @php
        $existingCertificates = collect();
        if ($membership && $membership->certificates->count() > 0) {
            foreach ($membership->certificates as $cert) {
                $existingCertificates->push($cert);
            }
        } 
        
        if ($provider) {
            $mediaCerts = $provider->getMedia('certificates');
            foreach ($mediaCerts as $media) {
                $existingCertificates->push((object)[
                    'id' => null,
                    'name' => $media->name,
                    'media' => $media
                ]);
            }
        }
    @endphp

    <div id="certificates-container">
        @if ($existingCertificates->count() > 0)
            @foreach ($existingCertificates as $index => $certificate)
                <div class="certificate-item card border shadow-none p-3 mb-3" data-index="{{ $index }}">
                    <div class="row align-items-center">
                        <div class="col-md-5 mb-3 mb-md-0">
                            <label class="form-label">{{ __('admin.certificate_name') }}</label>
                            <input type="text" name="certificates[{{ $index }}][name]"
                                class="form-control" value="{{ $certificate->name }}" placeholder="{{ __('admin.certificate_name') }}" required>
                            @php
                                $certIdValue = '';
                                if ($certificate instanceof \App\Models\MembershipCertificate) {
                                    $certIdValue = $certificate->id;
                                } elseif (isset($certificate->media) && $certificate->media) {
                                    $certIdValue = 'media_' . $certificate->media->id;
                                }
                            @endphp
                            <input type="hidden" name="certificates[{{ $index }}][id]" value="{{ $certIdValue }}">
                        </div>
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label">{{ __('admin.certificate_image') }}</label>
                            @php
                                $certUrl = '';
                                if (isset($certificate->id) && $certificate->id) {
                                    $certUrl = $certificate->getFirstMediaUrl('certificate_image');
                                } elseif (isset($certificate->media)) {
                                    $certUrl = $certificate->media->getUrl();
                                }
                            @endphp
                            <div class="dropzone needsclick certificate-dropzone p-2" id="dropzone-cert-{{ $index }}"
                                data-image-url="{{ $certUrl }}" style="min-height: 80px;">
                                <div class="dz-message needsclick py-2 text-center">
                                    <small class="text-muted">{{ __('admin.Drop files here or click to upload') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1 text-center">
                            @if(!(isset($provider) && $provider->active === 'active'))
                            <button type="button" class="btn btn-icon btn-label-danger remove-certificate mt-md-4">
                                <i class="ti tabler-trash"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

<!-- Works (Portfolio) -->
<div class="card-header border-bottom mb-4 pb-3 mt-5 px-0 d-flex justify-content-between align-items-center">
    <h5 class="card-title mb-0 text-primary">
        <i class="ti tabler-briefcase me-2"></i> {{ __('admin.works_portfolio') ?? 'الأعمال السابقة (البورتفوليو)' }}
    </h5>
    <button type="button" class="btn btn-sm btn-outline-primary" id="add-work">
        <i class="ti tabler-plus me-1"></i> {{ __('admin.add_work') ?? 'إضافة عمل' }}
    </button>
</div>

<div class="row">
    @php
        $existingWorks = collect();
        if (isset($provider) && $provider->works) {
            $existingWorks = $provider->works;
        }
    @endphp

    <div id="works-container" class="col-12">
        @if ($existingWorks->count() > 0)
            @foreach ($existingWorks as $index => $work)
                <div class="work-item card border shadow-none p-3 mb-3" data-index="{{ $index }}">
                    <div class="row align-items-start">
                        <div class="col-md-5 mb-3 mb-md-0">
                            <label class="form-label">{{ __('admin.work_title') ?? 'اسم العمل' }}</label>
                            <input type="text" name="works[{{ $index }}][title]"
                                class="form-control mb-3" value="{{ $work->title }}" placeholder="{{ __('admin.work_title') ?? 'اسم العمل' }}" required>
                            
                            <label class="form-label">{{ __('admin.work_description') ?? 'وصف العمل' }}</label>
                            <textarea name="works[{{ $index }}][description]" class="form-control" rows="3" placeholder="{{ __('admin.work_description') ?? 'وصف العمل' }}">{{ $work->description }}</textarea>
                            <input type="hidden" name="works[{{ $index }}][id]" value="{{ $work->id }}">
                        </div>
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label">{{ __('admin.work_images') ?? 'صور العمل (يمكن رفع أكثر من صورة)' }}</label>
                            <input type="file" name="works[{{ $index }}][images][]" multiple class="form-control mb-3" accept="image/*">
                            
                            @if($work->hasMedia('work_images'))
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    @foreach($work->getMedia('work_images') as $media)
                                        <div class="position-relative border p-1 rounded overflow-hidden" style="width: 80px; height: 80px;">
                                            <img src="{{ $media->getUrl() }}" alt="Work Image" class="w-100 h-100 object-fit-cover">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="col-md-1 text-center">
                            <button type="button" class="btn btn-icon btn-label-danger remove-work mt-md-4">
                                <i class="ti tabler-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

<!-- Sticky Bottom Save Bar (Optional, but let's keep it consistent) -->
<div class="row mt-5 pt-3 border-top">
    <div class="col-12 text-end">
        <button type="submit" class="btn btn-primary btn-lg px-5">
            <i class="ti tabler-device-floppy me-1"></i> {{ __('admin.save') }}
        </button>
    </div>
</div>
