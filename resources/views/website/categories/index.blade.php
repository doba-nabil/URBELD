@extends('layouts.website')
@section('body_class', 'sup-page')
@section('content')
<!-- Header Start -->
<div class="services-header-section">
    <div class="container p-5 mb-5">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="services-header-title wow fadeInUp" data-wow-delay="0.1s">
                    {{ __('website.nav_services') }}
                </h1>
                <nav aria-label="breadcrumb" class="services-breadcrumb mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('website.nav_home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('website.nav_services') }}</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-6">
                <p class="services-header-description wow fadeInUp" data-wow-delay="0.2s">
                    {{ __('website.contact_intro') }}
                </p>
            </div>
        </div>
    </div>
</div>
<!-- Header End -->

<!-- Services Category Start -->
<div class="container-fluid bg-white services-section">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 700px;">
            <span class="section-label">{{ __('website.what_we_offer') }}</span>
            <h1 class="mb-4 mt-3">{{ \App\Models\Setting::getValue('home_services_title', app()->getLocale(), __('website.services_overview')) }}</h1>
        </div>

        <div class="services-grid">
            @forelse($categories as $category)
            <a href="{{ route('website.category.show', $category->id) }}" class="service-card-flip wow fadeInUp" data-wow-delay="{{ 0.1 * $loop->iteration }}s">
                <div class="service-arrow-flip-fixed">
                    <i class="bi bi-arrow-up-left"></i>
                </div>
                <div class="flip-card-inner">
                    <!-- Front Face -->
                    <div class="flip-card-front">
                        <div class="service-image-full-front">
                            <img src="{{ $category->getFirstMediaUrl('categories') ?: asset('website/assets/img/logo.png') }}" alt="{{ $category->name }}" class="service-bg-front">
                            <div class="service-overlay-green"></div>
                        </div>
                        <div class="service-content-front">
                            <h5>{{ $category->name }}</h5>
                            <p>{{ Str::limit($category->description, 60) }}</p>
                        </div>
                    </div>
                    <!-- Back Face -->
                    <div class="flip-card-back">
                        <div class="service-link">
                            <div class="service-content">
                                <h5>{{ $category->name }}</h5>
                                <p>{{ Str::limit($category->description, 60) }}</p>
                            </div>
                            <div class="service-image-wrapper">
                                <img src="{{ $category->getFirstMediaUrl('categories') ?: asset('website/assets/img/logo.png') }}" alt="{{ $category->name }}" class="service-image">
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-12 text-center">
                <p>{{ __('website.no_services_available') }}</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
<!-- Services Category End -->
@endsection
