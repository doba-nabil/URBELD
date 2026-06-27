@extends('layouts.website')
@section('body_class', 'sup-page')
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

    <div class="page-content">
        <!-- About Hero Section -->
        <div class="about-hero-section-new pt-0">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-12">
                        <div class="about-hero-left">
                            <a href="#" class="btn-who-we-are wow fadeInUp" data-wow-delay="0.1s">
                                <span>{{ __('website.who_we_are') }}</span>
                                <i class="bi bi-arrow-left"></i>
                            </a>
                            <h1 class="about-hero-title-new wow fadeInUp" data-wow-delay="0.2s">{{ __('website.about_hero_title') }}</h1>
                            <div class="about-hero-description-wrapper wow fadeInUp" data-wow-delay="0.3s">
                                    {{ __('website.about_hero_desc_1') }}
                                <p class="about-hero-description-new">
                                    {{ __('website.about_hero_desc_2') }}
                                </p>
                            </div>
                            <button type="button" class=" btn btn-icon py-3 px-5 animated fadeIn">
                                <span>{{ __('website.meet_the_team') }}</span>
                                <i class="icon-btn bi bi-arrow-up-left"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="about-hero-image-wrapper-new wow fadeInUp" data-wow-delay="0.5s">
                            <div class="statistics-cards-container">
                                <!-- Statistics Card 1 - Top -->
                                <div class="statistics-card-new statistics-card-new-1">
                                    <div class="stat-card-label">{{ __('website.local_expertise') }}</div>
                                    <div class="stat-card-number">
                                        <span class="counter" data-target="800">0</span>+
                                    </div>
                                    <div class="stat-card-desc">{{ __('website.projects_under_development') }}</div>
                                </div>
                                <!-- Statistics Cards 2 & 3 - Bottom Row -->
                                <div class="statistics-cards-row">
                                    <div class="statistics-card-new statistics-card-new-2">
                                        <div class="stat-card-label">{{ __('website.local_expertise') }}</div>
                                        <div class="stat-card-number">
                                            <span class="counter" data-target="90">0</span>m+
                                        </div>
                                        <div class="stat-card-desc">{{ __('website.square_feet_realestate') }}</div>
                                    </div>
                                    <div class="statistics-card-new statistics-card-new-3">
                                        <div class="stat-card-label">{{ __('website.local_expertise') }}</div>
                                        <div class="stat-card-number">
                                            <span class="counter" data-target="500">0</span>+
                                        </div>
                                        <div class="stat-card-desc">{{ __('website.total_project_cost') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Our Story Timeline Section -->
        <div class="our-story-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="our-story-header">
                            <span class="our-story-label wow fadeInUp" data-wow-delay="0.1s">{{ __('website.our_story') }}</span>
                            <h2 class="our-story-title wow fadeInUp" data-wow-delay="0.2s">{{ __('website.our_story_title') }}</h2>
                        </div>
                        <div class="our-story-timeline">
                            <div class="timeline-item-new wow fadeInUp" data-wow-delay="0.3s">
                                <div class="timeline-year-new">1995</div>
                                <div class="timeline-image-wrapper">
                                    <img src="{{ asset('website/assets/img/state.png') }}" alt="1995 Project"
                                        class="timeline-image">
                                </div>
                                <div class="timeline-text">
                                    <p>{{ __('website.timeline_desc_1') }}</p>
                                </div>
                            </div>

                            <div class="timeline-item-new wow fadeInUp" data-wow-delay="0.4s">
                                <div class="timeline-year-new">2005</div>
                                <div class="timeline-image-wrapper">
                                    <img src="{{ asset('website/assets/img/state.png') }}" alt="2005 Project"
                                        class="timeline-image">
                                </div>
                                <div class="timeline-text">
                                    <p>{{ __('website.timeline_desc_2') }}</p>
                                </div>
                            </div>

                            <div class="timeline-item-new wow fadeInUp" data-wow-delay="0.5s">
                                <div class="timeline-year-new">2010</div>
                                <div class="timeline-image-wrapper">
                                    <img src="{{ asset('website/assets/img/state.png') }}" alt="2010 Project"
                                        class="timeline-image">
                                </div>
                                <div class="timeline-text">
                                    <p>{{ __('website.timeline_desc_3') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <x-website.services-section />

    <x-website.success-partners :title="\App\Models\Setting::getValue(
        'home_partners_title',
        app()->getLocale(),
        'نفخر بالشراكة مع عملاء من الطراز الأول',
    )" />
@endsection

@push('js')
    <script>
        // Counter Animation
        document.addEventListener('DOMContentLoaded', function() {
            const counters = document.querySelectorAll('.counter');
            const speed = 200; // Animation speed (lower is faster)

            const animateCounter = (counter) => {
                const target = parseInt(counter.getAttribute('data-target'));
                const count = parseInt(counter.innerText);
                const increment = target / speed;

                if (count < target) {
                    counter.innerText = Math.ceil(count + increment);
                    setTimeout(() => animateCounter(counter), 1);
                } else {
                    counter.innerText = target;
                }
            };

            // Intersection Observer to trigger animation when element is in view
            const observerOptions = {
                threshold: 0.5,
                rootMargin: '0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const counters = entry.target.querySelectorAll('.counter');
                        counters.forEach(counter => {
                            if (counter.innerText === '0') {
                                animateCounter(counter);
                            }
                        });
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            // Observe the statistics section
            const statsSection = document.querySelector('.about-hero-image-wrapper-new');
            if (statsSection) {
                observer.observe(statsSection);
            }
        });
    </script>
@endpush
