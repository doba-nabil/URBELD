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

                        <form class="contact-form" action="{{ route('website.supply-requests.store') }}" method="POST">
                            @csrf
                            
                            @if(isset($provider))
                                <input type="hidden" name="provider_id" value="{{ $provider->id }}">
                            @endif
                            @if(request()->has('category'))
                                <input type="hidden" name="category_id" value="{{ request('category') }}">
                            @endif

                            <div class="row g-4">
                                <div class="col-12">
                                    <label class="form-label fw-bold">{{ __('website.request_title') ?? 'عنوان الطلب' }} <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" placeholder="{{ __('website.supply_request_title_placeholder') ?? 'مثال: توريد 50 جهاز كمبيوتر مكتبي' }}" required>
                                </div>

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
                                    <div class="col-12">
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
                                                    <optgroup label="{{ $region->name }}">
                                                        @foreach($citiesInRegion as $city)
                                                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                                                        @endforeach
                                                    </optgroup>
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

                                <div class="col-12 text-center mt-5">
                                    <button type="submit" class="btn btn-primary submit-btn px-5 py-3 rounded-pill fw-bold">
                                        <span>{{ __('website.publish_request') ?? 'نشر الطلب' }}</span>
                                        <i class="bi bi-arrow-left ms-2"></i>
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
@endsection


