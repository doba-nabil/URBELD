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
                    @if($isSubscriptionEnabled && count($packages) > 0)
                        <div id="upgrade-section">
                            <h4 class="fw-bold mb-4 text-dark">{{ __('website.available_packages') ?? 'الباقات المتاحة للترقية' }}</h4>
                            <div class="row g-4">
                                @foreach($packages as $pkg)
                                    <div class="col-md-6">
                                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden transition-all hover-translate-y">
                                            <div class="card-body p-4">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div>
                                                        <span class="badge bg-soft-primary text-primary px-3 py-1 mb-2">{{ $pkg->badge_name }}</span>
                                                        <h5 class="fw-bold mb-0">{{ $pkg->name }}</h5>
                                                    </div>
                                                    <div class="text-end">
                                                        <div class="h4 fw-bold text-primary mb-0">{{ number_format($pkg->price, 2) }}</div>
                                                        <small class="text-muted">/ {{ $pkg->duration_days }} {{ __('website.days') ?? 'يوم' }}</small>
                                                    </div>
                                                </div>
                                                
                                                <p class="text-muted small mb-4">{{ Str::limit($pkg->description, 100) }}</p>
                                                
                                                <ul class="list-unstyled mb-4">
                                                    <li class="mb-2 d-flex align-items-center">
                                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                                        <span class="small">{{ __('website.max_services') ?? 'الخدمات' }}: {{ $pkg->max_services }}</span>
                                                    </li>
                                                    <li class="mb-2 d-flex align-items-center">
                                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                                        <span class="small">{{ __('website.works_limit') ?? 'أعمال المعرض' }}: {{ $pkg->works_limit }}</span>
                                                    </li>
                                                    @php
                                                        $pkgFeatures = $pkg->features;
                                                        if(is_string($pkgFeatures)) $pkgFeatures = json_decode($pkgFeatures, true);
                                                    @endphp
                                                    @if(is_array($pkgFeatures))
                                                        @foreach($pkgFeatures as $feature)
                                                            @if(!empty($feature))
                                                                <li class="mb-2 d-flex align-items-center">
                                                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                                                    <span class="small">{{ $feature }}</span>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </ul>
                                                
                                                <div class="alert alert-info py-2 px-3 mb-0 rounded-3" style="font-size: 0.85rem;">
                                                    <i class="bi bi-info-circle me-1"></i>
                                                    {{ __('website.contact_admin_to_subscribe') ?? 'يرجى التواصل مع الإدارة للاشتراك أو الترقية' }}
                                                </div>
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
