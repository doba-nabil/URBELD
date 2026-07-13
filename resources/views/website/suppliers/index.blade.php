@extends('layouts.website')

@section('content')
    <!-- Header Start -->
    <div class="category-header-section text-center services-header-section without-search">
        @if(isset($selectedCategory))
            <h1 class="fw-bold mb-3 wow fadeInUp" data-wow-delay="0.1s">{{ $selectedCategory->name }}</h1>
            <p class="mb-0 wow fadeInUp" data-wow-delay="0.2s">{{ $selectedCategory->description }}</p>
        @else
            <h1 class="fw-bold mb-3 wow fadeInUp" data-wow-delay="0.1s">{{ __('website.suppliers_hub') ?? 'دليل الموردين' }}</h1>
            <p class="mb-0 wow fadeInUp" data-wow-delay="0.2s">{{ __('website.browse_suppliers') ?? 'تصفح أفضل الموردين واطلب تسعيرة لتوريداتك بكل سهولة' }}</p>
        @endif
    </div>
    <!-- Header End -->

    <!-- BROADCAST BANNER -->
    <div class="bc-wrap">
      <div class="bc-banner">
        <div class="bc-left">
          <div class="bc-icon-wrap"><i class="bi bi-megaphone-fill text-white"></i></div>
          <div>
            <div class="bc-title">{{ isset($selectedCategory) && $selectedCategory->bulk_request_title ? $selectedCategory->bulk_request_title : __('website.send_request_to_all_providers_at_once') }}</div>
            <div class="bc-sub">{{ isset($selectedCategory) && $selectedCategory->bulk_request_subtitle ? $selectedCategory->bulk_request_subtitle : __('website.choose_section_and_receive_quotes') }}</div>
          </div>
        </div>
        <a href="{{ isset($selectedCategory) ? route('website.supply-requests.create', ['category_id' => $selectedCategory->id]) : route('website.supply-requests.create') }}" class="btn-bc" style="text-decoration: none;"><i class="bi bi-send-fill me-1"></i> {{ isset($selectedCategory) && $selectedCategory->bulk_request_button_text ? $selectedCategory->bulk_request_button_text : (__('website.send_bulk_request') ?? 'طلب توريد عام') }}</a>
      </div>
    </div>

    <!-- FILTER -->
    <div class="filter-wrap">
      <div class="filter-card">
        <div class="filter-top">
          <div class="filter-title"><i class="bi bi-search me-1"></i> {{ __('website.search_for_provider') }}</div>
        </div>
        <form action="{{ route('website.suppliers.index') }}" method="GET">
            @if(isset($selectedCategory))
                <input type="hidden" name="category_id" value="{{ $selectedCategory->id }}">
            @endif
            <div class="filter-grid" style="grid-template-columns: repeat(4, 1fr);">
              <div class="fg">
                <label>{{ __('website.region') }}</label>
                <select name="region_id" class="select2">
                  <option value="">{{ __('website.all') }}</option>
                  @if (isset($regions))
                      @foreach ($regions as $region)
                          <option value="{{ $region->id }}" {{ request('region_id') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                      @endforeach
                  @endif
                </select>
              </div>
              <div class="fg">
                <label>{{ __('website.city') }}</label>
                <select name="city_id" class="select2">
                  <option value="">{{ __('website.all') }}</option>
                  @if (isset($cities))
                      @foreach ($cities as $city)
                          <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                      @endforeach
                  @endif
                </select>
              </div>
              <div class="fg">
                <label>{{ __('website.supply_section') ?? 'قسم التوريد' }}</label>
                <select name="category_id" id="subFilter" class="select2">
                  <option value="">{{ __('website.all_sections') }}</option>
                  @if (isset($supplyCategories))
                      @foreach ($supplyCategories as $cat)
                          <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                          @foreach ($cat->children ?? [] as $sub)
                              <option value="{{ $sub->id }}" {{ request('category_id') == $sub->id ? 'selected' : '' }}>&nbsp;&nbsp;↳ {{ $sub->name }}</option>
                          @endforeach
                      @endforeach
                  @endif
                </select>
              </div>
              <div class="fg">
                <label>{{ __('website.supply_volume') ?? 'حجم التوريد' }}</label>
                <select name="classification_id" class="select2">
                  <option value="">{{ __('website.all') }}</option>
                  @if (isset($classifications))
                      @foreach ($classifications as $class)
                          <option value="{{ $class->id }}" {{ request('classification_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                      @endforeach
                  @endif
                </select>
              </div>
            </div>
            <div class="filter-actions">
              <button type="submit" class="btn-search"><i class="bi bi-search me-1"></i> {{ __('website.search_providers') }}</button>
            </div>
        </form>
      </div>
    </div>

    <!-- SUB-CATEGORIES -->
    <div class="sub-wrap">
      <div class="sub-title">{{ __('website.browse_by_section') }}</div>
      <div class="sub-grid">
        <a href="{{ route('website.suppliers.index') }}" class="sub-box {{ !request('category_id') ? 'active' : '' }}">
          <div class="sub-icon"><i class="bi bi-grid-fill"></i></div>
          <div class="sub-label">{{ __('website.all') }}</div>
          <div class="sub-count">{{ $suppliers->count() ?? 0 }} {{ __('website.supplier') ?? 'مورد' }}</div>
        </a>

        @foreach($supplyCategories as $sub)
        <a href="{{ route('website.suppliers.index', ['category_id' => $sub->id]) }}" class="sub-box {{ request('category_id') == $sub->id ? 'active' : '' }}">
          <div class="sub-icon">
             @if($sub->getFirstMediaUrl('categories'))
                 <img src="{{ $sub->getFirstMediaUrl('categories') }}" alt="" style="width: 28px; height: 28px; object-fit: contain;">
             @else
                 <i class="bi bi-folder-fill"></i>
             @endif
          </div>
          <div class="sub-label">{{ $sub->name }}</div>
          <div class="sub-count">{{ $sub->providers_count ?? 0 }} {{ __('website.supplier') ?? 'مورد' }}</div>
        </a>
        @endforeach
      </div>
    </div>

    <!-- COMPANIES -->
    <div class="companies-wrap">
      <div class="sec-header">
        <div class="sec-header-left">
          <h2 id="providers-title">الموردين <span id="activeSubLabel"></span></h2>
          <p id="countLabel">{{ $suppliers->count() ?? 0 }} مورد مسجّل في هذا القسم</p>
        </div>
      </div>

      <div class="wow fadeIn" id="providerTabsContent" data-wow-delay="0.2s">
          @include('website.suppliers.partials._providers_list')
      </div>
    </div>
    
@endsection
