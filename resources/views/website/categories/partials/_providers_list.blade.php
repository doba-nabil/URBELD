@if ($providers->isNotEmpty())
    <div class="companies-grid" id="providers-container-inner">
        @foreach ($providers as $index => $provider)
            @php
                $isPremium = $provider->hasActiveSubscription();
                $isTrusted = $provider->is_trusted;
                $borderColor = $isPremium && isset($category) && isset($category->color) && $category->color ? $category->color : '#e2e8f0';
                $catColor = isset($category) && isset($category->color) ? $category->color : '#f59e0b';
            @endphp
            <div class="co-card {{ $isPremium ? 'featured' : '' }}" style="border-color: {{ $borderColor }}; border-width: {{ $isPremium ? '2px' : '1px' }};">
              <div class="co-header">
                @if($isPremium)
                <div class="featured-ribbon" style="background-color: {{ $catColor }};"><i class="bi bi-award-fill"></i> مميّز</div>
                @endif

                <a href="{{ route('member.public', $provider->id) }}" class="text-decoration-none">
                    <div class="co-name" style="color: #2c3e50;">{{ $provider->name }}</div>
                </a>
                <div class="co-city"><i class="bi bi-geo-alt-fill text-danger"></i> {{ $provider->city->name ?? __('website.jeddah') }}</div>
                <div class="co-sub"><i class="bi bi-tools text-primary"></i> {{ isset($category) ? $category->name : ($provider->categories->first()->name ?? '') }}</div>
                <div class="badges-row">
                  @if($isPremium)
                  <span class="badge b-featured" style="background-color: rgba({{ hexdec(substr($catColor, 1, 2)) }}, {{ hexdec(substr($catColor, 3, 2)) }}, {{ hexdec(substr($catColor, 5, 2)) }}, 0.1); color: {{ $catColor }};"><i class="bi bi-award-fill"></i> مميّز</span>
                  @endif
                  @if($isTrusted)
                  <span class="badge b-verified"><i class="bi bi-check-circle-fill"></i> موثوق</span>
                  @endif
                  
                  @if($provider->provider_type === 'supplier')
                      @if($provider->classification_id && $provider->classification)
                          <span class="badge" style="background: rgba(217,119,6,0.1); color: #d97706; border: 1px solid #b45309;"><i class="bi bi-box-seam"></i> {{ $provider->classification->name }}</span>
                      @endif
                      @if($provider->deliveryCities()->exists())
                          <span class="badge" style="background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid #059669;"><i class="bi bi-truck"></i> {{ __('website.delivery_available') ?? 'توصيل متاح' }}</span>
                      @endif
                  @else
                      @if($provider->years_of_experience)
                          <span class="badge b-exp"><i class="bi bi-mortarboard-fill"></i> +{{ $provider->years_of_experience }} {{ __('website.years_of_experience') ?? 'سنة خبرة' }}</span>
                      @endif
                  @endif
                  <span class="badge b-avail"><i class="bi bi-circle-fill" style="font-size: 8px;"></i> {{ __('website.available') ?? 'متاح' }}</span>
                </div>
                <div class="co-stats">
                  <div class="co-stat"><i class="bi bi-trophy-fill text-warning"></i> <strong>{{ $provider->completed_projects_count ?? 0 }}</strong> {{ __('website.project') ?? 'مشروع' }}</div>
                  <div class="co-stat"><i class="bi bi-clipboard-data text-secondary"></i> {{ __('website.classification') ?? 'تصنيف' }} <strong>{{ $provider->classification ? $provider->classification->name : __('website.unspecified') ?? 'غير محدد' }}</strong></div>
                </div>
              </div>
              <hr class="co-divider">
              <div class="co-footer">
                <div class="rating-chip"><i class="bi bi-star-fill text-warning"></i> {{ number_format($provider->average_rating, 1) }} <span style="color:#9ca3af;font-weight:400;font-size:11px;">({{ $provider->ratings_count ?? 0 }} تقييم)</span></div>
                
                @auth
                    <a href="{{ route('requests.create', array_filter(['provider_id' => $provider->id, 'category' => $category->id ?? null])) }}" class="btn-quote {{ $isPremium ? 'featured-btn' : '' }}">طلب عرض سعر</a>
                @else
                    <a href="{{ route('login') }}" class="btn-quote {{ $isPremium ? 'featured-btn' : '' }}">طلب عرض سعر</a>
                @endauth
              </div>
            </div>
        @endforeach
    </div>
@else
    <!-- No Results -->
    <div class="text-center py-5 w-100">
        <div class="mb-4">
            <i class="bi bi-search" style="font-size: 4rem; color: #143526; opacity: 0.2;"></i>
        </div>
        <h4 class="fw-bold mb-3" style="color: #2c3e50;">{{ __('website.no_providers_found') }}</h4>
        <p class="text-muted">{{ __('website.no_providers_desc') }}</p>
    </div>
@endif
