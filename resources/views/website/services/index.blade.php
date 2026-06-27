@extends('layouts.website')

@section('content')
    <!-- Header Start -->
    <div class="services-header-section">
        <div class="container p-md-5 p-3 mb-md-5">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <h1 class="services-header-title wow fadeInUp" data-wow-delay="0.1s">
                        {{ __('website.nav_services') }}
                    </h1>
                </div>
                <div class="col-lg-12 mt-md-5">
                    <p class="services-header-description wow fadeInUp" data-wow-delay="0.2s">
                        {{ __('website.services_overview') }}
                    </p>
                </div>
            </div>
        </div>
        <!-- Search Start -->
        <div class="search-section">
            <div class="container">
                <div class="search-content wow fadeIn" data-wow-delay="0.1s">
                    <form action="{{ route('website.services.index') }}" method="GET">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <select name="category_id" class="form-select select2 border-0 py-3">
                                    <option value="">{{ __('website.service_all') }}</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-icon py-3 px-5 me-3 animated fadeIn w-100">
                                    <span>{{ __('website.search') }}</span>
                                    <i class="icon-btn bi bi-arrow-up-left"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Search End -->
    </div>
    <!-- Header End -->

    <!-- Services Grid Start -->
    <div class="container-fluid bg-white py-5">
        <div class="container">
            <div class="row g-4">
                @forelse ($services as $service)
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
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">{{ __('website.no_services_available') }}</p>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $services->links() }}
            </div>
        </div>
    </div>
    <!-- Services Grid End -->
@endsection
