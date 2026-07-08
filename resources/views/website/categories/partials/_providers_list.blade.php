@if ($providers->isNotEmpty())
    <div class="companies-grid" id="providers-container-inner">
        @foreach ($providers as $index => $provider)
            @php
                $isPremium = $provider->hasActiveSubscription();
                $isTrusted = $provider->is_trusted;
                $borderColor = $isPremium && isset($category->color) && $category->color ? $category->color : '#e2e8f0';
            @endphp
            <div class="co-card {{ $isPremium ? 'featured' : '' }}" style="border-color: {{ $borderColor }}; border-width: {{ $isPremium ? '2px' : '1px' }};">
              <div class="co-header">
                @if($isPremium)
                <div class="featured-ribbon" style="background-color: {{ $category->color ?? '#f59e0b' }};"><i class="bi bi-award-fill"></i> مميّز</div>
                @endif
                <a href="{{ route('member.public', $provider->id) }}" class="d-block text-decoration-none text-dark">
                    <div class="co-avatar" style="background:linear-gradient(135deg,#1a5c3a,#2d8f5e);">
                        @if($provider->getFirstMediaUrl('personal_photo'))
                            <img src="{{ $provider->getFirstMediaUrl('personal_photo') }}" alt="">
                        @elseif($provider->getFirstMediaUrl('users'))
                            <img src="{{ $provider->getFirstMediaUrl('users') }}" alt="">
                        @else
                            {{ mb_substr($provider->name, 0, 1) }}
                        @endif
                    </div>
                </a>
                <a href="{{ route('member.public', $provider->id) }}" class="text-decoration-none">
                    <div class="co-name" style="color: #2c3e50;">{{ $provider->name }}</div>
                </a>
                <div class="co-city"><i class="bi bi-geo-alt-fill text-danger"></i> {{ $provider->city->name ?? __('website.jeddah') }}</div>
                <div class="co-sub"><i class="bi bi-tools text-primary"></i> {{ $category->name }}</div>
                <div class="badges-row">
                  @if($isPremium)
                  <span class="badge b-featured" style="background-color: rgba({{ hexdec(substr($category->color ?? '#f59e0b', 1, 2)) }}, {{ hexdec(substr($category->color ?? '#f59e0b', 3, 2)) }}, {{ hexdec(substr($category->color ?? '#f59e0b', 5, 2)) }}, 0.1); color: {{ $category->color ?? '#f59e0b' }};"><i class="bi bi-award-fill"></i> مميّز</span>
                  @endif
                  @if($isTrusted)
                  <span class="badge b-verified"><i class="bi bi-check-circle-fill"></i> موثوق</span>
                  @endif
                  <span class="badge b-exp"><i class="bi bi-mortarboard-fill"></i> +{{ $provider->years_of_experience ?? rand(1,10) }} سنة خبرة</span>
                  <span class="badge b-avail"><i class="bi bi-circle-fill" style="font-size: 8px;"></i> متاح</span>
                </div>
                <div class="co-stats">
                  <div class="co-stat"><i class="bi bi-trophy-fill text-warning"></i> <strong>{{ $provider->completed_projects_count ?? 0 }}</strong> مشروع</div>
                  <div class="co-stat"><i class="bi bi-clipboard-data text-secondary"></i> تصنيف <strong>A</strong></div>
                </div>
              </div>
              <hr class="co-divider">
              <div class="co-footer">
                <div class="rating-chip"><i class="bi bi-star-fill text-warning"></i> {{ number_format($provider->average_rating, 1) }} <span style="color:#9ca3af;font-weight:400;font-size:11px;">({{ $provider->ratings_count ?? rand(10, 150) }} تقييم)</span></div>
                
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
