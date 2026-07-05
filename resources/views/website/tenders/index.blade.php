@extends('layouts.website')

@section('title', 'المناقصات والمشاريع')

@section('content')

<!-- Header Start -->
<div class="category-header-section text-center services-header-section without-search">
    <div class="container" style="max-width: 1320px;">
        <h1 class="fw-bold mb-3 wow fadeInUp" data-wow-delay="0.1s">المناقصات والمشاريع</h1>
        <p class="mb-0 wow fadeInUp" data-wow-delay="0.2s">تصفح المناقصات المتاحة وقدّم عروضك للمشاريع الهندسية في المملكة العربية السعودية</p>
    </div>
</div>
<!-- Header End -->

<div class="filter-wrapper" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
  <div class="filter-card">
    <div class="filter-title"><i class="bi bi-search me-1"></i> ابحث عن مناقصة</div>
    <div class="filter-grid">
      <div class="filter-group">
        <label>المنطقة</label>
        <select>
          <option>كل المناطق</option>
          <option>منطقة مكة المكرمة</option>
          <option>منطقة الرياض</option>
          <option>المنطقة الشرقية</option>
          <option>منطقة المدينة المنورة</option>
          <option>منطقة عسير</option>
        </select>
      </div>
      <div class="filter-group">
        <label>المدينة</label>
        <select>
          <option>كل المدن</option>
          <option>جدة</option>
          <option>الرياض</option>
          <option>مكة المكرمة</option>
          <option>الدمام</option>
          <option>تبوك</option>
        </select>
      </div>
      <div class="filter-group">
        <label>التخصص</label>
        <select>
          <option>كل التخصصات</option>
          <option>تصميم معماري</option>
          <option>إشراف هندسي</option>
          <option>دراسات وتقارير</option>
          <option>استشارات قانونية</option>
          <option>توريد ومواد</option>
        </select>
      </div>
      <div class="filter-group">
        <label>الميزانية</label>
        <select>
          <option>أي ميزانية</option>
          <option>أقل من 10,000</option>
          <option>10,000 - 50,000</option>
          <option>50,000 - 100,000</option>
          <option>أكثر من 100,000</option>
        </select>
      </div>
    </div>
    <button class="btn-search"><i class="bi bi-search me-1"></i> بحث عن مناقصات</button>

    <!-- CATEGORY BOXES -->
    <div class="cat-boxes">
      <div class="cat-box active" onclick="selectCat(this)">
        <div class="cat-icon"><i class="bi bi-buildings-fill text-primary"></i></div>
        <div class="cat-label">استشارات هندسية</div>
        <div class="cat-count">18 مناقصة</div>
      </div>
      <div class="cat-box" onclick="selectCat(this)">
        <div class="cat-icon"><i class="bi bi-hammer text-secondary"></i></div>
        <div class="cat-label">مقاولات</div>
        <div class="cat-count">24 مناقصة</div>
      </div>
      <div class="cat-box" onclick="selectCat(this)">
        <div class="cat-icon"><i class="bi bi-box-seam-fill text-warning"></i></div>
        <div class="cat-label">توريد</div>
        <div class="cat-count">11 مناقصة</div>
      </div>
    </div>
  </div>
</div>

<div class="main-tenders-list" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
  <div class="top-row">
    <h2>المناقصات</h2>
    <a href="{{ route('home') }}" class="back-link"><i class="bi bi-arrow-right"></i> العودة للوحة الرئيسية</a>
  </div>

  <div class="add-btn-row">
    <a href="#" class="btn-add-new"><i class="bi bi-plus-lg me-1"></i> إضافة مناقصة جديدة</a>
  </div>

  <div class="tabs-row">
    <button class="tab-pill active" onclick="setTab(this)">الكل (12)</button>
    <button class="tab-pill" onclick="setTab(this)">مفتوحة (8)</button>
    <button class="tab-pill" onclick="setTab(this)">مغلقة (3)</button>
    <button class="tab-pill" onclick="setTab(this)">عاجلة (1)</button>
  </div>

  <div class="stats-bar">
    <div class="stats-count">عرض <span>12</span> مناقصة</div>
    <select class="sort-select">
      <option>ترتيب: الأحدث أولاً</option>
      <option>ترتيب: الأعلى ميزانية</option>
      <option>ترتيب: ينتهي قريباً</option>
    </select>
  </div>

  <!-- CARD 1 - OPEN -->
  <div class="t-card-v2">
    <div class="t-card-left">
      <span class="status-badge status-open">مفتوحة</span>
      <a href="{{ route('website.tenders.show', 1) }}" class="btn-offer-v2"><i class="bi bi-plus-lg me-1"></i> تقديم عرض</a>
    </div>
    <div class="t-card-right">
      <div class="t-card-title">تصميم مخططات هندسية لمبنى سكني</div>
      <div class="t-card-meta">
        <div class="t-meta-item"><i class="bi bi-file-earmark-text text-secondary"></i> تصميم معماري</div>
        <div class="t-meta-item"><i class="bi bi-geo-alt-fill text-danger"></i> جدة</div>
        <div class="t-meta-item"><i class="bi bi-cash-stack text-success"></i> 45,000 ريال</div>
        <span class="deadline-chip"><i class="bi bi-calendar-event"></i> ينتهي: 26/7/م</span>
      </div>
      <p class="t-card-desc">مطلوب مكتب هندسي أو مصمم متخصص لتصميم مخططات معمارية كاملة لمبنى سكني من 4 طوابق في حي الشاطئ بجدة، مع الالتزام بكود البناء السعودي.</p>
    </div>
  </div>

  <!-- CARD 2 - OPEN -->
  <div class="t-card-v2">
    <div class="t-card-left">
      <span class="status-badge status-open">مفتوحة</span>
      <a href="{{ route('website.tenders.show', 2) }}" class="btn-offer-v2"><i class="bi bi-plus-lg me-1"></i> تقديم عرض</a>
    </div>
    <div class="t-card-right">
      <div class="t-card-title">إشراف على مشروع تجاري</div>
      <div class="t-card-meta">
        <div class="t-meta-item"><i class="bi bi-person-workspace text-secondary"></i> إشراف هندسي</div>
        <div class="t-meta-item"><i class="bi bi-geo-alt-fill text-danger"></i> الرياض</div>
        <div class="t-meta-item"><i class="bi bi-cash-stack text-success"></i> 80,000 ريال</div>
        <span class="deadline-chip"><i class="bi bi-calendar-event"></i> ينتهي: 28/7/م</span>
      </div>
      <p class="t-card-desc">مطلوب مشرف هندسي ذو خبرة لمتابعة تنفيذ مشروع تجاري في حي النزهة بالرياض، مدة الإشراف 6 أشهر مع تقارير أسبوعية.</p>
    </div>
  </div>

  <!-- CARD 3 - CLOSED -->
  <div class="t-card-v2" style="opacity:0.85;">
    <div class="t-card-left">
      <span class="status-badge status-closed">مغلقة</span>
      <button class="btn-offer-disabled" disabled>انتهت المناقصة</button>
    </div>
    <div class="t-card-right">
      <div class="t-card-title">دراسة جدوى مشروع صناعي</div>
      <div class="t-card-meta">
        <div class="t-meta-item"><i class="bi bi-graph-up text-secondary"></i> دراسات وتقارير</div>
        <div class="t-meta-item"><i class="bi bi-geo-alt-fill text-danger"></i> الدمام</div>
        <div class="t-meta-item"><i class="bi bi-cash-stack text-success"></i> 25,000 ريال</div>
        <span class="deadline-chip closed"><i class="bi bi-calendar-event"></i> انتهى: 30/7/م</span>
      </div>
      <p class="t-card-desc">دراسة جدوى اقتصادية وفنية شاملة لإنشاء مصنع صغير لإنتاج مواد البناء في المنطقة الصناعية بالدمام.</p>
    </div>
  </div>

  <!-- CARD 4 - URGENT -->
  <div class="t-card-v2" style="border-right: 4px solid #f59e0b;">
    <div class="t-card-left">
      <span class="status-badge status-urgent"><i class="bi bi-lightning-charge-fill"></i> عاجل</span>
      <a href="{{ route('website.tenders.show', 4) }}" class="btn-offer-v2" style="background:#d97706;"><i class="bi bi-plus-lg me-1"></i> تقديم عرض</a>
    </div>
    <div class="t-card-right">
      <div class="t-card-title">توريد مواد البناء - مشروع سكني</div>
      <div class="t-card-meta">
        <div class="t-meta-item"><i class="bi bi-box-seam text-secondary"></i> توريد ومواد</div>
        <div class="t-meta-item"><i class="bi bi-geo-alt-fill text-danger"></i> جدة - المنطقة الصناعية</div>
        <div class="t-meta-item"><i class="bi bi-cash-stack text-success"></i> 142,500 ريال</div>
        <span class="deadline-chip"><i class="bi bi-lightning-charge-fill"></i> ينتهي: 22/6/م</span>
      </div>
      <p class="t-card-desc">توريد حديد وأسمنت وطوب لمشروع سكني مع الكميات موضحة في ملف المواصفات المرفق. التسليم خلال أسبوعين من الترسية.</p>
    </div>
  </div>

  <div class="pagination-row">
    <button class="page-btn"><i class="bi bi-chevron-right"></i></button>
    <button class="page-btn active">1</button>
    <button class="page-btn">2</button>
    <button class="page-btn">3</button>
    <button class="page-btn"><i class="bi bi-chevron-left"></i></button>
  </div>
</div>

@endsection

@push('js')
<script>
  function selectCat(el) {
    document.querySelectorAll('.cat-box').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
  }
  function setTab(el) {
    document.querySelectorAll('.tab-pill').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
  }
</script>
@endpush
