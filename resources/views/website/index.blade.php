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
                    <div class="content justify-content-center">
                        <a href="{{ \App\Models\Setting::getValue('home_hero_btn_link', app()->getLocale()) ?: route('website.categories.index') }}"
                            class="auth btn btn-icon py-3 px-5 me-3 animated fadeIn">
                            <span>{{ \App\Models\Setting::getValue('home_hero_btn_text', app()->getLocale(), 'اعرض الخدمات') }}</span>
                            <i class="icon-btn bi bi-arrow-up-left"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Start -->
        <div class="search-section">
            <div class="search-background"></div>
            <div class="container">
                <form action="{{ route('providers.search') }}" method="GET">
                    <div class="search-content wow fadeIn" data-wow-delay="0.1s">
                        <div class="row g-2">
                            <div class="col-md-9">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <span class="search-text">
                                            {!! __('website.search_your_service') !!}
                                        </span>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-select select2 border-0 py-3" name="category_id">
                                            <option selected value="">{{ __('website.service') }}</option>
                                            @foreach (\App\Models\Category::whereNull('parent_id')->where('is_active', true)->get() as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-select select2 border-0 py-3" name="city_id">
                                            <option selected value="">{{ __('website.city') }}</option>
                                            @foreach (\App\Models\City::orderBy('name')->get() as $city)
                                                <option value="{{ $city->id }}">{{ $city->name }}</option>
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

    <!-- About Start -->
    <div class="about-section">
        <div class="container-fluid py-5 px-0">
            <div class="">
                <div class="about-carousel owl-carousel">
                    @php
                        $aboutList = json_decode(
                            \App\Models\Setting::getValue('home_about_list', app()->getLocale(), '[]'),
                            true,
                        );
                        if (empty($aboutList)) {
                            $aboutList = [
                                [
                                    'title' => 'المكان الأول للعثور على العقار المثالي',
                                    'description' =>
                                        'نحن نساعدك في العثور على منزل أحلامك من خلال خدماتنا المتميزة. نوفر لك مجموعة واسعة من الخيارات العقارية التي تناسب احتياجاتك وميزانيتك. نلتزم بتقديم أفضل تجربة عقارية لعملائنا',
                                    'points' => "خدمات عقارية متكاملة\nفريق محترف وخبير\nأفضل الأسعار والعروض",
                                ],
                            ];
                        }
                        $defaultImages = [
                            asset('website/assets/img/about.jpg'),
                            asset('website/assets/img/logo.png'),
                            asset('website/assets/img/logo.png'),
                        ];
                    @endphp

                    @foreach ($aboutList as $index => $item)
                        @php
                            $slideTitle = is_array($item['title'] ?? null) ? ($item['title'][app()->getLocale()] ?? ($item['title']['ar'] ?? '')) : ($item['title'] ?? '');
                            $slideDesc = is_array($item['description'] ?? null) ? ($item['description'][app()->getLocale()] ?? ($item['description']['ar'] ?? '')) : ($item['description'] ?? '');
                            $slideBtnText = is_array($item['btn_text'] ?? null) ? ($item['btn_text'][app()->getLocale()] ?? ($item['btn_text']['ar'] ?? '')) : ($item['btn_text'] ?? '');
                            $slidePoints = is_array($item['points'] ?? null) ? ($item['points'][app()->getLocale()] ?? ($item['points']['ar'] ?? '')) : ($item['points'] ?? '');
                        @endphp
                        <!-- Slide {{ $index + 1 }} -->
                        <div class="about-slide">
                            <div class="row g-5 align-items-center">
                                <div class="col-md-6 wow fadeIn" data-wow-delay="0.1s">
                                    <div class="about-img position-relative overflow-hidden">
                                        <img class="img-fluid w-100"
                                            src="{{ \App\Models\Setting::getMediaUrl('home_about_image_' . $index) ?: $defaultImages[$index % 3] }}"
                                            alt="{{ $slideTitle }}">
                                    </div>
                                </div>
                                <div class="col-md-6 wow fadeIn" data-wow-delay="0.5s">
                                    <h1 class="mb-4">{{ $slideTitle }}</h1>
                                    <p class="mb-4">{{ $slideDesc }}</p>

                                    @if (!empty($slidePoints))
                                        @php
                                            // Split points by newline and trim each
                                            $pointsArray = array_filter(
                                                array_map('trim', explode("\n", $slidePoints)),
                                            );
                                        @endphp
                                        @foreach ($pointsArray as $point)
                                            <p><i class="fa fa-check text-primary me-3"></i>{{ $point }}</p>
                                        @endforeach
                                    @endif

                                    @if (!empty($item['btn_link']) || !empty($slideBtnText))
                                        <a href="{{ $item['btn_link'] ?? route('website.categories.index') }}"
                                            class="btn btn-icon py-3 px-5 me-3 animated fadeIn">
                                            <span>{{ $slideBtnText ?: __('website.view_services') }}</span>
                                            <i class="icon-btn bi bi-arrow-up-left"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('website.categories.index') }}"
                                            class="btn btn-icon py-3 px-5 me-3 animated fadeIn">
                                            <span>{{ __('website.view_services') }}</span>
                                            <i class="icon-btn bi bi-arrow-up-left"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->


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
                @foreach ($categories as $category)
                    <!-- Service Card -->
                    <div class="service-card wow fadeInUp" data-wow-delay="0.1s">
                        <a href="{{ route('website.category.show', $category->id) }}" class="service-link">
                            <div class="service-image-wrapper">
                                <img src="{{ $category->getFirstMediaUrl('categories') }}" alt="{{ $category->name }}"
                                    class="service-image">
                                <div class="service-arrow">
                                    <i class="bi bi-arrow-up-left"></i>
                                </div>
                            </div>
                            <div class="service-content">
                                <h5>{{ $category->name }}</h5>
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


    <!-- Video Showcase Section Start -->
    <div class="video-showcase-section">
        <div class="row g-0">
            <!-- Left Content Side -->
            <div class="col-lg-6 video-content-side">
                <div class="video-content-wrapper">
                    <span
                        class="video-label">{{ \App\Models\Setting::getValue('home_video_label', app()->getLocale(), 'كيف نعمل') }}</span>

                    <h1 class="video-title">
                        {{ \App\Models\Setting::getValue('home_video_title', app()->getLocale(), 'تصاميم مبتكرة، انطباعات دائمة') }}
                    </h1>

                </div>
            </div>

            <!-- Right Video Side -->
            <div class="col-lg-6 video-media-side">
                <div class="video-wrapper" id="videoShowcase">
                    <img src="{{ \App\Models\Setting::getMediaUrl('home_video_cover') ?: asset('website/assets/img/video-section.png') }}"
                        alt="Building" class="video-thumbnail">
                    <div class="video-overlay"></div>
                    <button class="play-button" id="playButton">
                        <img src="{{ asset('website/assets/img/play-button.png') }}" alt="Play" class="play-icon">
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Video Popup Modal -->
    <div class="video-popup" id="videoPopup">
        <div class="video-popup-content">
            <button class="close-video" id="closeVideo">
                <i class="bi bi-x-lg"></i>
            </button>
            <video id="videoPlayer" controls autoplay>
                @php
                    $videoUrl =
                        \App\Models\Setting::getValue('home_video_url') ?:
                        \App\Models\Setting::getMediaUrl('home_video');
                @endphp
                <source src="{{ $videoUrl ?: asset('website/assets/img/video.mp4') }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>
    <!-- Video Showcase Section End -->

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

    @if (count($activeServices) > 0)
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

    <x-website.success-partners :title="\App\Models\Setting::getValue(
        'home_partners_title',
        app()->getLocale(),
        'نفخر بالشراكة مع عملاء من الطراز الأول',
    )" />

    <!-- Contact Form Section Start -->
    <div class="py-3 bg-white"></div>
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
    </div>
    <!-- What Makes Us Different Section End -->
@endsection
