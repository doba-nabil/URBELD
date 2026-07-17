@extends('layouts.website')
@section('title', __('website.create_supply_request') ?? 'إضافة طلب توريد جديد')

@section('content')
<!-- Header Start -->
<div class="services-header-section without-search bg-notwhite">
    <div class="container p-md-5 p-4 mb-md-5">
        <div class="row align-items-center">
            <div class="col-lg-12">
            </div>
        </div>
    </div>
</div>
<!-- Header End -->

<div class="contact-form-section" style="padding-top: 60px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                <div class="contact-form-card wow fadeInUp" data-wow-delay="0.2s">
                    <div class="contact-form-card-content">
                        <!-- Quick Enquiry Badge -->
                        <div class="text-center mb-4">
                            <span class="quick-enquiry-badge">
                                {{ __('website.create_supply_request') ?? 'طلب توريد جديد' }}
                            </span>
                        </div>

                        @php
                            $siteLogo = app()->getLocale() == 'ar' 
                                       ? \App\Models\Setting::getMediaUrl('logo_ar') 
                                       : \App\Models\Setting::getMediaUrl('logo_en');
                            $siteLogo = $siteLogo ?: asset('website/assets/img/logo.png');
                        @endphp

                        @if (isset($provider))
                            <div class="alert alert-info border-0 rounded-3 shadow-sm mb-4" style="background-color: #eef2f5;">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $provider->getFirstMediaUrl('personal_photo') ?: ($provider->getFirstMediaUrl('users') ?: $siteLogo) }}"
                                        alt="{{ $provider->name }}" class="rounded-circle me-3"
                                        style="width: 55px; height: 55px; object-fit: cover; border: 2px solid #fff;">
                                    <div>
                                        <h6 class="mb-1 text-dark fw-bold">{{ __('website.request_directed_to') ?? 'طلب موجه إلى' }}: {{ $provider->name }}</h6>
                                        <p class="mb-0 small text-muted"><i class="bi bi-info-circle me-1"></i> {{ __('website.request_directed_desc') ?? 'هذا الطلب سيصل مباشرة للمورد الذي قمت باختياره.' }}</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <h2 class="contact-form-title text-center mb-4">
                                {{ __('website.create_supply_request_desc') ?? 'قم بتعبئة النموذج التالي لنشر طلب التوريد بأفضل المواصفات.' }}
                            </h2>
                        @endif

                        <form class="contact-form" action="{{ route('website.supply-requests.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            @if(isset($provider))
                                <input type="hidden" name="provider_id" value="{{ $provider->id }}">
                            @endif
                            @if(request()->has('category'))
                                <input type="hidden" name="category_id" value="{{ request('category') }}">
                            @endif

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">{{ __('website.request_title') ?? 'عنوان الطلب' }} <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" placeholder="{{ __('website.supply_request_title_placeholder') ?? 'مثال: توريد 50 جهاز كمبيوتر مكتبي' }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">الكمية <span class="text-danger">*</span></label>
                                    <input type="text" name="quantity" class="form-control" placeholder="الكمية المطلوبة (مثال: 50 قطعة)" required>
                                </div>

                                @if(isset($provider) && $provider->categories->whereNotNull('parent_id')->count() > 0)
                                    <div class="col-12">
                                        <label class="form-label fw-bold">القسم المطلوب <span class="text-danger">*</span></label>
                                        <select name="sub_category_id" class="form-select select2" required>
                                            <option value="" disabled selected>اختر القسم الفرعي</option>
                                            @foreach($provider->categories->whereNotNull('parent_id') as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                @if(isset($providerDoesNotDeliver) && $providerDoesNotDeliver)
                                    <!-- No Delivery Logic -->
                                    <input type="hidden" name="city_id" value="{{ $provider->city_id ?? '' }}">
                                    <div class="col-12">
                                        <div class="alert alert-warning border-0 rounded-3 d-flex align-items-center mb-0" style="background-color: #fffbeb; color: #b45309;">
                                            <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                                            <div>
                                                <strong>تنبيه:</strong> هذا المورد لا يوفر خدمة التوصيل. سيتم استلام الطلب مباشرة من مقر المورد في مدينة ({{ $provider->city->name ?? 'غير محدد' }}).
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">المنطقة <span class="text-danger">*</span></label>
                                        <select name="region_id" class="form-select select2" required>
                                            <option value="" disabled selected>اختر المنطقة</option>
                                            @foreach($regions as $region)
                                                <option value="{{ $region->id }}">{{ $region->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('website.city') ?? 'المدينة' }} <span class="text-danger">*</span></label>
                                        <select name="city_id" class="form-select select2" required>
                                            <option value="" disabled selected>{{ __('website.choose_city') ?? 'اختر المدينة' }}</option>
                                            @foreach($regions as $region)
                                                @php
                                                    $citiesInRegion = $region->cities;
                                                    if (!empty($providerCities)) {
                                                        $citiesInRegion = $citiesInRegion->whereIn('id', $providerCities);
                                                    }
                                                @endphp
                                                @if($citiesInRegion->isNotEmpty())
                                                    @foreach($citiesInRegion as $city)
                                                        <option value="{{ $city->id }}" data-region="{{ $region->id }}">{{ $city->name }}</option>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </select>
                                        @if(!empty($providerCities))
                                            <small class="text-muted"><i class="bi bi-info-circle"></i> تظهر فقط المدن التي يدعم المورد التوصيل إليها.</small>
                                        @endif
                                    </div>
                                @endif

                                <div class="col-12">
                                    <label class="form-label fw-bold">{{ __('website.delivery_date') ?? 'آخر موعد للتسليم (اختياري)' }}</label>
                                    <div class="input-group-custom">
                                        <input type="date" name="delivery_date" class="form-control" min="{{ date('Y-m-d') }}">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-bold">{{ __('website.request_details') ?? 'تفاصيل ومواصفات الطلب' }} <span class="text-danger">*</span></label>
                                    <textarea name="description" class="form-control" placeholder="{{ __('website.supply_request_details_placeholder') ?? 'يرجى كتابة كافة المواصفات والكميات المطلوبة بدقة...' }}" style="height: 150px" required></textarea>
                                </div>

                                <!-- Voice Record -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold"><i class="bi bi-mic-fill me-1"></i> تسجيل صوتي لوصف الطلب (اختياري)</label>
                                    <input type="file" name="voice_record" class="form-control" accept="audio/*" capture>
                                    <small class="text-muted">يمكنك إرفاق ملف صوتي أو تسجيل صوتك مباشرة.</small>
                                </div>

                                <!-- Map Location -->
                                <div class="col-12 mt-4">
                                    <label class="form-label fw-bold"><i class="bi bi-geo-alt-fill me-1"></i> الموقع الجغرافي (اختياري)</label>
                                    <div class="mb-3 d-flex gap-2">
                                        <button type="button" class="btn btn-outline-primary" id="getLocationBtn">
                                            <i class="bi bi-crosshair me-1"></i> تحديد موقعي الحالي
                                        </button>
                                        <div id="locationStatus" class="align-self-center text-muted small"></div>
                                    </div>
                                    <input type="hidden" name="latitude" id="latitude">
                                    <input type="hidden" name="longitude" id="longitude">
                                    <input type="text" name="location" id="locationAddress" class="form-control mb-2" placeholder="أو أدخل العنوان التفصيلي يدوياً (مثال: الرياض، حي الملقا، شارع 1)">
                                    <div id="mapPreview" style="height: 250px; display: none; border-radius: 8px; border: 1px solid #dee2e6;"></div>
                                </div>

                                <div class="col-12 text-center mt-5">
                                    <button type="submit" class="btn btn-primary submit-btn px-5 py-3 rounded-pill fw-bold">
                                        <span>إرسال طلب توريد</span>
                                        <i class="bi bi-send ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('getLocationBtn').addEventListener('click', function() {
        const statusDiv = document.getElementById('locationStatus');
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        const mapPreview = document.getElementById('mapPreview');
        
        statusDiv.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> جاري تحديد الموقع...';
        
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                latInput.value = lat;
                lngInput.value = lng;
                statusDiv.innerHTML = '<i class="bi bi-check-circle-fill text-success"></i> تم التحديد بنجاح';
                
                // Show OpenStreetMap Preview
                mapPreview.style.display = 'block';
                mapPreview.innerHTML = `<iframe width="100%" height="100%" style="border:0; border-radius: 8px;" loading="lazy" src="https://www.openstreetmap.org/export/embed.html?bbox=${lng - 0.01}%2C${lat - 0.01}%2C${lng + 0.01}%2C${lat + 0.01}&layer=mapnik&marker=${lat}%2C${lng}"></iframe>`;
            }, function(error) {
                statusDiv.innerHTML = '<i class="bi bi-x-circle-fill text-danger"></i> تعذر الوصول للموقع. يرجى إدخال العنوان يدوياً.';
            });
        } else {
            statusDiv.innerHTML = '<i class="bi bi-x-circle-fill text-danger"></i> المتصفح لا يدعم تحديد الموقع.';
        }
    });
</script>
@endpush
@endsection


