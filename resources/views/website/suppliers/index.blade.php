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
    @php
        $bannerCategory = null;
        if (isset($selectedCategory)) {
            $bannerCategory = $selectedCategory->parent_id !== null ? $selectedCategory->parent : $selectedCategory;
        }
    @endphp
    <div class="bc-wrap">
      <div class="bc-banner">
        <div class="bc-left">
          <div class="bc-icon-wrap"><i class="bi bi-megaphone-fill text-white"></i></div>
          <div>
            <div class="bc-title">{{ $bannerCategory && $bannerCategory->bulk_request_title ? $bannerCategory->bulk_request_title : __('website.send_request_to_all_providers_at_once') }}</div>
            <div class="bc-sub">{{ $bannerCategory && $bannerCategory->bulk_request_subtitle ? $bannerCategory->bulk_request_subtitle : __('website.choose_section_and_receive_quotes') }}</div>
          </div>
        </div>
        <a href="{{ $bannerCategory ? route('website.supply-requests.create', ['category_id' => $bannerCategory->id]) : route('website.supply-requests.create') }}" class="btn-bc" style="text-decoration: none;"><i class="bi bi-send-fill me-1"></i> {{ $bannerCategory && $bannerCategory->bulk_request_button_text ? $bannerCategory->bulk_request_button_text : (__('website.send_bulk_request') ?? 'طلب توريد عام') }}</a>
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
                          <option value="{{ $city->id }}" data-region="{{ $city->region_id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                      @endforeach
                  @endif
                </select>
              </div>
              <div class="fg">
                <label>{{ __('website.supply_section') ?? 'قسم التوريد' }}</label>
                <select name="category_id" id="subFilter" class="select2">
                  <option value="">{{ __('website.all_sections') }}</option>
                  
                  @if (isset($selectedCategory))
                      @if ($selectedCategory->parent_id === null)
                          <option value="{{ $selectedCategory->id }}" selected>
                              {{ $selectedCategory->name }} ({{ __('website.all') }})
                          </option>
                      @else
                          <option value="{{ $selectedCategory->parent->id ?? '' }}">
                              {{ $selectedCategory->parent->name ?? __('website.back_to_main') }} ({{ __('website.all') }})
                          </option>
                      @endif
                  @endif

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
    </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const regionSelect = document.querySelector('select[name="region_id"]');
        const citySelect = document.querySelector('select[name="city_id"]');
        
        if (regionSelect && citySelect) {
            regionSelect.addEventListener('change', function() {
                const regionId = this.value;
                const options = citySelect.querySelectorAll('option');
                
                let firstVisible = null;
                options.forEach(option => {
                    if (option.value === "") {
                        option.style.display = '';
                    } else if (!regionId || option.getAttribute('data-region') === regionId) {
                        option.style.display = '';
                        if (!firstVisible) firstVisible = option;
                    } else {
                        option.style.display = 'none';
                    }
                });
                
                citySelect.value = "";
                if (typeof jQuery !== 'undefined' && $(citySelect).hasClass('select2-hidden-accessible')) {
                    $(citySelect).trigger('change');
                }
            });
        }
    });
</script>
@endpush
@endsection
