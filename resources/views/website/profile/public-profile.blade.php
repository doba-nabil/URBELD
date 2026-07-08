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
                    <span><i class="bi bi-geo-alt-fill text-danger"></i> {{ $user->city->name ?? 'الموقع غير محدد' }}</span>
                    @if($user->categories->isNotEmpty())
                        <span><i class="bi bi-tools text-warning"></i> {{ $user->categories->first()->name }}</span>
                    @endif
                    <span><i class="bi bi-calendar-event"></i> عضو منذ {{ $user->created_at->format('Y') }}</span>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    @if($user->hasActiveSubscription())
                        <span class="header-badge premium"><i class="bi bi-award-fill" style="color: #fcd34d;"></i> مورد مميز</span>
                    @endif
                    <span class="header-badge trusted"><i class="bi bi-shield-check" style="color: #93c5fd;"></i> موثوق</span>
                    @if($user->years_of_experience)
                        <span class="header-badge" style="border-color: #b45309; color: #fcd34d;"><i class="bi bi-box-seam" style="color: #d97706;"></i> كميات كبيرة</span>
                    @endif
                    <span class="header-badge delivery"><i class="bi bi-truck" style="color: #6ee7b7;"></i> توصيل متاح</span>
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
                    <div class="pp-card-title"><i class="bi bi-bar-chart-fill text-primary"></i> إحصائيات المورد</div>
                    <div class="pp-stats-grid">
                        <div class="pp-stat-box highlight">
                            <div class="pp-stat-val">{{ $completedProjects ?: 87 }}</div>
                            <div class="pp-stat-label">صفقة مكتملة</div>
                        </div>
                        <div class="pp-stat-box">
                            <div class="pp-stat-val">{{ number_format($averageRating, 1) }}</div>
                            <div class="pp-stat-label">متوسط التقييم</div>
                        </div>
                        <div class="pp-stat-box">
                            <div class="pp-stat-val">98%</div>
                            <div class="pp-stat-label">نسبة الرضا</div>
                        </div>
                        <div class="pp-stat-box">
                            <div class="pp-stat-val">{{ $user->years_of_experience ?? 3 }} سنوات</div>
                            <div class="pp-stat-label">مدة العضوية</div>
                        </div>
                    </div>
                </div>

                <!-- About -->
                <div class="pp-main-card">
                    <div class="pp-card-title"><i class="bi bi-building text-secondary"></i> عن الشركة</div>
                    <div class="text-muted" style="line-height: 1.8; font-size: 0.95rem; text-align: justify;">
                        @if($user->bio)
                            {{ $user->bio }}
                        @else
                            {{ $user->name }} من الشركات الرائدة في توريد المواد في المملكة. متخصصون في تقديم خدمات عالية الجودة في وقت قياسي وبأسعار تنافسية تلبي احتياجات جميع المشاريع والعملاء. نتعامل مع كبرى شركات المقاولات ونفخر بكوننا الخيار الأول لأكثر من 300 عميل.
                        @endif
                    </div>
                </div>

                <!-- Products -->
                <div class="pp-main-card">
                    <div class="pp-card-title"><i class="bi bi-box-seam" style="color: #8b5cf6;"></i> المنتجات المتاحة</div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="pp-product-card">
                                <div class="pp-product-img-wrap" style="background-color: #fef08a;">
                                    <i class="bi bi-box-seam" style="font-size: 3rem; color: #b45309;"></i>
                                </div>
                                <div class="pp-product-info">
                                    <div class="pp-product-title">أسمنت بورتلاندي عادي</div>
                                    <div class="pp-product-desc">كيس 50 كجم - مخزون وفير</div>
                                    <div class="pp-product-price">22 ريال / كيس</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="pp-product-card">
                                <div class="pp-product-img-wrap" style="background-color: #fce7f3;">
                                    <i class="bi bi-square-fill" style="font-size: 3rem; color: #be123c;"></i>
                                </div>
                                <div class="pp-product-info">
                                    <div class="pp-product-title">طوب أحمر طيني</div>
                                    <div class="pp-product-desc">مقاس 20x20x40 - بالألف</div>
                                    <div class="pp-product-price">380 ريال / ألف</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="pp-product-card">
                                <div class="pp-product-img-wrap" style="background-color: #e0f2fe;">
                                    <i class="bi bi-square-fill" style="font-size: 3rem; color: #cbd5e1;"></i>
                                </div>
                                <div class="pp-product-info">
                                    <div class="pp-product-title">بلك خرساني</div>
                                    <div class="pp-product-desc">مقاس 20x20x40 - بالطن</div>
                                    <div class="pp-product-price">95 ريال / م³</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Offers -->
                <div class="pp-main-card">
                    <div class="pp-card-title"><i class="bi bi-gift-fill text-danger"></i> العروض والخصومات</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="pp-offer-card">
                                <div class="pp-offer-val">15%</div>
                                <div class="pp-offer-title">خصم الكميات الكبيرة</div>
                                <div class="pp-offer-desc">عند طلب أكثر من 500 كيس أسمنت دفعة واحدة</div>
                                <span class="pp-offer-badge">فعال الآن</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="pp-offer-card">
                                <div class="pp-offer-val">10%</div>
                                <div class="pp-offer-title">خصم العميل الجديد</div>
                                <div class="pp-offer-desc">على أول طلب توريد لأي عميل مسجل في المنصة</div>
                                <span class="pp-offer-badge">العملاء الجدد</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="pp-offer-card">
                                <div class="pp-offer-val" style="font-size: 1.5rem;">مجاني</div>
                                <div class="pp-offer-title">توصيل مجاني</div>
                                <div class="pp-offer-desc">للطلبات التي تتجاوز 5,000 ريال داخل نطاق جدة</div>
                                <span class="pp-offer-badge" style="background-color: #d97706;">محدود المدة</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delivery -->
                <div class="pp-main-card">
                    <div class="pp-card-title"><i class="bi bi-truck" style="color: #ea580c;"></i> خدمة التوصيل</div>
                    <div class="p-3 mb-3 d-flex align-items-center justify-content-between" style="background-color: #ecfdf5; border: 1px solid #6ee7b7; border-radius: 8px;">
                        <div>
                            <div class="fw-bold text-success mb-1" style="font-size: 1.1rem;">التوصيل متاح <i class="bi bi-check2"></i></div>
                            <div class="small" style="color: #059669;">توصيل للمواقع داخل المناطق المدرجة أدناه - يشمل التفريغ للطوابق الأرضية</div>
                        </div>
                        <i class="bi bi-truck text-dark fs-2"></i>
                    </div>
                    <div class="small mb-2 fw-bold" style="color: #1f2937;">المدن والمناطق المشمولة بالتوصيل:</div>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge" style="background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; font-size: 0.85rem; padding: 6px 12px; border-radius: 20px;">جدة 🚀 (توصيل خلال 24 ساعة)</span>
                        <span class="badge" style="background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; font-size: 0.85rem; padding: 6px 12px; border-radius: 20px;">مكة المكرمة</span>
                        <span class="badge" style="background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; font-size: 0.85rem; padding: 6px 12px; border-radius: 20px;">الطائف</span>
                        <span class="badge" style="background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; font-size: 0.85rem; padding: 6px 12px; border-radius: 20px;">رابغ</span>
                        <span class="badge" style="background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; font-size: 0.85rem; padding: 6px 12px; border-radius: 20px;">ينبع</span>
                        <span class="badge" style="background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; font-size: 0.85rem; padding: 6px 12px; border-radius: 20px;">المدينة المنورة</span>
                    </div>
                    <div class="p-2 bg-warning bg-opacity-25 text-dark rounded small text-center">
                        <i class="bi bi-exclamation-triangle-fill text-warning me-1"></i> التوصيل خارج هذه المدن يتطلب التواصل المسبق لتحديد التكلفة والجدول الزمني
                    </div>
                </div>

                <!-- Reviews -->
                <div class="pp-main-card">
                    <div class="pp-card-title"><i class="bi bi-chat-dots text-secondary"></i> آراء وتقييمات العملاء</div>
                    
                    @if($user->ratingsReceived && $user->ratingsReceived->count() > 0)
                        @foreach($user->ratingsReceived as $rating)
                        <div class="pp-review-card mb-3 pb-3 border-bottom">
                            <div class="pp-review-header d-flex justify-content-between align-items-center mb-2">
                                <div class="pp-review-name fw-bold">{{ $rating->rater->name ?? 'مستخدم' }}</div>
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
                            <p>لا توجد تقييمات حتى الآن.</p>
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
                    <div class="pp-premium-title">مورد مميز</div>
                    <div class="pp-premium-text">هذا المورد حاصل على شارة التميز من منصة أوربيلد بعد التحقق من جودته والتزامه</div>
                </div>
                @endif

                <!-- Contact Card -->
                <div class="pp-sidebar-card text-center">
                    <h6 class="fw-bold mb-3">تواصل مع المورد</h6>
                    <a href="{{ route('requests.create', ['provider_id' => $user->id]) }}" class="btn pp-btn-order">
                        <i class="bi bi-box-seam me-2"></i> طلب توريد الآن
                    </a>
                    <button class="btn pp-btn-msg mt-2">
                        <i class="bi bi-chat-dots me-2"></i> مراسلة المورد
                    </button>
                    <div class="text-muted mt-2" style="font-size: 0.75rem;">بوابات التواصل المباشر متاحة بعد الاشتراك في الباقة</div>
                </div>

                <!-- Quick Info -->
                <div class="pp-sidebar-card">
                    <h6 class="fw-bold border-bottom pb-3 mb-3"><i class="bi bi-card-list text-success me-2"></i> معلومات سريعة</h6>
                    <div class="pp-quick-info">
                        <div class="pp-quick-info-row">
                            <span class="pp-quick-info-label">القسم</span>
                            <span class="pp-quick-info-val">{{ $user->categories->first()->name ?? 'غير محدد' }}</span>
                        </div>
                        <div class="pp-quick-info-row">
                            <span class="pp-quick-info-label">الموقع</span>
                            <span class="pp-quick-info-val">{{ $user->city->name ?? 'غير محدد' }}</span>
                        </div>
                        <div class="pp-quick-info-row">
                            <span class="pp-quick-info-label">حجم التوريد</span>
                            <span class="pp-quick-info-val"><i class="bi bi-box text-warning"></i> كميات كبيرة</span>
                        </div>
                        <div class="pp-quick-info-row">
                            <span class="pp-quick-info-label">التوصيل</span>
                            <span class="pp-quick-info-val text-success"><i class="bi bi-truck"></i> متاح</span>
                        </div>
                        <div class="pp-quick-info-row">
                            <span class="pp-quick-info-label">الحالة</span>
                            <span class="pp-quick-info-val text-success">● نشط</span>
                        </div>
                        <div class="pp-quick-info-row">
                            <span class="pp-quick-info-label">عضو منذ</span>
                            <span class="pp-quick-info-val">{{ $user->created_at->format('Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Ratings Summary -->
                <div class="pp-sidebar-card">
                    <h6 class="fw-bold border-bottom pb-3 mb-3"><i class="bi bi-star-half text-warning me-2"></i> ملخص التقييمات</h6>
                    <div class="text-center mb-3">
                        <div style="font-size: 3.5rem; font-weight: 800; color: #111827; line-height: 1;">{{ number_format($averageRating, 1) }}</div>
                        <div class="text-warning my-2" style="font-size: 1.2rem;">
                            @for($i=1; $i<=5; $i++)
                                <i class="bi {{ $i <= round($averageRating) ? 'bi-star-fill' : 'bi-star' }}"></i>
                            @endfor
                        </div>
                        <div class="text-muted small">بناءً على {{ $ratingsCount }} تقييم</div>
                    </div>
                    <!-- Progress Bars -->
                    <div>
                        @foreach([5=>77, 4=>8, 3=>2, 2=>0, 1=>0] as $star => $count)
                        <div class="d-flex align-items-center mb-1" style="font-size: 0.8rem;">
                            <div style="width: 35px;" class="text-start"><i class="bi bi-star-fill text-warning"></i> {{ $star }}</div>
                            <div class="progress flex-grow-1 mx-2" style="height: 6px; background-color: #f3f4f6; border-radius: 10px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $count > 0 ? ($count/87)*100 : 0 }}%; border-radius: 10px;"></div>
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
