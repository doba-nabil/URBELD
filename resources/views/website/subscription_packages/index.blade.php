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
                @php
                    $isRecommended = $package->is_recommended ?? false; 
                @endphp
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="{{ 0.1 * ($loop->index + 1) }}s">
                    <div class="up-pkg-card {{ $isRecommended ? 'recommended-pkg' : '' }}">
                        @if($isRecommended)
                            <div class="up-pkg-badge">{{ __('website.recommended_package') ?? 'الباقة الموصى بها' }}</div>
                        @endif
                        
                        <div class="up-pkg-name">{{ $package->name }}</div>
                        
                        <div class="up-pkg-price-wrap">
                            <div class="up-pkg-price">{{ number_format($package->price, 0) }}</div>
                            <div class="up-pkg-duration">{{ __('website.rs_per_year') ?? 'ريال / سنوياً' }}</div>
                        </div>
                        
                        <ul class="up-pkg-features">
                            <li>
                                <i class="bi bi-check-circle up-pkg-icon"></i>
                                <span class="feature-text">{{ $package->max_services > 0 ? $package->max_services : __('admin.all') }} {{ __('website.services_count') ?? 'خدمات مدرجة' }}</span>
                            </li>
                            <li>
                                <i class="bi bi-check-circle up-pkg-icon"></i>
                                <span class="feature-text">{{ $package->works_limit > 0 ? $package->works_limit : __('admin.all') }} {{ __('website.works_count') ?? 'أعمال معرض' }}</span>
                            </li>
                            @php
                                $pkgFeatures = $package->features;
                                if(is_string($pkgFeatures)) $pkgFeatures = json_decode($pkgFeatures, true);
                            @endphp
                            @if(is_array($pkgFeatures))
                                @foreach($pkgFeatures as $feature)
                                    @if(!empty($feature))
                                        <li>
                                            <i class="bi bi-check-circle up-pkg-icon"></i>
                                            <span class="feature-text">{{ $feature }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            @endif
                        </ul>
                        
                        <div class="mt-auto">
                            <a href="{{ route('checkout.package', $package->id) }}" class="up-pkg-btn {{ $isRecommended ? 'recommended-btn' : '' }}">
                                {{ __('website.choose_package') ?? 'اختيار الباقة' }}
                            </a>
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
@endsection
