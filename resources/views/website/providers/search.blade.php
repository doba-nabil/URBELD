@extends('layouts.website')
@section('body_class', 'sup-page')
@section('content')
    <!-- Header Start -->
    <div class="services-header-section without-search">
        <div class="container p-md-5 p-4 mb-md-5">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <h1 class="services-header-title wow fadeInUp" data-wow-delay="0.1s">
                        @if (isset($selectedCategory) && $selectedCategory)
                            {{ $selectedCategory->name }}
                        @else
                            {{ __('website.search_results') }}
                        @endif
                    </h1>
                </div>
                <div class="col-lg-12">
                    <p class="services-header-description wow fadeInUp mt-md-5" data-wow-delay="0.2s">
                        @if (isset($selectedCategory) && $selectedCategory && $selectedCategory->description)
                            {{ $selectedCategory->description }}
                        @else
                            {{ __('website.search_providers_desc') }}
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="search-section">
            <div class="container">
                <div class="search-content wow fadeIn" data-wow-delay="0.1s">
                    <form action="{{ route('providers.search') }}" method="GET">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <select name="sub_category_id" class="form-select select2 border-0 py-3">
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}
                                        </option>
                                        @foreach ($cat->children ?? [] as $sub)
                                            <option value="{{ $sub->id }}"
                                                {{ request('category_id') == $sub->id ? 'selected' : '' }}>&nbsp;&nbsp;↳
                                                {{ $sub->name }}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="city_id" class="form-select select2 border-0 py-3">
                                    <option value="">{{ __('website.all_cities') }}</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}"
                                            {{ request('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}
                                        </option>
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
    </div>
    <!-- Header End -->


    <!-- Services Category Start -->
    <div class="container-fluid bg-white services-section">
        <div class="container">

            <div class="category-companies-grid">
                @if (isset($selectedCategory))
                    <a href="{{ route('requests.create', ['category' => $selectedCategory->parent_id ?: $selectedCategory->id, 'subcategory' => $selectedCategory->parent_id ? $selectedCategory->id : '']) }}"
                        class="subcategory-card subcategory-card-featured wow fadeInUp d-block" data-wow-delay="0.3s"
                        data-card-icon="bi-gear" data-card-title="{{ $selectedCategory->name }}"
                        data-card-desc="{{ $selectedCategory->description }}"
                        data-card-image="{{ $selectedCategory->getFirstMediaUrl('categories') ?: asset('website/assets/img/cat-1.png') }}">
                        <div class="subcategory-featured-bg">
                            <img src="{{ $selectedCategory->getFirstMediaUrl('categories') ?: asset('website/assets/img/cat-1.png') }}"
                                alt="{{ $selectedCategory->name }}">
                            <div class="subcategory-featured-overlay"></div>
                        </div>
                        <div class="subcategory-featured-content">
                            <div class="subcategory-icon">
                                <i class="bi bi-gear"></i>
                            </div>
                            <h5 class="m-0">{{ $selectedCategory->name }}</h5>
                            <p class="m-0">{{ Str::limit($selectedCategory->description, 100) }}</p>
                            <div class="btn-div text-center">
                                <div class="btn-order-now-wrapper">
                                    <button class="btn-order-now" type="button">
                                        <span>{{ __('website.order_now') }}</span>
                                    </button>
                                    <div class="btn-order-now-circle">
                                        <i class="bi bi-arrow-up-right"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endif
            </div>
        </div>
    </div>
    <!-- Services Category End -->

    <!-- Search Filter Bar Start -->
    <div class="container-fluid bg-white services-section pb-0">
        <div class="container">

            @if (request()->hasAny(['category_id', 'city_id', 'keyword']))
                <div class="">
                    <small class="text-muted">
                        <i class="bi bi-funnel me-1"></i>
                        {{ __('website.found') }} <strong>{{ $providers->count() }}</strong> {{ __('website.service_provider') }}
                        @if (request('category_id') && isset($selectedCategory))
                            {{ __('website.in') }} <strong>{{ $selectedCategory->name }}</strong>
                        @endif
                        @if (request('city_id'))
                            @php $selectedCity = $cities->firstWhere('id', request('city_id')); @endphp
                            @if ($selectedCity)
                                {{ __('website.in_city') }} <strong>{{ $selectedCity->name }}</strong>
                            @endif
                        @endif
                    </small>
                    <a href="{{ route('providers.search') }}" class="btn btn-sm btn-outline-secondary ms-2">
                        <i class="bi bi-x-circle"></i> {{ __('website.clear_filters') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
    <!-- Search Filter Bar End -->

    <!-- Providers Section Start -->
    <div class="container-fluid bg-white individuals-section py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-12">
                    <h1 class="individuals-section-title wow fadeInUp" data-wow-delay="0.1s">
                        {{ __('website.search_results') }}
                    </h1>
                    <p class="individuals-section-description wow fadeInUp" data-wow-delay="0.2s">
                        {{ __('website.providers_desc') }}
                    </p>
                </div>
            </div>

            <!-- Tabs Start -->
            <ul class="nav nav-tabs border-0 mb-4 justify-content-center wow fadeInUp" id="searchProviderTabs" role="tablist" data-wow-delay="0.3s">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill px-4 me-2 shadow-sm border-0" id="search-companies-tab" data-bs-toggle="tab" data-bs-target="#search-companies" type="button" role="tab" aria-controls="search-companies" aria-selected="true" style="background-color: #f8f9fa; color: #333;">
                        <i class="bi bi-building me-2"></i>{{ __('website.companies_and_institutions') }}
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-4 shadow-sm border-0" id="search-individuals-tab" data-bs-toggle="tab" data-bs-target="#search-individuals" type="button" role="tab" aria-controls="search-individuals" aria-selected="false" style="background-color: #f8f9fa; color: #333;">
                        <i class="bi bi-person me-2"></i>{{ __('website.individual_providers') }}
                    </button>
                </li>
            </ul>

            <style>
                #searchProviderTabs .nav-link.active {
                    background-color: var(--primary, #00B98E) !important;
                    color: white !important;
                }
                #searchProviderTabs .nav-link:hover:not(.active) {
                    background-color: #e9ecef !important;
                }
            </style>

            <div class="tab-content wow fadeIn" id="searchProviderTabsContent" data-wow-delay="0.4s">
                <div class="tab-pane fade show active" id="search-companies" role="tabpanel" aria-labelledby="search-companies-tab">
                    @include('website.categories.partials._providers_list', ['providers' => $companyProviders])
                </div>
                <div class="tab-pane fade" id="search-individuals" role="tabpanel" aria-labelledby="search-individuals-tab">
                    @include('website.categories.partials._providers_list', ['providers' => $individualProviders])
                </div>
            </div>
            <!-- Tabs End -->
        </div>
    </div>
    <!-- Providers Section End -->
    <!-- Providers Section End -->
@endsection
