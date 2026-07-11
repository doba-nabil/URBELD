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
                            @if(auth()->user()->isSupplier())
                                <!-- Products Limit Box -->
                                <div class="limit-box">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="limit-title">{{ __('website.products_limit') ?? 'استهلاك المنتجات' }}</span>
                                        <span class="limit-count">{{ $maxProducts }} / {{ $usedProducts }}</span>
                                    </div>
                                    <div class="custom-progress">
                                        <div class="custom-progress-bar" style="width: {{ $productsPercent }}%"></div>
                                    </div>
                                    <div class="limit-subtext">يمكنك إضافة المزيد من المنتجات لمتجرك</div>
                                </div>

                                <!-- Offers Limit Box -->
                                <div class="limit-box">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="limit-title">{{ __('website.offers_limit') ?? 'العروض والخصومات' }}</span>
                                        <span class="limit-count">{{ $maxOffers }} / {{ $usedOffers }}</span>
                                    </div>
                                    <div class="custom-progress">
                                        <div class="custom-progress-bar" style="width: {{ $offersPercent }}%"></div>
                                    </div>
                                    <div class="limit-subtext">عزز مبيعاتك بإضافة المزيد من العروض للعملاء</div>
                                </div>
                            @else
                                <!-- Services Limit Box -->
                                <div class="limit-box">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="limit-title">{{ __('website.services_limit') ?? 'استهلاك الخدمات' }}</span>
                                        <span class="limit-count">{{ $maxServices }} / {{ $usedServices }}</span>
                                    </div>
                                    <div class="custom-progress">
                                        <div class="custom-progress-bar" style="width: {{ $servicesPercent }}%"></div>
                                    </div>
                                    <div class="limit-subtext">يمكنك استقبال المزيد من الطلبات للخدمات المتاحة</div>
                                </div>

                                <!-- Works Limit Box -->
                                <div class="limit-box">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="limit-title">{{ __('website.works_limit') ?? 'أعمال معرض الأعمال' }}</span>
                                        <span class="limit-count">{{ $maxWorks }} / {{ $usedWorks }}</span>
                                    </div>
                                    <div class="custom-progress">
                                        <div class="custom-progress-bar" style="width: {{ $worksPercent }}%"></div>
                                    </div>
                                    <div class="limit-subtext">عزز معرض أعمالك لجذب المزيد من العملاء</div>
                                </div>
                            @endif
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
                                    @if(auth()->user()->isSupplier())
                                        <div class="feature-item">
                                            <i class="bi bi-check-circle feature-icon"></i>
                                            <span>{{ $maxProducts }} منتجات متوفرة</span>
                                        </div>
                                        <div class="feature-item">
                                            <i class="bi bi-check-circle feature-icon"></i>
                                            <span>{{ $maxOffers }} عروض متوفرة</span>
                                        </div>
                                    @else
                                        <div class="feature-item">
                                            <i class="bi bi-check-circle feature-icon"></i>
                                            <span>{{ $maxServices }} خدمات مدرجة</span>
                                        </div>
                                        <div class="feature-item">
                                            <i class="bi bi-check-circle feature-icon"></i>
                                            <span>{{ $maxWorks }} أعمال معرض</span>
                                        </div>
                                    @endif
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
                                                @if(auth()->user()->isSupplier())
                                                    <li>
                                                        <i class="bi bi-check-circle up-pkg-icon"></i>
                                                        <span class="feature-text">{{ $pkg->max_products }} منتجات متوفرة</span>
                                                    </li>
                                                    <li>
                                                        <i class="bi bi-check-circle up-pkg-icon"></i>
                                                        <span class="feature-text">{{ $pkg->max_offers }} عروض متوفرة</span>
                                                    </li>
                                                @else
                                                    <li>
                                                        <i class="bi bi-check-circle up-pkg-icon"></i>
                                                        <span class="feature-text">{{ $pkg->max_services }} {{ __('website.services_count') ?? 'خدمات مدرجة' }}</span>
                                                    </li>
                                                    <li>
                                                        <i class="bi bi-check-circle up-pkg-icon"></i>
                                                        <span class="feature-text">{{ $pkg->works_limit }} {{ __('website.works_count') ?? 'أعمال معرض' }}</span>
                                                    </li>
                                                @endif
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
                    @else
                        @if(isset($user) && $user->hasActiveSubscription())
                            <div class="text-center py-5">
                                <i class="bi bi-star-fill fs-1 text-warning mb-3"></i>
                                <h5>أنت بالفعل على الباقة الأعلى!</h5>
                                <p class="text-muted">شكراً لثقتك بنا، أنت تتمتع بأفضل المميزات المتاحة حالياً.</p>
                            </div>
                        @endif
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
