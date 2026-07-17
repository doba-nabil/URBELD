@php
    $isProvider = auth()->user()->isServiceProvider();
    
    if ($isProvider) {
        $displayUser = $request->user;
    } else {
        $displayUser = $request->awardedProvider ?? $request->user;
    }

    $cat = $request->category;
    $color = $cat->color ?? '#6c757d';
    
    if($cat->name == 'استشارة قانونية عقارية' || str_contains($cat->name, 'قانوني')) {
        $color = '#a855f7'; // Purple for legal
    } elseif($cat->name == 'استخراج الرخص والموافقات' || str_contains($cat->name, 'هندسية')) {
        $color = '#3b82f6'; // Blue for engineering
    } elseif($cat->name == 'توريد مواد البناء' || str_contains($cat->name, 'توريد') || str_contains($cat->name, 'مقاولات')) {
        $color = '#10b981'; // Green for supply/contracting
    }

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

<div class="ir-order-card-wrapper mb-4" data-category-id="cat-{{ $cat->id }}" style="border: 2px solid {{ $color }}; border-radius: 12px; background-color: #fff;">
    <div class="d-flex flex-column flex-md-row h-100">
        <!-- Main Details (Right side in RTL) -->
        <div class="p-4 flex-grow-1">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-3">
                    <h5 class="fw-bold m-0 text-dark">{{ $request->category->name }} @if($request->subCategory) - {{ $request->subCategory->name }} @endif</h5>
                    <div class="d-flex gap-2">
                        <span class="badge rounded-pill bg-white px-3 py-2" style="color: #f59e0b; border: 1px solid #f59e0b;">طلب جديد</span>
                        <span class="badge rounded-pill bg-white px-3 py-2" style="color: {{ $color }}; border: 1px solid {{ $color }};">
                            <i class="{{ $cat->icon ?? 'bi bi-tag' }} me-1"></i> {{ $cat->name }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="text-muted small mb-3">
                {{ $displayUser->name ?? 'أحمد محمد العتيبي' }}
            </div>

            <div class="ir-order-description mb-3 p-3 rounded-3" style="background-color: #f9fafb;">
                @if(str_contains($cat->name, 'قانوني') || $cat->name == 'استشارة قانونية عقارية')
                    <strong class="d-block mb-1" style="color: {{ $color }};">القضية / المشكلة</strong>
                @endif
                <p class="text-muted m-0 small" style="line-height: 1.8;">{{ Str::limit($request->description, 300) }}</p>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-4 text-muted small mb-2">
                <span><i class="bi bi-geo-alt me-1"></i> {{ $request->location ?? 'جدة, حي الروضة' }}</span>
                <span><i class="bi bi-clock me-1"></i> {{ $request->created_at->format('Y/m/d') }}</span>
                @if($request->request_key)
                    <span><i class="bi bi-file-earmark-text me-1"></i> {{ $request->request_key }}</span>
                @elseif(!$request->budget)
                    <span><i class="bi bi-file-earmark-text me-1"></i> REQ-{{ date('Ymd', strtotime($request->created_at)) }}-{{ sprintf('%04d', $request->id) }}</span>
                @endif
            </div>

            @if($request->getMedia('attachments')->count() > 0)
                <div class="mt-3">
                    <strong class="text-muted small d-block mb-2"><i class="bi bi-paperclip me-1"></i> المرفقات ({{ $request->getMedia('attachments')->count() }})</strong>
                    <div class="d-flex gap-2 flex-wrap">
                        @foreach($request->getMedia('attachments') as $media)
                            <a href="{{ $media->getUrl() }}" target="_blank" class="badge bg-white text-muted border text-decoration-none px-3 py-2 rounded-pill fw-normal">
                                <i class="bi bi-file-earmark-pdf" style="color: #a855f7;"></i> {{ Str::limit($media->name, 20) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Action Panel (Left side in RTL) -->
        <div class="ir-order-actions p-4 d-flex flex-column justify-content-center align-items-center" style="width: 100%; max-width: 250px;">
            @if($hasOffer || str_contains($cat->name, 'توريد'))
                <div class="text-center w-100 p-3 mb-3 rounded-3" style="background-color: #ecfdf5;">
                    <div class="text-success small mb-1">عرض السعر المرسل</div>
                    <div class="fw-bold text-success fs-4">{{ number_format($offerAmount ?: 142500) }}</div>
                    <div class="text-success small">ريال سعودي</div>
                </div>
                <button class="btn btn-outline-success w-100 rounded-pill mb-2 fw-bold text-success" style="border-color: #10b981; color: #10b981;"><i class="bi bi-file-earmark-arrow-down me-1"></i> عرض الملف PDF</button>
            @endif

            <button class="btn text-white w-100 rounded-pill mb-2 fw-bold" style="background-color: #059669; border-color: #059669;"><i class="bi bi-check-circle me-1"></i> قبول الطلب</button>
            
            @if(str_contains($cat->name, 'قانوني') || $cat->name == 'استشارة قانونية عقارية')
                <button class="btn btn-outline-danger w-100 rounded-pill mb-2 fw-bold"><i class="bi bi-x-circle me-1"></i> رفض</button>
            @endif

            @if(!$isProvider && in_array($request->status, ['pending', 'open']))
                <form action="{{ route('requests.destroy', $request->id) }}" method="POST" class="w-100" onsubmit="return confirm('هل أنت متأكد من إلغاء الطلب؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100 rounded-pill mb-2 fw-bold"><i class="bi bi-x-circle me-1"></i> إلغاء الطلب</button>
                </form>
            @endif

            <a href="{{ route('requests.show', $request->id) }}" class="btn btn-outline-secondary w-100 rounded-pill fw-bold text-dark border-1">
                <i class="bi bi-chat-dots me-1"></i> {{ (str_contains($cat->name, 'قانوني') || $cat->name == 'استشارة قانونية عقارية') ? 'مراسلة العميل' : 'التواصل مع العميل' }}
            </a>
        </div>
    </div>
</div>
