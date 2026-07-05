@extends('layouts.website')

@section('title', 'تصميم مخططات هندسية لمبنى سكني')

@section('content')
<!-- Header (Dark Green) -->
<div class="tender-show-header" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="container text-white">
        
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-white-50 text-decoration-none">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('website.tenders.index') }}" class="text-white-50 text-decoration-none">المناقصات</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">تصميم مخططات هندسية لمبنى سكني</li>
            </ol>
        </nav>

        <h1 class="fw-bold mb-4" style="font-size: 2.2rem; line-height: 1.4;">تصميم مخططات هندسية لمبنى سكني من 4 طوابق – حي الشاطئ، جدة</h1>
        
        <div class="d-flex flex-wrap gap-2">
            <span class="badge" style="background-color: #166534; color: #bbf7d0; font-size: 0.9rem; padding: 8px 15px; border-radius: 20px;">
                ● مفتوحة
            </span>
            <span class="header-badge-dark">
                <i class="bi bi-file-earmark-text text-light"></i> تصميم معماري
            </span>
            <span class="header-badge-dark">
                <i class="bi bi-geo-alt-fill text-danger"></i> جدة
            </span>
            <span class="header-badge-dark">
                <i class="bi bi-calendar-event text-light"></i> ينتهي 26 / 7 / 1446
            </span>
        </div>
    </div>
</div>

<!-- Main Body -->
<div class="tender-show-body py-5" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="container">
        <div class="row g-4">
            
            <!-- Main Content (Right Column in RTL) -->
            <div class="col-lg-8">
                
                <!-- Tender Details Grid -->
                <div class="tender-section-card mb-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-grid text-primary me-2"></i> تفاصيل المناقصة</h5>
                    
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="t-detail-box">
                                <div class="t-detail-icon"><i class="bi bi-cash-stack" style="color: #d97706;"></i></div>
                                <div class="t-detail-label">الميزانية التقديرية</div>
                                <div class="t-detail-val text-success">45,000 ريال</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="t-detail-box" style="background-color: #fef3c7;">
                                <div class="t-detail-icon"><i class="bi bi-calendar-event text-danger"></i></div>
                                <div class="t-detail-label">تاريخ الانتهاء</div>
                                <div class="t-detail-val text-danger">1446 / 7 / 26</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="t-detail-box">
                                <div class="t-detail-icon"><i class="bi bi-geo-alt-fill text-danger"></i></div>
                                <div class="t-detail-label">الموقع</div>
                                <div class="t-detail-val">جدة - حي الشاطئ</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="t-detail-box">
                                <div class="t-detail-icon"><i class="bi bi-file-earmark-text text-secondary"></i></div>
                                <div class="t-detail-label">التخصص</div>
                                <div class="t-detail-val">تصميم معماري</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="t-detail-box">
                                <div class="t-detail-icon"><i class="bi bi-building text-primary"></i></div>
                                <div class="t-detail-label">نوع المشروع</div>
                                <div class="t-detail-val">مبنى سكني</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="t-detail-box">
                                <div class="t-detail-icon"><i class="bi bi-calendar2-check text-success"></i></div>
                                <div class="t-detail-label">تاريخ النشر</div>
                                <div class="t-detail-val">1446 / 7 / 10</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="tender-section-card mb-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-card-text text-warning me-2"></i> وصف المناقصة</h5>
                    <div class="text-muted" style="line-height: 1.8; font-size: 0.95rem;">
                        <p>نطلب من المكاتب الهندسية المتخصصة وذوي الخبرة المعمارية تقديم عروضهم لتنفيذ تصميم معماري متكامل لمبنى سكني مكون من <strong>4 طوابق + دور أرضي</strong> في حي الشاطئ بمدينة جدة.</p>
                        <p>يشمل نطاق العمل ما يلي:</p>
                        <ul class="mb-3">
                            <li>المخططات المعمارية الكاملة (مساقط أفقية، واجهات، قطاعات)</li>
                            <li>مخططات الموقع العام والمحيط</li>
                            <li>مخططات التنسيق بين التخصصات (معماري، إنشائي، ميكانيكا)</li>
                            <li>حزمة جداول الكميات لاستكمال رخصة البناء البلدية</li>
                            <li>الامتثال الكامل لكود البناء السعودي وأنظمة أمانة جدة</li>
                        </ul>
                        <p class="mb-0">المساحة الإجمالية للأرض <strong>400 م²</strong>، والمساحة البنائية لكل طابق <strong>220 م²</strong> تقريباً. يُفضل التصميم المتوافق مع المناخ الساحلي.</p>
                    </div>
                </div>

                <!-- Requirements -->
                <div class="tender-section-card mb-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-check-circle-fill text-success me-2"></i> متطلبات التأهل</h5>
                    <ul class="list-unstyled mb-0 text-muted" style="line-height: 1.8; font-size: 0.95rem;">
                        <li class="mb-2"><i class="bi bi-check2 text-success me-2 fs-5"></i> ترخيص ممارسة المهنة من هيئة المهندسين السعوديين (سعودي أو معتمد)</li>
                        <li class="mb-2"><i class="bi bi-check2 text-success me-2 fs-5"></i> خبرة لا تقل عن 5 سنوات في تصميم المباني السكنية</li>
                        <li class="mb-2"><i class="bi bi-check2 text-success me-2 fs-5"></i> تقديم محفظة أعمال تتضمن مشاريع مماثلة (3 مشاريع على الأقل)</li>
                        <li class="mb-2"><i class="bi bi-check2 text-success me-2 fs-5"></i> القدرة على التسليم خلال 3 أسابيع من توقيع العقد</li>
                        <li class="mb-0"><i class="bi bi-check2 text-success me-2 fs-5"></i> وجود مكتب مرخص وعنوان وطني معتمد</li>
                    </ul>
                </div>

                <!-- Attachments -->
                <div class="tender-section-card mb-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-paperclip text-secondary me-2"></i> مرفقات المناقصة</h5>
                    
                    <div class="d-flex justify-content-between align-items-center p-3 mb-2 border rounded" style="background-color: #f9fafb;">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-file-earmark-pdf text-danger fs-3"></i>
                            <div>
                                <div class="fw-bold text-dark">كراسة الشروط والمواصفات.pdf</div>
                                <div class="text-muted small">2.4 MB</div>
                            </div>
                        </div>
                        <button class="btn btn-outline-success btn-sm px-3 rounded-pill">
                            <i class="bi bi-download me-1"></i> تحميل
                        </button>
                    </div>

                    <div class="d-flex justify-content-between align-items-center p-3 mb-2 border rounded" style="background-color: #f9fafb;">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-filetype-dwg text-primary fs-3"></i>
                            <div>
                                <div class="fw-bold text-dark">مخطط الأرض والموقع.dwg</div>
                                <div class="text-muted small">1.1 MB</div>
                            </div>
                        </div>
                        <button class="btn btn-outline-success btn-sm px-3 rounded-pill">
                            <i class="bi bi-download me-1"></i> تحميل
                        </button>
                    </div>

                    <div class="d-flex justify-content-between align-items-center p-3 border rounded" style="background-color: #f9fafb;">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-file-zip text-warning fs-3"></i>
                            <div>
                                <div class="fw-bold text-dark">صور الموقع الحالي.zip</div>
                                <div class="text-muted small">8.7 MB</div>
                            </div>
                        </div>
                        <button class="btn btn-outline-success btn-sm px-3 rounded-pill">
                            <i class="bi bi-download me-1"></i> تحميل
                        </button>
                    </div>
                </div>

                <!-- Upload Offer -->
                <div class="tender-section-card">
                    <h5 class="fw-bold mb-4"><i class="bi bi-cloud-arrow-up text-primary me-2"></i> رفع ملفات عرضك</h5>
                    <p class="text-muted small mb-3">ارفع ملفات عرضك كاملة (العرض الفني والمالي (PDF, DWG, ZIP) - بحد أقصى 20MB لكل ملف)</p>
                    
                    <div class="p-5 text-center rounded border border-success" style="background-color: #ecfdf5; border-style: dashed !important; border-width: 2px !important;">
                        <i class="bi bi-cloud-arrow-up text-success mb-2" style="font-size: 3rem;"></i>
                        <h6 class="fw-bold text-success mb-2">اسحب الملفات هنا أو اضغط للرفع</h6>
                        <div class="text-muted small">PDF - DWG - ZIP - JPG (حتى 20MB)</div>
                    </div>
                </div>

            </div>

            <!-- Sidebar (Left Column in RTL) -->
            <div class="col-lg-4">
                
                <!-- Action Card -->
                <div class="tender-sidebar-card text-center mb-4">
                    <div class="badge w-100 py-2 mb-3" style="background-color: #dcfce7; color: #166534; font-size: 0.95rem; border-radius: 8px;">
                        ● المناقصة مفتوحة
                    </div>
                    <button class="btn btn-submit-offer-lg w-100 mb-2">
                        <i class="bi bi-send-fill me-2"></i> شارك بتقديم عرضك الآن
                    </button>
                    <button class="btn btn-outline-success w-100 fw-bold">
                        <i class="bi bi-bookmark-heart me-2"></i> حفظ المناقصة
                    </button>
                    <div class="text-muted small mt-3 px-3">
                        بتقديم عرضك توافق على شروط وأحكام منصة أوربيلد السعودية
                    </div>
                </div>

                <!-- Timer Card -->
                <div class="tender-sidebar-card mb-4" style="background-color: #fffbeb; border-color: #fef3c7;">
                    <div class="text-center fw-bold mb-3" style="color: #b45309;">
                        <i class="bi bi-hourglass-split me-1"></i> الوقت المتبقي لانتهاء المناقصة
                    </div>
                    <div class="d-flex justify-content-center gap-2" dir="ltr">
                        <div class="timer-box">
                            <div class="timer-val">16</div>
                            <div class="timer-label">يوم</div>
                        </div>
                        <div class="timer-box">
                            <div class="timer-val">07</div>
                            <div class="timer-label">ساعة</div>
                        </div>
                        <div class="timer-box">
                            <div class="timer-val">57</div>
                            <div class="timer-label">دقيقة</div>
                        </div>
                        <div class="timer-box">
                            <div class="timer-val">37</div>
                            <div class="timer-label">ثانية</div>
                        </div>
                    </div>
                </div>

                <!-- Client Info Card -->
                <div class="tender-sidebar-card">
                    <h6 class="fw-bold mb-3"><i class="bi bi-person-badge text-secondary me-2"></i> صاحب المناقصة</h6>
                    
                    <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                        <div class="client-avatar">
                            <i class="bi bi-building"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark">شركة الفراز للاستشارات</div>
                            <div class="text-muted small"><i class="bi bi-geo-alt-fill text-danger"></i> جدة، المملكة العربية السعودية</div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-center gap-3 mb-3 text-muted small">
                        <div><i class="bi bi-star-fill text-warning"></i> 5.0 / 5.0</div>
                        <div>● طلب مكتمل 1</div>
                    </div>
                    
                    <button class="btn btn-light w-100 border text-dark fw-bold">
                        <i class="bi bi-chat-dots me-2"></i> التواصل مع العميل
                    </button>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection
