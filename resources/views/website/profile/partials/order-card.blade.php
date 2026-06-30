@php
    $isProvider = auth()->user()->isServiceProvider();
    $statusColor = match ($request->status) {
        'pending' => 'bg-info text-primary',
        'provider_accepted', 'inspection_scheduled', 'inspection_done' => 'bg-primary text-white',
        'work_completed', 'completed' => 'bg-success text-white',
        'cancelled', 'rejected', 'timeout' => 'bg-danger text-white',
        default => 'bg-secondary text-white',
    };
    
    if ($isProvider) {
        $displayUser = $request->user;
        $userLabel = __('website.service_seeker_lbl');
    } else {
        $displayUser = $request->awardedProvider ?? $request->user;
        $userLabel = $request->awardedProvider ? __('website.service_provider_lbl') : __('website.service_seeker_lbl');
    }

    $cat = $request->category;
    $color = $cat->color ?? '#064B3B';

    // Check if the current user has submitted an offer
    $hasOffer = false;
    $offerAmount = 0;
    if ($isProvider) {
        $myResponse = $request->responses->where('user_id', auth()->id())->first();
        if ($myResponse && in_array($myResponse->status, ['pending', 'accepted', 'timeout']) && $myResponse->proposed_price > 0) {
            $hasOffer = true;
            $offerAmount = $myResponse->proposed_price;
        }
    } else {
        $acceptedResponse = $request->acceptedResponse;
        if ($acceptedResponse) {
            $hasOffer = true;
            $offerAmount = $acceptedResponse->proposed_price;
        }
    }
@endphp

<div class="order-card-wrapper mb-4" data-category-id="cat-{{ $cat->id }}" style="--card-color: {{ $color }}">
    <div class="order-card p-4">
        <div class="row">
            <!-- Details Column -->
            <div class="col-md-{{ $hasOffer ? '9' : '12' }} order-card-details">
                
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h4 class="order-title fw-bold m-0 text-dark">{{ $request->category->name }} @if($request->subCategory) - {{ $request->subCategory->name }} @endif</h4>
                    
                    <div class="d-flex gap-2 align-items-center">
                        <span class="category-pill" style="color: {{ $color }}; background-color: {{ $color }}15;">
                            <i class="{{ $cat->icon ?? 'bi bi-tag' }}"></i> {{ $cat->name }}
                        </span>
                        <span class="badge rounded-pill {{ $statusColor }} px-3 py-2">{{ __('admin.' . $request->status) }}</span>
                    </div>
                </div>

                <div class="text-muted small mb-3">
                    {{ $displayUser->name }}
                </div>

                <div class="order-description mb-3">
                    <strong class="text-purple d-block mb-1">ملخص القضية / المشكلة</strong>
                    <p class="text-muted m-0">{{ Str::limit($request->description, 200) }}</p>
                </div>

                <div class="order-meta d-flex flex-wrap gap-4 text-muted small mb-3">
                    <span><i class="bi bi-geo-alt"></i> {{ $request->location }}</span>
                    <span><i class="bi bi-calendar3"></i> {{ $request->created_at->format('Y/m/d') }}</span>
                    @if($request->budget)
                        <span><i class="bi bi-cash"></i> {{ $request->budget }} ريال</span>
                    @else
                        <span><i class="bi bi-tag"></i> {{ 'LEG-2026-'.sprintf('%03d', $request->id) }}</span>
                    @endif
                </div>

                @if($request->getMedia('attachments')->count() > 0)
                <div class="order-attachments mb-4">
                    <strong class="d-block mb-2 text-muted"><i class="bi bi-paperclip"></i> المرفقات ({{ $request->getMedia('attachments')->count() }})</strong>
                    <div class="d-flex gap-2 flex-wrap">
                        @foreach($request->getMedia('attachments') as $media)
                            <a href="{{ $media->getUrl() }}" target="_blank" class="attachment-pill text-decoration-none">
                                <i class="bi bi-file-earmark-pdf text-danger"></i> {{ Str::limit($media->name, 20) }}
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="order-actions d-flex gap-3 align-items-center mt-3">
                    @if($isProvider)
                        <a href="{{ route('requests.show', $request->id) }}" class="btn btn-outline-primary rounded-pill px-4">
                            <i class="bi bi-chat-dots me-1"></i> مراسلة المستفيد
                        </a>
                    @else
                        @if($request->awarded_provider_id)
                        <a href="{{ route('requests.show', $request->id) }}" class="btn btn-outline-primary rounded-pill px-4">
                            <i class="bi bi-chat-dots me-1"></i> مراسلة {{ __('website.service_provider_lbl') }}
                        </a>
                        @endif
                    @endif
                    <a href="{{ route('requests.show', $request->id) }}" class="btn text-muted">
                        <i class="bi bi-eye me-1"></i> عرض التفاصيل
                    </a>
                </div>
            </div>

            <!-- Offer Column -->
            @if($hasOffer)
            <div class="col-md-3 border-start">
                <div class="offer-box h-100 d-flex flex-column justify-content-center align-items-center text-center p-3 bg-light rounded" style="background-color: #f8fff9 !important;">
                    <span class="text-success small fw-bold mb-1">عرض السعر المقدم</span>
                    <h3 class="text-success fw-bold mb-0">{{ number_format($offerAmount) }}</h3>
                    <small class="text-muted mb-3">ريال سعودي</small>
                    
                    <a href="#" class="btn btn-outline-success btn-sm rounded-pill w-100 mb-2">
                        <i class="bi bi-file-earmark-pdf"></i> عرض السعر PDF
                    </a>
                    
                    @if(!$isProvider && $request->status == 'pending')
                    <button class="btn btn-success btn-sm rounded-pill w-100 mb-2">
                        <i class="bi bi-check-circle"></i> قبول العرض
                    </button>
                    @endif
                    
                    <a href="{{ route('requests.show', $request->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill w-100">
                        <i class="bi bi-chat"></i> التواصل مع مزود الخدمة
                    </a>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
