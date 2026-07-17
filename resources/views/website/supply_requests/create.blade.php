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
                                @elseif(!isset($provider))
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">القسم الرئيسي <span class="text-danger">*</span></label>
                                        @php $preselectCat = request('category') ?? ''; @endphp
                                        <select name="category_id" id="main_category" class="form-select select2" required>
                                            <option value="" {{ !$preselectCat ? 'selected' : '' }} disabled>اختر القسم الرئيسي</option>
                                            @foreach (\App\Models\Category::whereNull('parent_id')->get() as $category)
                                                <option value="{{ $category->id }}" {{ $preselectCat == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">القسم الفرعي <span class="text-danger">*</span></label>
                                        <select name="sub_category_id" id="sub_category_id" class="form-select select2" required>
                                            <option value="" selected disabled>اختر القسم الفرعي</option>
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

                                <!-- Voice Recording -->
                                <div class="col-md-12 mt-3">
                                    <label class="form-label fw-bold"><i class="bi bi-mic-fill me-1"></i> {{ __('website.voice_record') ?? 'تسجيل صوتي لوصف الطلب (اختياري)' }}</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <button type="button" id="startRecordBtn" class="btn btn-outline-primary"><i class="bi bi-mic me-1"></i> {{ __('website.start_recording') ?? 'بدء التسجيل' }}</button>
                                        <button type="button" id="stopRecordBtn" class="btn btn-danger d-none"><i class="bi bi-stop-circle me-1"></i> {{ __('website.stop_recording') ?? 'إيقاف التسجيل' }}</button>
                                        <audio id="audioPlayback" controls class="d-none"></audio>
                                        <button type="button" id="clearRecordBtn" class="btn btn-secondary d-none"><i class="bi bi-trash"></i></button>
                                    </div>
                                    <input type="file" name="voice_record" id="voice_record_file" class="d-none" accept="audio/*">
                                </div>

                                <div class="col-md-12 mt-4">
                                    <input type="hidden" name="location" id="location_address">
                                    <!-- Hidden lat/lng for map -->
                                    <input type="hidden" name="latitude" id="latitude">
                                    <input type="hidden" name="longitude" id="longitude">
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label fw-bold"><i class="bi bi-geo-alt-fill me-1"></i> {{ __('website.choose_location_on_map') ?? 'تحديد الموقع على الخريطة (اختياري)' }}</label>
                                        <button type="button" id="getCurrentLocation" class="btn btn-sm btn-outline-primary shadow-sm bg-white">
                                            <i class="bi bi-geo-alt-fill me-1"></i> {{ __('website.set_my_current_location') ?? 'تحديد موقعي الحالي' }}
                                        </button>
                                    </div>
                                    <!-- Map Container -->
                                    <div id="map" style="height: 250px; width: 100%; border-radius: 8px;"></div>
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
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=places&callback=initMap" async defer></script>
<script>
    let map, marker;
    function initMap() {
        const defaultLoc = { lat: 24.7136, lng: 46.6753 }; // Riyadh
        const mapEl = document.getElementById("map");
        if(mapEl) {
            map = new google.maps.Map(mapEl, {
                center: defaultLoc,
                zoom: 12,
            });
            marker = new google.maps.Marker({
                position: defaultLoc,
                map: map,
                draggable: true
            });

            map.addListener("click", (mapsMouseEvent) => {
                marker.setPosition(mapsMouseEvent.latLng);
                updateLatLng(mapsMouseEvent.latLng);
            });

            marker.addListener('dragend', function() {
                updateLatLng(marker.getPosition());
            });
        }
    }
    function updateLatLng(latLng) {
        document.getElementById('latitude').value = latLng.lat();
        document.getElementById('longitude').value = latLng.lng();
    }

    // Set Current Location Logic
    const locationBtn = document.getElementById('getCurrentLocation');
    if (locationBtn) {
        locationBtn.addEventListener('click', function() {
            if (navigator.geolocation) {
                const btn = this;
                const originalHtml = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> {{ __('website.loading') ?? "جاري التحميل..." }}';

                navigator.geolocation.getCurrentPosition(function(position) {
                    const pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    if (map && marker) {
                        map.setCenter(pos);
                        map.setZoom(15);
                        marker.setPosition(pos);
                        updateLatLng(marker.getPosition());
                    }
                    
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                }, function() {
                    alert("{{ __('website.location_access_denied') ?? 'عذراً، يرجى السماح بالوصول إلى الموقع.' }}");
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                });
            } else {
                alert("{{ __('website.geolocation_not_supported') ?? 'المتصفح الخاص بك لا يدعم تحديد الموقع.' }}");
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        let mediaRecorder;
        let audioChunks = [];
        
        const startRecordBtn = document.getElementById('startRecordBtn');
        const stopRecordBtn = document.getElementById('stopRecordBtn');
        const audioPlayback = document.getElementById('audioPlayback');
        const clearRecordBtn = document.getElementById('clearRecordBtn');
        const voiceRecordFileInput = document.getElementById('voice_record_file');

        if(startRecordBtn) {
            startRecordBtn.addEventListener('click', async () => {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    mediaRecorder = new MediaRecorder(stream);
                    audioChunks = [];

                    mediaRecorder.ondataavailable = event => {
                        if (event.data.size > 0) {
                            audioChunks.push(event.data);
                        }
                    };

                    mediaRecorder.onstop = () => {
                        const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                        const audioUrl = URL.createObjectURL(audioBlob);
                        audioPlayback.src = audioUrl;
                        audioPlayback.classList.remove('d-none');
                        clearRecordBtn.classList.remove('d-none');
                        
                        const file = new File([audioBlob], "voice_record_" + Date.now() + ".webm", {
                            type: "audio/webm",
                        });
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        voiceRecordFileInput.files = dataTransfer.files;
                        
                        stream.getTracks().forEach(track => track.stop());
                    };

                    mediaRecorder.start();
                    startRecordBtn.classList.add('d-none');
                    stopRecordBtn.classList.remove('d-none');
                } catch (err) {
                    alert("{{ __('website.microphone_access_denied', [], app()->getLocale(), 'عفوا، يرجى السماح بالوصول إلى الميكروفون.') }}");
                }
            });

            stopRecordBtn.addEventListener('click', () => {
                if(mediaRecorder && mediaRecorder.state !== 'inactive') {
                    mediaRecorder.stop();
                    stopRecordBtn.classList.add('d-none');
                    startRecordBtn.classList.remove('d-none');
                    startRecordBtn.innerHTML = '<i class="bi bi-mic me-1"></i> {{ __('website.record_again', [], app()->getLocale(), 'تسجيل مرة أخرى') }}';
                }
            });

            clearRecordBtn.addEventListener('click', () => {
                audioPlayback.src = "";
                audioPlayback.classList.add('d-none');
                clearRecordBtn.classList.add('d-none');
                voiceRecordFileInput.value = "";
                startRecordBtn.innerHTML = '<i class="bi bi-mic me-1"></i> {{ __('website.start_recording') ?? "بدء التسجيل" }}';
            });
        }
    });
</script>
@endpush
@endsection


