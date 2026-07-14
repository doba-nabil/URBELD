@extends('layouts.landing')


@section('content')
    <!-- Premium Glass Navbar (Standalone) -->
    <nav class="navbar navbar-expand-lg navbar-glass fixed-top mx-5 my-3 z-3 py-3" id="landingNavbar">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ $siteLogo }}" alt="{{ $siteName }}" height="50" class="me-2">
            </a>
            
            <div class="navbar-nav-right d-flex align-items-center gap-3">
                <a href="{{ route('website.lang', $isRtl ? 'en' : 'ar') }}" class="lang-capsule">
                   <i class="fas fa-globe me-2"></i>
                   <span>{{ $isRtl ? 'English' : 'العربية' }}</span>
                </a>
                <a href="{{ route('register') }}" class="btn btn-premium btn-premium-primary text-nowrap px-4 py-2">
                    {{ __('website.nav_register') }}
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" style="background-image: url('{{ $heroBg }}'); background-size: cover; background-position: center;">
        <div class="hero-overlay"></div>
        <div class="container position-relative z-2">
            <div class="row">
                <div class="col-lg-10 mx-auto text-center">
                    <h1 class="mb-4 text-white" data-aos="fade-down" style="z-index: 10;">
                        {!! $heroTitle ?: ($isRtl ? 'مشروع أحلامك<br>في انتظارك' : 'Your Dream Project<br>Awaits You') !!}
                    </h1>
                    <p class="text-white opacity-75 mb-5 mx-auto p-content" data-aos="fade-up" data-aos-delay="200" style="max-width: 800px; font-size: 1.25rem;">
                        {!! $heroSubtitle ?: ($isRtl ? 'نحن نؤمن بأن كل مساحة تروي قصة. فريقنا متخصص في تحويل رؤيتك إلى واقع ملموس يجمع بين الفخامة والعملية.' : 'We believe every space tells a story. Our team specializes in turning your vision into a reality that combines luxury and functionality.') !!}
                    </p>
                    <div class="d-flex justify-content-center flex-wrap gap-4" data-aos="fade-up" data-aos-delay="400">
                        <a href="#about" class="btn btn-premium btn-premium-outline fs-5">{{ __('website.nav_about') }}</a>
                        <a href="{{ route('register') }}" class="btn btn-premium btn-premium-primary fs-5">{{ __('website.create_account') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <a href="#about" class="position-absolute bottom-0 start-50 translate-middle-x mb-5 z-3">
             <div class="mouse-scroll"></div>
        </a>
    </section>

    <!-- About Section (Refined Home Style) -->
    <div class="about-section py-5" id="about">
        <div class="container py-5">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.1s">
                    <div class="about-img-frame position-relative">
                        <img class="img-fluid w-100 about-img shadow-lg" src="{{ $aboutImage }}" alt="About ERSAA">
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.3s">
                    <span class="section-label mb-2 d-inline-block text-primary fw-bold text-uppercase" style="letter-spacing: 2px;">{{ __('website.nav_about') }}</span>
                    <h2 class="mb-4 display-5 fw-bold">{{ $aboutTitle ?: __('website.who_we_are') }}</h2>
                    <p class="mb-5 fs-5 text-muted leading-relaxed">{{ $aboutDesc ?: __('website.about_hero_desc_2') }}</p>
                    
                    <ul class="about-points-list">
                        @if($aboutPoint1)
                            <li><i class="fa fa-check"></i> <span>{{ $aboutPoint1 }}</span></li>
                        @endif
                        @if($aboutPoint2)
                            <li><i class="fa fa-check"></i> <span>{{ $aboutPoint2 }}</span></li>
                        @endif
                        @if(!$aboutPoint1 && !$aboutPoint2)
                             <li><i class="fa fa-check"></i> <span>{{ __('website.team_experts') }}</span></li>
                             <li><i class="fa fa-check"></i> <span>{{ __('website.most_trusted') }}</span></li>
                        @endif
                    </ul>

                    <a href="{{ route('register') }}" class="btn btn-premium btn-premium-primary mt-4 py-3 px-5 rounded-pill">
                        {{ __('website.create_account') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Video Showcase Section (Interactive Modal) -->
    <div class="video-showcase-section py-5" style="background: #f8f9fa;">
        <div class="container">
             <div class="row g-0 rounded-4 overflow-hidden shadow-lg hover-shadow transition-all">
                <!-- Left Content Side -->
                <div class="col-lg-6 video-content-side bg-white d-flex align-items-center">
                    <div class="p-5 p-lg-5">
                        <span class="section-label d-block mb-2 text-primary fw-bold text-uppercase" style="letter-spacing: 2px;">{{ __('website.how_we_work') }}</span>
                        <h2 class="display-6 fw-bold mb-0 lh-base">
                            {{ $videoTitle ?: ($isRtl ? 'نحن نصمم المساحات التي تلهم' : 'We design spaces that inspire') }}
                        </h2>
                    </div>
                </div>

                <!-- Right Video Side -->
                <div class="col-lg-6 video-media-side position-relative" style="min-height: 400px;">
                    <div class="video-wrapper h-100 w-100 position-relative" id="videoShowcase">
                        <img src="{{ $videoCover }}" alt="Video Preview" class="w-100 h-100" style="object-fit: cover; transition: transform 0.5s ease;">
                        <div class="video-overlay position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,0.35);"></div>
                        <button class="play-button position-absolute top-50 start-50 translate-middle border-0 bg-transparent p-0 cursor-pointer pulse-animation" id="playButton" style="outline: none;">
                            <img src="{{ asset('website/assets/img/play-button.png') }}" alt="Play" width="90" class="hover-scale">
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section (Dynamic from Admin) -->
    <div class="what-makes-us-different-section py-5" id="features">
        <div class="container py-5">
            <div class="row g-5 align-items-center">
                <!-- Image Side -->
                <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.1s">
                    <div class="wmud-image-wrapper bg-white shadow-sm rounded-4">
                        <img src="{{ $featuresMainImage }}" alt="Features" class="img-fluid rounded-4">
                    </div>
                </div>

                <!-- Content Side -->
                <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.3s">
                    <div class="wmud-content ps-lg-4">
                        <span class="wmud-badge d-inline-block px-3 py-1 bg-primary text-white rounded-pill mb-3 small fw-bold text-uppercase">{{ __('admin.features') }}</span>
                        <h2 class="wmud-title display-5 fw-bold mb-4">{{ $featuresTitle ?: __('website.what_we_offer') }}</h2>
                        <p class="wmud-description text-muted mb-5 fs-5">
                            {{ $featuresSubtitle ?: ($isRtl ? 'ملتزمون بتقديم أعلى معايير الجودة والابتكار في كل مشروع، لنضمن لك تجربة فريدة ومستدامة.' : 'We are committed to delivering the highest standards of quality and innovation in every project, ensuring a unique and sustainable experience.') }}
                        </p>

                        <div class="wmud-features">
                            @forelse($features as $index => $feature)
                                <div class="wmud-feature-item d-flex align-items-start rounded-3 hover-bg-light transition-all">
                                    <div class="wmud-feature-icon me-3 flex-shrink-0" style="width: 50px;">
                                        <img src="{{ $feature->getFirstMediaUrl('image') }}" alt="{{ $feature->title }}" class="w-100 img-contain">
                                    </div>
                                    <div class="wmud-feature-content">
                                        <h5 class="fw-bold mb-1">{{ $feature->title }}</h5>
                                        <p class="text-muted mb-0 small">{{ $feature->description }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-light text-center">-- {{ __('admin.no_data') }} --</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Video Popup Modal -->
    <div class="video-popup" id="videoPopup">
        <div class="video-popup-content position-relative" style="width: 90%; max-width: 1000px; background: #000; border-radius: 15px; overflow: hidden; box-shadow: 0 30px 60px rgba(0,0,0,0.5);">
            <button class="close-video btn btn-light rounded-circle position-absolute top-0 end-0 m-3 z-3" id="closeVideo" style="width: 45px; height: 45px;">
                x
            </button>
            <video id="videoPlayer" controls class="w-100" style="background: #000;">
                <source src="{{ $videoUrl }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>


    <!-- Minimalist Footer -->
    <footer class="minimal-footer py-4 bg-white">
        <div class="container text-center">
            <div class="d-flex justify-content-center flex-wrap gap-4 mb-4">
                @if($sitePhone)
                    <a href="tel:{{ $sitePhone }}" class="social-link fs-4" title="{{ $sitePhone }}"><i class="fas fa-phone-alt"></i></a>
                @endif
                @if($siteEmail)
                    <a href="mailto:{{ $siteEmail }}" class="social-link fs-4" title="{{ $siteEmail }}"><i class="fas fa-envelope"></i></a>
                @endif
                @if(!empty($socials))
                    @foreach($socials as $social)
                        @if(!empty($social['url']))
                            <a href="{{ $social['url'] }}" class="social-link fs-4 text-decoration-none" target="_blank">{!! $social['icon'] ?? '' !!}</a>
                        @endif
                    @endforeach
                @endif
            </div>
            <div class="footer-copyright border-top pt-4">
                <span class="opacity-75">{!! __('website.footer_copyright', ['site_name' => 'ERSAA']) !!}</span>
            </div>
        </div>
    </footer>

    @push('js')
    <script>
        $(document).ready(function() {
            // Navbar Scrolled Effect
            $(window).scroll(function() {
                if ($(this).scrollTop() > 100) {
                    $('#landingNavbar').addClass('scrolled');
                } else {
                    $('#landingNavbar').removeClass('scrolled');
                }
            });
        });
    </script>
    <style>
        .hover-scale { transition: transform 0.3s ease; }
        .hover-scale:hover { transform: scale(1.1); }
        .hover-shadow:hover { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15) !important; }
        .pulse-animation {
            animation: pulse 2s infinite;
            border-radius: 50%;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(0, 185, 142, 0.4); }
            70% { box-shadow: 0 0 0 20px rgba(0, 185, 142, 0); }
            100% { box-shadow: 0 0 0 0 rgba(0, 185, 142, 0); }
        }
        .max-vh-80 { max-height: 80vh; }
        .leading-relaxed { line-height: 1.8; }
        .hover-bg-light:hover { background-color: #fcfcfc; }
        .transition-all { transition: all 0.3s ease; }
        .img-contain { object-fit: contain; }
    </style>
    @endpush
@endsection
