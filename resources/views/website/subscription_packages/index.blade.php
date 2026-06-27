@extends('layouts.website')

@section('title', __('website.nav_subscription_packages') . ' - ' . config('app.name'))
@push('css')
<style>
    body {
        background-color: #ffffff;
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

<!-- Contact Page Header -->
<div class="contact-page-header">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <div class="contact-header-content">
                    <h1 class="contact-main-title wow fadeInUp" data-wow-delay="0.1s">{{ __('website.nav_subscription_packages') }}</h1>
                    <nav aria-label="breadcrumb" class="contact-breadcrumb wow fadeInUp" data-wow-delay="0.2s">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('website.nav_home') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('website.nav_subscription_packages') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-lg-12 text-center">
            </div>
        </div>
    </div>
</div>

<!-- Pricing Plan Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
            <h1 class="mb-3">{{ __('website.pricing_title') }}</h1>
            <p>{{ __('website.pricing_subtitle') }}</p>
        </div>
        <div class="row g-4 justify-content-center">
            @foreach($packages as $package)
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="{{ 0.1 * ($loop->index + 1) }}s">
                    <div class="card h-100 border-0 shadow-lg pricing-item rounded-4 overflow-hidden">
                        <div class="p-4 text-center bg-primary text-white">
                            <h4 class="text-white mb-2">{{ $package->name }}</h4>
                            <div class="display-5 mb-0">
                                {{ $package->price }} <small class="fs-6">{{ __('website.rs') }}</small>  <span class="fs-6">  {{ '/' . ' ' .$package->duration_days }} {{ __('admin.days') }}</span>
                            </div>
                            
                        </div>
                        <div class="card-body p-4 bg-white">
                            @if($package->description)
                                <p class="text-muted text-center mb-4">{{ $package->description }}</p>
                            @endif
                            <ul class="list-unstyled mb-4">
                                @php
                                    $features = $package->features;
                                    if(is_string($features)) $features = json_decode($features, true);
                                @endphp
                                <li class="mb-2 d-flex align-items-start">
                                    <i class="bi bi-check-circle-fill text-primary me-2 mt-1"></i>
                                    <span>{{ __('website.max_services') }}: {{ $package->max_services > 0 ? $package->max_services : __('admin.all') }}</span>
                                </li>
                                <li class="mb-2 d-flex align-items-start">
                                    <i class="bi bi-check-circle-fill text-primary me-2 mt-1"></i>
                                    <span>{{ __('website.works_limit') }}: {{ $package->works_limit > 0 ? $package->works_limit : __('admin.all') }}</span>
                                </li>
                                @if(is_array($features))
                                    @foreach($features as $feature)
                                        @if($feature)
                                            <li class="mb-2 d-flex align-items-start">
                                                <i class="bi bi-check-circle-fill text-primary me-2 mt-1"></i>
                                                <span>{{ $feature }}</span>
                                            </li>
                                        @endif
                                    @endforeach
                                @endif
                            </ul>
                            <div class="text-center mt-auto">
                                <a href="{{ route('contact') }}" class="btn btn-primary px-4 py-2 rounded-pill w-100 fw-bold shadow-sm transition-all hover-up">
                                    {{ __('website.subscribe_now') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @if($packages->isEmpty())
                <div class="col-12 text-center py-5">
                    <div class="alert alert-info d-inline-block px-5">
                        {{ __('website.no_packages_available') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
<!-- Pricing Plan End -->

<style>
.pricing-item {
    transition: all 0.3s ease;
}
.pricing-item:hover {
    transform: translateY(-10px);
}
.hover-up:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 185, 142, 0.3) !important;
}
.rounded-4 {
    border-radius: 1.5rem !important;
}
.transition-all {
    transition: all 0.3s ease;
}
</style>
@endsection
