@extends('website.layouts.profile')

@section('profile-content')
    <div class="subscription-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <!-- Current Subscription Card -->
                    <div class="card border-0 shadow-sm mb-5 overflow-hidden rounded-4">
                        <div class="card-header bg-primary text-white p-4 border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0 fw-bold"><i class="bi bi-patch-check me-2"></i>{{ __('website.my_subscription') ?? 'اشتراكي الحالي' }}</h4>
                                @if($user->hasActiveSubscription())
                                    @if($user->isSubscriptionExpiringSoon())
                                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm">
                                            <i class="bi bi-clock-history me-1"></i>
                                            {{ __('website.expiring_soon') ?? 'يوشك على الانتهاء' }}
                                        </span>
                                    @else
                                        <span class="badge bg-white text-primary px-3 py-2 rounded-pill shadow-sm">
                                            <i class="bi bi-check-circle-fill me-1"></i>
                                            {{ __('website.active') ?? 'نشط' }}
                                        </span>
                                    @endif
                                @else
                                    <span class="badge bg-danger text-white px-3 py-2 rounded-pill shadow-sm">
                                        <i class="bi bi-x-circle-fill me-1"></i>
                                        {{ __('website.inactive_or_expired') ?? 'منتهي أو غير نشط' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body p-4">
                            @if($user->subscription_package_id && $user->subscriptionPackage)
                                <div class="row align-items-center">
                                    <div class="col-md-7">
                                        <h3 class="fw-bold text-primary mb-3">{{ $user->subscriptionPackage->name }}</h3>
                                        <p class="text-muted mb-4">{{ $user->subscriptionPackage->description }}</p>
                                        
                                        <div class="subscription-meta d-flex flex-wrap gap-4 mb-4">
                                            <div class="meta-item">
                                                <small class="text-muted d-block">{{ __('website.subscription_start') ?? 'تاريخ البدء' }}</small>
                                                <span class="fw-bold">{{ $user->subscription_start_at ? $user->subscription_start_at->format('Y-m-d') : '-' }}</span>
                                            </div>
                                            <div class="meta-item">
                                                <small class="text-muted d-block">{{ __('website.subscription_end') ?? 'تاريخ الانتهاء' }}</small>
                                                <span class="fw-bold {{ ($user->subscription_end_at && $user->subscription_end_at->isPast()) ? 'text-danger' : '' }}">
                                                    {{ $user->subscription_end_at ? $user->subscription_end_at->format('Y-m-d') : '-' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="limits-card bg-light p-4 rounded-4">
                                            <h6 class="fw-bold mb-3">{{ __('website.package_limits') ?? 'حدود الباقة' }}</h6>
                                            <div class="limit-item mb-3">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span>{{ __('website.services_limit') ?? 'الخدمات' }}</span>
                                                    <span class="fw-bold">{{ $user->services()->count() }} / {{ $user->subscriptionPackage->max_services }}</span>
                                                </div>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-primary" role="progressbar" 
                                                         style="width: {{ ($user->subscriptionPackage->max_services > 0) ? min(100, ($user->services()->count() / $user->subscriptionPackage->max_services) * 100) : 0 }}%"></div>
                                                </div>
                                            </div>
                                            <div class="limit-item">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span>{{ __('website.works_limit') ?? 'أعمال المعرض' }}</span>
                                                    <span class="fw-bold">{{ $user->works()->count() }} / {{ $user->subscriptionPackage->works_limit }}</span>
                                                </div>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-success" role="progressbar" 
                                                         style="width: {{ ($user->subscriptionPackage->works_limit > 0) ? min(100, ($user->works()->count() / $user->subscriptionPackage->works_limit) * 100) : 0 }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-exclamation-triangle fs-1 text-warning mb-3"></i>
                                    <h5>{{ __('website.no_active_subscription') ?? 'لا يوجد لديك اشتراك نشط حالياً' }}</h5>
                                    <p class="text-muted">{{ __('website.please_choose_package') ?? 'يرجى اختيار باقة من الأسفل للبدء في استخدام كافة المميزات.' }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Available Packages -->
                    @if($isSubscriptionEnabled && count($packages) > 0)
                        <h4 class="fw-bold mb-4"><i class="bi bi-grid-fill me-2 text-primary"></i>{{ __('website.available_packages') ?? 'الباقات المتاحة' }}</h4>
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
                                                    $features = $pkg->features;
                                                    if(is_string($features)) $features = json_decode($features, true);
                                                @endphp
                                                @if(is_array($features))
                                                    @foreach($features as $feature)
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
