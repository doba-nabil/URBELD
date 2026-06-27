@extends('layouts.website')

@section('title', __('website.new_service_request_title'))

@push('css')
    <style>
        .dyn-req {
            transition: all 0.3s ease;
        }
    </style>
@endpush

@section('content')
<!-- Header Start -->
    <div class="services-header-section without-search">
        <div class="container p-md-5 p-4 mb-md-5">
            <div class="row align-items-center">
                <div class="col-lg-12">

                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->
    <div class="contact-form-section" style="padding-top: 60px;">
        <div class="contact-background-overlay"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-9">
                    <div class="contact-form-card wow fadeInUp" data-wow-delay="0.2s">
                        <div class="contact-form-card-content">
                            <!-- Quick Enquiry Badge -->
                                <div class="text-center mb-4">
                                    <span class="quick-enquiry-badge">
                                        @if(request('is_consultation'))
                                            {{ __('website.consultation_request') }}
                                        @else
                                            {{ __('website.new_request') }}
                                        @endif
                                    </span>
                                </div>
 
                                 @php
                                     $siteLogo = app()->getLocale() == 'ar' 
                                                ? \App\Models\Setting::getMediaUrl('logo_ar') 
                                                : \App\Models\Setting::getMediaUrl('logo_en');
                                     $siteLogo = $siteLogo ?: asset('website/assets/img/logo.png');
                                 @endphp

                                 @if (isset($service))
                                     <div class="alert alert-primary border-0 rounded-3 shadow-sm mb-4"
                                         style="background-color: #f0f7ff;">
                                         <div class="d-flex align-items-center">
                                             <img src="{{ $service->getFirstMediaUrl('services') ?: $siteLogo }}"
                                                 alt="{{ $service->title }}" class="rounded me-3"
                                                 style="width: 70px; height: 70px; object-fit: cover; border: 2px solid #fff;">
                                             <div>
                                                 <h6 class="mb-1 text-dark fw-bold">{{ __('website.service') }}: {{ $service->title }}</h6>
                                                 <p class="mb-0 small text-muted"><i class="bi bi-person me-1"></i> {{ $service->user->name }}</p>
                                             </div>
                                         </div>
                                     </div>
                                 @elseif (isset($provider))
                                     <div class="alert alert-info border-0 rounded-3 shadow-sm mb-4"
                                         style="background-color: #eef2f5;">
                                         <div class="d-flex align-items-center">
                                             <img src="{{ $provider->getFirstMediaUrl('personal_photo') ?: ($provider->getFirstMediaUrl('users') ?: $siteLogo) }}"
                                                 alt="{{ $provider->name }}" class="rounded-circle me-3"
                                                 style="width: 55px; height: 55px; object-fit: cover; border: 2px solid #fff;">
                                             <div>
                                                 <h6 class="mb-1 text-dark fw-bold">{{ __('website.request_directed_to') }}: {{ $provider->name }}</h6>
                                                 <p class="mb-0 small text-muted"><i class="bi bi-info-circle me-1"></i> {{ __('website.request_directed_desc') }}</p>
                                             </div>
                                         </div>
                                     </div>
                                 @else
                                     <h2 class="contact-form-title text-center mb-4">
                                         @if(request('is_consultation'))
                                             {{ __('website.request_consultant') }}
                                         @else
                                             {{ __('website.create_new_request_desc') }}
                                         @endif
                                     </h2>
                                 @endif
 
                                 <!-- Form -->
                                 <form class="contact-form" id="requestForm" action="{{ route('requests.store') }}"
                                     method="POST" enctype="multipart/form-data">
                                     @csrf
                                     <input type="hidden" name="is_consultation" value="{{ request('is_consultation', 0) }}">
                                     @if (isset($service))
                                         <input type="hidden" name="service_id" value="{{ $service->id }}">
                                         <input type="hidden" name="provider_id" value="{{ $service->user_id }}">
                                     @elseif (isset($provider))
                                         <input type="hidden" name="provider_id" value="{{ $provider->id }}">
                                     @endif

                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="mb-2 fw-bold">{{ __('website.main_category') }} <span class="text-danger">*</span></label>
                                        @php 
                                            $preselectCat = $service->category_id ?? (request('category') ?? ''); 
                                        @endphp
                                        <select name="category_id" id="main_category" class="form-select contact-input"
                                            required
                                            {{ (isset($provider) || isset($service)) && $preselectCat ? 'style=pointer-events:none;background-color:#e9ecef;' : '' }}>
                                            <option value="" {{ !$preselectCat ? 'selected' : '' }} disabled>{{ __('website.choose_category') }}</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ $preselectCat == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @if ((isset($provider) || isset($service)) && $preselectCat)
                                            <!-- Ensure it submits if select is visually disabled -->
                                            <input type="hidden" name="category_id" value="{{ $preselectCat }}">
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <label class="mb-2 fw-bold">{{ __('website.required_service') }} <span
                                                class="text-danger">*</span></label>
                                        <select name="sub_category_id" id="sub_category_id"
                                            class="form-select contact-input" required>
                                            <option value="" selected disabled>{{ __('website.choose_sub_category') }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="mb-2 fw-bold">{{ __('website.city') }} <span class="text-danger">*</span></label>
                                        <select name="city_id" class="form-select contact-input" required>
                                            <option value="" selected disabled>{{ __('website.choose_city') }}</option>
                                            @foreach (\App\Models\City::orderBy('name')->get() as $city)
                                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label class="mb-2 fw-bold">{{ __('website.neighborhood') }}</label>
                                        <input type="text" name="neighborhood" class="form-control contact-input"
                                            placeholder="{{ __('website.enter_neighborhood') }}">
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <input type="hidden" name="location" id="location_address">
                                        <!-- Hidden lat/lng for map -->
                                        <input type="hidden" name="latitude" id="latitude">
                                        <input type="hidden" name="longitude" id="longitude">
                                        
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="fw-bold">{{ __('website.choose_location_on_map') ?? 'تحديد الموقع على الخريطة' }}</label>
                                            <button type="button" id="getCurrentLocation" class="btn btn-sm btn-outline-primary shadow-sm bg-white">
                                                <i class="bi bi-geo-alt-fill me-1"></i> {{ __('website.set_my_current_location') }}
                                            </button>
                                        </div>
                                        <!-- Map Container -->
                                        <div id="map" style="height: 250px; width: 100%; border-radius: 8px;"></div>
                                    </div>


                                    <div class="col-md-12 mt-3">
                                        <label class="mb-2 fw-bold">{{ __('website.detailed_description') }} <span
                                                class="text-danger">*</span></label>
                                        <textarea name="description" class="form-control contact-input" rows="5"
                                            placeholder="{{ __('website.write_details_here') }}" required></textarea>
                                    </div>

                                    <!-- Voice Recording -->
                                    <div class="col-md-12 mt-3">
                                        <label class="mb-2 fw-bold">{{ __('website.voice_record') }}</label>
                                        <div class="d-flex align-items-center gap-3">
                                            <button type="button" id="startRecordBtn" class="btn btn-outline-primary"><i class="bi bi-mic me-1"></i> {{ __('website.start_recording') }}</button>
                                            <button type="button" id="stopRecordBtn" class="btn btn-danger d-none"><i class="bi bi-stop-circle me-1"></i> {{ __('website.stop_recording') }}</button>
                                            <audio id="audioPlayback" controls class="d-none"></audio>
                                            <button type="button" id="clearRecordBtn" class="btn btn-secondary d-none"><i class="bi bi-trash"></i></button>
                                        </div>
                                        <input type="file" name="voice_record" id="voice_record_file" class="d-none" accept="audio/*">
                                    </div>

                                    <div class="col-md-12 mt-4">
                                        <label class="mb-2 fw-bold">{{ __('website.attach_files') }}</label>
                                        <div class="dropzone" id="attachmentsDropzone"
                                            style="border: 2px dashed var(--primary); border-radius: 10px; background: #f8f9fa;">
                                            <div class="dz-message needsclick text-center py-4">
                                                <i class="bi bi-cloud-arrow-up display-4 text-primary mb-3"></i>
                                                <h5>{{ __('website.drag_drop_files') }}</h5>
                                                <span class="text-muted fs-7">{{ __('website.supported_files') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer -->
                                <div class="contact-form-footer mt-4 border-top pt-4">
                                    <!-- Error Alert Area for Dropzone AJAX -->
                                    <div id="formErrors" class="alert alert-danger d-none mb-3"></div>

                                    <button type="submit" id="submitRequestBtn"
                                        class="btn btn-icon py-3 px-5 animated fadeIn w-100">
                                        <span>{{ __('website.send_request') }}</span>
                                        <i class="icon-btn bi bi-arrow-up-left"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    @php
        $mappedCategories = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name, // Gets evaluated string instead of ['ar' => '...', 'en' => '...'] object
                'children' => $category->children
                    ->map(function ($child) {
                        return [
                            'id' => $child->id,
                            'name' => $child->name,
                        ];
                    })
                    ->values(),
            ];
        });
    @endphp
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
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> {{ __('website.loading') }}';

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
                        alert("{{ __('website.location_access_denied') }}");
                        btn.disabled = false;
                        btn.innerHTML = originalHtml;
                    });
                } else {
                    alert("{{ __('website.geolocation_not_supported') }}");
                }
            });
        }

        // Disable Dropzone auto-discovery globally before DOMContentLoaded
        if (typeof Dropzone !== 'undefined') {
            Dropzone.autoDiscover = false;
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Encode categories with the evaluated name property since they are likely Translatable
            const categories = @json($mappedCategories);
            const mainCategorySelect = document.getElementById('main_category');
            const subCategorySelect = document.getElementById('sub_category_id');

            const providerIdInput = document.querySelector('input[name="provider_id"]');
            let providerSubCategories = null;

            function populateSubCategories(categoryId) {
                subCategorySelect.innerHTML =
                    '<option value="" selected disabled>{{ __('website.choose_sub_category') }}</option>';
                
                if (window.providerSubCategories) {
                    const filtered = window.providerSubCategories.filter(s => s.parent_id == categoryId);
                    if (filtered.length > 0) {
                        filtered.forEach(sub => {
                            const option = document.createElement('option');
                            option.value = sub.id;
                            option.textContent = sub.name;
                            subCategorySelect.appendChild(option);
                        });
                    } else {
                        const option = document.createElement('option');
                        option.value = "";
                        option.textContent = "{{ __('website.no_sub_categories') }}";
                        option.disabled = true;
                        subCategorySelect.appendChild(option);
                    }
                } else {
                    const selectedCat = categories.find(cat => cat.id == categoryId);
                    if (selectedCat && selectedCat.children && selectedCat.children.length > 0) {
                        selectedCat.children.forEach(sub => {
                            const option = document.createElement('option');
                            option.value = sub.id;
                            option.textContent = sub.name;
                            subCategorySelect.appendChild(option);
                        });
                    } else if (selectedCat) {
                        const option = document.createElement('option');
                        option.value = "";
                        option.textContent = "{{ __('website.no_sub_categories') }}";
                        option.disabled = true;
                        subCategorySelect.appendChild(option);
                    }
                }
            }

            // Populate on change
            mainCategorySelect.addEventListener('change', function() {
                populateSubCategories(this.value);
            });

            // If provider_id exists, fetch their specific categories via AJAX
            if (providerIdInput && providerIdInput.value) {
                fetch('{{ route('requests.provider-categories', ':id') }}'.replace(':id', providerIdInput.value))
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            window.providerSubCategories = data.sub_categories;
                            
                            // Store current value to restore it after repopulating
                            const currentVal = mainCategorySelect.value;
                            
                            // Filter main categories list
                            mainCategorySelect.innerHTML = '<option value="" disabled>{{ __('website.choose_category') }}</option>';
                            data.main_categories.forEach(cat => {
                                const option = new Option(cat.name, cat.id);
                                mainCategorySelect.add(option);
                            });
                            
                            // Restore value if it exists in the new list
                            if (currentVal) mainCategorySelect.value = currentVal;
                            
                            // If still have a value, populate subcategories
                            if (mainCategorySelect.value) {
                                populateSubCategories(mainCategorySelect.value);
                                
                                // Pre-select subcategory if provided in query param or service object
                                const urlParams = new URLSearchParams(window.location.search);
                                const preselectSub = "{{ $service->sub_category_id ?? '' }}" || urlParams.get('subcategory');
                                if (preselectSub) {
                                    subCategorySelect.value = preselectSub;
                                }
                            }
                        }
                    });
            } else {
                // Populate on load if pre-selected (general request)
                if (mainCategorySelect.value) {
                    populateSubCategories(mainCategorySelect.value);

                    // Pre-select subcategory if provided in query param or service object
                    const urlParams = new URLSearchParams(window.location.search);
                    const preselectSub = "{{ $service->sub_category_id ?? '' }}" || urlParams.get('subcategory');
                    if (preselectSub) {
                        subCategorySelect.value = preselectSub;
                    }
                }
            }
            
            // Auto update hidden location
            const citySelect = document.querySelector('select[name="city_id"]');
            const neighborhoodInput = document.querySelector('input[name="neighborhood"]');
            const locationInput = document.getElementById('location_address');

            function updateLocationText() {
                let cityText = '';
                if (citySelect && citySelect.selectedIndex > 0) {
                    cityText = citySelect.options[citySelect.selectedIndex].text;
                }
                let neighborhoodText = neighborhoodInput ? neighborhoodInput.value.trim() : '';
                
                if (cityText && neighborhoodText) {
                    locationInput.value = cityText + ' - ' + neighborhoodText;
                } else if (cityText) {
                    locationInput.value = cityText;
                } else {
                    locationInput.value = '';
                }
            }

            if (citySelect) citySelect.addEventListener('change', updateLocationText);
            if (neighborhoodInput) neighborhoodInput.addEventListener('input', updateLocationText);

            // Voice Recording Logic
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
                    startRecordBtn.innerHTML = '<i class="bi bi-mic me-1"></i> {{ __('website.start_recording') }}';
                });
            }

            // Dropzone Configuration
            if (typeof Dropzone !== 'undefined') {

                const myDropzone = new Dropzone("#attachmentsDropzone", {
                    url: document.getElementById('requestForm').action,
                    autoProcessQueue: false,
                    uploadMultiple: true,
                    parallelUploads: 10,
                    maxFiles: 10,
                    paramName: "attachments",
                    acceptedFiles: "image/*,application/pdf",
                    addRemoveLinks: true,
                    dictRemoveFile: "{{ __('website.delete_file') }}",
                    dictCancelUpload: "{{ __('website.cancel') }}",
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    init: function() {
                        const dz = this;
                        const submitBtn = document.getElementById('submitRequestBtn');
                        const form = document.getElementById('requestForm');
                        const formErrors = document.getElementById('formErrors');

                        submitBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();

                            if (!form.checkValidity()) {
                                form.reportValidity();
                                return;
                            }

                            submitBtn.disabled = true;
                            submitBtn.innerHTML =
                                '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> {{ __('website.sending') }}';
                            formErrors.classList.add('d-none');

                            if (dz.getQueuedFiles().length > 0) {
                                dz.processQueue();
                            } else {
                                // Submit standard via AJAX manually since we removed standard submission to unify UX
                                let formData = new FormData(form);
                                fetch(form.action, {
                                        method: 'POST',
                                        body: formData,
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest'
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            window.location.href = data.redirect;
                                        } else {
                                            handleAjaxError(data);
                                        }
                                    })
                                    .catch(err => {
                                        formErrors.innerHTML =
                                            '{{ __('website.server_error') }}';
                                        formErrors.classList.remove('d-none');
                                        resetSubmitBtn();
                                    });
                            }
                        });

                        this.on("sendingmultiple", function(data, xhr, formData) {
                            let formElements = new FormData(form);
                            // Append all form inputs EXCEPT attachments[] to the Dropzone request
                            for (let [name, value] of formElements.entries()) {
                                if (name !== 'attachments' && name !== 'attachments[]') {
                                    formData.append(name, value);
                                }
                            }
                        });

                        this.on("successmultiple", function(files, response) {
                            // Since we are parsing JSON from a Fetch request normally, Dropzone returns parsed JSON response as well
                            if (response.success) {
                                window.location.href = response.redirect;
                            } else {
                                handleAjaxError(response);
                                dz.removeAllFiles(true);
                            }
                        });

                        this.on("errormultiple", function(files, response, xhr) {
                            // response could be JSON object or string
                            let msg = typeof response === 'string' ? response : (response
                                .message || '{{ __('website.upload_error') }}');
                            if (xhr && xhr.status === 422 && response.errors) {
                                msg = Object.values(response.errors).map(e => e.join('<br>'))
                                    .join('<br>');
                            }
                            formErrors.innerHTML = msg;
                            formErrors.classList.remove('d-none');
                            resetSubmitBtn();
                            dz.removeAllFiles(true);
                        });

                        function resetSubmitBtn() {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML =
                                '<span>{{ __('website.send_request') }}</span><i class="icon-btn bi bi-arrow-up-left"></i>';
                        }

                        function handleAjaxError(data) {
                            formErrors.innerHTML = data.message || '{{ __('website.error_occurred') }}';
                            formErrors.classList.remove('d-none');
                            resetSubmitBtn();
                        }
                    }
                });
            }
        });
    </script>
@endpush
