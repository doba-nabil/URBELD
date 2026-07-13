@extends('layouts.website')

@section('content')
    <!-- Header Start -->
    @php
        $mainBgType = \App\Models\Setting::getValue('main_background_type', null, 'image');
        $mainBgMedia = \App\Models\Setting::getMediaUrl('main_background') ?: \App\Models\Setting::getMediaUrl('home_hero_image');
        $mainBgFallbackUrl = $mainBgMedia ?: asset('website/assets/img/header-bg.jpg');
    @endphp

    <div class="header main-section position-relative overflow-hidden"
        @if($mainBgType == 'image')
            style="background-image: url('{{ $mainBgFallbackUrl }}'); background-size: cover; background-position: center;"
        @endif>

        @if($mainBgType == 'video')
            <video autoplay loop muted playsinline class="position-absolute w-100 h-100" style="top: 0; left: 0; z-index: 0; object-fit: cover;">
                <source src="{{ $mainBgFallbackUrl }}" type="video/mp4">
            </video>
            <div class="position-absolute w-100 h-100" style="top: 0; left: 0; z-index: 0; background: rgba(0,0,0,0.4);"></div>
        @endif

        <div class="container position-relative" style="z-index: 1;">
            <div class="row g-0 align-items-center flex-column-reverse flex-md-row">
                <div class="col-md-12 p-5 mt-lg-5">
                    <div class="main-content">
                        <h1 class="display-5 animated fadeIn mb-4">
                            {{ \App\Models\Setting::getValue('home_hero_title', app()->getLocale(), 'نصنع المستقبل من خلال التميز') }}
                        </h1>
                        <p class="animated fadeIn mb-4 pb-2">
                            {{ \App\Models\Setting::getValue('home_hero_desc', app()->getLocale(), 'نحن من أفضل 25 شركة بناء وتطوير ملتزمون بالكامل بعملائنا. نوفر أفضل الحلول العقارية المبتكرة') }}
                        </p>
                    </div>
                    <style>
                        .hero-pill-btn {
                            transition: all 0.3s ease-in-out;
                        }
                        .hero-pill-btn:hover {
                            transform: translateY(-3px);
                            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
                        }
                        .hero-pill-btn:hover .icon-circle {
                            transform: scale(1.1) rotate(15deg);
                        }
                        .hero-pill-btn .icon-circle {
                            transition: all 0.3s ease-in-out;
                        }
                        .btn-tenders-all {
                            background-color: #ffffff !important;
                            color: #064B3B !important;
                            border: 2px solid #064B3B !important;
                            border-radius: 20px !important;
                            font-weight: bold !important;
                            transition: all 0.3s ease !important;
                        }
                        .btn-tenders-all:hover {
                            background-color: #064B3B !important;
                            color: #ffffff !important;
                            box-shadow: 0 4px 15px rgba(6, 75, 59, 0.4) !important;
                            transform: translateY(-2px) !important;
                        }
                    </style>
                    <div class="content d-flex gap-3 justify-content-center flex-wrap">
                        @php
                            $homeVideoMedia = \App\Models\Setting::getMediaUrl('home_video');
                            $homeVideoUrl = \App\Models\Setting::getValue('home_video_url') ?: $homeVideoMedia;
                        @endphp
                        
                        <!-- Watch Video Button -->
                        @if($homeVideoUrl)
                            <a href="#" data-bs-toggle="modal" data-bs-target="#videoModal" class="btn rounded-pill d-inline-flex align-items-center gap-3 pe-2 ps-4 animated fadeIn hero-pill-btn" style="background-color: #aed9d9; color: #0b4541; font-weight: bold; text-decoration: none;">
                                <span>{{ __('website.watch_how_platform_works') ?? 'شاهد كيف تعمل المنصة' }}</span>
                                <span class="icon-circle rounded-circle d-flex align-items-center justify-content-center" style="background-color: #0b4541; color: #aed9d9; width: 40px; height: 40px;">
                                    <i class="bi bi-arrow-up-left fs-5"></i>
                                </span>
                            </a>
                        @endif

                        <!-- Search Provider Button -->
                        <a href="{{ route('providers.search') }}" class="btn rounded-pill d-inline-flex align-items-center gap-3 pe-2 ps-4 animated fadeIn hero-pill-btn" style="background-color: #ffffff; color: #333333; font-weight: bold; text-decoration: none; border: 1px solid #e0e0e0;">
                            <span>{{ __('website.search_service_provider') ?? 'إبحث عن مزود خدمة' }}</span>
                            <span class="icon-circle rounded-circle d-flex align-items-center justify-content-center" style="background-color: #aed9d9; color: #0b4541; width: 40px; height: 40px;">
                                <i class="bi bi-arrow-up-left fs-5"></i>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Video Modal -->
        @if($homeVideoUrl)
        <div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content bg-transparent border-0">
                    <div class="modal-body p-0 position-relative">
                        <!-- Floating Close Button -->
                        <button type="button" class="btn-close btn-close-white position-absolute m-3 shadow-none bg-dark rounded-circle p-2" 
                                data-bs-dismiss="modal" aria-label="Close" 
                                style="z-index: 1055; opacity: 0.9; top: -15px; right: -15px; border: 2px solid #fff;"></button>
                        
                        <div class="rounded-4 overflow-hidden bg-black border border-white border-opacity-10" style="box-shadow: 0 25px 50px -12px rgba(0,0,0,0.6) !important;">
                            @if(Str::contains($homeVideoUrl, ['youtube.com', 'youtu.be']))
                                @php
                                    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $homeVideoUrl, $match);
                                    $youtubeId = $match[1] ?? null;
                                @endphp
                                @if($youtubeId)
                                    <div class="ratio ratio-16x9">
                                        <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}?autoplay=1" allow="autoplay; encrypted-media" allowfullscreen style="border: 0;"></iframe>
                                    </div>
                                @endif
                            @else
                                <video class="w-100 d-block" controls autoplay style="max-height: 85vh; object-fit: contain;">
                                    <source src="{{ $homeVideoUrl }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Search Start -->
        <div class="search-section">
            <div class="search-background"></div>
            <div class="container">
                <form action="{{ route('providers.search') }}" method="GET">
                    <div class="search-content wow fadeIn" data-wow-delay="0.1s">
                        <div class="row g-2">
                            <div class="col-md-9">
                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <span class="search-text" style="font-size: 0.9rem;">
                                            {!! __('website.search_your_service') ?? 'ابحث عن الخدمة' !!}
                                        </span>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-select select2 border-0 py-3" name="region_id">
                                            <option selected value="">{{ __('website.region') ?? 'المنطقة' }}</option>
                                            <option value="makkah">{{ __('website.makkah_region') ?? 'منطقة مكة المكرمة' }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-select select2 border-0 py-3" name="city_id">
                                            <option selected value="">{{ __('website.city') }}</option>
                                            @foreach (\App\Models\City::orderBy('name')->get() as $city)
                                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-select select2 border-0 py-3" name="category_id">
                                            <option selected value="">{{ __('website.service') ?? 'التصنيف' }}</option>
                                            @foreach (\App\Models\Category::whereNull('parent_id')->where('is_active', true)->where('supports_supply_requests', false)->get() as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-icon py-3 px-5 me-3 animated fadeIn">
                                    <span>{{ __('website.search') }}</span>
                                    <i class="icon-btn bi bi-arrow-up-left"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Search End -->

    </div>
    <!-- Header End -->

    <!-- Banner Slider Start -->
    @if(isset($banners) && $banners->count() > 0)
    <div class="container-fluid py-4 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div id="homeBannerCarousel" class="carousel slide rounded overflow-hidden shadow-sm" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach($banners as $index => $banner)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                            <a href="{{ $banner->link ?? '#' }}" target="_blank">
                                <img src="{{ $banner->image_url }}" class="d-block w-100" alt="{{ $banner->title }}" style="max-height: 400px; object-fit: cover;">
                            </a>
                        </div>
                    @endforeach
                </div>
                @if($banners->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#homeBannerCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#homeBannerCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
                @endif
            </div>
        </div>
    </div>
    @endif
    <!-- Banner Slider End -->

    @if(isset($categories) && count($categories) > 0)
    <!-- Services Category Start -->
    <div class="container-fluid services-section py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 700px;">
                <span class="section-label">{{ __('website.what_we_offer') }}</span>
                <h1 class="mb-4 mt-3">
                    {{ \App\Models\Setting::getValue('home_services_title', app()->getLocale(), 'نظرة سريعة على بعض الخدمات التي نقدمها') }}
                </h1>
            </div>

            <div class="services-grid">
                @foreach ($categories as $index => $category)
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

                    <!-- Service Card -->
                    <div class="service-card wow fadeInUp {{ $category->is_full_width ? 'text-center' : '' }}" data-wow-delay="0.1s" style="{{ $category->is_full_width ? 'grid-column: 1 / -1;' : '' }}">
                        <a href="{{ route('website.category.show', $category->id) }}" class="service-link">
                            <div class="service-image-wrapper">
                                <img src="{{ $category->getFirstMediaUrl('categories') }}" alt="{{ $category->name }}"
                                    class="service-image">
                                <div class="service-arrow">
                                    <i class="bi bi-arrow-up-left"></i>
                                </div>
                            </div>
                            <div class="service-content {{ $category->is_full_width ? 'text-center' : '' }}">
                                <h5>{{ $category->name }}</h5>
                                <p>{{ $category->description }}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- View All Button -->
            <div class="text-center mt-5 wow fadeInUp" data-wow-delay="0.6s">
                <a href="{{ route('website.categories.index') }}" class="btn btn-view-all">{{ __('website.view_all') }}</a>
            </div>
        </div>
    </div>
    <!-- Services Category End -->
    @endif

    @if(isset($activeTenders) && $activeTenders->count() > 0)
    <section class="asas-tenders-wrapper py-5" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="container">
            <div class="tenders-header wow fadeInUp" data-wow-delay="0.1s">
                <h2 class="tenders-badge">{{ __('tenders.latest_tenders') ?? 'أحدث المناقصات' }}</h2>
            </div>
            
            <div class="tenders-grid wow fadeInUp" data-wow-delay="0.2s">
                @if(isset($activeTenders) && $activeTenders->count() > 0)
                    @foreach($activeTenders as $tender)
                    <div class="tender-card">
                        <div class="card-location">
                            <svg viewBox="0 0 24 24" width="18" height="18" stroke="#fff" stroke-width="2" fill="#064B3B" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <span>{{ $tender->city ? $tender->city->name : __('website.jeddah') }}</span>
                        </div>
                        <h3 class="card-title">{{ $tender->title }}</h3>
                        <div class="card-footer">
                            <span class="card-info">{{ __('tenders.budget') ?? 'ميزانية' }}: {{ $tender->budget ? number_format($tender->budget) . ' ' . __('tenders.sar') : __('tenders.not_specified') }}</span>
                            <a href="{{ route('website.tenders.show', $tender->id) }}" class="subscribe-btn">{{ __('tenders.apply_offer') ?? 'إشترك الأن' }}</a>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="col-12 text-center text-muted">لا توجد مناقصات نشطة حالياً.</div>
                @endif
            </div>
            
            @if(isset($activeTenders) && $activeTenders->count() > 0)
            <div class="text-center mt-4 wow fadeInUp" data-wow-delay="0.3s">
                <a href="{{ route('website.tenders.index') }}" class="btn px-4 btn-tenders-all">{{ __('tenders.view_all_tenders') }}</a>
            </div>
            @endif
        </div>
    </section>
    @endif

    <!--  -->

    @if (count($topProviders) > 0)
        <!-- What You Need Section Start -->
        <div class="what-you-need-section py-5">
            <div class="container">
                <!-- Header -->
                <div class="wyn-header d-flex justify-content-between align-items-center mb-5 wow fadeInUp"
                    data-wow-delay="0.1s">
                    <div class="wyn-header-left">
                        <span class="wyn-label">{{ __('website.most_trusted') }}</span>
                        <h2 class="wyn-title">{{ __('website.most_active_members') }}</h2>
                    </div>
                    <div class="wyn-header-right">
                        <a href="{{ route('providers.search') }}" class="btn btn-icon py-3 px-5 me-3 animated fadeIn">
                            <span>{{ __('website.view_all') }}</span>
                            <i class="icon-btn bi bi-arrow-up-left"></i>
                        </a>
                    </div>
                </div>

                <!-- Cards Grid -->
                <div class="row g-4">
                    @foreach ($topProviders as $provider)
                        <!-- Provider Card -->
                        <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                            <div class="wyn-card position-relative overflow-hidden">
                                @if($provider->hasActiveSubscription() && $provider->subscriptionPackage && $provider->subscriptionPackage->badge_name)
                                    <div class="card-ribbon">
                                        <span style="background-color: {{ $provider->subscriptionPackage->color ?: 'var(--bs-primary)' }}">
                                            {{ $provider->subscriptionPackage->badge_name }}
                                        </span>
                                    </div>
                                @endif
                                <a href="{{ route('member.public', $provider->id) }}" class="wyn-card-link">
                                    <div class="wyn-card-image {{ !$provider->getFirstMediaUrl('personal_photo') && !$provider->getFirstMediaUrl('users') ? 'bg-white p-3' : '' }}">
                                        @php
                                            $photoUrl = $provider->getFirstMediaUrl('personal_photo') ?: $provider->getFirstMediaUrl('users');
                                            $isLogo = false;
                                            if (!$photoUrl) {
                                                $photoFallback = app()->getLocale() == 'ar' 
                                                            ? \App\Models\Setting::getMediaUrl('logo_ar') 
                                                            : \App\Models\Setting::getMediaUrl('logo_en');
                                                $photoUrl = $photoFallback ?: asset('website/assets/img/logo.png');
                                                $isLogo = true;
                                            }
                                        @endphp
                                        <img src="{{ $photoUrl }}"
                                            alt="{{ $provider->name }}"
                                            style="{{ $isLogo ? 'object-fit: contain;' : '' }}">
                                        <div class="wyn-card-overlay"></div>
                                    </div>
                                    <div class="wyn-card-content">
                                        <div class="card-content-header">
                                            <span class="wyn-category">
                                                {{ $provider->categories->first()->name ?? ($provider->provider_type == 'company' ? __('website.contracting_company') : __('website.service_provider')) }}
                                            </span>
                                            <div class="wyn-dots"
                                                title="{{ number_format($provider->average_rating, 1) }}">
                                                @php $rating = round($provider->average_rating); @endphp
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <span class="dot {{ $i <= $rating ? 'active' : '' }}"></span>
                                                @endfor
                                            </div>
                                        </div>
                                        <h5 class="wyn-card-title">{{ $provider->name }}</h5>
                                        <p class="text-muted small mb-0 mt-2">
                                            <i class="bi bi-check-circle-fill text-success ms-1"></i>
                                            {{ $provider->completed_projects_count }} {{ __('website.completed_request') }}
                                        </p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- What You Need Section End -->
    @endif

    @if (isset($topSuppliers) && count($topSuppliers) > 0)
        <!-- Suppliers Section Start -->
        <div class="what-you-need-section py-5 bg-white">
            <div class="container">
                <!-- Header -->
                <div class="wyn-header d-flex justify-content-between align-items-center mb-5 wow fadeInUp"
                    data-wow-delay="0.1s">
                    <div class="wyn-header-left">
                        <span class="wyn-label"><i class="bi bi-box-seam me-1"></i> شركاء التوريد</span>
                        <h2 class="wyn-title">أبرز الموردين المعتمدين</h2>
                    </div>
                    <div class="wyn-header-right">
                        <a href="{{ route('website.suppliers.index') }}" class="btn btn-icon py-3 px-5 me-3 animated fadeIn">
                            <span>عرض كل الموردين</span>
                            <i class="icon-btn bi bi-arrow-up-left"></i>
                        </a>
                    </div>
                </div>

                <!-- Cards Grid -->
                <div class="row g-4">
                    @foreach ($topSuppliers as $provider)
                        <!-- Supplier Card -->
                        <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                            <div class="wyn-card position-relative overflow-hidden">
                                @if($provider->hasActiveSubscription() && $provider->subscriptionPackage && $provider->subscriptionPackage->badge_name)
                                    <div class="card-ribbon">
                                        <span style="background-color: {{ $provider->subscriptionPackage->color ?: 'var(--bs-primary)' }}">
                                            {{ $provider->subscriptionPackage->badge_name }}
                                        </span>
                                    </div>
                                @endif
                                <a href="{{ route('member.public', $provider->id) }}" class="wyn-card-link">
                                    <div class="wyn-card-image {{ !$provider->getFirstMediaUrl('personal_photo') && !$provider->getFirstMediaUrl('users') ? 'bg-white p-3' : '' }}">
                                        @php
                                            $photoUrl = $provider->getFirstMediaUrl('personal_photo') ?: $provider->getFirstMediaUrl('users');
                                            $isLogo = false;
                                            if (!$photoUrl) {
                                                $photoFallback = app()->getLocale() == 'ar' 
                                                            ? \App\Models\Setting::getMediaUrl('logo_ar') 
                                                            : \App\Models\Setting::getMediaUrl('logo_en');
                                                $photoUrl = $photoFallback ?: asset('website/assets/img/logo.png');
                                                $isLogo = true;
                                            }
                                        @endphp
                                        <img src="{{ $photoUrl }}"
                                            alt="{{ $provider->name }}"
                                            style="{{ $isLogo ? 'object-fit: contain;' : '' }}">
                                        <div class="wyn-card-overlay"></div>
                                    </div>
                                    <div class="wyn-card-content">
                                        <div class="card-content-header">
                                            <span class="wyn-category">
                                                <i class="bi bi-box-seam me-1"></i> {{ $provider->categories->first()->name ?? 'مورد معتمد' }}
                                            </span>
                                            <div class="wyn-dots"
                                                title="{{ number_format($provider->average_rating, 1) }}">
                                                @php $rating = round($provider->average_rating); @endphp
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <span class="dot {{ $i <= $rating ? 'active' : '' }}"></span>
                                                @endfor
                                            </div>
                                        </div>
                                        <h5 class="wyn-card-title">{{ $provider->name }}</h5>
                                        <div class="d-flex align-items-center mt-3 text-muted small">
                                            <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $provider->city->name ?? 'المملكة العربية السعودية' }}
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- Suppliers Section End -->
    @endif    @if (count($activeServices) > 0)
        <!-- Featured Services Section Start -->
        <div class="featured-services-section py-5 bg-light">
            <div class="container">
                <!-- Header -->
                <div class="wyn-header d-flex justify-content-between align-items-center mb-5 wow fadeInUp"
                    data-wow-delay="0.1s">
                    <div class="wyn-header-left">
                        <span class="wyn-label">{{ __('website.services_offered') }}</span>
                        <h2 class="wyn-title">{{ __('website.services_offered_by_providers') }}</h2>
                    </div>
                    <div class="wyn-header-right">
                        <a href="{{ route('website.services.index') }}" class="btn btn-icon py-3 px-5 me-3 animated fadeIn">
                            <span>{{ __('website.view_all') }}</span>
                            <i class="icon-btn bi bi-arrow-up-left"></i>
                        </a>
                    </div>
                </div>

                <!-- Services Grid -->
                <div class="row g-4">
                    @foreach ($activeServices as $service)
                        <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                            <div class="wyn-card">
                                <a href="{{ route('website.services.show', $service->id) }}" class="wyn-card-link">
                                    <div class="wyn-card-image">
                                        <img src="{{ $service->getFirstMediaUrl('services') ?: asset('website/assets/img/service-placeholder.png') }}"
                                            alt="{{ $service->title }}">
                                        <div class="wyn-card-overlay"></div>
                                    </div>
                                    <div class="wyn-card-content">
                                        <div class="card-content-header">
                                            <span class="wyn-category">{{ $service->category->name ?? '' }}</span>
                                        </div>
                                        <h5 class="wyn-card-title">{{ $service->title }}</h5>
                                        <div class="d-flex align-items-center mt-3">
                                            <img src="{{ $service->user->getFirstMediaUrl('personal_photo') ?: asset('website/assets/img/logo.png') }}"
                                                class="rounded-circle me-2" width="30" height="30" style="object-fit: cover;">
                                            <span class="text-muted small">{{ $service->user->name }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- Featured Services Section End -->
    @endif

    @if(isset($successPartners) && count($successPartners) > 0)
    <x-website.success-partners :title="\App\Models\Setting::getValue(
        'home_partners_title',
        app()->getLocale(),
        'نفخر بالشراكة مع عملاء من الطراز الأول',
    )" />
    @endif

    <!-- Contact Form Section Start -->
    <div class="contact-form-section">
        <div class="contact-background-overlay"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-9">
                    <div class="contact-form-card wow fadeInUp" data-wow-delay="0.2s">
                        <div class="contact-form-card-content">
                            <!-- Quick Enquiry Badge -->
                            <div class="text-center mb-4">
                                <span
                                    class="quick-enquiry-badge">{{ \App\Models\Setting::getValue('home_contact_badge', app()->getLocale(), 'استفسار سريع') }}</span>
                            </div>

                            <!-- Title -->
                            <h2 class="contact-form-title text-center mb-4">
                                {{ \App\Models\Setting::getValue('home_contact_title', app()->getLocale(), 'احصل على استشارة متخصصة للعقارات السكنية أو التجارية') }}
                            </h2>

                            <!-- Form -->
                            <form class="contact-form" id="contactForm" action="{{ route('website.contact.store') }}"
                                method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <input type="text" name="name" class="form-control contact-input"
                                            placeholder="{{ __('website.your_name_required') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="email" name="email" class="form-control contact-input"
                                            placeholder="{{ __('website.your_email_required') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="tel" name="phone" class="form-control contact-input"
                                            placeholder="{{ __('website.phone_number_required') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-select contact-input" name="subject" required>
                                            <option value="" selected>{{ __('website.choose_service_required') }}</option>
                                            <option value="residential">{{ __('website.residential') }}</option>
                                            <option value="commercial">{{ __('website.commercial') }}</option>
                                            <option value="property">{{ __('website.property_management') }}</option>
                                            <option value="consulting">{{ __('website.engineering_consulting') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <textarea name="message" class="form-control contact-input" placeholder="{{ __('website.your_message_required') }}" rows="3" required></textarea>
                                    </div>
                                </div>

                                <!-- Footer -->
                                <div class="contact-form-footer">
                                    <div class="form-notice">
                                        <p class="mb-0">{!! nl2br(
                                            \App\Models\Setting::getValue(
                                                'home_contact_desc',
                                                app()->getLocale(),
                                                "نحن متحمسون للتواصل معك!\n<span>الحقول المطلوبة مميزة بـ *</span>",
                                            ),
                                        ) !!}
                                        </p>
                                    </div>
                                    <button type="submit" class=" btn btn-icon py-3 px-5 animated fadeIn">
                                        <span>{{ \App\Models\Setting::getValue('home_contact_btn', app()->getLocale(), 'اطلب اتصال') }}</span>
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
    <!-- Contact Form Section End -->

    <!-- What Makes Us Different Section Start -->
    <div class="what-makes-us-different-section">
        <div class="container">
            <div class="row g-5 align-items-center">
                <!-- Image Side -->
                <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.1s">
                    <div class="wmud-image-wrapper">
                        <img src="{{ asset('website/assets/img/why-us.png') }}" alt="Our Team" class="wmud-image">
                    </div>
                </div>

                <!-- Content Side -->
                <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.3s">
                    <div class="wmud-content">
                        <!-- Badge -->
                        <span
                            class="wmud-badge">{{ \App\Models\Setting::getValue('home_commitments_badge', app()->getLocale(), 'التزامنا') }}</span>

                        <!-- Title -->
                        <h2 class="wmud-title">
                            {{ \App\Models\Setting::getValue('home_commitments_title', app()->getLocale(), 'ما يجعلنا مختلفين') }}
                        </h2>

                        <!-- Description -->
                        <p class="wmud-description">
                            {{ \App\Models\Setting::getValue('home_commitments_desc', app()->getLocale(), 'الأمر لا يتعلق فقط بإنشاء شيء جيد. بل يتعلق بالتصميم والابتكار والتعاون لصنع تجارب رائعة لا مثيل لها.') }}
                        </p>

                        <!-- Features List -->
                        <div class="wmud-features">
                            @php
                                $commitmentsList = json_decode(
                                    \App\Models\Setting::getValue('home_commitments_list', app()->getLocale(), '[]'),
                                    true,
                                );
                                // Default images array to cycle through or use specific logic if needed
                                $defaultImages = [
                                    asset('website/assets/img/why-us1.png'),
                                    asset('website/assets/img/why-us2.png'),
                                    asset('website/assets/img/why-us3.png'),
                                ];
                            @endphp

                            @if (count($commitmentsList) > 0)
                                @foreach ($commitmentsList as $index => $item)
                                    @php
                                        $commitTitle = is_array($item['title'] ?? null) ? ($item['title'][app()->getLocale()] ?? ($item['title']['ar'] ?? '')) : ($item['title'] ?? '');
                                        $commitDesc = is_array($item['description'] ?? null) ? ($item['description'][app()->getLocale()] ?? ($item['description']['ar'] ?? '')) : ($item['description'] ?? '');
                                    @endphp
                                    <div class="wmud-feature-item">
                                        <div class="wmud-feature-icon">
                                            @if (!empty($item['image']))
                                                <img src="{{ asset($item['image']) }}" alt="{{ $commitTitle }}">
                                            @else
                                                <img src="{{ $defaultImages[$index % 3] }}"
                                                    alt="{{ $commitTitle }}">
                                            @endif
                                        </div>
                                        <div class="wmud-feature-content">
                                            <h5>{{ $commitTitle }}</h5>
                                            <p>{{ $commitDesc }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <!-- Fallback static content if no settings are saved -->
                                <div class="wmud-feature-item">
                                    <div class="wmud-feature-icon">
                                        <img src="{{ asset('website/assets/img/why-us1.png') }}"
                                            alt="{{ __('website.corporate_responsibility') }}">
                                    </div>
                                    <div class="wmud-feature-content">
                                        <h5>{{ __('website.corporate_responsibility') }}</h5>
                                        <p>{{ __('website.zero_accidents_goal') }}</p>
                                    </div>
                                </div>
                                <div class="wmud-feature-item">
                                    <div class="wmud-feature-icon">
                                        <img src="{{ asset('website/assets/img/why-us2.png') }}" alt="{{ __('website.team_experts') }}">
                                    </div>
                                    <div class="wmud-feature-content">
                                        <h5>{{ __('website.team_experts') }}</h5>
                                        <p>{{ __('website.zero_accidents_goal') }}</p>
                                    </div>
                                </div>
                                <div class="wmud-feature-item">
                                    <div class="wmud-feature-icon">
                                        <img src="{{ asset('website/assets/img/why-us3.png') }}"
                                            alt="{{ __('website.diversity_equity_inclusion') }}">
                                    </div>
                                    <div class="wmud-feature-content">
                                        <h5>{{ __('website.diversity_equity_inclusion') }}</h5>
                                        <p>{{ __('website.zero_accidents_goal') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- What Makes Us Different Section End -->
@endsection

@push('js')
<script>
  @if(session('error_popup') == 'subscription_required')
      document.addEventListener("DOMContentLoaded", function() {
          if(typeof showSubscriptionPopup === 'function') {
              showSubscriptionPopup();
          } else {
              alert('{{ __("tenders.sub_required") ?? "يجب الإشتراك أو ترقية الباقة لتقديم عروض على المناقصات النشطة" }}');
          }
      });
  @endif
</script>
@endpush
