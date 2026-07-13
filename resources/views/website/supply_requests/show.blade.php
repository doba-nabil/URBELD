@extends('website.layouts.master')
@section('title', $supplyRequest->title)

@section('content')
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
                        
                        <h2 class="fw-bold mb-4">{{ $supplyRequest->title }}</h2>
                        
                        <div class="d-flex gap-4 mb-4 pb-4 border-bottom">
                            <div class="d-flex align-items-center text-muted">
                                <div class="bg-light rounded-circle p-2 me-2">
                                    <i class="bi bi-geo-alt-fill text-primary"></i>
                                </div>
                                <div>
                                    <small class="d-block">المدينة</small>
                                    <span class="fw-bold text-dark">{{ $supplyRequest->city ? $supplyRequest->city->name : 'غير محدد' }}</span>
                                </div>
                            </div>
                            @if($supplyRequest->delivery_date)
                            <div class="d-flex align-items-center text-muted">
                                <div class="bg-light rounded-circle p-2 me-2">
                                    <i class="bi bi-calendar-check text-danger"></i>
                                </div>
                                <div>
                                    <small class="d-block">آخر موعد للتسليم</small>
                                    <span class="fw-bold text-dark" dir="ltr">{{ $supplyRequest->delivery_date->format('Y-m-d') }}</span>
                                </div>
                            </div>
                            @endif
                        </div>

                        <h5 class="fw-bold mb-3">تفاصيل الطلب:</h5>
                        <div class="mb-5 text-muted" style="line-height: 1.8;">
                            {!! nl2br(e($supplyRequest->description)) !!}
                        </div>

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
                <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 100px;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            @php
                                $siteLogo = app()->getLocale() == 'ar' 
                                           ? \App\Models\Setting::getMediaUrl('logo_ar') 
                                           : \App\Models\Setting::getMediaUrl('logo_en');
                                $siteLogo = $siteLogo ?: asset('website/assets/img/logo.png');
                            @endphp
                            <img src="{{ $supplyRequest->user->getFirstMediaUrl('personal_photo') ?: $supplyRequest->user->getFirstMediaUrl('users') ?: $siteLogo }}" class="rounded-circle object-fit-cover" width="60" height="60" alt="User">
                            <div class="ms-3">
                                <h6 class="mb-1 fw-bold">{{ $supplyRequest->user->name }}</h6>
                                <small class="text-muted">صاحب الطلب</small>
                            </div>
                        </div>

                        @if(auth()->check() && auth()->id() != $supplyRequest->user_id && auth()->user()->isServiceProvider())
                            @php
                                $hasApplied = $supplyRequest->responses()->where('user_id', auth()->id())->exists();
                            @endphp

                            @if($hasApplied)
                                <div class="alert alert-success text-center mb-0">
                                    <i class="bi bi-check-circle-fill d-block fs-3 mb-2"></i>
                                    لقد قمت بتقديم عرض لهذا الطلب مسبقاً
                                </div>
                            @else
                                <h5 class="fw-bold mb-3 border-top pt-4">تقديم عرض أسعار</h5>
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
                        @elseif(!auth()->check())
                            <div class="alert alert-warning text-center mb-0">
                                يرجى <a href="{{ route('login') }}" class="fw-bold text-dark">تسجيل الدخول</a> كمورد لتقديم عرض.
                            </div>
                        @endif
                    </div>
                </div>
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
                <div class="d-flex justify-content-center gap-3 rating-stars" style="direction:ltr;">
                    <input type="radio" name="score" value="1" id="star1" required> <label for="star1" class="text-warning fs-3"><i class="bi bi-star-fill"></i></label>
                    <input type="radio" name="score" value="2" id="star2"> <label for="star2" class="text-warning fs-3"><i class="bi bi-star-fill"></i></label>
                    <input type="radio" name="score" value="3" id="star3"> <label for="star3" class="text-warning fs-3"><i class="bi bi-star-fill"></i></label>
                    <input type="radio" name="score" value="4" id="star4"> <label for="star4" class="text-warning fs-3"><i class="bi bi-star-fill"></i></label>
                    <input type="radio" name="score" value="5" id="star5"> <label for="star5" class="text-warning fs-3"><i class="bi bi-star-fill"></i></label>
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

@endsection
