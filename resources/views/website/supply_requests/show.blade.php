@extends('website.layouts.master')
@section('title', $supplyRequest->title)

@section('content')
<!-- Header Start -->
<div class="category-header-section text-center services-header-section without-search">
    <div class="container" style="max-width: 1320px;">
        <h1 class="fw-bold mb-3 wow fadeInUp" data-wow-delay="0.1s">{{ $supplyRequest->title }}</h1>
        <p class="mb-0 wow fadeInUp" data-wow-delay="0.2s">تفاصيل طلب التوريد</p>
    </div>
</div>
<!-- Header End -->

<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Request Details -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-5">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="badge bg-primary rounded-pill px-3 py-2 fs-6">
                                {{ $supplyRequest->status == 'open' ? 'مفتوح' : ($supplyRequest->status == 'closed' ? 'مغلق' : 'مكتمل') }}
                            </span>
                            <span class="text-muted"><i class="bi bi-clock me-1"></i> {{ $supplyRequest->created_at->diffForHumans() }}</span>
                        </div>
                        
                        <h2 class="fw-bold mb-3">{{ $supplyRequest->title }}</h2>
                        
                        <div class="d-flex align-items-center mb-4 pb-4 border-bottom">
                            <img src="{{ $supplyRequest->user->getFirstMediaUrl('personal_photo') ?: asset('website/assets/img/logo.png') }}" class="rounded-circle me-3 border" width="50" height="50" style="object-fit:cover;" alt="صاحب الطلب">
                            <div>
                                <small class="text-muted d-block">صاحب الطلب</small>
                                <a href="{{ route('member.public', $supplyRequest->user_id) }}" class="fw-bold text-dark text-decoration-none fs-5">{{ $supplyRequest->user->name }}</a>
                            </div>
                        </div>
                        
                        <div class="row g-4 mb-4 pb-4 border-bottom">
                            <div class="col-md-6 d-flex align-items-center text-muted">
                                <div class="bg-light rounded-circle p-2 me-2">
                                    <i class="bi bi-geo-alt-fill text-primary"></i>
                                </div>
                                <div>
                                    <small class="d-block">المدينة</small>
                                    <span class="fw-bold text-dark">{{ $supplyRequest->city ? $supplyRequest->city->name : 'غير محدد' }}</span>
                                </div>
                            </div>
                            @if($supplyRequest->delivery_date)
                            <div class="col-md-6 d-flex align-items-center text-muted">
                                <div class="bg-light rounded-circle p-2 me-2">
                                    <i class="bi bi-calendar-check text-danger"></i>
                                </div>
                                <div>
                                    <small class="d-block">آخر موعد للتسليم</small>
                                    <span class="fw-bold text-dark" dir="ltr">{{ $supplyRequest->delivery_date->format('Y-m-d') }}</span>
                                </div>
                            </div>
                            @endif
                            @if($supplyRequest->quantity)
                            <div class="col-md-6 d-flex align-items-center text-muted">
                                <div class="bg-light rounded-circle p-2 me-2">
                                    <i class="bi bi-box-seam text-success"></i>
                                </div>
                                <div>
                                    <small class="d-block">الكمية المطلوبة</small>
                                    <span class="fw-bold text-dark">{{ $supplyRequest->quantity }}</span>
                                </div>
                            </div>
                            @endif
                            @if($supplyRequest->category_id)
                            <div class="col-md-6 d-flex align-items-center text-muted">
                                <div class="bg-light rounded-circle p-2 me-2">
                                    <i class="bi bi-tags text-warning"></i>
                                </div>
                                <div>
                                    <small class="d-block">التصنيف</small>
                                    <span class="fw-bold text-dark">
                                        {{ \App\Models\Category::find($supplyRequest->category_id)->name ?? 'غير محدد' }}
                                        @if($supplyRequest->sub_category_id)
                                            - {{ \App\Models\Category::find($supplyRequest->sub_category_id)->name ?? '' }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                            @endif
                        </div>

                        <h5 class="fw-bold mb-3 border-top pt-4">تفاصيل ومواصفات الطلب:</h5>
                        <div class="mb-4 text-dark" style="line-height: 1.8; font-size: 1.05rem;">
                            {!! nl2br(e($supplyRequest->description)) !!}
                        </div>

                        @if($supplyRequest->voice_record)
                            <div class="mb-4 p-3 bg-light rounded-3 border">
                                <h6 class="fw-bold mb-2"><i class="bi bi-mic-fill text-primary me-2"></i> التسجيل الصوتي المرفق:</h6>
                                <audio controls class="w-100 mt-2">
                                    <source src="{{ asset('storage/' . $supplyRequest->voice_record) }}" type="audio/webm">
                                    <source src="{{ asset('storage/' . $supplyRequest->voice_record) }}" type="audio/mp3">
                                    <source src="{{ asset('storage/' . $supplyRequest->voice_record) }}" type="audio/ogg">
                                    متصفحك لا يدعم تشغيل الملفات الصوتية.
                                </audio>
                            </div>
                        @endif

                        @if($supplyRequest->latitude && $supplyRequest->longitude)
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3"><i class="bi bi-map-fill text-primary me-2"></i> الموقع الجغرافي:</h6>
                                @if($supplyRequest->location)
                                    <p class="text-muted small mb-2"><i class="bi bi-geo me-1"></i> {{ $supplyRequest->location }}</p>
                                @endif
                                <div id="mapPreview" style="height: 300px; width: 100%; border-radius: 10px; border: 1px solid #dee2e6;"></div>
                            </div>
                        @endif

                        @if(auth()->id() == $supplyRequest->user_id)
                            <!-- Owner View: Show Responses -->
                            <h4 class="fw-bold mb-4 border-top pt-4">العروض المقدمة ({{ $supplyRequest->responses->count() }})</h4>
                            @forelse($supplyRequest->responses as $response)
                                @php
                                    $isAwarded = ($supplyRequest->awarded_provider_id === $response->user_id);
                                    $isHidden = ($supplyRequest->awarded_provider_id && !$isAwarded);
                                @endphp
                                @if(!$isHidden)
                                <div class="card bg-light border-0 rounded-4 mb-3">
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="fw-bold mb-0"><a href="{{ route('member.public', $response->user->id) }}" class="text-dark text-decoration-none">{{ $response->user->name }}</a></h6>
                                            <span class="badge bg-success rounded-pill px-3 py-2 fs-6">{{ $response->proposed_price }} ر.س</span>
                                        </div>
                                        <p class="mb-0 text-muted">{{ $response->notes }}</p>

                                        @if($supplyRequest->status === \App\Models\SupplyRequest::STATUS_OPEN || $supplyRequest->status === \App\Models\SupplyRequest::STATUS_PENDING)
                                            <form action="{{ route('website.supply-requests.acceptApplication', ['id' => $supplyRequest->id, 'applicationId' => $response->id]) }}" method="POST" class="mt-3">
                                                @csrf
                                                <button type="submit" class="btn btn-success"><i class="bi bi-check-circle"></i> {{ __('website.accept_offer') ?? 'قبول العرض' }}</button>
                                            </form>
                                        @elseif($isAwarded)
                                            <div class="alert alert-success mt-3 mb-0 p-2 text-center">
                                                <i class="bi bi-star-fill text-warning"></i> {{ __('website.offer_accepted') ?? 'العرض المقبول' }}
                                            </div>
                                            @php
                                                $existingChat = \App\Models\Chat::whereHas('participants', function($q) use ($supplyRequest) {
                                                    $q->where('users.id', $supplyRequest->user_id);
                                                })->whereHas('participants', function($q) use ($response) {
                                                    $q->where('users.id', $response->user_id);
                                                })->first();
                                            @endphp
                                            @if($existingChat)
                                                <a href="{{ route('dashboard.chat.show', $existingChat->id) }}" class="btn btn-outline-primary w-100 mt-2">
                                                    <i class="bi bi-chat-dots me-1"></i> محادثة مع المورد
                                                </a>
                                            @endif
                                            @if($supplyRequest->status === \App\Models\SupplyRequest::STATUS_IN_PROGRESS)
                                                <form action="{{ route('website.supply-requests.completeWork', $supplyRequest->id) }}" method="POST" class="mt-2">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-flag-fill"></i> {{ __('website.mark_as_completed') ?? 'تأكيد إنتهاء العمل' }}</button>
                                                </form>
                                            @elseif($supplyRequest->status === \App\Models\SupplyRequest::STATUS_COMPLETED)
                                                <button type="button" class="btn btn-warning w-100 mt-2" data-bs-toggle="modal" data-bs-target="#ratingModal">
                                                    <i class="bi bi-star"></i> {{ __('website.rate_provider') ?? 'تقييم المورد' }}
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                @endif
                            @empty
                                <div class="alert alert-info text-center">لا توجد عروض مقدمة حتى الآن.</div>
                            @endforelse
                        @endif


                    </div>
                </div>
            </div>

            <!-- Sidebar / Apply Form -->
            <div class="col-lg-4">
                @if(auth()->check() && auth()->id() != $supplyRequest->user_id && auth()->user()->isServiceProvider())
                <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 100px;">
                    <div class="card-body p-4">
                            @php
                                $hasApplied = $supplyRequest->responses()->where('user_id', auth()->id())->exists();
                                $isAwarded = ($supplyRequest->awarded_provider_id === auth()->id());
                                $isCompleted = ($supplyRequest->status === \App\Models\SupplyRequest::STATUS_COMPLETED);
                                $isClosedOrInProgress = in_array($supplyRequest->status, [\App\Models\SupplyRequest::STATUS_CLOSED, \App\Models\SupplyRequest::STATUS_IN_PROGRESS]);
                            @endphp

                            @if($isAwarded && $isCompleted)
                                <div class="alert alert-success text-center mb-0">
                                    <i class="bi bi-trophy-fill d-block fs-1 mb-2 text-warning"></i>
                                    <h5 class="fw-bold">تهانينا!</h5>
                                    لقد تم الانتهاء من هذا الطلب وأنت من قمت بتنفيذه بنجاح.
                                </div>
                            @elseif($isAwarded && $isClosedOrInProgress)
                                <div class="alert alert-primary text-center mb-0">
                                    <i class="bi bi-star-fill d-block fs-3 mb-2 text-warning"></i>
                                    لقد تم قبول عرضك! الطلب الآن قيد التنفيذ من قبلك.
                                </div>
                            @elseif($supplyRequest->status !== 'open' && $supplyRequest->status !== 'pending')
                                <div class="alert alert-secondary text-center mb-0">
                                    <i class="bi bi-lock-fill d-block fs-3 mb-2"></i>
                                    هذا الطلب مغلق أو تم الانتهاء منه.
                                </div>
                            @elseif($hasApplied)
                                <div class="alert alert-success text-center mb-0">
                                    <i class="bi bi-check-circle-fill d-block fs-3 mb-2"></i>
                                    لقد قمت بتقديم عرض لهذا الطلب مسبقاً بانتظار رد العميل.
                                </div>
                            @else
                                <h5 class="fw-bold mb-3">تقديم عرض أسعار</h5>
                                <form action="{{ route('website.supply-requests.storeApplication', $supplyRequest->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">السعر المقترح (ر.س) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" name="proposed_price" class="form-control bg-light border-0" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label">ملاحظات إضافية</label>
                                        <textarea name="notes" rows="3" class="form-control bg-light border-0" placeholder="اكتب تفاصيل عرضك هنا..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold">
                                        إرسال العرض
                                    </button>
                                </form>
                            @endif
                    </div>
                </div>
                @elseif(!auth()->check())
                <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 100px;">
                    <div class="card-body p-4">
                        <div class="alert alert-warning text-center mb-0">
                            يرجى <a href="{{ route('login') }}" class="fw-bold text-dark">تسجيل الدخول</a> كمورد لتقديم عرض.
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Rating Modal -->
@if($supplyRequest->status === \App\Models\SupplyRequest::STATUS_COMPLETED && auth()->check() && auth()->id() === $supplyRequest->user_id)
<div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('website.supply-requests.rate', $supplyRequest->id) }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title fw-bold" id="ratingModalLabel"><i class="bi bi-star-fill text-warning"></i> {{ __('website.rate_provider') ?? 'تقييم المورد' }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3 text-center">
                <label class="form-label d-block fw-bold">{{ __('website.select_rating') ?? 'اختر التقييم (من 1 إلى 5):' }}</label>
                <style>
                    .rating-stars {
                        display: flex;
                        flex-direction: row-reverse;
                        justify-content: center;
                    }
                    .rating-stars input {
                        display: none;
                    }
                    .rating-stars label {
                        cursor: pointer;
                        color: #e4e5e9;
                        font-size: 2.5rem;
                        transition: color 0.2s;
                    }
                    .rating-stars input:checked ~ label,
                    .rating-stars label:hover,
                    .rating-stars label:hover ~ label {
                        color: #ffc107 !important;
                    }
                    .rating-stars label i {
                        color: inherit;
                    }
                </style>
                <div class="rating-stars" style="direction:ltr;">
                    <input type="radio" name="score" value="5" id="star5" required> <label for="star5"><i class="bi bi-star-fill"></i></label>
                    <input type="radio" name="score" value="4" id="star4"> <label for="star4"><i class="bi bi-star-fill"></i></label>
                    <input type="radio" name="score" value="3" id="star3"> <label for="star3"><i class="bi bi-star-fill"></i></label>
                    <input type="radio" name="score" value="2" id="star2"> <label for="star2"><i class="bi bi-star-fill"></i></label>
                    <input type="radio" name="score" value="1" id="star1"> <label for="star1"><i class="bi bi-star-fill"></i></label>
                </div>
            </div>
            <div class="mb-3">
                <label for="comment" class="form-label fw-bold">{{ __('website.rating_comment') ?? 'تعليق (اختياري):' }}</label>
                <textarea class="form-control" name="comment" id="comment" rows="3"></textarea>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('website.cancel') ?? 'إلغاء' }}</button>
          <button type="submit" class="btn btn-primary">{{ __('website.submit_rating') ?? 'إرسال التقييم' }}</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif

@push('scripts')
@if($supplyRequest->latitude && $supplyRequest->longitude)
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const lat = {{ $supplyRequest->latitude }};
        const lng = {{ $supplyRequest->longitude }};
        const loc = { lat: lat, lng: lng };
        
        const map = new google.maps.Map(document.getElementById("mapPreview"), {
            zoom: 14,
            center: loc,
        });
        
        new google.maps.Marker({
            position: loc,
            map: map,
        });
    });
</script>
@endif
@endpush

@endsection
