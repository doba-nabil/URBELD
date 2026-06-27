@extends('layouts.website')

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

<!-- Contact Page Header -->
<div class="contact-page-header">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <div class="contact-header-content">
                    <h1 class="contact-main-title wow fadeInUp" data-wow-delay="0.1s">{{ __('website.contact_us') }}</h1>
                    <nav aria-label="breadcrumb" class="contact-breadcrumb wow fadeInUp" data-wow-delay="0.2s">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('website.nav_home') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('website.contact_us') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-lg-12 text-center">
                    {{ __('website.contact_intro') }}
            </div>
        </div>
    </div>
</div>

<!-- Contact Information Cards -->
<div class="contact-info-section">
    <div class="container">
        <div class="row g-4">
            <!-- Email Card -->
            <div class="col-lg-4 col-md-6">
                <div class="contact-info-card wow fadeInUp" data-wow-delay="0.1s">
                    <div class="contact-info-icon email-icon">
                        <i class="bi bi-envelope"></i>
                        <span class="at-symbol">@</span>
                    </div>
                    <h5 class="contact-info-title">{{ __('website.support_email') }}</h5>
                    <p class="contact-info-text">{{ $settings['site_email'] }}</p>
                    <a href="mailto:{{ $settings['site_email'] }}" class="btn-contact-action">{{ __('website.email_us') }}</a>
                </div>
            </div>

            <!-- Phone Card -->
            <div class="col-lg-4 col-md-6">
                <div class="contact-info-card wow fadeInUp" data-wow-delay="0.2s">
                    <div class="contact-info-icon">
                        <i class="bi bi-telephone"></i>
                    </div>
                    <h5 class="contact-info-title">{{ __('website.phone') }}</h5>
                    <p class="contact-info-text">{{ $settings['site_phone'] }}</p>
                    <a href="tel:{{ str_replace([' ', '(', ')', '-'], '', $settings['site_phone']) }}" class="btn-contact-action">{{ __('website.call_us') }}</a>
                </div>
            </div>

            <!-- Location Card -->
            <div class="col-lg-4 col-md-6">
                <div class="contact-info-card wow fadeInUp" data-wow-delay="0.3s">
                    <div class="contact-info-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <h5 class="contact-info-title">{{ __('website.location') }}</h5>
                    <p class="contact-info-text">{{ \App\Models\Setting::getValue('site_address', app()->getLocale(), 'شارع خالد بن الوليد، الرياض، المملكة العربية السعودية') }}</p>
                    <a href="#map-section" class="btn-contact-action">{{ __('website.visit_us') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact Form and Map Section -->
<div class="contact-form-map-section">
    <div class="container">
        @php
            $hasMap = isset($settings['location']['lat']) && isset($settings['location']['long']) && $settings['location']['lat'] && $settings['location']['long'];
        @endphp
        <div class="row">
            <!-- Contact Form -->
            <div class="{{ $hasMap ? 'col-lg-6' : 'col-lg-8 mx-auto' }}">
                <div class="leave-message-section wow fadeInUp" data-wow-delay="0.1s">
                    <h2 class="leave-message-title">{{ __('website.leave_message') }}</h2>
                    <form class="contact-message-form" id="contactMessageForm" method="POST" action="{{ route('website.contact.store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-12">
                                <input type="text" name="name" class="form-control contact-message-input" 
                                    placeholder="{{ __('website.full_name') }}" required>
                            </div>
                            <div class="col-12">
                                <input type="email" name="email" class="form-control contact-message-input" 
                                    placeholder="{{ __('website.email') }}" required>
                            </div>
                            <div class="col-12">
                                <input type="text" name="phone" class="form-control contact-message-input" 
                                    placeholder="{{ __('website.phone') }}">
                            </div>
                            <div class="col-12">
                                <textarea name="message" class="form-control contact-message-input" rows="5" 
                                    placeholder="{{ __('website.message') }}" required></textarea>
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-icon py-3 px-5 animated fadeIn btn-get-callback mx-auto">
                                    <span>{{ __('website.request_call') }}</span>
                                    <i class="icon-btn bi bi-arrow-up-left"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if($hasMap)
                <!-- Map Section -->
                    <div class="map-section wow fadeInUp" data-wow-delay="0.2s" id="map-section">
                        <iframe 
                            src="https://www.google.com/maps/embed/v1/place?q={{ $settings['location']['lat'] }},{{ $settings['location']['long'] }}&key={{ config('services.google_maps.key') }}" 
                            width="100%" 
                            height="100%" 
                            style="border:0; border-radius: 20px;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Contact form handling with real AJAX
    document.addEventListener('DOMContentLoaded', function () {
        const contactForm = document.getElementById('contactMessageForm');
        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get form button
                const submitBtn = this.querySelector('.btn-get-callback');
                const originalText = submitBtn.innerHTML;
                
                // Show loading state
                submitBtn.innerHTML = '<span>{{ __('website.sending') }}</span>';
                submitBtn.disabled = true;

                let formData = new FormData(this);

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Success message
                    submitBtn.innerHTML = '<span>{{ __('website.sent_successfully') }}</span>';
                    submitBtn.style.background = '#28a745';
                    
                    // Reset form
                    contactForm.reset();
                    
                    // Reset button after 3 seconds
                    setTimeout(function() {
                        submitBtn.innerHTML = originalText;
                        submitBtn.style.background = '';
                        submitBtn.disabled = false;
                    }, 3000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    submitBtn.innerHTML = '<span>{{ __('website.error_occurred') }}</span>';
                    submitBtn.style.background = '#dc3545';
                    setTimeout(function() {
                        submitBtn.innerHTML = originalText;
                        submitBtn.style.background = '';
                        submitBtn.disabled = false;
                    }, 3000);
                });
            });
        }
    });
</script>
@endpush
