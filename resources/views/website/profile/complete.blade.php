@extends('website.layouts.profile')

@section('profile-content')
    @php
        $user = auth()->user();
        $hasId = $user->getFirstMediaUrl('id_front') || $user->getFirstMediaUrl('commercial_registration');
        $isActive = ($user->active === 'active' || $user->active === '1' || $user->active === 1);
        $isPendingWithId = ($user->active === 'pending' || $user->active === '0' || $user->active === 0) && $hasId;
        
        // Locked means view-only mode for categories and docs, but let's treat it as full view mode for the UI
        $isLocked = $isActive || $isPendingWithId;
        
        $userCategoryIds = $user->categories->pluck('id')->toArray();
        $userMainCategory = $user->categories->whereNull('parent_id')->first();
        $userMainCategoryId = $userMainCategory->id ?? null;
        $userSubCategories = $user->categories->whereNotNull('parent_id');
        $cities = \App\Models\City::orderBy('name')->get();
    @endphp

    <style>
        .pd-readonly-box, .pd-input-box {
            background-color: #f9f9f9;
            border: 1px solid #f0f0f0;
            border-radius: 8px;
            padding: 0 16px;
            color: #222;
            font-weight: 700;
            display: flex;
            align-items: center;
            width: 100%;
            height: 56px;
            font-size: 0.95rem;
        }
        select.pd-input-box {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: left 1rem center;
            background-size: 16px 12px;
        }
        .pd-input-box:focus {
            outline: none;
            border-color: #d1d1d1;
            box-shadow: none;
            background-color: #fff;
        }
        .pd-label {
            color: #888;
            font-size: 0.85rem;
            margin-bottom: 8px;
            display: block;
            font-weight: 500;
        }
        .pd-doc-box {
            border: 1px solid #f0f0f0;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #fff;
            height: auto !important;
        }
        .pd-doc-icon {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }
        .pd-subcat-badge {
            background-color: #eef2f0;
            color: #556b60;
            border: none;
            padding: 6px 14px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.85rem;
            margin-top: 6px;
        }
        .pd-status-badge {
            font-size: 0.75rem;
            padding: 4px 12px;
            border-radius: 12px;
            font-weight: 700;
        }
        .pd-status-verified {
            background-color: #e6f4ea;
            color: #1e8e3e;
        }
        .pd-status-attached {
            background-color: #e8f0fe;
            color: #1a73e8;
        }
        .profile-container {
            max-width: 1100px;
            margin: 0 auto;
        }
        .main-card {
            background-color: #fff;
            border-radius: 12px;
            border: 1px solid #eaeaea;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            padding: 40px;
        }
    </style>

<div class="container mt-4 mb-5 profile-container" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="mb-4 text-start">
        <a href="{{ route('profile.edit') }}" class="text-muted text-decoration-none small fw-bold">
            <i class="bi bi-arrow-right me-1"></i> العودة للوحة التحكم
        </a>
    </div>

    <div class="main-card">
        
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-4">
            <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.4rem;">بياناتي المهنية</h3>
            <a href="{{ route('profile.edit') }}" class="btn btn-white btn-sm rounded-3 px-4 py-2 fw-bold text-dark border" style="background: #fff; font-size: 0.9rem;">
                <i class="bi bi-pencil-square me-2 text-muted"></i> تعديل البيانات
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success rounded-3 border-0">
                {{ session('success') }}
            </div>
        @endif

        @if ($isActive)
            <div class="alert mb-4 rounded-3 d-flex align-items-center" style="background-color: #fffbeb; border: 1px solid #fef08a; color: #b45309; padding: 16px;">
                <i class="bi bi-exclamation-circle me-3 fs-5"></i>
                <div class="fw-bold" style="font-size: 0.9rem;">
                    تم تفعيل عضويتك! تم إغلاق إضافة أو تعديل الشهادات والمرفقات والتصنيفات. لأي تغييرات، يرجى التواصل مع الإدارة.
                </div>
            </div>
        @elseif ($isPendingWithId)
            <div class="alert mb-4 rounded-3 d-flex align-items-center" style="background-color: #fffbeb; border: 1px solid #fef08a; color: #b45309; padding: 16px;">
                <i class="bi bi-info-circle me-3 fs-5"></i>
                <div class="fw-bold" style="font-size: 0.9rem;">
                    {{ __('website.membership_review_desc') }}
                </div>
            </div>
        @endif

        <form action="{{ route('profile.complete.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Form Grid -->
            <div class="row g-4 mb-5">
                
                <!-- Row 1 -->
                <div class="col-md-6">
                    <div class="">
                        <label class="pd-label"><i class="bi bi-diagram-3 me-1"></i> التصنيف الرئيسي</label>
                        @if($isLocked)
                            <div class="pd-readonly-box justify-content-{{ app()->getLocale() == 'ar' ? 'start' : 'end' }}">{{ $userMainCategory->name ?? __('website.not_specified') }}</div>
                        @else
                            <select class="pd-input-box text-{{ app()->getLocale() == 'ar' ? 'start' : 'end' }}" id="main_category" name="categories[]" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
                                <option value="">{{ __('website.choose_main_category') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ $userMainCategoryId == $category->id ? 'selected' : '' }} data-subcategories="{{ json_encode($category->children) }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="">
                        <label class="pd-label"><i class="bi bi-geo-alt me-1"></i> المنطقة</label>
                        @if($isLocked)
                            <div class="pd-readonly-box justify-content-{{ app()->getLocale() == 'ar' ? 'start' : 'end' }}">{{ $user->city->region->name ?? __('website.not_specified') }}</div>
                        @else
                            @php $regions = \App\Models\Region::all(); @endphp
                            <select id="region_id" class="pd-input-box text-{{ app()->getLocale() == 'ar' ? 'start' : 'end' }}" name="region_id" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
                                <option value="">{{ __('website.choose_region') ?? 'اختر المنطقة' }}</option>
                                @foreach ($regions as $region)
                                    <option value="{{ $region->id }}" {{ ($user->city && $user->city->region_id == $region->id) ? 'selected' : '' }}>{{ $region->name }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="">
                        <label class="pd-label"><i class="bi bi-geo-alt me-1"></i> المدينة</label>
                        @if($isLocked)
                            <div class="pd-readonly-box justify-content-{{ app()->getLocale() == 'ar' ? 'start' : 'end' }}">{{ $user->city->name ?? __('website.not_specified') }}</div>
                        @else
                            <select id="city_id" class="pd-input-box text-{{ app()->getLocale() == 'ar' ? 'start' : 'end' }}" name="city_id" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
                                <option value="">{{ __('website.choose_city') }}</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}" data-region="{{ $city->region_id }}" {{ $user->city_id == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="col-md-6">
                    <div class="">
                        <label class="pd-label">التصنيفات الفرعية</label>
                        @if($isLocked)
                            <div class="d-flex flex-wrap gap-2 justify-content-{{ app()->getLocale() == 'ar' ? 'start' : 'end' }} mt-1">
                                @forelse($userSubCategories as $subcat)
                                    <span class="pd-subcat-badge">{{ $subcat->name }}</span>
                                @empty
                                    <span class="text-muted small">لا يوجد</span>
                                @endforelse
                            </div>
                        @else
                            <select class="pd-input-box text-{{ app()->getLocale() == 'ar' ? 'end' : 'start' }}" id="sub_categories" name="categories[]" multiple dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" style="height: auto; min-height: 56px;">
                                <!-- Options populated by JS -->
                            </select>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="">
                        <label class="pd-label"><i class="bi bi-envelope me-1"></i> البريد الإلكتروني</label>
                        @if($isLocked)
                            <div class="pd-readonly-box justify-content-end font-monospace" dir="ltr">{{ $user->email }}</div>
                        @else
                            <input type="email" class="pd-input-box  font-monospace" disabled value="{{ $user->email }}" dir="ltr">
                        @endif
                    </div>
                </div>

                <!-- Row 3 -->
                <div class="col-md-6">
                    <div class="">
                        <label class="pd-label">سنوات الخبرة</label>
                        @if($isLocked)
                            <div class="pd-readonly-box justify-content-start">{{ $user->years_of_experience ?? 0 }} سنة</div>
                        @else
                            <input type="number" class="pd-input-box " name="years_of_experience" value="{{ $user->years_of_experience ?? 0 }}">
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="">
                        <label class="pd-label"><i class="bi bi-telephone me-1"></i> رقم التواصل</label>
                        @if($isLocked)
                            <div class="pd-readonly-box justify-content-start font-monospace" dir="ltr">{{ $user->phone ?? 'غير متوفر' }}</div>
                        @else
                            <input type="text" class="pd-input-box  font-monospace" disabled value="{{ $user->phone }}" dir="ltr">
                        @endif
                    </div>
                </div>

            </div>

            <!-- Row 4 (For Companies) -->
            @if($user->membership_type == 'company')
            <div class="row g-4 flex-row-reverse mt-2">
                <div class="col-md-6">
                    <div class="">
                        <label class="pd-label">رقم السجل التجاري</label>
                        @if($isLocked)
                            <div class="pd-readonly-box justify-content-start font-monospace">{{ $user->company_registration_number ?? 'غير متوفر' }}</div>
                        @else
                            <input type="text" class="pd-input-box font-monospace text-end" name="company_registration_number" value="{{ $user->company_registration_number }}">
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="">
                        <label class="pd-label">اسم المفوض</label>
                        @if($isLocked)
                            <div class="pd-readonly-box justify-content-start">{{ $user->representative_name ?? 'غير متوفر' }}</div>
                        @else
                            <input type="text" class="pd-input-box text-start" name="representative_name" value="{{ $user->representative_name }}">
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Official Documents -->
            <div class="mb-5 border-top pt-4 ">
                <label class="pd-label mb-3">المستندات الرسمية</label>
                
                <div class="row g-4 flex-row-reverse">
                    <!-- Commercial Registration / ID (Right in visual RTL) -->
                    <div class="col-md-6">
                        @if(auth()->user()->membership_type == 'company')
                            @if(auth()->user()->getFirstMediaUrl('commercial_registration'))
                                <div class="pd-doc-box">
                                    <span class="pd-status-badge pd-status-verified">موثق</span>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="">
                                            <div class="fw-bold text-dark mb-1">السجل التجاري</div>
                                            <a href="{{ auth()->user()->getFirstMediaUrl('commercial_registration') }}" target="_blank" class="text-primary text-decoration-underline" style="font-size: 0.85rem;"><i class="bi bi-eye me-1"></i>عرض الملف</a>
                                        </div>
                                        <div class="pd-doc-icon" style="background-color: #e6f4ea; color: #1e8e3e;"><i class="bi bi-file-earmark-text fs-4"></i></div>
                                    </div>
                                </div>
                            @elseif(!$isLocked)
                                <div class="pd-doc-box d-block  p-3">
                                    <label class="pd-label mb-2 fw-bold text-dark">رفع السجل التجاري <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control bg-white" name="commercial_registration" required>
                                </div>
                            @endif
                        @else
                            @if(auth()->user()->getFirstMediaUrl('id_front'))
                                <div class="pd-doc-box">
                                    <span class="pd-status-badge pd-status-verified">موثق</span>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="">
                                            <div class="fw-bold text-dark mb-1">الهوية الوطنية</div>
                                            <a href="{{ auth()->user()->getFirstMediaUrl('id_front') }}" target="_blank" class="text-primary text-decoration-underline" style="font-size: 0.85rem;"><i class="bi bi-eye me-1"></i>عرض الملف</a>
                                        </div>
                                        <div class="pd-doc-icon" style="background-color: #e6f4ea; color: #1e8e3e;"><i class="bi bi-file-earmark-person fs-4"></i></div>
                                    </div>
                                </div>
                            @elseif(!$isLocked)
                                <div class="pd-doc-box d-block  p-3">
                                    <label class="pd-label mb-2 fw-bold text-dark">رفع الهوية الوطنية <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control bg-white" name="id_front" required>
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Additional Certificates (Left in visual RTL) -->
                    <div class="col-md-6">
                        @if(auth()->user()->getMedia('certificates')->count() > 0)
                            @foreach(auth()->user()->getMedia('certificates') as $media)
                                <div class="pd-doc-box mb-3">
                                    <span class="pd-status-badge pd-status-attached">مرفق</span>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="">
                                            <div class="fw-bold text-dark mb-1">{{ $media->name ?? 'أوراق ومستندات رسمية' }}</div>
                                            <a href="{{ $media->getUrl() }}" target="_blank" class="text-primary text-decoration-underline" style="font-size: 0.85rem;"><i class="bi bi-eye me-1"></i>عرض الملف</a>
                                        </div>
                                        <div class="pd-doc-icon" style="background-color: #e8f0fe; color: #1a73e8;"><i class="bi bi-file-earmark-text fs-4"></i></div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        @if(!$isLocked)
                            <div class="pd-doc-box d-block p-3 mt-3" id="certificates-container">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="pd-label mb-0 fw-bold text-dark">إضافة المزيد من الأوراق والمستندات</label>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="add-certificate-btn">
                                        <i class="bi bi-plus-lg"></i> إضافة ملف
                                    </button>
                                </div>
                                <div class="certificate-group mb-3 border-bottom pb-3">
                                    <input type="file" class="form-control bg-white mb-2" name="certificates[]">
                                    <input type="text" class="form-control bg-white" name="certificate_names[]" placeholder="اسم الشهادة (اختياري)">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Bio -->
            <div class="mb-4 border-top pt-4 ">
                <label class="pd-label">نبذة عن المكتب/الشركة</label>
                @if($isLocked)
                    <div class="pd-readonly-box justify-content-start align-items-start" style="min-height: 80px; padding-top: 16px; font-weight: normal;">
                        {{ auth()->user()->bio ?? 'لا توجد نبذة حالياً.' }}
                    </div>
                @else
                    <textarea class="pd-input-box " name="bio" rows="4" dir="rtl" style="resize: vertical; min-height: 100px; padding-top: 16px; display: block;">{{ auth()->user()->bio }}</textarea>
                @endif
            </div>

            @if(!$isLocked)
                <!-- Subscription Packages (Only if not locked and enabled) -->
                @if (isset($isSubscriptionEnabled) && $isSubscriptionEnabled && count($packages) > 0)
                    <div class="mb-4 pt-4 border-top ">
                        <label class="pd-label">اختر باقة الاشتراك <span class="text-danger">*</span></label>
                        <div class="row g-3 flex-row-reverse">
                            @foreach ($packages as $pkg)
                                <div class="col-md-4">
                                    <label class="w-100 cursor-pointer">
                                        <input type="radio" name="subscription_package_id" value="{{ $pkg->id }}" class="d-none peer" {{ (old('subscription_package_id') == $pkg->id || $user->subscription_package_id == $pkg->id) ? 'checked' : '' }}>
                                        <div class="pd-doc-box peer-checked-border text-center flex-column">
                                            <h6 class="fw-bold mb-2">{{ $pkg->name }}</h6>
                                            <div class="fw-bold text-primary">{{ number_format($pkg->price, 2) }} ريال</div>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class=" mt-5">
                    <button type="submit" class="btn btn-primary px-5 py-3 rounded-pill fw-bold w-100 fs-5">حفظ البيانات</button>
                </div>
            @endif

        </form>
    </div>
    </div>
@endsection

@push('js')
    @if(!$isLocked)
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                const userCategoryIds = @json($userCategoryIds);
                const rawMainCategorySelect = document.getElementById('main_category');
                const rawSubCategoriesSelect = document.getElementById('sub_categories');

                function populateSubcategories() {
                    rawSubCategoriesSelect.innerHTML = '';
                    const selectedOption = rawMainCategorySelect.options[rawMainCategorySelect.selectedIndex];

                    if (selectedOption && selectedOption.value) {
                        const subcats = JSON.parse(selectedOption.getAttribute('data-subcategories') || '[]');

                        if (subcats && subcats.length > 0) {
                            subcats.forEach(sub => {
                                const option = document.createElement('option');
                                option.value = sub.id;
                                option.textContent = (typeof sub.name === 'object' && sub.name !== null) ? (sub.name.ar || sub.name.en) : sub.name;
                                if (userCategoryIds.includes(sub.id)) {
                                    option.selected = true;
                                }
                                rawSubCategoriesSelect.appendChild(option);
                            });
                        }
                    }
                    $('#sub_categories').trigger('change.select2');
                }

                if (rawMainCategorySelect) {
                    const dir = "{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}";
                    $('#main_category').select2({ dir: dir, width: '100%' });
                    $('#sub_categories').select2({ dir: dir, width: '100%' });
                    $('select[name="city_id"]').select2({ dir: dir, width: '100%' });

                    if (rawMainCategorySelect.value) {
                        populateSubcategories();
                    }

                    $('#main_category').on('change', populateSubcategories);
                }

                // Add more certificates dynamically
                $('#add-certificate-btn').on('click', function() {
                    const newGroup = `
                        <div class="certificate-group mb-3 border-bottom pb-3 position-relative">
                            <button type="button" class="btn btn-sm btn-danger position-absolute remove-certificate-btn" style="top: 0; left: 0; z-index: 10;">
                                <i class="bi bi-trash"></i>
                            </button>
                            <input type="file" class="form-control bg-white mb-2" name="certificates[]">
                            <input type="text" class="form-control bg-white" name="certificate_names[]" placeholder="اسم الشهادة (اختياري)">
                        </div>
                    `;
                    $('#certificates-container').append(newGroup);
                });

                // Remove certificate dynamically
                $(document).on('click', '.remove-certificate-btn', function() {
                    $(this).closest('.certificate-group').remove();
                });

                // Region to City filtering
                const regionSelect = $('#region_id');
                const citySelect = $('#city_id');
                if (regionSelect.length && citySelect.length) {
                    const originalCities = citySelect.find('option').clone();
                    
                    regionSelect.on('change', function() {
                        const regionId = $(this).val();
                        const currentCityVal = citySelect.val();
                        
                        citySelect.empty();
                        citySelect.append('<option value="">{{ __('website.choose_city') }}</option>');
                        
                        if (regionId) {
                            originalCities.each(function() {
                                if ($(this).val() && $(this).data('region') == regionId) {
                                    citySelect.append($(this).clone());
                                }
                            });
                        } else {
                            originalCities.each(function() {
                                if ($(this).val()) {
                                    citySelect.append($(this).clone());
                                }
                            });
                        }
                        
                        // Try to keep selection if valid
                        if (citySelect.find(`option[value="${currentCityVal}"]`).length) {
                            citySelect.val(currentCityVal);
                        }
                        
                        citySelect.trigger('change');
                    });
                    
                    // Initial trigger if a region is selected
                    if (regionSelect.val()) {
                        regionSelect.trigger('change');
                    }
                }
            });
        </script>
        <style>
            .peer:checked + .peer-checked-border {
                border: 2px solid #0d6efd !important;
                background-color: #f0f7ff !important;
            }
            /* Select2 customization to match .pd-input-box */
            .select2-container--default .select2-selection--single,
            .select2-container--default .select2-selection--multiple {
                background-color: #f9f9f9 !important;
                border: 1px solid #f0f0f0 !important;
                border-radius: 8px !important;
                min-height: 56px !important;
                display: flex;
                align-items: center;
                padding-left: 8px;
                padding-right: 8px;
            }
            .select2-container--default .select2-selection--multiple {
                height: auto !important;
                flex-wrap: wrap;
                padding-top: 4px;
                padding-bottom: 4px;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: #222 !important;
                font-weight: 700 !important;
                font-size: 0.95rem !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 54px !important;
                {{ app()->getLocale() == 'ar' ? 'left: 10px !important; right: auto !important;' : 'right: 10px !important; left: auto !important;' }}
            }
            .select2-container--default .select2-search--inline .select2-search__field {
                margin-top: 0 !important;
                height: 32px;
            }
            .select2-dropdown {
                border: 1px solid #f0f0f0 !important;
                border-radius: 8px !important;
                box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            }
            .select2-results__option {
                font-weight: 600;
                color: #444;
            }
        </style>
    @endif
@endpush
