@if ($providers->isNotEmpty())
    <div class="individuals-grid" id="providers-container-inner">
        @foreach ($providers as $index => $provider)
            <div class="individual-card wow fadeInUp position-relative overflow-hidden" data-wow-delay="{{ (($index % 4) + 1) * 0.1 }}s">
                @if($provider->hasActiveSubscription() && $provider->subscriptionPackage && $provider->subscriptionPackage->badge_name)
                    <div class="card-ribbon">
                        <span style="background-color: {{ $provider->subscriptionPackage->color ?: 'var(--bs-primary)' }}">
                            {{ $provider->subscriptionPackage->badge_name }}
                        </span>
                    </div>
                @endif
                <a href="{{ route('member.public', $provider->id) }}" class="text-decoration-none">
                    <div class="individual-profile-image">
                        @php
                            $providerLogo = app()->getLocale() == 'ar' 
                                ? \App\Models\Setting::getMediaUrl('logo_ar') 
                                : \App\Models\Setting::getMediaUrl('logo_en');
                            $defaultAvatar = $providerLogo ?: asset('website/assets/img/default-avatar.png');
                        @endphp
                        <img src="{{ $provider->getFirstMediaUrl('personal_photo') ?: ($provider->getFirstMediaUrl('users') ?: $defaultAvatar) }}"
                            alt="{{ $provider->name }}">
                        @if ($provider->isOnline())
                            <span class="online-indicator" title="{{ __('website.online_now') }}"
                                style="position:absolute;bottom:10px;left:10px;width:14px;height:14px;border-radius:50%;background:#28a745;border:2px solid #fff;"></span>
                        @endif
                    </div>
                    <div class="individual-badge">
                        <div class="stars">
                            @php
                                $avgRating = $provider->average_rating;
                                $fullStars = floor($avgRating);
                                $halfStar = $avgRating - $fullStars >= 0.5;
                            @endphp
                            @for ($i = 0; $i < $fullStars; $i++)
                                <i class="bi bi-star-fill"></i>
                            @endfor
                            @if ($halfStar)
                                <i class="bi bi-star-half"></i>
                            @endif
                            @for ($i = $fullStars + ($halfStar ? 1 : 0); $i < 5; $i++)
                                <i class="bi bi-star"></i>
                            @endfor
                        </div>
                    </div>
                    <h5 class="individual-name">{{ $provider->name }}</h5>
                </a>
                <div class="individual-stats">
                    <span class="stat-item">
                        <span>{{ $provider->completed_projects_count ?? 0 }}</span>
                        <span>{{ __('website.project') }}</span>
                    </span>
                    <span class="stat-item">
                        @if ($provider->isOnline())
                            <span style="color: #28a745;">{{ __('website.online') }}</span>
                        @elseif($provider->last_seen_at)
                            <span>{{ $provider->last_seen_at->diffForHumans() }}</span>
                        @else
                            <span>{{ __('website.offline') }}</span>
                        @endif
                        <span>{{ __('website.last_seen') }}</span>
                    </span>
                    <span class="stat-item">
                        <span>{{ $provider->years_of_experience ?? 0 }}</span>
                        <span>{{ __('website.years_of_experience') }}</span>
                    </span>
                </div>
                @auth
                    <a href="{{ route(
                        'requests.create',
                        array_filter([
                            'provider_id' => $provider->id,
                            'category' => $category->id ?? $provider->categories->first()?->id,
                        ]),
                    ) }}"
                        class="btn btn-icon py-3 px-5 animated fadeIn">
                        <span>{{ __('website.request_quote') }}</span>
                        <i class="icon-btn bi bi-arrow-up-left"></i>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-icon py-3 px-5 animated fadeIn">
                        <span>{{ __('website.request_quote') }}</span>
                        <i class="icon-btn bi bi-arrow-up-left"></i>
                    </a>
                @endauth
            </div>
        @endforeach
    </div>
@else
    <!-- No Results - Elegant Empty State -->
    <div class="text-center py-5 w-100">
        <div class="mb-4">
            <i class="bi bi-search" style="font-size: 4rem; color: #d4af37; opacity: 0.5;"></i>
        </div>
        <h4 class="fw-bold mb-3" style="color: #2c3e50;">{{ __('website.no_providers_found') }}</h4>
            {{ __('website.no_providers_desc') }}
        </p>
    </div>
@endif
