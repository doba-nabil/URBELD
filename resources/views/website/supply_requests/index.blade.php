@extends('website.layouts.master')
@section('title', 'طلبات التوريد')

@section('content')
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="section-title text-center text-primary text-uppercase">تصفح وتفاعل</h6>
            <h1 class="mb-5">أحدث <span class="text-primary text-uppercase">طلبات التوريد</span></h1>
        </div>

        <div class="d-flex justify-content-end mb-4">
            <a href="{{ route('website.supply-requests.create') }}" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-plus-lg me-2"></i> إضافة طلب توريد
            </a>
        </div>

        <div class="row g-4">
            @forelse($requests as $req)
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="card border-0 shadow-sm rounded-4 h-100 tender-card overflow-hidden">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-primary rounded-pill px-3 py-2">
                                    {{ $req->status == 'open' ? 'مفتوح' : ($req->status == 'closed' ? 'مغلق' : 'مكتمل') }}
                                </span>
                                <small class="text-muted"><i class="bi bi-calendar-event me-1"></i> {{ $req->created_at->format('Y-m-d') }}</small>
                            </div>
                            <h5 class="card-title fw-bold mb-3">
                                <a href="{{ route('website.supply-requests.show', $req->id) }}" class="text-dark text-decoration-none">
                                    {{ $req->title }}
                                </a>
                            </h5>
                            <p class="text-muted mb-4 text-truncate-2">{{ Str::limit($req->description, 100) }}</p>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center text-muted">
                                    <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                                    <span>{{ $req->city ? $req->city->name : 'غير محدد' }}</span>
                                </div>
                                @if($req->delivery_date)
                                    <div class="d-flex align-items-center text-muted">
                                        <i class="bi bi-clock-history text-danger me-2"></i>
                                        <span dir="ltr">{{ $req->delivery_date->format('Y-m-d') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 p-4 pt-0">
                            <a href="{{ route('website.supply-requests.show', $req->id) }}" class="btn btn-outline-primary w-100 rounded-pill">
                                التفاصيل وتقديم عرض
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center py-5 rounded-4">
                        <i class="bi bi-info-circle-fill fs-1 mb-3 d-block"></i>
                        <h5>لا توجد طلبات توريد حالياً</h5>
                        <p class="mb-0">كن أول من يضيف طلب توريد جديد!</p>
                    </div>
                </div>
            @endforelse
        </div>
        
        <div class="d-flex justify-content-center mt-5">
            {{ $requests->links() }}
        </div>
    </div>
</div>

<style>
.tender-card { transition: all 0.3s ease; }
.tender-card:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
.text-truncate-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>
@endsection
