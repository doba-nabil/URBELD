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
        <div class="nav d-flex justify-content-start mb-4 border-bottom flex-wrap gap-2">
            @if(auth()->user()->isServiceProvider() || auth()->user()->isSupplier() || auth()->user()->isCompanyProvider())
            <a href="?tab=incoming" class="btn text-decoration-none fw-bold px-4 py-2 {{ $tab == 'incoming' ? 'border ir-tab-active bg-white' : 'border-0 text-muted' }}" style="{{ $tab == 'incoming' ? 'border-bottom: none !important; border-bottom-left-radius: 0; border-bottom-right-radius: 0; color: #1f2937;' : '' }}">
                المناقصات الواردة ({{ $incomingTenders->total() }})
            </a>
            @endif
            <a href="?tab=my_tenders" class="btn text-decoration-none fw-bold px-4 py-2 {{ $tab == 'my_tenders' ? 'border ir-tab-active bg-white' : 'border-0 text-muted' }}" style="{{ $tab == 'my_tenders' ? 'border-bottom: none !important; border-bottom-left-radius: 0; border-bottom-right-radius: 0; color: #1f2937;' : '' }}">
                مناقصاتي وتقديماتي ({{ $myTenders->count() + $myApplications->count() }})
            </a>
            <a href="?tab=saved" class="btn text-decoration-none fw-bold px-4 py-2 {{ $tab == 'saved' ? 'border ir-tab-active bg-white' : 'border-0 text-muted' }}" style="{{ $tab == 'saved' ? 'border-bottom: none !important; border-bottom-left-radius: 0; border-bottom-right-radius: 0; color: #1f2937;' : '' }}">
                المناقصات المحفوظة ({{ $savedTenders->count() }})
            </a>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Incoming Tenders Tab -->
            @if($tab == 'incoming' && (auth()->user()->isServiceProvider() || auth()->user()->isSupplier() || auth()->user()->isCompanyProvider()))
            <div id="incoming-tenders">
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
            @if($tab == 'my_tenders')
            <div id="my-tenders">
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
            @endif

            <!-- Saved Tenders Tab -->
            @if($tab == 'saved')
            <div id="saved-tenders">
                <div class="row">
                    @forelse($savedTenders as $savedTender)
                        <div class="col-md-12 mb-3">
                            <div class="card border-0 shadow-sm rounded-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title fw-bold">
                                            <a href="{{ route('website.tenders.show', $savedTender->tender->id) }}" class="text-dark text-decoration-none">{{ $savedTender->tender->title }}</a>
                                        </h5>
                                        <button class="btn btn-sm text-danger" onclick="toggleSaveTender({{ $savedTender->tender->id }}, this)"><i class="bi bi-bookmark-fill"></i> إزالة</button>
                                    </div>
                                    <p class="text-muted small mb-0"><i class="bi bi-folder"></i> {{ $savedTender->tender->category->name ?? '' }} | <i class="bi bi-geo-alt"></i> {{ $savedTender->tender->city->name ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12"><div class="alert alert-info text-center rounded-3 border-0">لا توجد مناقصات محفوظة</div></div>
                    @endforelse
                </div>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection

@push('js')
<script>

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
        if(data.status === 'removed') {
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
