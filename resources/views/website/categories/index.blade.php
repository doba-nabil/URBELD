@extends('layouts.website')
@section('body_class', 'sup-page')
@section('content')
<!-- Header Start -->
<div class="services-header-section bg-notwhite">
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
<div class="container-fluid services-section">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 700px;">
            <span class="section-label">{{ __('website.what_we_offer') }}</span>
            <h1 class="mb-4 mt-3">{{ \App\Models\Setting::getValue('home_services_title', app()->getLocale(), __('website.services_overview')) }}</h1>
        </div>

        <div class="services-grid">
            @forelse($categories as $index => $category)
                @if($index == count($categories) - 1)
                    @if(\App\Models\Setting::getValue('show_suppliers_card', null, '1') == '1')
                        <div class="service-card wow fadeInUp" data-wow-delay="0.1s">
                            <a href="{{ \App\Models\Setting::getValue('suppliers_card_link', null, route('website.suppliers.index')) }}" class="service-link">
                                <div class="service-image-wrapper">
                                    <img src="{{ \App\Models\Setting::getMediaUrl('suppliers_card_image') ?: asset('website/assets/img/suppliers-icon.png') }}" alt="{{ \App\Models\Setting::getValue('suppliers_card_title', app()->getLocale(), 'شركات التوريد والمواد') }}" class="service-image" style="width: auto; max-height: 120px; object-fit: contain;">
                                    <div class="service-arrow">
                                        <i class="bi bi-arrow-up-left"></i>
                                    </div>
                                </div>
                                <div class="service-content">
                                    <h5>{{ \App\Models\Setting::getValue('suppliers_card_title', app()->getLocale(), 'شركات التوريد والمواد') }}</h5>
                                    <p>{{ \App\Models\Setting::getValue('suppliers_card_desc', app()->getLocale(), 'نربطك بأفضل موردي مواد البناء والمعدات الهندسية في المملكة، قارن الأسعار واطلب عروضاً مباشرةً من خلال المنصة.') }}</p>
                                </div>
                            </a>
                        </div>
                    @endif
                @endif

                <div class="service-card wow fadeInUp {{ $category->is_full_width ? 'text-center' : '' }}" data-wow-delay="{{ 0.1 * $loop->iteration }}s" style="{{ $category->is_full_width ? 'grid-column: 1 / -1;' : '' }}">
                    <a href="{{ route('website.category.show', $category->id) }}" class="service-link">
                        <div class="service-image-wrapper">
                            <img src="{{ $category->getFirstMediaUrl('categories') ?: asset('website/assets/img/logo.png') }}" alt="{{ $category->name }}" class="service-image">
                            <div class="service-arrow">
                                <i class="bi bi-arrow-up-left"></i>
                            </div>
                        </div>
                        <div class="service-content">
                            <h5>{{ $category->name }}</h5>
                            <p>{{ Str::limit($category->description, 60) }}</p>
                        </div>
                    </a>
                </div>
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
