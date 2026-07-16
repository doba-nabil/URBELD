@extends('layouts.website')
@section('body_class', 'sup-page')
@section('content')
    <!-- Header Start -->
    <div class="services-header-section">
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

        <!-- FILTER -->
        <div class="filter-wrap position-relative" style='z-index: 9;'>
          <div class="filter-card">
            <div class="filter-top">
              <div class="filter-title"><i class="bi bi-search me-1"></i> {{ __('website.search_for_provider') }}</div>
            </div>
            <form action="{{ route('providers.search') }}" method="GET">
                <div class="filter-grid" style="grid-template-columns: repeat(4, 1fr);">
                    <div class="fg">
                        <label>{{ __('website.region') }}</label>
                        <select name="region_id" class="select2">
                            <option value="">{{ __('website.all') }}</option>
                            @foreach ($regions ?? [] as $region)
                                <option value="{{ $region->id }}" {{ request('region_id') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fg">
                        <label>{{ __('website.city') }}</label>
                        <select name="city_id" class="select2">
                            <option value="">{{ __('website.all') }}</option>
                            @foreach ($cities ?? [] as $city)
                                <option value="{{ $city->id }}" data-region="{{ $city->region_id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fg">
                        <label>{{ __('website.sub_category') ?? 'القسم الفرعي' }}</label>
                        <select name="sub_category_id" class="select2">
                            <option value="">{{ __('website.all_sections') }}</option>
                            @foreach ($categories ?? [] as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @foreach ($cat->children ?? [] as $sub)
                                    <option value="{{ $sub->id }}" {{ request('category_id') == $sub->id ? 'selected' : '' }}>&nbsp;&nbsp;↳ {{ $sub->name }}</option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                    <div class="fg">
                        <label>{{ __('website.company_size') ?? 'حجم الشركة' }}</label>
                        <select name="classification_id" class="select2">
                            <option value="">{{ __('website.all') }}</option>
                            @foreach ($classifications ?? [] as $class)
                                <option value="{{ $class->id }}" {{ request('classification_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn-search"><i class="bi bi-search me-1"></i> {{ __('website.search_providers') ?? 'ابحث عن مزودي الخدمة' }}</button>
                </div>
            </form>
          </div>
        </div>
    </div>
    <!-- Header End -->


    <!-- Services Category Start -->
    <div class="container-fluid">
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

            <div class="wow fadeIn" data-wow-delay="0.4s">
                @include('website.categories.partials._providers_list', ['providers' => $providers])
            </div>
        </div>
    </div>
    <!-- Providers Section End -->
    <!-- Providers Section End -->
@endsection
