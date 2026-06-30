@extends('layouts.website')

@section('title', $user->name . ' - ' . __('website.profile'))

@section('content')
<!-- Header (Dark Green) -->
<div class="provider-public-header" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
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
                        <span class="badge bg-warning text-dark px-3 py-2" style="border-radius: 20px;"><i class="bi bi-award-fill"></i> مورد مميز</span>
                    @endif
                    <span class="badge bg-primary px-3 py-2" style="border-radius: 20px;"><i class="bi bi-shield-check"></i> موثوق</span>
                    @if($user->years_of_experience)
                        <span class="badge bg-info text-dark px-3 py-2" style="border-radius: 20px;"><i class="bi bi-mortarboard-fill"></i> خبرات كبيرة</span>
                    @endif
                    <span class="badge bg-success px-3 py-2" style="border-radius: 20px;"><i class="bi bi-truck"></i> توصيل متاح</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Body -->
<div class="public-profile-body py-5" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="container">
        <div class="row g-4">
            
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
                        <div style="font-size: 3rem; font-weight: 800; color: #1f2937; line-height: 1;">{{ number_format($averageRating, 1) }}</div>
                        <div class="text-warning my-2 fs-5">
                            @for($i=1; $i<=5; $i++)
                                <i class="bi {{ $i <= round($averageRating) ? 'bi-star-fill' : 'bi-star' }}"></i>
                            @endfor
                        </div>
                        <div class="text-muted small">بناءً على {{ $ratingsCount }} تقييم</div>
                    </div>
                    <!-- Mock Progress Bars -->
                    <div dir="ltr">
                        @foreach([5=>75, 4=>15, 3=>5, 2=>3, 1=>2] as $star => $pct)
                        <div class="d-flex align-items-center mb-2" style="font-size: 0.8rem;">
                            <div style="width: 25px;" class="text-end">{{ $star }} <i class="bi bi-star-fill text-warning"></i></div>
                            <div class="progress flex-grow-1 mx-2" style="height: 6px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $pct }}%;"></div>
                            </div>
                            <div style="width: 20px; text-align: left;" class="text-muted">{{ $pct }}%</div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- Main Content (Left Column in RTL) -->
            <div class="col-lg-8">
                
                <!-- Stats -->
                <div class="pp-main-card">
                    <div class="pp-card-title"><i class="bi bi-bar-chart-fill text-primary"></i> إحصائيات المورد</div>
                    <div class="pp-stats-grid">
                        <div class="pp-stat-box" style="border-bottom: 3px solid #10b981;">
                            <div class="pp-stat-val text-success">{{ $completedProjects }}</div>
                            <div class="pp-stat-label">عملية مكتملة</div>
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
                            <div class="pp-stat-label">خبرة تشغيلية</div>
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

                <!-- Products (Placeholder structure for now) -->
                <div class="pp-main-card">
                    <div class="pp-card-title"><i class="bi bi-box-seam text-warning"></i> المنتجات المتاحة</div>
                    <div class="text-center text-muted py-4 bg-light rounded">
                        <p class="mb-0">قريباً سيتم إضافة صور المنتجات وتفاصيلها هنا...</p>
                    </div>
                </div>

                <!-- Offers (Placeholder structure for now) -->
                <div class="pp-main-card">
                    <div class="pp-card-title"><i class="bi bi-percent text-danger"></i> العروض والخصومات</div>
                    <div class="text-center text-muted py-4 bg-light rounded">
                        <p class="mb-0">لا يوجد عروض نشطة في الوقت الحالي...</p>
                    </div>
                </div>

                <!-- Delivery -->
                <div class="pp-main-card">
                    <div class="pp-card-title"><i class="bi bi-truck text-success"></i> خدمة التوصيل</div>
                    <div class="p-3 bg-success bg-opacity-10 rounded border border-success border-opacity-25 mb-3 d-flex align-items-center gap-3">
                        <i class="bi bi-check-circle-fill text-success fs-3"></i>
                        <div>
                            <div class="fw-bold text-success mb-1">التوصيل متاح</div>
                            <div class="small text-success">توصيل للمواقع داخل المناطق المحددة أدناه - يشمل التفريغ للمواقع الأرضية</div>
                        </div>
                    </div>
                    <div class="small text-muted mb-2 fw-bold">المدن والمناطق المشمولة بالتوصيل:</div>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge bg-light text-dark border"><i class="bi bi-geo-alt text-danger"></i> جدة (التوصيل خلال 24 ساعة)</span>
                        <span class="badge bg-light text-dark border">مكة المكرمة</span>
                        <span class="badge bg-light text-dark border">الطائف</span>
                        <span class="badge bg-light text-dark border">رابغ</span>
                        <span class="badge bg-light text-dark border">ينبع</span>
                    </div>
                    <div class="p-2 bg-warning bg-opacity-10 text-warning rounded small">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> التوصيل خارج هذه المدن يتطلب التواصل المسبق لتحديد التكلفة والجدول الزمني
                    </div>
                </div>

                <!-- Reviews (Placeholder structure for now) -->
                <div class="pp-main-card">
                    <div class="pp-card-title"><i class="bi bi-chat-quote text-info"></i> آراء العملاء</div>
                    <div class="text-center text-muted py-4 bg-light rounded">
                        <p class="mb-0">سيتم عرض آراء العملاء الموثقة هنا...</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
