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

    <div class="professional-data-container bg-white rounded-4 p-4 shadow-sm" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <h3 class="fw-bold mb-0">بياناتي المهنية</h3>
            @if($isLocked)
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-bold" onclick="alert('لأي تغييرات، يرجى التواصل مع الإدارة.')">
                    <i class="bi bi-pencil-square me-1"></i> تعديل البيانات
                </button>
            @endif
        </div>

        @if (session('success'))
            <div class="alert alert-success rounded-3 border-0">
                {{ session('success') }}
            </div>
        @endif

        @if ($isActive)
            <div class="alert mb-4 rounded-3 d-flex align-items-center" style="background-color: #fffbeb; border: 1px solid #fde68a; color: #b45309;">
                <i class="bi bi-exclamation-circle me-2 fs-5"></i>
                <div>
                    تم تفعيل عضويتك! تم إغلاق إضافة أو تعديل الشهادات والمرفقات والتصنيفات. لأي تغييرات، يرجى التواصل مع الإدارة.
                </div>
            </div>
        @elseif ($isPendingWithId)
            <div class="alert mb-4 rounded-3 d-flex align-items-center" style="background-color: #fffbeb; border: 1px solid #fde68a; color: #b45309;">
                <i class="bi bi-info-circle me-2 fs-5"></i>
                <div>
                    {{ __('website.membership_review_desc') }}
                </div>
            </div>
        @endif

        <form action="{{ route('profile.complete.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Form Grid -->
            <div class="row g-4 mb-4 pb-4 border-bottom">
                
                <!-- Right Column (in RTL) -->
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold"><i class="bi bi-diagram-3 me-1"></i> التصنيف الرئيسي</label>
                        @if($isLocked)
                            <div class="pd-readonly-box fw-bold fs-5">{{ $userMainCategory->name ?? 'غير محدد' }}</div>
                        @else
                            <select class="form-select bg-light border-0 py-2" id="main_category" name="categories[]">
                                <option value="">{{ __('website.choose_main_category') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ $userMainCategoryId == $category->id ? 'selected' : '' }} data-subcategories="{{ json_encode($category->children) }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold">التصنيفات الفرعية</label>
                        @if($isLocked)
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                @forelse($userSubCategories as $subcat)
                                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">{{ $subcat->name }}</span>
                                @empty
                                    <span class="text-muted">لا يوجد</span>
                                @endforelse
                            </div>
                        @else
                            <select class="form-select bg-light border-0 py-2" id="sub_categories" name="categories[]" multiple>
                                <!-- Options populated by JS -->
                            </select>
                        @endif
                    </div>

                    <div>
                        <label class="form-label text-muted small fw-bold">سنوات الخبرة</label>
                        @if($isLocked)
                            <div class="pd-readonly-box fw-bold fs-5">{{ $user->years_of_experience ?? 0 }} سنة</div>
                        @else
                            <input type="number" class="form-control bg-light border-0 py-2 fw-bold" name="years_of_experience" value="{{ $user->years_of_experience ?? 0 }}">
                        @endif
                    </div>
                </div>

                <!-- Left Column (in RTL) -->
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold"><i class="bi bi-geo-alt me-1"></i> المدينة</label>
                        @if($isLocked)
                            <div class="pd-readonly-box fw-bold fs-5">{{ $user->city->name ?? 'غير محدد' }}</div>
                        @else
                            <select class="form-select bg-light border-0 py-2" name="city_id">
                                <option value="">{{ __('website.choose_city') }}</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}" {{ $user->city_id == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold"><i class="bi bi-envelope me-1"></i> البريد الإلكتروني</label>
                        @if($isLocked)
                            <div class="pd-readonly-box fw-bold fs-6">{{ $user->email }}</div>
                        @else
                            <input type="email" class="form-control bg-light border-0 py-2 fw-bold" disabled value="{{ $user->email }}">
                            <small class="text-muted">البريد الإلكتروني لا يمكن تغييره من هنا</small>
                        @endif
                    </div>

                    <div>
                        <label class="form-label text-muted small fw-bold"><i class="bi bi-telephone me-1"></i> رقم التواصل</label>
                        @if($isLocked)
                            <div class="pd-readonly-box fw-bold fs-5">{{ $user->phone ?? 'غير متوفر' }}</div>
                        @else
                            <input type="text" class="form-control bg-light border-0 py-2 fw-bold" disabled value="{{ $user->phone }}">
                        @endif
                    </div>
                </div>

            </div>

            <!-- Official Documents -->
            <div class="mb-4 pb-4 border-bottom">
                <label class="form-label text-muted small fw-bold mb-3">المستندات الرسمية</label>
                
                <div class="row g-3">
                    <!-- Commercial Registration / ID -->
                    <div class="col-md-6">
                        @if(auth()->user()->membership_type == 'company')
                            @if(auth()->user()->getFirstMediaUrl('commercial_registration'))
                                <div class="pd-doc-box d-flex align-items-center justify-content-between p-3 border rounded-3 bg-light">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="pd-doc-icon bg-success bg-opacity-10 text-success p-2 rounded"><i class="bi bi-file-earmark-text fs-4"></i></div>
                                        <div>
                                            <div class="fw-bold">السجل التجاري</div>
                                            <a href="{{ auth()->user()->getFirstMediaUrl('commercial_registration') }}" target="_blank" class="text-muted small text-decoration-none">commercial_registration.pdf</a>
                                        </div>
                                    </div>
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">موثق</span>
                                </div>
                            @elseif(!$isLocked)
                                <div class="p-3 border rounded-3 bg-light">
                                    <label class="form-label fw-bold small">رفع السجل التجاري</label>
                                    <input type="file" class="form-control border-0" name="commercial_registration">
                                </div>
                            @endif
                        @else
                            @if(auth()->user()->getFirstMediaUrl('id_front'))
                                <div class="pd-doc-box d-flex align-items-center justify-content-between p-3 border rounded-3 bg-light">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="pd-doc-icon bg-success bg-opacity-10 text-success p-2 rounded"><i class="bi bi-file-earmark-person fs-4"></i></div>
                                        <div>
                                            <div class="fw-bold">الهوية الوطنية (الأمام)</div>
                                            <a href="{{ auth()->user()->getFirstMediaUrl('id_front') }}" target="_blank" class="text-muted small text-decoration-none">id_front.jpg</a>
                                        </div>
                                    </div>
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">موثق</span>
                                </div>
                            @elseif(!$isLocked)
                                <div class="p-3 border rounded-3 bg-light">
                                    <label class="form-label fw-bold small">رفع الهوية الوطنية</label>
                                    <input type="file" class="form-control border-0" name="id_front">
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Additional Certificates -->
                    <div class="col-md-6">
                        @if(auth()->user()->getMedia('certificates')->count() > 0)
                            @foreach(auth()->user()->getMedia('certificates') as $media)
                                <div class="pd-doc-box d-flex align-items-center justify-content-between p-3 border rounded-3 bg-light mb-2">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="pd-doc-icon bg-primary bg-opacity-10 text-primary p-2 rounded"><i class="bi bi-file-earmark-text fs-4"></i></div>
                                        <div>
                                            <div class="fw-bold">{{ $media->name ?? 'أوراق ومستندات رسمية' }}</div>
                                            <a href="{{ $media->getUrl() }}" target="_blank" class="text-muted small text-decoration-none">official_documents.pdf</a>
                                        </div>
                                    </div>
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">مرفق</span>
                                </div>
                            @endforeach
                        @elseif(!$isLocked)
                            <div class="p-3 border rounded-3 bg-light" id="certificates-container">
                                <label class="form-label fw-bold small">إضافة شهادة أو مستند</label>
                                <input type="file" class="form-control border-0 mb-2" name="certificates[]">
                                <input type="text" class="form-control border-0" name="certificate_names[]" placeholder="اسم الشهادة (اختياري)">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Bio -->
            <div class="mb-4">
                <label class="form-label text-muted small fw-bold">نبذة عن المكتب/الشركة</label>
                @if($isLocked)
                    <div class="pd-readonly-box p-3 bg-light rounded-3 text-dark" style="min-height: 80px;">
                        {{ auth()->user()->bio ?? 'لا توجد نبذة حالياً.' }}
                    </div>
                @else
                    <textarea class="form-control bg-light border-0 py-2" name="bio" rows="4">{{ auth()->user()->bio }}</textarea>
                @endif
            </div>

            @if(!$isLocked)
                <!-- Subscription Packages (Only if not locked and enabled) -->
                @if (isset($isSubscriptionEnabled) && $isSubscriptionEnabled && count($packages) > 0)
                    <div class="mb-4 pt-3 border-top">
                        <label class="form-label text-muted small fw-bold">اختر باقة الاشتراك <span class="text-danger">*</span></label>
                        <div class="row g-3">
                            @foreach ($packages as $pkg)
                                <div class="col-md-4">
                                    <label class="w-100 cursor-pointer">
                                        <input type="radio" name="subscription_package_id" value="{{ $pkg->id }}" class="d-none peer" {{ (old('subscription_package_id') == $pkg->id || $user->subscription_package_id == $pkg->id) ? 'checked' : '' }}>
                                        <div class="card border-0 bg-light p-3 rounded-3 peer-checked-border">
                                            <h6 class="fw-bold">{{ $pkg->name }}</h6>
                                            <div class="fw-bold text-primary">{{ number_format($pkg->price, 2) }} ريال</div>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill fw-bold">حفظ البيانات</button>
                </div>
            @endif

        </form>
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
                    $('#main_category').select2({ dir: "rtl", width: '100%' });
                    $('#sub_categories').select2({ dir: "rtl", width: '100%' });

                    if (rawMainCategorySelect.value) {
                        populateSubcategories();
                    }

                    $('#main_category').on('change', populateSubcategories);
                }
            });
        </script>
        <style>
            .peer:checked + .peer-checked-border {
                border: 2px solid #0d6efd !important;
                background-color: #f0f7ff !important;
            }
        </style>
    @endif
@endpush
