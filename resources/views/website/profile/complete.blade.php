@extends('website.layouts.profile')

@section('profile-content')
    @php
        $user = auth()->user();
        $hasId = $user->getFirstMediaUrl('id_front') || $user->getFirstMediaUrl('commercial_registration');
        $isActive = ($user->active === 'active' || $user->active === '1' || $user->active === 1);
        $isPendingWithId = ($user->active === 'pending' || $user->active === '0' || $user->active === 0) && $hasId;
        
        // Locked means view-only mode
        $isLocked = $isActive || $isPendingWithId;
        
        $userCategoryIds = $user->categories->pluck('id')->toArray();
        $userMainCategoryId = $user->categories->whereNull('parent_id')->first()->id ?? null;
        $cities = \App\Models\City::orderBy('name')->get();
    @endphp

    <!-- Certificates Section -->
    <div class="certificates-section-new">
        <div class="container">
            @if ($isActive)
                <div class="alert alert-success mb-4 shadow-sm border-0 d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2 fs-4 text-success"></i>
                    <div>
                        <strong>{{ __('website.membership_active_alert') }}</strong>
                        {{ __('website.membership_active_desc') }}
                    </div>
                </div>
            @elseif ($isPendingWithId)
                <div class="alert alert-warning mb-4 shadow-sm border-0 d-flex align-items-center">
                    <i class="bi bi-info-circle-fill me-2 fs-4"></i>
                    <div>
                        <strong>{{ __('website.membership_review_alert') }}</strong>
                        {{ __('website.membership_review_desc') }}
                    </div>
                </div>
            @endif

            <div class="certificates-header">
                <i class="bi bi-patch-check certificate-header-icon"></i>
                <h2 class="certificates-title">{{ __('website.attached_files') }}</h2>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <ul class="certificates-simple-list mb-4">
                <!-- ID/Registration -->
                @if (auth()->user()->membership_type == 'company')
                    @if (auth()->user()->getFirstMediaUrl('commercial_registration'))
                        <li class="certificate-list-item mb-2">
                            <span class="badge bg-primary me-2">{{ __('website.commercial_registration') }}</span>
                            <span class="certificate-list-text">
                                <a href="{{ auth()->user()->getFirstMediaUrl('commercial_registration') }}"
                                    target="_blank">{{ __('website.view_commercial_registration') }}</a>
                            </span>
                        </li>
                    @endif
                @else
                    @if (auth()->user()->getFirstMediaUrl('id_front'))
                        <li class="certificate-list-item mb-2">
                            <span class="badge bg-primary me-2">{{ __('website.id_front') }}</span>
                            <span class="certificate-list-text">
                                <a href="{{ auth()->user()->getFirstMediaUrl('id_front') }}" target="_blank">{{ __('website.view_id_front') }}</a>
                            </span>
                            @if (!$isLocked)
                                <form
                                    action="{{ route('profile.media.destroy', auth()->user()->getFirstMedia('id_front')->id) }}"
                                    method="POST" class="d-inline ms-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="btn btn-sm btn-link text-danger p-0 border-0 bg-transparent"
                                        {{ $isLocked ? 'disabled' : '' }}><i class="bi bi-trash"></i></button>
                                </form>
                            @endif
                        </li>
                    @endif
                    @if (auth()->user()->getFirstMediaUrl('id_back'))
                        <li class="certificate-list-item mb-2">
                            <span class="badge bg-primary me-2">{{ __('website.id_back') }}</span>
                            <span class="certificate-list-text">
                                <a href="{{ auth()->user()->getFirstMediaUrl('id_back') }}" target="_blank">{{ __('website.view_id_back') }}</a>
                            </span>
                            @if (!$isLocked)
                                <form
                                    action="{{ route('profile.media.destroy', auth()->user()->getFirstMedia('id_back')->id) }}"
                                    method="POST" class="d-inline ms-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="btn btn-sm btn-link text-danger p-0 border-0 bg-transparent"
                                        {{ $isLocked ? 'disabled' : '' }}><i class="bi bi-trash"></i></button>
                                </form>
                            @endif
                        </li>
                    @endif
                @endif

                <!-- Certificates -->
                @forelse(auth()->user()->getMedia('certificates') as $media)
                    <li class="certificate-list-item mb-2">
                        <span class="badge bg-success me-2">{{ __('website.certificate') }}</span>
                        <span class="certificate-list-text">
                            <a href="{{ $media->getUrl() }}" target="_blank">{{ $media->name ?? $media->file_name }}</a>
                        </span>
                    </li>
                @empty
                    @if (auth()->user()->getMedia('certificates')->isEmpty() &&
                            empty(auth()->user()->getFirstMediaUrl('id_front')) &&
                            empty(auth()->user()->getFirstMediaUrl('commercial_registration')))
                        <li class="certificate-list-item text-muted">
                            <span class="certificate-bullet"></span>
                            <span class="certificate-list-text">{{ __('website.no_files_attached') }}</span>
                        </li>
                    @endif
                @endforelse
            </ul>

            <!-- Upload Form -->

                <div class="card border-0 shadow-sm mt-4 w-100">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4 fw-bold text-primary">{{ $isLocked ? __('website.current_professional_data') : __('website.update_professional_data') }}</h5>
                        <form action="{{ route('profile.complete.store') }}" method="POST" enctype="multipart/form-data"
                            class="login-form">
                            @csrf

                            <!-- Subscription Packages -->
                            @if ($isSubscriptionEnabled && count($packages) > 0)
                                <div class="mb-5">
                                    <h5 class="card-title mb-4 fw-bold text-primary">{{ $isLocked ? __('website.current_subscription_package') : (__('website.choose_subscription_package') ?? 'اختر باقة الاشتراك') }} @if(!$isLocked)<span class="text-danger">*</span>@endif</h5>
                                    <div class="row g-4">
                                        @foreach ($packages as $pkg)
                                            @php
                                                $isUserPkg = ($user->subscription_package_id == $pkg->id);
                                                // If locked and user has a package, only show their package
                                                if ($isLocked && $user->subscription_package_id && !$isUserPkg) {
                                                    continue;
                                                }
                                            @endphp
                                            <div class="col-md-4">
                                                <div class="card h-100 border-0 shadow-sm pricing-item rounded-4 overflow-hidden {{ $isLocked ? '' : 'package-card cursor-pointer' }}" 
                                                     {!! $isLocked ? '' : "onclick=\"selectPackage('{$pkg->id}')\"" !!} id="pkg-card-{{ $pkg->id }}">
                                                    <div class="p-3 text-center bg-primary text-white package-header transition-all">
                                                        <h6 class="text-white mb-1">{{ $pkg->name }}</h6>
                                                        <div class="h5 mb-0 text-white">
                                                            {{ number_format($pkg->price, 2) }} <small class="fs-6">{{ __('website.rs') }}</small>
                                                        </div>
                                                        <small class="text-white-50">{{ $pkg->duration_days }} {{ __('website.days') }}</small>
                                                    </div>
                                                    <div class="card-body p-3 bg-white">
                                                        <ul class="list-unstyled text-start small mb-0">
                                                            <li class="mb-2 d-flex align-items-start">
                                                                <i class="bi bi-check-circle-fill text-primary me-2 mt-1"></i>
                                                                <span>{{ __('website.max_services') }}: {{ $pkg->max_services > 0 ? $pkg->max_services : __('admin.all') }}</span>
                                                            </li>
                                                            <li class="mb-2 d-flex align-items-start">
                                                                <i class="bi bi-check-circle-fill text-primary me-2 mt-1"></i>
                                                                <span>{{ __('website.works_limit') }}: {{ $pkg->works_limit > 0 ? $pkg->works_limit : __('admin.all') }}</span>
                                                            </li>
                                                            @php
                                                                $features = $pkg->features;
                                                                if(is_string($features)) $features = json_decode($features, true);
                                                            @endphp
                                                            @if(is_array($features))
                                                                @foreach($features as $feature)
                                                                    @if($feature)
                                                                        <li class="mb-1 d-flex align-items-start">
                                                                            <i class="bi bi-check-circle-fill text-primary me-2 mt-1"></i>
                                                                            <span>{{ $feature }}</span>
                                                                        </li>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </ul>
                                                    </div>
                                                    <input type="radio" name="subscription_package_id" value="{{ $pkg->id }}" class="d-none" id="pkg-radio-{{ $pkg->id }}" {{ (old('subscription_package_id') == $pkg->id || $user->subscription_package_id == $pkg->id) ? 'checked' : '' }} {{ $isLocked ? 'disabled' : '' }}>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('subscription_package_id')
                                        <span class="text-danger small d-block mt-2">{{ $message }}</span>
                                    @enderror
                                </div>

                                <style>
                                    .package-card { transition: all 0.3s ease; border: 2px solid transparent !important; }
                                    .package-card:hover { transform: translateY(-5px); box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1) !important; }
                                    .package-card.active { border: 2px solid #0d6efd !important; box-shadow: 0 1rem 3rem rgba(13, 110, 253, 0.15) !important; }
                                    .package-card.active .package-header { background-color: #0b5ed7 !important; }
                                    .rounded-4 { border-radius: 1rem !important; }
                                    .transition-all { transition: all 0.3s ease; }
                                </style>

                                <script>
                                    function selectPackage(id) {
                                        if (document.getElementById('pkg-radio-' + id).disabled) return;
                                        document.querySelectorAll('.package-card').forEach(el => el.classList.remove('active'));
                                        document.getElementById('pkg-card-' + id).classList.add('active');
                                        document.getElementById('pkg-radio-' + id).checked = true;
                                    }
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const checked = document.querySelector('input[name="subscription_package_id"]:checked');
                                        if (checked) selectPackage(checked.value);
                                    });
                                </script>
                            @endif

                            <!-- Categories Selection -->
                            <div class="row mb-4">
                                <div class="col-md-6 form-group login-form-group">
                                    <label for="main_category" class="form-label">{{ __('website.main_category') }}</label>
                                    <select class="form-select login-input" id="main_category" name="categories[]"
                                        {{ $isLocked ? 'disabled' : '' }}>
                                        <option value="">{{ __('website.choose_main_category') }}</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ $userMainCategoryId == $category->id ? 'selected' : '' }}
                                                data-subcategories="{{ json_encode($category->children) }}">
                                                {{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 form-group login-form-group">
                                    <label for="sub_categories" class="form-label">{{ __('website.sub_categories') }}</label>
                                    <select class="form-select login-input" id="sub_categories" name="categories[]" multiple
                                        {{ $isLocked ? 'disabled' : '' }} style="height: 120px;">
                                        <!-- Options populated by JS -->
                                    </select>
                                </div>
                            </div>

                            <!-- City and Experience -->
                            <div class="row mb-4">
                                <div class="col-md-6 form-group login-form-group">
                                    <label for="city_id" class="form-label">{{ __('website.city') }} <span class="text-danger">*</span></label>
                                    <select class="form-select login-input" id="city_id" name="city_id"
                                        {{ $isLocked ? 'disabled' : '' }}>
                                        <option value="">{{ __('website.choose_city') }}</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}"
                                                {{ $user->city_id == $city->id ? 'selected' : '' }}>{{ $city->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 form-group login-form-group">
                                    <label for="years_of_experience" class="form-label">{{ __('website.years_of_experience') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control login-input" id="years_of_experience"
                                        name="years_of_experience" min="0" max="60"
                                        value="{{ $user->years_of_experience ?? 0 }}" placeholder="مثل: 5"
                                        {{ $isLocked ? 'disabled' : '' }}>
                                </div>
                            </div>

                            @if (!$isLocked)
                                <div id="certificates-container">
                                    <div class="certificate-entry border rounded p-3 mb-3 position-relative bg-light">
                                        <button type="button"
                                            class="btn btn-sm btn-outline-danger position-absolute top-0 start-0 m-2 remove-certificate"
                                            style="display:none;" title="حذف الشهادة" {{ $isLocked ? 'disabled' : '' }}><i
                                                class="bi bi-trash"></i></button>
                                        <div class="form-group login-form-group mb-3">
                                            <label class="form-label">{{ __('website.certificate_name_optional') }}</label>
                                            <input type="text" class="form-control login-input" name="certificate_names[]"
                                                placeholder="..."
                                                {{ $isLocked ? 'disabled' : '' }}>
                                        </div>
                                        <div class="form-group login-form-group mb-0">
                                            <label class="form-label">{{ __('website.certificate_file') }}</label>
                                            <input type="file" class="form-control login-input" name="certificates[]"
                                                {{ $isLocked ? 'disabled' : '' }}>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-primary mb-4" id="add-certificate"
                                    {{ $isLocked ? 'disabled' : '' }}><i class="bi bi-plus-circle me-1"></i> {{ __('website.add_another_certificate') }}</button>
                            @endif

                            @if (!$isLocked)
                                @if (auth()->user()->membership_type == 'company')
                                    <div class="form-group login-form-group mb-4">
                                        <label for="commercial_registration" class="form-label">{{ __('website.commercial_registration') }}</label>
                                        <input type="file" class="form-control login-input" id="commercial_registration"
                                            name="commercial_registration" {{ $isLocked ? 'disabled' : '' }}>
                                    </div>
                                @else
                                    <div class="form-group login-form-group mb-4">
                                        <label for="id_front" class="form-label">{{ __('website.id_front_label') }}</label>
                                        <input type="file" class="form-control login-input" id="id_front" name="id_front"
                                            {{ $isLocked ? 'disabled' : '' }}>
                                    </div>
                                    <div class="form-group login-form-group mb-4">
                                        <label for="id_back" class="form-label">{{ __('website.id_back_label') }}</label>
                                        <input type="file" class="form-control login-input" id="id_back" name="id_back"
                                            {{ $isLocked ? 'disabled' : '' }}>
                                    </div>
                                @endif
                            @endif

                            <div class="form-group login-form-group mb-4">
                                <textarea class="form-control login-input" id="bio" name="bio" rows="4"
                                    placeholder="..." {{ $isLocked ? 'disabled' : '' }}>{{ auth()->user()->bio }}</textarea>
                            </div>

                            @if(!$isLocked)
                                <button type="submit" class="auth btn btn-primary w-100 py-3" style="border-radius: 50px;">{{ __('website.save_data') ?? 'حفظ البيانات' }}</button>
                            @endif
                        </form>
                    </div>
                </div>
        </div>
</div>
        @endsection


        @push('js')
        <!-- Script for Dynamic Certificates and Categories -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <style>
            .select2-container .select2-selection--single {
                height: 45px;
                padding: 8px;
                border: 1px solid #ced4da;
                border-radius: 8px;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 45px;
                left: 10px;
                right: auto;
            }

            .select2-container .select2-selection--multiple {
                min-height: 45px;
                border: 1px solid #ced4da;
                border-radius: 8px;
            }

            .select2-container--default[dir="rtl"] .select2-selection--multiple .select2-selection__choice {
                margin-top: 8px;
                background-color: #0d6efd;
                border: none;
                color: #fff;
            }

            .select2-container--default[dir="rtl"] .select2-selection--multiple .select2-selection__choice__remove {
                color: #fff;
                margin-left: 5px;
                margin-right: 0;
                border-left: 1px solid rgba(255, 255, 255, 0.2);
            }
        </style>
        <script>
            $(document).ready(function() {
                const container = document.getElementById('certificates-container');
                const addButton = document.getElementById('add-certificate');

                if (addButton && container) {
                    addButton.addEventListener('click', function() {
                        const template = container.querySelector('.certificate-entry').cloneNode(true);
                        template.querySelectorAll('input').forEach(input => input.value = '');
                        template.querySelector('.remove-certificate').style.display = 'block';
                        container.appendChild(template);
                    });

                    container.addEventListener('click', function(e) {
                        if (e.target.closest('.remove-certificate')) {
                            e.target.closest('.certificate-entry').remove();

                            const entries = container.querySelectorAll('.certificate-entry');
                            if (entries.length === 1) {
                                entries[0].querySelector('.remove-certificate').style.display = 'none';
                            }
                        }
                    });
                }

                // Categories logic
                const rawMainCategorySelect = document.getElementById('main_category');
                const rawSubCategoriesSelect = document.getElementById('sub_categories');
                const userCategoryIds = @json($userCategoryIds);

                function populateSubcategories() {
                    rawSubCategoriesSelect.innerHTML = '';
                    const selectedOption = rawMainCategorySelect.options[rawMainCategorySelect.selectedIndex];

                    if (selectedOption && selectedOption.value) {
                        const subcats = JSON.parse(selectedOption.getAttribute('data-subcategories') || '[]');

                        if (subcats && subcats.length > 0) {
                            subcats.forEach(sub => {
                                const option = document.createElement('option');
                                option.value = sub.id;
                                option.textContent = (typeof sub.name === 'object' && sub.name !== null) ? (sub
                                    .name.ar || sub.name.en) : sub.name;
                                if (userCategoryIds.includes(sub.id)) {
                                    option.selected = true;
                                }
                                rawSubCategoriesSelect.appendChild(option);
                            });
                        } else {
                            const option = document.createElement('option');
                            option.value = "";
                            option.textContent = "{{ __('website.no_sub_categories') }}";
                            option.disabled = true;
                            rawSubCategoriesSelect.appendChild(option);
                        }
                    }
                    $('#sub_categories').trigger('change.select2');
                }

                if (rawMainCategorySelect) {
                    $('#main_category').select2({
                        dir: "rtl",
                        width: '100%'
                    });
                    $('#sub_categories').select2({
                        dir: "rtl",
                        width: '100%'
                    });

                    // Trigger initially if there's a selected main category before binding change event
                    if (rawMainCategorySelect.value) {
                        populateSubcategories();
                    }

                    $('#main_category').on('change', populateSubcategories);
                }
            });
        </script>
    @endpush
