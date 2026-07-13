@extends('layouts.website')

@section('title', $user->name . ' - ' . __('website.profile'))

@section('content')
<!-- Header (Dark Green) -->
<div class="provider-public-header services-header-section without-search" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="fw-bold mb-2">{{ $user->name }}</h1>
                <div class="d-flex flex-wrap gap-3 mb-4 text-white-50" style="font-size: 0.9rem;">
                    <span><i class="bi bi-geo-alt-fill text-danger"></i> {{ $user->city->name ?? __('website.location_unspecified') ?? 'الموقع غير محدد' }}</span>
                    @if($user->categories->isNotEmpty())
                        <span><i class="bi bi-tools text-warning"></i> {{ $user->categories->first()->name }}</span>
                    @endif
                    <span><i class="bi bi-calendar-event"></i> {{ __('website.member_since') ?? 'عضو منذ' }} {{ $user->created_at->format('Y') }}</span>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    @if($user->hasActiveSubscription())
                        <span class="header-badge premium"><i class="bi bi-award-fill" style="color: #fcd34d;"></i> {{ __('website.premium_supplier') ?? 'مورد مميز' }}</span>
                    @endif
                    @if($user->is_trusted)
                        <span class="header-badge trusted"><i class="bi bi-shield-check" style="color: #93c5fd;"></i> {{ __('website.trusted') ?? 'موثوق' }}</span>
                    @endif
                    @if($user->classification_id && $user->classification)
                        <span class="header-badge" style="border-color: #b45309; color: #fcd34d;"><i class="bi bi-box-seam" style="color: #d97706;"></i> {{ $user->classification->name }}</span>
                    @endif
                    @if($user->deliveryCities()->exists())
                        <span class="header-badge delivery"><i class="bi bi-truck" style="color: #6ee7b7;"></i> {{ __('website.delivery_available') ?? 'توصيل متاح' }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Body -->
<div class="public-profile-body py-5" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="container">
        <div class="row g-4">
            
            

            <!-- Main Content (Left Column in RTL) -->
            <div class="col-lg-8">
                
                <!-- Stats -->
                <div class="pp-main-card">
                    <div class="pp-card-title"><i class="bi bi-bar-chart-fill text-primary"></i> {{ __('website.supplier_statistics') ?? 'إحصائيات المورد' }}</div>
                    <div class="pp-stats-grid">
                        <div class="pp-stat-box highlight">
                            <div class="pp-stat-val">{{ $completedProjects ?: 0 }}</div>
                            <div class="pp-stat-label">{{ __('website.successful_supply_operation') ?? 'عملية توريد ناجحة' }}</div>
                        </div>
                        <div class="pp-stat-box">
                            <div class="pp-stat-val">{{ number_format($averageRating, 1) }}</div>
                            <div class="pp-stat-label">{{ __('website.average_rating') ?? 'متوسط التقييم' }}</div>
                        </div>
                        <div class="pp-stat-box">
                            @php
                                $satisfaction = $averageRating > 0 ? min(100, round(($averageRating / 5) * 100)) : 0;
                            @endphp
                            <div class="pp-stat-val">{{ $satisfaction }}%</div>
                            <div class="pp-stat-label">{{ __('website.satisfaction_rate') ?? 'نسبة الرضا' }}</div>
                        </div>
                        <div class="pp-stat-box">
                            <div class="pp-stat-val">100%</div>
                            <div class="pp-stat-label">{{ __('website.response_rate') ?? 'معدل الاستجابة' }}</div>
                        </div>
                    </div>
                </div>

                <!-- About -->
                <div class="pp-main-card">
                    <div class="pp-card-title"><i class="bi bi-building text-secondary"></i> {{ __('website.about_company') ?? 'عن الشركة' }}</div>
                    <div class="text-muted" style="line-height: 1.8; font-size: 0.95rem; text-align: justify;">
                        @if($user->bio)
                            {{ $user->bio }}
                        @else
                            {{ $user->name }} {{ __('website.default_supplier_bio') ?? 'من الشركات الرائدة في توريد المواد في المملكة. متخصصون في تقديم خدمات عالية الجودة في وقت قياسي وبأسعار تنافسية تلبي احتياجات جميع المشاريع والعملاء.' }}
                        @endif
                    </div>
                </div>

                <!-- Products -->
                @if($user->products && $user->products->isNotEmpty())
                <div class="pp-main-card">
                    <div class="pp-card-title"><i class="bi bi-box-seam" style="color: #8b5cf6;"></i> {{ __('website.available_products') ?? 'المنتجات المتاحة' }}</div>
                    <div class="row g-3">
                        @foreach($user->products->take(6) as $product)
                        <div class="col-md-4">
                            <div class="pp-product-card">
                                <div class="pp-product-img-wrap" style="background-color: #fef08a;">
                                    @if($product->getFirstMediaUrl('product_images'))
                                        <img src="{{ $product->getFirstMediaUrl('product_images') }}" alt="{{ $product->title }}" style="width:100%; height:100%; object-fit:cover; border-radius:12px;">
                                    @else
                                        <i class="bi bi-box-seam" style="font-size: 3rem; color: #b45309;"></i>
                                    @endif
                                </div>
                                <div class="pp-product-info">
                                    <div class="pp-product-title">{{ $product->title }}</div>
                                    <div class="pp-product-desc">{{ Str::limit($product->subtitle, 40) }}</div>
                                    <div class="pp-product-price">{{ $product->price }}</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Offers -->
                @if($user->supplierOffers && $user->supplierOffers->isNotEmpty())
                <div class="pp-main-card">
                    <div class="pp-card-title"> {{ __('website.offers_and_discounts') ?? 'العروض والخصومات' }}</div>
                    <div class="row g-3">
                        @foreach($user->supplierOffers as $offer)
                        <div class="col-md-6">
                            <div class="pp-offer-card">
                                <div class="pp-offer-val">{{ $offer->discount_percentage ? $offer->discount_percentage . '%' : ($offer->subtitle ?? __('website.special_offer') ?? 'عرض خاص') }}</div>
                                <div class="pp-offer-title">{{ $offer->title }}</div>
                                <div class="pp-offer-desc">{{ $offer->description }}</div>
                                @if($offer->badge_text)
                                    <span class="pp-offer-badge">{{ $offer->badge_text }}</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Delivery -->
                @if($user->deliveryCities && $user->deliveryCities->isNotEmpty())
                <div class="pp-main-card">
                    <div class="pp-card-title"><i class="bi bi-truck" style="color: #ea580c;"></i> {{ __('website.delivery_service') ?? 'خدمة التوصيل' }}</div>
                    <div class="p-3 mb-3 d-flex align-items-center justify-content-between" style="background-color: #ecfdf5; border: 1px solid #6ee7b7; border-radius: 8px;">
                        <div>
                            <div class="fw-bold text-success mb-1" style="font-size: 1.1rem;">{{ __('website.delivery_available') ?? 'التوصيل متاح' }} <i class="bi bi-check2"></i></div>
                            <div class="small" style="color: #059669;">{{ __('website.delivery_desc') ?? 'توصيل للمواقع داخل المناطق المدرجة أدناه - يشمل التفريغ للطوابق الأرضية' }}</div>
                        </div>
                        <i class="bi bi-truck text-dark fs-2"></i>
                    </div>
                    <div class="small mb-2 fw-bold" style="color: #1f2937;">{{ __('website.covered_cities') ?? 'المدن والمناطق المشمولة بالتوصيل:' }}</div>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @foreach($user->deliveryCities as $city)
                        <span class="badge" style="background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; font-size: 0.85rem; padding: 6px 12px; border-radius: 20px;">{{ $city->name }}</span>
                        @endforeach
                    </div>
                    <div class="p-2 bg-warning bg-opacity-25 text-dark rounded small text-center">
                        <i class="bi bi-exclamation-triangle-fill text-warning me-1"></i> {{ __('website.delivery_outside_desc') ?? 'التوصيل خارج هذه المدن يتطلب التواصل المسبق لتحديد التكلفة والجدول الزمني' }}
                    </div>
                </div>
                @endif

                <!-- Reviews -->
                <div class="pp-main-card">
                    <div class="pp-card-title"><i class="bi bi-chat-dots text-secondary"></i> {{ __('website.customer_reviews') ?? 'آراء وتقييمات العملاء' }}</div>
                    
                    @if($user->ratingsReceived && $user->ratingsReceived->count() > 0)
                        @foreach($user->ratingsReceived as $rating)
                        <div class="pp-review-card mb-3 pb-3 border-bottom">
                            <div class="pp-review-header d-flex justify-content-between align-items-center mb-2">
                                <div class="pp-review-name fw-bold">{{ $rating->rater->name ?? __('website.user') ?? 'مستخدم' }}</div>
                                <div class="pp-review-stars text-warning">
                                    @for($i=1; $i<=5; $i++)
                                        <i class="bi {{ $i <= $rating->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            <div class="pp-review-text text-muted mb-2">{{ $rating->comment }}</div>
                            <div class="pp-review-time text-end small" style="color: #9ca3af;">{{ $rating->created_at->diffForHumans() }}</div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-chat-square-text fs-1 mb-2 text-light"></i>
                            <p>{{ __('website.no_reviews_yet') ?? 'لا توجد تقييمات حتى الآن.' }}</p>
                        </div>
                    @endif
                </div>

            </div>

            <!-- Sidebar (Right Column in RTL) -->
            <div class="col-lg-4">
                
                @if($user->hasActiveSubscription())
                <!-- Premium Badge Card -->
                <div class="pp-sidebar-card pp-premium-box">
                    <div class="pp-premium-icon"><i class="bi bi-award-fill"></i></div>
                    <div class="pp-premium-title">{{ __('website.premium_supplier') ?? 'مورد مميز' }}</div>
                    <div class="pp-premium-text">{{ __('website.premium_supplier_desc') ?? 'هذا المورد حاصل على شارة التميز من منصة أوربيلد بعد التحقق من جودته والتزامه' }}</div>
                </div>
                @endif

                <!-- Contact Card -->
                <div class="pp-sidebar-card text-center">
                    <h6 class="fw-bold mb-3">{{ __('website.contact_supplier') ?? 'تواصل مع المورد' }}</h6>
                    <a href="{{ route('website.supply-requests.create', array_filter(['provider_id' => $user->id, 'category' => $user->categories->first()->id ?? null])) }}" class="btn pp-btn-order">
                        <i class="bi bi-box-seam me-2"></i> {{ __('website.request_supply_now') ?? 'طلب توريد الآن' }}
                    </a>
                    <a href="{{ route('chat.start', $user->id) }}" class="btn pp-btn-msg mt-2">
                        <i class="bi bi-chat-dots me-2"></i> {{ __('website.message_supplier') ?? 'مراسلة المورد' }}
                    </a>
                    <div class="text-muted mt-2" style="font-size: 0.75rem;">{{ __('website.direct_contact_available_after_subscription') ?? 'بوابات التواصل المباشر متاحة بعد الاشتراك في الباقة' }}</div>
                </div>

                <!-- Quick Info -->
                <div class="pp-sidebar-card">
                    <h6 class="fw-bold border-bottom pb-3 mb-3"><i class="bi bi-card-list text-success me-2"></i> {{ __('website.quick_info') ?? 'معلومات سريعة' }}</h6>
                    <div class="pp-quick-info">
                        <div class="pp-quick-info-row">
                            <span class="pp-quick-info-label">{{ __('website.section') ?? 'القسم' }}</span>
                            <span class="pp-quick-info-val">{{ $user->categories->first()->name ?? __('website.unspecified') ?? 'غير محدد' }}</span>
                        </div>
                        <div class="pp-quick-info-row">
                            <span class="pp-quick-info-label">{{ __('website.location') ?? 'الموقع' }}</span>
                            <span class="pp-quick-info-val">{{ $user->city->name ?? __('website.unspecified') ?? 'غير محدد' }}</span>
                        </div>
                        @if($user->email)
                        <div class="pp-quick-info-row">
                            <span class="pp-quick-info-label">{{ __('website.email') ?? 'البريد الإلكتروني' }}</span>
                            <span class="pp-quick-info-val" style="word-break: break-all;">{{ $user->email }}</span>
                        </div>
                        @endif
                        @if($user->phone)
                        <div class="pp-quick-info-row">
                            <span class="pp-quick-info-label">{{ __('website.mobile_number') ?? 'رقم الجوال' }}</span>
                            <span class="pp-quick-info-val">{{ $user->phone }}</span>
                        </div>
                        @endif
                        @if($user->id_number)
                        <div class="pp-quick-info-row">
                            <span class="pp-quick-info-label">{{ __('website.commercial_register_or_id') ?? 'السجل / الهوية' }}</span>
                            <span class="pp-quick-info-val">{{ $user->id_number }}</span>
                        </div>
                        @endif
                        @if($user->representative_name)
                        <div class="pp-quick-info-row">
                            <span class="pp-quick-info-label">{{ __('website.representative') ?? 'الممثل' }}</span>
                            <span class="pp-quick-info-val">{{ $user->representative_name }}</span>
                        </div>
                        @endif
                        @if($user->classification)
                        <div class="pp-quick-info-row">
                            <span class="pp-quick-info-label">{{ __('website.supply_volume') ?? 'حجم التوريد' }}</span>
                            <span class="pp-quick-info-val"><i class="bi bi-box text-warning"></i> {{ $user->classification->name }}</span>
                        </div>
                        @endif
                        <div class="pp-quick-info-row">
                            <span class="pp-quick-info-label">{{ __('website.delivery') ?? 'التوصيل' }}</span>
                            @if($user->deliveryCities && $user->deliveryCities->isNotEmpty())
                                <span class="pp-quick-info-val text-success"><i class="bi bi-truck"></i> {{ __('website.available') ?? 'متاح' }}</span>
                            @else
                                <span class="pp-quick-info-val text-danger"><i class="bi bi-x-circle"></i> {{ __('website.unavailable') ?? 'غير متاح' }}</span>
                            @endif
                        </div>
                        <div class="pp-quick-info-row">
                            <span class="pp-quick-info-label">{{ __('website.status') ?? 'الحالة' }}</span>
                            <span class="pp-quick-info-val text-success">● {{ __('website.active') ?? 'نشط' }}</span>
                        </div>
                        <div class="pp-quick-info-row">
                            <span class="pp-quick-info-label">{{ __('website.member_since') ?? 'عضو منذ' }}</span>
                            <span class="pp-quick-info-val">{{ $user->created_at->format('Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Ratings Summary -->
                <div class="pp-sidebar-card">
                    <h6 class="fw-bold border-bottom pb-3 mb-3"><i class="bi bi-star-half text-warning me-2"></i> {{ __('website.ratings_summary') ?? 'ملخص التقييمات' }}</h6>
                    <div class="text-center mb-3">
                        <div style="font-size: 3.5rem; font-weight: 800; color: #111827; line-height: 1;">{{ number_format($averageRating, 1) }}</div>
                        <div class="text-warning my-2" style="font-size: 1.2rem;">
                            @for($i=1; $i<=5; $i++)
                                <i class="bi {{ $i <= round($averageRating) ? 'bi-star-fill' : 'bi-star' }}"></i>
                            @endfor
                        </div>
                        <div class="text-muted small">{{ __('website.based_on') ?? 'بناءً على' }} {{ $ratingsCount }} {{ __('website.evaluation') ?? 'تقييم' }}</div>
                    </div>
                    <!-- Progress Bars -->
                    @php
                        $ratingCounts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
                        if (isset($user->ratingsReceived) && $user->ratingsReceived) {
                            foreach($user->ratingsReceived as $rating) {
                                $star = round($rating->rating);
                                if (isset($ratingCounts[$star])) {
                                    $ratingCounts[$star]++;
                                }
                            }
                        }
                    @endphp
                    <div>
                        @foreach($ratingCounts as $star => $count)
                        <div class="d-flex align-items-center mb-1" style="font-size: 0.8rem;">
                            <div style="width: 35px;" class="text-start"><i class="bi bi-star-fill text-warning"></i> {{ $star }}</div>
                            <div class="progress flex-grow-1 mx-2" style="height: 6px; background-color: #f3f4f6; border-radius: 10px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $ratingsCount > 0 ? ($count/$ratingsCount)*100 : 0 }}%; border-radius: 10px;"></div>
                            </div>
                            <div style="width: 25px;" class="text-muted text-end">{{ $count }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
