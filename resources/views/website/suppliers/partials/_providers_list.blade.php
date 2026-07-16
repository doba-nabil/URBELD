@if ($suppliers->isNotEmpty())
    <div class="companies-grid" id="providers-container-inner">
        @foreach ($suppliers as $index => $provider)
            @php
                $isPremium = $provider->hasActiveSubscription();
                $isTrusted = $provider->is_trusted;
                
                // Get the first supply category for this provider if not explicitly filtering by one
                $providerCategory = $provider->categories->where('supports_supply_requests', 1)->first() ?? $provider->categories->first();
                $borderColor = $isPremium && isset($providerCategory->color) && $providerCategory->color ? $providerCategory->color : '#e2e8f0';
                $themeColor = $providerCategory->color ?? '#10b981';
            @endphp
            <div class="co-card {{ $isPremium ? 'featured' : '' }}" style="border-color: {{ $borderColor }}; border-width: {{ $isPremium ? '2px' : '1px' }};">
              <div class="co-header">
                @if($isPremium)
                <div class="featured-ribbon" style="background-color: {{ $themeColor }};"><i class="bi bi-award-fill"></i> مميّز</div>
                @endif
                <a href="{{ route('member.public', $provider->id) }}" class="text-decoration-none">
                    <div class="co-name" style="color: #2c3e50;">{{ $provider->name }}</div>
                </a>
                <div class="co-city"><i class="bi bi-geo-alt-fill text-danger"></i> {{ $provider->city->name ?? __('website.jeddah') }}</div>
                @if($providerCategory)
                <div class="co-sub"><i class="bi bi-tools text-primary"></i> {{ $providerCategory->name }}</div>
                @endif
                <div class="badges-row">
                  @if($isPremium)
                  <span class="badge b-featured" style="background-color: rgba({{ hexdec(substr($themeColor, 1, 2)) }}, {{ hexdec(substr($themeColor, 3, 2)) }}, {{ hexdec(substr($themeColor, 5, 2)) }}, 0.1); color: {{ $themeColor }};"><i class="bi bi-award-fill"></i> مميّز</span>
                  @endif
                  @if($isTrusted)
                  <span class="badge b-verified"><i class="bi bi-check-circle-fill"></i> موثوق</span>
                  @endif
                  
                  @if($provider->classification_id && $provider->classification)
                      <span class="badge" style="background: rgba(217,119,6,0.1); color: #d97706; border: 1px solid #b45309;"><i class="bi bi-box-seam"></i> {{ $provider->classification->name }}</span>
                  @endif
                  @if($provider->deliveryCities()->exists())
                      <span class="badge" style="background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid #059669;"><i class="bi bi-truck"></i> {{ __('website.delivery_available') ?? 'توصيل متاح' }}</span>
                  @endif
                  
                  <span class="badge b-avail"><i class="bi bi-circle-fill" style="font-size: 8px;"></i> {{ __('website.available') ?? 'متاح' }}</span>
                </div>
                <div class="co-stats">
                  <div class="co-stat"><strong>{{ $provider->products()->count() }}</strong> {{ __('website.products_count') ?? 'منتج' }}</div>
                </div>
              </div>
              <hr class="co-divider">
              <div class="co-footer">
                <div class="rating-chip"><i class="bi bi-star-fill text-warning"></i> {{ number_format($provider->average_rating, 1) }} <span style="color:#9ca3af;font-weight:400;font-size:11px;">({{ $provider->ratings_count ?? 0 }} تقييم)</span></div>
                
                @auth
                    @if(auth()->id() !== $provider->id)
                    <a href="{{ route('website.supply-requests.create', array_filter(['provider_id' => $provider->id, 'category' => $providerCategory->id ?? null])) }}" class="btn-quote {{ $isPremium ? 'featured-btn' : '' }}">طلب تسعيرة توريد</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn-quote {{ $isPremium ? 'featured-btn' : '' }}">طلب تسعيرة توريد</a>
                @endauth
              </div>
            </div>
        @endforeach
    </div>
@else
    <div class="col-12 text-center py-5 bg-white rounded shadow-sm">
        <img src="{{ asset('website/assets/img/no-data.svg') }}" alt="لا يوجد بيانات" class="mb-3" style="width: 150px; opacity: 0.5;">
        <h4 class="text-muted fw-bold">لم يتم العثور على موردين</h4>
        <p class="text-muted mb-0">لا يوجد موردين يطابقون معايير البحث الحالية في هذا القسم. جرب تغيير التصنيف للحصول على نتائج أفضل.</p>
    </div>
@endif
