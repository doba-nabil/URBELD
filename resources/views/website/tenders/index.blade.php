@extends('layouts.website')

@section('title', 'المناقصات والمشاريع')

@section('content')
<!-- Header (Dark Green) -->
<div class="tenders-header" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="container text-center text-white">
        <h1 class="fw-bold mb-3" style="font-size: 2.5rem;">المناقصات والمشاريع</h1>
        <p class="mb-0 fs-5 text-white-50">تصفح المناقصات المتاحة وقدم عروضك للمشاريع الهندسية في المملكة العربية السعودية</p>
    </div>
</div>

<!-- Main Body -->
<div class="tenders-body" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="container">
        
        <!-- Search Card (Overlapping) -->
        <div class="tenders-search-card">
            <h5 class="fw-bold mb-4"><i class="bi bi-search text-secondary me-2"></i> ابحث عن مناقصة</h5>
            
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label small text-muted">المنطقة</label>
                    <select class="form-select border-0 bg-light">
                        <option>كل المناطق</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">المدينة</label>
                    <select class="form-select border-0 bg-light">
                        <option>كل المدن</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">التخصص</label>
                    <select class="form-select border-0 bg-light">
                        <option>كل التخصصات</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">الميزانية</label>
                    <select class="form-select border-0 bg-light">
                        <option>أي ميزانية</option>
                    </select>
                </div>
            </div>
            
            <button class="btn btn-search-tenders mb-4">
                <i class="bi bi-search me-2"></i> بحث عن مناقصات
            </button>
            
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="tender-category-card">
                        <i class="bi bi-box-seam" style="color: #b45309;"></i>
                        <div class="fw-bold mt-2">توريد</div>
                        <div class="text-muted small">11 مناقصة</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tender-category-card">
                        <i class="bi bi-hammer" style="color: #4b5563;"></i>
                        <div class="fw-bold mt-2">مقاولات</div>
                        <div class="text-muted small">24 مناقصة</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tender-category-card active">
                        <i class="bi bi-buildings" style="color: #059669;"></i>
                        <div class="fw-bold mt-2">استشارات هندسية</div>
                        <div class="text-muted small">18 مناقصة</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tenders List Section -->
        <div class="tenders-list-section mt-5 pt-3">
            
            <!-- Section Header -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <h4 class="fw-bold mb-0">المناقصات</h4>
                <a href="#" class="btn btn-add-tender">
                    <i class="bi bi-plus-lg me-2"></i> إضافة مناقصة جديدة
                </a>
            </div>
            
            <!-- Filters & Sorting -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div class="d-flex flex-wrap gap-2">
                    <span class="tender-filter-pill active">الكل (12)</span>
                    <span class="tender-filter-pill">مفتوحة (6)</span>
                    <span class="tender-filter-pill">مغلقة (3)</span>
                    <span class="tender-filter-pill">عاجلة (1)</span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted small">عرض 12 مناقصة</span>
                    <select class="form-select form-select-sm border-0 bg-light" style="width: auto; min-width: 150px;">
                        <option>ترتيب: الأحدث أولاً</option>
                    </select>
                </div>
            </div>
            
            <!-- Tenders List -->
            <div class="tenders-list">
                
                <!-- Tender Card 1 -->
                <div class="tender-card">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h5 class="fw-bold text-dark mb-2">تصميم مخططات هندسية لمبنى سكني</h5>
                            <div class="d-flex flex-wrap align-items-center gap-3 small mb-3">
                                <span class="text-muted"><i class="bi bi-file-earmark-text text-secondary"></i> تصميم معماري</span>
                                <span class="text-muted"><i class="bi bi-geo-alt-fill text-danger"></i> جدة</span>
                                <span class="text-muted"><i class="bi bi-cash-stack text-warning"></i> 45,000 ريال</span>
                                <span class="badge" style="background-color: #fef3c7; color: #b45309; border: 1px dashed #fcd34d;">
                                    <i class="bi bi-calendar-event text-danger"></i> ينتهي 26/7/2026
                                </span>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge mb-2" style="background-color: #dcfce7; color: #166534; font-size: 0.8rem; padding: 6px 15px; border-radius: 20px;">مفتوحة</span>
                            <div>
                                <button class="btn btn-submit-offer">
                                    <i class="bi bi-plus-lg"></i> تقديم عرض
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="text-muted" style="font-size: 0.95rem; line-height: 1.6;">
                        مطلوب مكتب هندسي أو مصمم متخصص لتصميم مخططات معمارية كاملة لمبنى سكني من 4 طوابق في حي الشاطئ بجدة، مع الالتزام بكود البناء السعودي.
                    </div>
                </div>
                
                <!-- Tender Card 2 -->
                <div class="tender-card">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h5 class="fw-bold text-dark mb-2">إشراف على مشروع تجاري</h5>
                            <div class="d-flex flex-wrap align-items-center gap-3 small mb-3">
                                <span class="text-muted"><i class="bi bi-person-workspace text-secondary"></i> إشراف هندسي</span>
                                <span class="text-muted"><i class="bi bi-geo-alt-fill text-danger"></i> الرياض</span>
                                <span class="text-muted"><i class="bi bi-cash-stack text-warning"></i> 80,000 ريال</span>
                                <span class="badge" style="background-color: #fef3c7; color: #b45309; border: 1px dashed #fcd34d;">
                                    <i class="bi bi-calendar-event text-danger"></i> ينتهي 28/7/2026
                                </span>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge mb-2" style="background-color: #dcfce7; color: #166534; font-size: 0.8rem; padding: 6px 15px; border-radius: 20px;">مفتوحة</span>
                            <div>
                                <button class="btn btn-submit-offer">
                                    <i class="bi bi-plus-lg"></i> تقديم عرض
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="text-muted" style="font-size: 0.95rem; line-height: 1.6;">
                        مطلوب مشرف هندسي ذو خبرة لمتابعة تنفيذ مشروع تجاري في حي النزهة بالرياض، مدة الإشراف 6 أشهر مع تقارير أسبوعية.
                    </div>
                </div>

                <!-- Tender Card 3 (Closed) -->
                <div class="tender-card">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h5 class="fw-bold text-dark mb-2">دراسة جدوى مشروع صناعي</h5>
                            <div class="d-flex flex-wrap align-items-center gap-3 small mb-3">
                                <span class="text-muted"><i class="bi bi-graph-up text-secondary"></i> دراسات وتقارير</span>
                                <span class="text-muted"><i class="bi bi-geo-alt-fill text-danger"></i> الدمام</span>
                                <span class="text-muted"><i class="bi bi-cash-stack text-warning"></i> 25,000 ريال</span>
                                <span class="badge" style="background-color: #fee2e2; color: #b91c1c; border: 1px dashed #fca5a5;">
                                    <i class="bi bi-calendar-event text-danger"></i> انتهى 15/7/2026
                                </span>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge mb-2" style="background-color: #fee2e2; color: #b91c1c; font-size: 0.8rem; padding: 6px 15px; border-radius: 20px;">مغلقة</span>
                            <div class="text-muted small fw-bold">
                                انتهت المناقصة
                            </div>
                        </div>
                    </div>
                    <div class="text-muted" style="font-size: 0.95rem; line-height: 1.6;">
                        دراسة جدوى اقتصادية وفنية شاملة لإنشاء مصنع صغير لإنتاج مواد البناء في المنطقة الصناعية بالدمام.
                    </div>
                </div>

                <!-- Tender Card 4 (Urgent) -->
                <div class="tender-card" style="border-right: 4px solid #f59e0b;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h5 class="fw-bold text-dark mb-2">توريد مواد بناء - مشروع سكني</h5>
                            <div class="d-flex flex-wrap align-items-center gap-3 small mb-3">
                                <span class="text-muted"><i class="bi bi-box-seam text-secondary"></i> توريد ومواد</span>
                                <span class="text-muted"><i class="bi bi-geo-alt-fill text-danger"></i> جدة - المنطقة الصناعية</span>
                                <span class="text-muted"><i class="bi bi-cash-stack text-warning"></i> 142,500 ريال</span>
                                <span class="badge" style="background-color: #fef3c7; color: #b45309; border: 1px dashed #fcd34d;">
                                    <i class="bi bi-calendar-event text-danger"></i> ينتهي 22/8/2026
                                </span>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge mb-2" style="background-color: #fef3c7; color: #d97706; font-size: 0.8rem; padding: 6px 15px; border-radius: 20px;"><i class="bi bi-lightning-charge-fill"></i> عاجل</span>
                            <div>
                                <button class="btn btn-add-tender"> <!-- Orange button for urgent -->
                                    <i class="bi bi-plus-lg"></i> تقديم عرض
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="text-muted" style="font-size: 0.95rem; line-height: 1.6;">
                        توريد حديد وأسمنت وطوب لمشروع سكني مع الكميات الموضحة في ملف المواصفات المرفق. التسليم خلال أسبوعين من الترسية.
                    </div>
                </div>

            </div>
            
            <!-- Pagination Placeholder -->
            <div class="d-flex justify-content-center mt-4">
                <nav>
                    <ul class="pagination pagination-sm">
                        <li class="page-item disabled"><a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a></li>
                        <li class="page-item active"><a class="page-link" style="background-color: #143526; border-color: #143526;" href="#">1</a></li>
                        <li class="page-item"><a class="page-link text-dark" href="#">2</a></li>
                        <li class="page-item"><a class="page-link text-dark" href="#">3</a></li>
                        <li class="page-item"><a class="page-link text-dark" href="#"><i class="bi bi-chevron-left"></i></a></li>
                    </ul>
                </nav>
            </div>

        </div>
    </div>
</div>
@endsection
