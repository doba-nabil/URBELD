@if ($providers->isNotEmpty())
    <div class="row g-4" id="providers-container-inner">
        @foreach ($providers as $index => $provider)
            @php
                $isPremium = $provider->hasActiveSubscription();
            @endphp
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="{{ (($index % 3) + 1) * 0.1 }}s">
                <div class="provider-card {{ $isPremium ? 'premium-card' : '' }}">
                    <!-- Name & Location -->
                    <div class="text-center mb-3">
                        <h5 class="provider-name">{{ $provider->name }}</h5>
                        <div class="provider-location">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i> 
                            {{ $provider->city->name ?? __('website.jeddah') }}
                        </div>
                    </div>
                    
                    <!-- Category tag -->
                    <div class="text-center mb-3">
                        <span class="provider-cat-tag">
                            <i class="bi bi-tools me-1"></i> {{ $category->name }}
                        </span>
                    </div>

                    <!-- Badges -->
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        @if($isPremium)
                            <span class="badge-premium"><i class="bi bi-award-fill"></i> مميز</span>
                            <span class="badge-trusted"><i class="bi bi-check-circle-fill"></i> موثوق</span>
                        @else
                            <span class="badge-trusted"><i class="bi bi-check-circle-fill"></i> موثوق</span>
                            <span class="badge-experience"><i class="bi bi-mortarboard-fill"></i> +{{ $provider->years_of_experience ?? rand(1,10) }} سنة خبرة</span>
                        @endif
                    </div>

                    <!-- Stats -->
                    <div class="provider-stats-row border-bottom pb-3">
                        <div class="stat-box">
                            <i class="bi bi-trophy-fill text-warning"></i>
                            <span>{{ $provider->completed_projects_count ?? 0 }} مشروع</span>
                        </div>
                        <div class="stat-box">
                            <i class="bi bi-clipboard-data text-secondary"></i>
                            <span>تصنيف A</span>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="d-flex justify-content-between align-items-center pt-2 mt-auto">
                        @auth
                            <a href="{{ route('requests.create', array_filter(['provider_id' => $provider->id, 'category' => $category->id ?? null])) }}" class="btn btn-request {{ $isPremium ? 'premium-btn' : 'normal-btn' }}">
                                طلب عرض سعر
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-request {{ $isPremium ? 'premium-btn' : 'normal-btn' }}">
                                طلب عرض سعر
                            </a>
                        @endauth
                        
                        <div class="d-flex align-items-center gap-1 dir-ltr justify-content-end" style="direction: ltr;">
                            <span class="provider-rating-count">({{ $provider->ratings_count ?? rand(10, 150) }} تقييم)</span>
                            <span class="provider-rating-score">
                                {{ number_format($provider->average_rating, 1) }} <i class="bi bi-star-fill text-warning mb-1"></i>
                            </span>
                        </div>
                    </div>
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
