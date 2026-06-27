@extends('website.layouts.master')

@section('title', __('website.faq'))

@section('content')
    <!-- Header Start -->
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
    <div class="faq-header-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="faq-header-content">
                        <h1 class="faq-main-title wow fadeInUp" data-wow-delay="0.1s">{{ __('website.faq') }}</h1>
                        <nav aria-label="breadcrumb" class="faq-breadcrumb wow fadeInUp" data-wow-delay="0.2s">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('website.nav_home') }}</a></li>
                                <li class="breadcrumb-item active">{{ __('website.faq') }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="faq-content-section" id="faq-content">
                        <div class="faq-content-head">
                            <div class="faq-btn">
                                <a href="#faq-content" class="btn-find-answer wow fadeInUp" data-wow-delay="0.3s">{{ __('website.find_your_answer') }}</a>
                            </div>
                            <h2 class="faq-section-title wow fadeInUp" data-wow-delay="0.2s">{{ __('website.read_common_questions') }}</h2>
                        </div>

                        <div class="faq-accordion">
                            @forelse($faqs as $index => $faq)
                                <div class="faq-item {{ $index === 0 ? 'active' : '' }}">
                                    <div class="faq-question">
                                        <div class="faq-icon {{ $index === 0 ? 'active' : '' }}">
                                            <i class="bi bi-question-circle"></i>
                                            <i class="bi bi-question-circle"></i>
                                        </div>
                                        <div class="faq-question-text">
                                            <h5>{{ $faq->question }}</h5>
                                            <div class="faq-answer">
                                                <p>{{ $faq->answer }}</p>
                                            </div>
                                        </div>
                                        <div class="faq-arrow">
                                            <i class="bi bi-arrow-{{ $index === 0 ? 'up' : 'down' }}-left"></i>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center p-5">
                                    <h4>{{ __('website.no_faqs') }}</h4>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->
@endsection

@push('js')
    <script>
        // FAQ Accordion functionality
        document.addEventListener('DOMContentLoaded', function() {
            const faqItems = document.querySelectorAll('.faq-item');

            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question');

                question.addEventListener('click', function() {
                    const isActive = item.classList.contains('active');

                    // Close all items
                    faqItems.forEach(faqItem => {
                        faqItem.classList.remove('active');
                        const faqArrow = faqItem.querySelector('.faq-arrow i');
                        if (faqArrow) {
                            faqArrow.className = 'bi bi-arrow-down-left';
                        }
                    });

                    // If clicked item was not active, open it
                    if (!isActive) {
                        item.classList.add('active');
                        const arrow = item.querySelector('.faq-arrow i');
                        if (arrow) {
                            arrow.className = 'bi bi-arrow-up-left';
                        }
                    }
                });
            });

            // Smooth scroll to FAQ section when clicking "Find Answer" button
            const findAnswerBtn = document.querySelector('.btn-find-answer');
            if (findAnswerBtn) {
                findAnswerBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const faqContent = document.getElementById('faq-content');
                    if (faqContent) {
                        faqContent.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            }
        });
    </script>
@endpush
