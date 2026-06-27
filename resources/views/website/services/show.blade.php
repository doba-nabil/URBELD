@extends('layouts.website')

@section('content')
    <!-- Hero Section Start -->
    <div class="service-detail-hero" style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ $service->getFirstMediaUrl('services') ?: asset('website/assets/img/service-placeholder.png') }}') center center no-repeat; background-size: cover;">
        <div class="container py-5">
            <nav aria-label="breadcrumb" class="wow fadeInUp" data-wow-delay="0.1s">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">{{ __('website.nav_home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('website.services.index') }}" class="text-white">{{ __('website.nav_services') }}</a></li>
                    <li class="breadcrumb-item active text-primary" aria-current="page">{{ $service->title }}</li>
                </ol>
            </nav>
            <div class="row">
                <div class="col-lg-8">
                    <h1 class="display-4 text-white fw-bold mb-4 wow fadeInUp" data-wow-delay="0.2s">
                        {{ $service->title }}
                    </h1>
                    <div class="d-flex align-items-center wow fadeInUp" data-wow-delay="0.3s">
                        <span class="badge bg-primary px-3 py-2 me-3">{{ $service->category->name ?? '' }}</span>
                        <div class="text-white-50">
                            <i class="bi bi-clock me-1"></i> {{ $service->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hero Section End -->

    <!-- Service Content Start -->
    <div class="container py-5">
        <div class="row g-5">
            <!-- Main Content -->
            <div class="col-lg-8 wow fadeInUp" data-wow-delay="0.1s">
                <div class="service-main-content bg-white p-4 rounded shadow-sm">
                    <div class="mb-4">
                        <img src="{{ $service->getFirstMediaUrl('services') ?: asset('website/assets/img/service-placeholder.png') }}" 
                             class="img-fluid rounded w-100 mb-4" alt="{{ $service->title }}" 
                             style="max-height: 400px; object-fit: cover;">
                    </div>
                    <h3 class="mb-4 border-bottom pb-2">{{ __('website.detailed_description') }}</h3>
                    <div class="service-description-text mb-5" style="line-height: 1.8; color: #555;">
                        {!! nl2br(e($service->content)) !!}
                    </div>

                    @if($service->sub_category_id)
                    <div class="p-3 bg-light rounded d-flex align-items-center mb-4">
                        <i class="bi bi-tag-fill text-primary me-3 fs-4"></i>
                        <div>
                            <span class="text-muted small d-block">{{ __('website.sub_category') }}</span>
                            <span class="fw-bold">{{ $service->subCategory->name ?? '' }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 100px;">
                    <!-- Provider Card -->
                    <div class="card border-0 shadow-sm rounded mb-4 wow fadeInRight" data-wow-delay="0.1s">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4 border-bottom pb-2">{{ __('website.service_provider_lbl') }}</h5>
                            <div class="text-center mb-4">
                                <img src="{{ $service->user->getFirstMediaUrl('personal_photo') ?: asset('website/assets/img/logo.png') }}" 
                                     class="rounded-circle mb-3 shadow-sm" width="100" height="100" style="object-fit: cover;">
                                <h4 class="mb-1">{{ $service->user->name }}</h4>
                                <p class="text-muted small mb-3">
                                    {{ $service->user->isCompany() ? __('website.company_office') : __('website.freelance_engineer') }}
                                </p>
                            </div>
                            
                            <hr>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('requests.create', ['service_id' => $service->id]) }}" class="btn btn-primary btn-lg py-3">
                                    <i class="bi bi-cart-plus me-2"></i> {{ __('website.order_now') }}
                                </a>
                                <a href="{{ route('member.public', $service->user->id) }}" class="btn btn-outline-secondary py-2">
                                    {{ __('website.view_details') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Safety Note -->
                    <div class="card border-0 bg-primary text-white rounded wow fadeInRight" data-wow-delay="0.2s">
                        <div class="card-body p-4 text-center">
                            <i class="bi bi-shield-check display-4 mb-3"></i>
                            <h5>{{ __('website.most_trusted') }}</h5>
                            <p class="small mb-0">{{ __('website.service_safety_note') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Services -->
        @if(count($relatedServices) > 0)
        <div class="mt-5 pt-5 border-top">
            <h3 class="mb-4 wow fadeInUp">{{ __('website.what_we_offer') }}</h3>
            <div class="row g-4">
                @foreach($relatedServices as $rService)
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="wyn-card">
                        <a href="{{ route('website.services.show', $rService->id) }}" class="wyn-card-link">
                            <div class="wyn-card-image">
                                <img src="{{ $rService->getFirstMediaUrl('services') ?: asset('website/assets/img/service-placeholder.png') }}"
                                    alt="{{ $rService->title }}">
                                <div class="wyn-card-overlay"></div>
                            </div>
                            <div class="wyn-card-content">
                                <h6 class="wyn-card-title">{{ $rService->title }}</h6>
                                <span class="wyn-category small">{{ $rService->category->name ?? '' }}</span>
                            </div>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
@endsection

@push('css')
<style>
    .service-detail-hero {
        min-height: 400px;
        display: flex;
        align-items: center;
        position: relative;
    }
    .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255,255,255,0.5);
    }
    .service-main-content {
        min-height: 500px;
    }
    .sticky-top {
        z-index: 10;
    }
</style>
@endpush
