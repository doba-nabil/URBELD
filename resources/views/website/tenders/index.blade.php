@extends('layouts.website')

@section('title', __('tenders.title'))

@section('content')

<!-- Header Start -->
<div class="category-header-section text-center services-header-section without-search">
    <div class="container" style="max-width: 1320px;">
        <h1 class="fw-bold mb-3 wow fadeInUp" data-wow-delay="0.1s">{{ __('tenders.title') }}</h1>
        <p class="mb-0 wow fadeInUp" data-wow-delay="0.2s">{{ __('tenders.subtitle') }}</p>
    </div>
</div>
<!-- Header End -->

<form action="{{ route('website.tenders.index') }}" method="GET" id="searchForm">
<div class="filter-wrapper" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
  <div class="filter-card">
    <div class="filter-title"><i class="bi bi-search me-1"></i> {{ __('tenders.search_title') }}</div>
    <div class="filter-grid">
      
      <!-- Category Filter -->
      <div class="filter-group">
        <label>{{ __('tenders.category') }}</label>
        <select name="category_id" class="form-select">
          <option value="">{{ __('tenders.all_categories') }}</option>
          @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
          @endforeach
        </select>
      </div>

      <!-- Region Filter -->
      <div class="filter-group">
        <label>المنطقة</label>
        <select name="region_id" class="form-select">
          <option value="">كل المناطق</option>
          @foreach(\App\Models\Region::all() as $region)
            <option value="{{ $region->id }}" {{ request('region_id') == $region->id ? 'selected' : '' }}>
                {{ $region->name }}
            </option>
          @endforeach
        </select>
      </div>

      <!-- City Filter -->
      <div class="filter-group">
        <label>{{ __('tenders.city') }}</label>
        <select name="city_id" class="form-select">
          <option value="">{{ __('tenders.all_cities') }}</option>
          @foreach($cities as $city)
            <option value="{{ $city->id }}" data-region="{{ $city->region_id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                {{ $city->name }}
            </option>
          @endforeach
        </select>
      </div>


      <!-- Sorting -->
      <div class="filter-group">
        <label>{{ __('tenders.sort_by') }}</label>
        <select name="sort" class="form-select">
          <option value="latest" {{ $sort == 'latest' ? 'selected' : '' }}>{{ __('tenders.sort_latest') }}</option>
          <option value="budget_high" {{ $sort == 'budget_high' ? 'selected' : '' }}>{{ __('tenders.sort_budget_high') }}</option>
          <option value="ending_soon" {{ $sort == 'ending_soon' ? 'selected' : '' }}>{{ __('tenders.sort_ending_soon') }}</option>
        </select>
      </div>

    </div>
    
    <input type="hidden" name="tab" id="tabInput" value="{{ $tab }}">
    <button type="submit" class="btn-search mt-3"><i class="bi bi-search me-1"></i> {{ __('tenders.search_btn') }}</button>

    <!-- CATEGORY BOXES (Quick Filters) -->
    <div class="cat-boxes mt-4">
      @foreach($categories->take(4) as $cat)
        <a href="{{ route('website.tenders.index', ['category_id' => $cat->id]) }}" class="text-decoration-none">
          <div class="cat-box {{ request('category_id') == $cat->id ? 'active' : '' }}">
            <div class="cat-icon"><i class="{{ $cat->icon ?? 'bi bi-buildings-fill' }} text-primary"></i></div>
            <div class="cat-label">{{ $cat->name }}</div>
          </div>
        </a>
      @endforeach
    </div>
  </div>
</div>
</form>

<div class="main-tenders-list" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
  <div class="top-row">
    <h2>{{ __('tenders.tenders_list') }}</h2>
    <a href="{{ route('home') }}" class="back-link"><i class="bi bi-arrow-right"></i> {{ __('tenders.back_to_home') }}</a>
  </div>

  <div class="add-btn-row">
    @if(auth()->check() && auth()->user()->user_type === 'service_provider' && auth()->user()->active !== 'active' && auth()->user()->active !== '1' && auth()->user()->active !== 1)
        <a href="{{ route('profile.complete') }}" class="btn-add-new" style="background-color: #f59e0b; border-color: #f59e0b;"><i class="bi bi-exclamation-triangle-fill me-1"></i> {{ __('website.please_activate_membership_to_add_tender') }}</a>
    @else
        <a href="{{ route('website.tenders.create') }}" class="btn-add-new"><i class="bi bi-plus-lg me-1"></i> {{ __('tenders.add_new') }}</a>
    @endif
  </div>

  <div class="tabs-row">
    <button class="tab-pill {{ $tab == 'all' ? 'active' : '' }}" onclick="changeTab('all')">{{ __('tenders.tab_all') }} ({{ $stats['all'] }})</button>
    <button class="tab-pill {{ $tab == 'open' ? 'active' : '' }}" onclick="changeTab('open')">{{ __('tenders.tab_open') ?? 'مفتوحة' }} ({{ $stats['open'] ?? 0 }})</button>
    <button class="tab-pill {{ $tab == 'closed' ? 'active' : '' }}" onclick="changeTab('closed')">{{ __('tenders.tab_closed') }} ({{ $stats['closed'] }})</button>
    <button class="tab-pill {{ $tab == 'urgent' ? 'active' : '' }}" onclick="changeTab('urgent')">{{ __('tenders.tab_urgent') }} ({{ $stats['urgent'] }})</button>
  </div>

  <div class="stats-bar">
    <div class="stats-count">{{ __('tenders.showing') }} <span>{{ $tenders->total() }}</span> {{ __('tenders.tender_word') }}</div>
  </div>

  @if($tenders->count() > 0)
    @foreach($tenders as $tender)
      <div class="t-card-v2 {{ $tender->is_urgent ? 'border-urgent' : '' }}" {!! $tender->is_urgent ? 'style="border-right: 4px solid #f59e0b;"' : '' !!}>
        <div class="t-card-left">
          @if($tender->status === \App\Models\Tender::STATUS_COMPLETED)
            <span class="status-badge status-closed" style="background:#10b981;color:#fff;border:none;"><i class="bi bi-check2-circle"></i> مكتملة</span>
          @elseif($tender->is_urgent)
            <span class="status-badge status-urgent"><i class="bi bi-lightning-charge-fill"></i> {{ __('tenders.status_urgent') }}</span>
          @elseif($tender->isExpired() || $tender->status === \App\Models\Tender::STATUS_CLOSED)
            <span class="status-badge status-closed">{{ __('tenders.status_closed') }}</span>
          @else
            <span class="status-badge status-open">{{ __('tenders.status_open') }}</span>
          @endif
          
          @if(!$tender->isExpired() && $tender->status === \App\Models\Tender::STATUS_ACTIVE)
            <a href="{{ route('website.tenders.show', $tender->id) }}" class="btn-offer-v2" {!! $tender->is_urgent ? 'style="background:#d97706;"' : '' !!}>
              <i class="bi bi-plus-lg me-1"></i> {{ __('tenders.apply_offer') }}
            </a>
          @else
            <button class="btn-offer-disabled" disabled>{{ __('tenders.ended_tender') }}</button>
          @endif
        </div>
        <div class="t-card-right">
          <a href="{{ route('website.tenders.show', $tender->id) }}" class="text-decoration-none">
            <div class="t-card-title">{{ $tender->title }}</div>
          </a>
          <div class="t-card-meta">
            @if($tender->category)
              <div class="t-meta-item"><i class="bi bi-folder text-secondary"></i> {{ $tender->category->name }}</div>
            @endif
            @if($tender->city)
              <div class="t-meta-item"><i class="bi bi-geo-alt-fill text-danger"></i> {{ $tender->city->name }}</div>
            @endif
            @if($tender->budget)
              <div class="t-meta-item"><i class="bi bi-cash-stack text-success"></i> {{ number_format($tender->budget) }} {{ __('tenders.sar') }}</div>
            @endif
            <span class="deadline-chip {{ $tender->isExpired() ? 'closed' : '' }}">
              <i class="bi bi-calendar-event"></i> {{ __('tenders.ends_at') }} {{ $tender->ends_at ? $tender->ends_at->format('Y-m-d') : __('tenders.not_specified') }}
            </span>
          </div>
          <p class="t-card-desc">{{ Str::limit(strip_tags($tender->description), 150) }}</p>
        </div>
      </div>
    @endforeach

    <div class="pagination-row mt-4 d-flex justify-content-center">
      {{ $tenders->links() }}
    </div>
  @else
    <div class="text-center py-5 rounded-4 mt-4" style="background-color: #f8f9fa; border: 1px dashed #dee2e6;">
        <i class="bi bi-folder-x text-muted mb-3 d-block" style="font-size: 3rem; opacity: 0.5;"></i>
        <h5 class="fw-bold text-dark mb-1">{{ __('tenders.no_tenders') }}</h5>
        <p class="text-muted small mb-0">يمكنك العودة لاحقاً أو تعديل معايير البحث لاستكشاف مناقصات جديدة.</p>
    </div>
  @endif

</div>

@endsection

@push('js')
<script>
  function changeTab(tabName) {
    document.getElementById('tabInput').value = tabName;
    document.getElementById('searchForm').submit();
  }
  
  @if(session('error_popup') == 'subscription_required')
      document.addEventListener("DOMContentLoaded", function() {
          if (typeof Swal !== 'undefined') {
              Swal.fire({
                  title: '{{ __("tenders.sub_required_title") ?? "باقة اشتراك مطلوبة" }}',
                  text: '{{ __("tenders.sub_required_text") ?? "عذراً، يجب أن يكون لديك باقة فعالة لإضافة مناقصة، أو يمكنك الدفع لمرة واحدة لهذه المناقصة فقط." }}',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonText: '{{ __("tenders.go_to_packages") ?? "الذهاب للباقات" }}',
                  cancelButtonText: '{{ __("tenders.pay_per_use") ?? "دفع للمناقصة فقط" }}',
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#10b981',
              }).then((result) => {
                  if (result.isConfirmed) {
                      window.location.href = "{{ route('website.subscription-packages.index') }}";
                  } else if (result.dismiss === Swal.DismissReason.cancel) {
                      window.location.href = "{{ route('website.tenders.paymentPage', ['type' => 'add']) }}";
                  }
              });
          } else {
              alert('{{ __("tenders.sub_required") ?? "عذراً، يجب أن يكون لديك باقة فعالة لإضافة مناقصة" }}');
          }
      });
  @endif
</script>
@endpush
