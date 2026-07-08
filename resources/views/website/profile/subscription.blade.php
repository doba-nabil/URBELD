@extends('website.layouts.profile')

@section('profile-content')
    <div class="subscription-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold mb-0 text-dark">{{ __('website.my_subscription') ?? 'اشتراكاتي' }}</h4>
                    </div>

                    @if($user->subscription_package_id && $user->subscriptionPackage)
                    <div class="subscription-main-card">
                        <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
                            <div>
                                <div class="d-flex align-items-center gap-3 mb-2">
                                    <h3 class="package-name mb-0">{{ $user->subscriptionPackage->name }}</h3>
                                    @if($user->hasActiveSubscription())
                                        @if($user->isSubscriptionExpiringSoon())
                                            <span class="badge-active bg-warning text-dark">{{ __('website.expiring_soon') ?? 'يوشك على الانتهاء' }}</span>
                                        @else
                                            <span class="badge-active">{{ __('website.active') ?? 'نشط' }}</span>
                                        @endif
                                    @else
                                        <span class="badge-active bg-danger">{{ __('website.inactive_or_expired') ?? 'منتهي' }}</span>
                                    @endif
                                </div>
                                <div class="date-text">
                                    صالح من {{ $user->subscription_start_at ? $user->subscription_start_at->format('Y/m/d') : '-' }} إلى {{ $user->subscription_end_at ? $user->subscription_end_at->format('Y/m/d') : '-' }}
                                </div>
                            </div>
                            <div>
                                <a href="#upgrade-section" class="upgrade-btn">
                                    <i class="bi bi-arrow-up-circle"></i> ترقية الباقة
                                </a>
                            </div>
                        </div>

                        <div class="d-flex gap-4 flex-column flex-md-row">
                            <!-- Services Limit Box -->
                            <div class="limit-box">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="limit-title">{{ __('website.services_limit') ?? 'استهلاك الخدمات' }}</span>
                                    <span class="limit-count">{{ $user->subscriptionPackage->max_services }} / {{ $user->services()->count() }}</span>
                                </div>
                                <div class="custom-progress">
                                    <div class="custom-progress-bar" style="width: {{ ($user->subscriptionPackage->max_services > 0) ? min(100, ($user->services()->count() / $user->subscriptionPackage->max_services) * 100) : 0 }}%"></div>
                                </div>
                                <div class="limit-subtext">يمكنك استقبال المزيد من الطلبات للخدمات المتاحة</div>
                            </div>

                            <!-- Works Limit Box -->
                            <div class="limit-box">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="limit-title">{{ __('website.works_limit') ?? 'أعمال معرض الأعمال' }}</span>
                                    <span class="limit-count">{{ $user->subscriptionPackage->works_limit }} / {{ $user->works()->count() }}</span>
                                </div>
                                <div class="custom-progress">
                                    <div class="custom-progress-bar" style="width: {{ ($user->subscriptionPackage->works_limit > 0) ? min(100, ($user->works()->count() / $user->subscriptionPackage->works_limit) * 100) : 0 }}%"></div>
                                </div>
                                <div class="limit-subtext">عزز معرض أعمالك لجذب المزيد من العملاء</div>
                            </div>
                        </div>

                        <div class="features-section">
                            <h5 class="features-title">مميزات الباقة الحالية:</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    @php
                                        $features = $user->subscriptionPackage->features;
                                        if(is_string($features)) $features = json_decode($features, true);
                                    @endphp
                                    @if(is_array($features))
                                        @foreach(array_slice($features, 0, ceil(count($features)/2)) as $feature)
                                            @if(!empty($feature))
                                                <div class="feature-item">
                                                    <i class="bi bi-check-circle feature-icon"></i>
                                                    <span>{{ $feature }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <div class="feature-item">
                                        <i class="bi bi-check-circle feature-icon"></i>
                                        <span>{{ $user->subscriptionPackage->max_services }} خدمات مدرجة</span>
                                    </div>
                                    <div class="feature-item">
                                        <i class="bi bi-check-circle feature-icon"></i>
                                        <span>{{ $user->subscriptionPackage->works_limit }} أعمال معرض</span>
                                    </div>
                                    @if(is_array($features))
                                        @foreach(array_slice($features, ceil(count($features)/2)) as $feature)
                                            @if(!empty($feature))
                                                <div class="feature-item">
                                                    <i class="bi bi-check-circle feature-icon"></i>
                                                    <span>{{ $feature }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-5 subscription-main-card">
                        <i class="bi bi-exclamation-triangle fs-1 text-warning mb-3"></i>
                        <h5>{{ __('website.no_active_subscription') ?? 'لا يوجد لديك اشتراك نشط حالياً' }}</h5>
                        <p class="text-muted">{{ __('website.please_choose_package') ?? 'يرجى اختيار باقة من الأسفل للبدء في استخدام كافة المميزات.' }}</p>
                    </div>
                    @endif

                    <!-- Available Packages -->
                    @if(count($packages) > 0)
                        <div id="upgrade-section" class="upgradable-packages-section">
                            <h4 class="upgradable-title">{{ __('website.available_packages') ?? 'الباقات المتاحة للترقية' }}</h4>
                            <div class="row g-4 justify-content-center">
                                @foreach($packages as $pkg)
                                    @php
                                        // Assume recommended package if badge_name is set, or you can use your own condition (e.g. $pkg->is_recommended)
                                        $isRecommended = $pkg->is_recommended ?? false; 
                                    @endphp
                                    <div class="col-md-4">
                                        <div class="up-pkg-card {{ $isRecommended ? 'recommended-pkg' : '' }}">
                                            @if($isRecommended)
                                                <div class="up-pkg-badge">{{ __('website.recommended_package') ?? 'الباقة الموصى بها' }}</div>
                                            @endif
                                            
                                            <div class="up-pkg-name">{{ $pkg->name }}</div>
                                            
                                            <div class="up-pkg-price-wrap">
                                                <div class="up-pkg-price">{{ number_format($pkg->price, 0) }}</div>
                                                <div class="up-pkg-duration">{{ __('website.rs_per_year') ?? 'ريال / سنوياً' }}</div>
                                            </div>
                                            
                                            <ul class="up-pkg-features">
                                                <li>
                                                    <i class="bi bi-check-circle up-pkg-icon"></i>
                                                    <span class="feature-text">{{ $pkg->max_services }} {{ __('website.services_count') ?? 'خدمات مدرجة' }}</span>
                                                </li>
                                                <li>
                                                    <i class="bi bi-check-circle up-pkg-icon"></i>
                                                    <span class="feature-text">{{ $pkg->works_limit }} {{ __('website.works_count') ?? 'أعمال معرض' }}</span>
                                                </li>
                                                @php
                                                    $pkgFeatures = $pkg->features;
                                                    if(is_string($pkgFeatures)) $pkgFeatures = json_decode($pkgFeatures, true);
                                                @endphp
                                                @if(is_array($pkgFeatures))
                                                    @foreach($pkgFeatures as $feature)
                                                        @if(!empty($feature))
                                                            <li>
                                                                <i class="bi bi-check-circle up-pkg-icon"></i>
                                                                <span class="feature-text">{{ $feature }}</span>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </ul>
                                            
                                            <div class="mt-auto">
                                                <a href="{{ route('checkout.package', $pkg->id) }}" class="up-pkg-btn {{ $isRecommended ? 'recommended-btn' : '' }}">
                                                    {{ __('website.choose_package') ?? 'اختيار الباقة' }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-soft-primary { background-color: rgba(13, 110, 253, 0.1); }
        .hover-translate-y:hover { transform: translateY(-5px); }
        .transition-all { transition: all 0.3s ease; }
    </style>
@endsection
