@extends('website.layouts.profile')

@section('title', 'المناقصات')

@section('profile-content')
<div class="incoming-requests-wrapper mt-4 mb-5" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="container bg-light rounded-4 p-4 shadow-sm" style="background-color: #f8f9fa !important;">
        
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0 text-dark">إدارة المناقصات</h3>
            <a href="{{ route('profile.edit') }}" class="text-decoration-none text-muted small fw-bold">
                العودة للوحة التحكم <i class="bi bi-arrow-left ms-1"></i>
            </a>
        </div>

        <!-- Tabs Section -->
        <div class="d-flex justify-content-start mb-4 border-bottom flex-wrap gap-2">
            @if(auth()->user()->isServiceProvider() || auth()->user()->isSupplier() || auth()->user()->isCompanyProvider())
            <button class="btn border fw-bold px-4 py-2 ir-tab-active bg-white" id="incoming-tab" data-bs-toggle="pill" data-bs-target="#incoming-tenders" type="button" role="tab" aria-selected="true" style="border-bottom: none !important; border-bottom-left-radius: 0; border-bottom-right-radius: 0; color: #1f2937;">
                المناقصات الواردة ({{ $incomingTenders->total() }})
            </button>
            @endif
            <button class="btn @if(auth()->user()->isServiceProvider() || auth()->user()->isSupplier() || auth()->user()->isCompanyProvider()) border-0 text-muted @else border ir-tab-active bg-white @endif fw-bold px-4 py-2" id="my-tenders-tab" data-bs-toggle="pill" data-bs-target="#my-tenders" type="button" role="tab" aria-selected="false" @if(!(auth()->user()->isServiceProvider() || auth()->user()->isSupplier() || auth()->user()->isCompanyProvider())) style="border-bottom: none !important; border-bottom-left-radius: 0; border-bottom-right-radius: 0; color: #1f2937;" @endif>
                مناقصاتي وتقديماتي ({{ $myTenders->count() + $myApplications->count() }})
            </button>
            <button class="btn border-0 fw-bold px-4 py-2 text-muted" id="saved-tab" data-bs-toggle="pill" data-bs-target="#saved-tenders" type="button" role="tab" aria-selected="false">
                المناقصات المحفوظة ({{ $savedTenders->count() }})
            </button>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Incoming Tenders Tab -->
            @if(auth()->user()->isServiceProvider() || auth()->user()->isSupplier() || auth()->user()->isCompanyProvider())
            <div class="tab-pane fade show active" id="incoming-tenders" role="tabpanel">
                <div class="row">
                    @forelse($incomingTenders as $tender)
                        <div class="col-md-12 mb-3">
                            <div class="card border-0 shadow-sm rounded-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title fw-bold">
                                            <a href="{{ route('website.tenders.show', $tender->id) }}" class="text-dark text-decoration-none">{{ $tender->title }}</a>
                                            @if($tender->is_urgent) <span class="badge bg-warning text-dark"><i class="bi bi-lightning-charge-fill"></i> عاجل</span> @endif
                                        </h5>
                                        <span class="badge {{ $tender->isExpired() ? 'bg-danger' : 'bg-success' }}">
                                            {{ $tender->isExpired() ? 'مغلقة' : 'مفتوحة' }}
                                        </span>
                                    </div>
                                    <p class="text-muted small mb-2"><i class="bi bi-folder"></i> {{ $tender->category->name ?? '' }} | <i class="bi bi-geo-alt"></i> {{ $tender->city->name ?? '' }} | <i class="bi bi-clock"></i> ينتهي: {{ $tender->ends_at ? $tender->ends_at->format('Y-m-d') : 'غير محدد' }}</p>
                                    <div class="mt-3">
                                        <a href="{{ route('website.tenders.show', $tender->id) }}" class="btn btn-sm btn-primary">عرض التفاصيل</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12"><div class="alert alert-info text-center rounded-3 border-0">لا توجد مناقصات واردة في تصنيفك حالياً</div></div>
                    @endforelse
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $incomingTenders->links() }}
                </div>
            </div>
            @endif

            <!-- My Tenders & Applications Tab -->
            <div class="tab-pane fade @if(!(auth()->user()->isServiceProvider() || auth()->user()->isSupplier() || auth()->user()->isCompanyProvider())) show active @endif" id="my-tenders" role="tabpanel">
                <h5 class="fw-bold mb-3 border-bottom pb-2">المناقصات التي قمت بطرحها</h5>
                <div class="row mb-4">
                    @forelse($myTenders as $tender)
                        <div class="col-md-12 mb-3">
                            <div class="card border-0 shadow-sm rounded-3 border-start border-4 border-primary">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title fw-bold">
                                            <a href="{{ route('website.tenders.show', $tender->id) }}" class="text-dark text-decoration-none">{{ $tender->title }}</a>
                                        </h5>
                                        <span class="badge bg-secondary">{{ $tender->applications_count }} عروض مستلمة</span>
                                    </div>
                                    <p class="text-muted small mb-2"><i class="bi bi-calendar"></i> تاريخ النشر: {{ $tender->created_at->format('Y-m-d') }}</p>
                                    <div class="mt-3">
                                        <a href="{{ route('website.tenders.show', $tender->id) }}" class="btn btn-sm btn-outline-primary">إدارة المناقصة</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12"><div class="alert alert-light text-center rounded-3 border-0 text-muted">لم تقم بطرح أي مناقصات بعد</div></div>
                    @endforelse
                </div>

                <h5 class="fw-bold mb-3 border-bottom pb-2 mt-4">عروضي وتقديماتي</h5>
                <div class="row">
                    @forelse($myApplications as $app)
                        @if($app->tender)
                        <div class="col-md-12 mb-3">
                            <div class="card border-0 shadow-sm rounded-3 border-start border-4 border-success">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title fw-bold">
                                            <a href="{{ route('website.tenders.show', $app->tender->id) }}" class="text-dark text-decoration-none">{{ $app->tender->title }}</a>
                                        </h5>
                                        <span class="badge bg-success">تم التقديم</span>
                                    </div>
                                    <p class="text-muted small mb-2">قيمة عرضك: {{ number_format($app->price) }} ريال | مدة التنفيذ: {{ $app->delivery_days }} يوم</p>
                                    <div class="mt-3">
                                        <a href="{{ route('website.tenders.show', $app->tender->id) }}" class="btn btn-sm btn-outline-secondary">عرض المناقصة</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @empty
                        <div class="col-12"><div class="alert alert-light text-center rounded-3 border-0 text-muted">لم تقم بتقديم أي عروض حتى الآن</div></div>
                    @endforelse
                </div>
            </div>

            <!-- Saved Tenders Tab -->
            <div class="tab-pane fade" id="saved-tenders" role="tabpanel">
                <div class="row">
                    @forelse($savedTenders as $tender)
                        <div class="col-md-12 mb-3">
                            <div class="card border-0 shadow-sm rounded-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title fw-bold">
                                            <a href="{{ route('website.tenders.show', $tender->id) }}" class="text-dark text-decoration-none">{{ $tender->title }}</a>
                                        </h5>
                                        <button class="btn btn-sm text-danger" onclick="toggleSaveTender({{ $tender->id }}, this)"><i class="bi bi-bookmark-fill"></i> إزالة</button>
                                    </div>
                                    <p class="text-muted small mb-0"><i class="bi bi-folder"></i> {{ $tender->category->name ?? '' }} | <i class="bi bi-geo-alt"></i> {{ $tender->city->name ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12"><div class="alert alert-info text-center rounded-3 border-0">لا توجد مناقصات محفوظة</div></div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tabs logic
    const tabs = document.querySelectorAll('.incoming-requests-wrapper [data-bs-toggle="pill"]');
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => {
                t.classList.remove('ir-tab-active', 'bg-white', 'border');
                t.classList.add('border-0', 'text-muted');
                t.style.color = '#6c757d';
                t.style.borderBottom = 'none';
            });
            this.classList.remove('border-0', 'text-muted');
            this.classList.add('ir-tab-active', 'bg-white', 'border');
            this.style.color = '#1f2937';
            this.style.borderBottomColor = 'white';
        });
    });
});

const csrfToken = '{{ csrf_token() }}';
window.toggleSaveTender = function(tenderId, btnElement) {
    btnElement.style.pointerEvents = 'none';
    btnElement.style.opacity = '0.6';

    fetch(`/tenders/${tenderId}/save`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        // If it was removed, hide the card
        if(data.status === 'unsaved') {
            btnElement.closest('.col-md-12').remove();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ');
        btnElement.style.pointerEvents = 'auto';
        btnElement.style.opacity = '1';
    });
}
</script>
@endpush
