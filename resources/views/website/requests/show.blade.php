@extends('website.layouts.profile')

@section('title', 'تفاصيل الطلب')

@section('profile-content')
    @php
        $isSeeker = auth()->check() && auth()->user()->user_type === 'service_seeker';
    @endphp
    <!-- Header Start -->
    <div class="services-header-section without-search">
        <div class="container p-md-5 p-4 mb-md-5">
            <div class="row align-items-center">
                <div class="col-lg-12">

                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->
    <div class="container py-4">
        <!-- Status Timeline (Horizontal) -->
        <div class="row mb-4">
            <div class="{{ (Auth::id() == $serviceRequest->user_id && $serviceRequest->status == 'pending') ? 'col-lg-8' : 'col-12' }}">
                <div class="card border-0 shadow-sm root-radius">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4 pb-2 border-bottom">تتبع الطلب</h5>
                        <div class="status-tracker mb-4">
                            <ul class="list-unstyled d-flex justify-content-between align-items-center mb-0 text-center overflow-auto pb-2">
                            @php
                                $statuses = [
                                    'under_review' => 'قيد المراجعة',
                                    'pending' => 'طلب جديد',
                                    'provider_accepted' => 'تم قبول العرض',
                                    'seeker_confirmed_provider' => 'تم تأكيد مقدم الخدمة',
                                    'inspection_scheduled' => 'موعد المعاينة',
                                    'inspection_done' => 'تمت المعاينة',
                                    'completed' => 'اكتمل العمل / التقييم',
                                ];
                                $activeStatus = $serviceRequest->status;
                                if ($activeStatus == 'work_completed') $activeStatus = 'completed';
                                $foundActive = false;
                                $step = 1;
                            @endphp
                            @foreach ($statuses as $key => $label)
                                @php
                                    $isCompleted = !$foundActive;
                                    if ($key == $activeStatus) {
                                        $foundActive = true;
                                        $isCurrent = true;
                                    } else {
                                        $isCurrent = false;
                                    }
                                    $isPast = $isCompleted && !$isCurrent;
                                    $bgColor = ($isPast || $isCurrent) ? 'bg-primary text-white' : 'bg-light text-muted border';
                                    $textColor = ($isPast || $isCurrent) ? 'fw-bold text-primary' : 'text-muted';
                                @endphp
                                    <li class="flex-fill px-2">
                                        <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center {{ $bgColor }}" style="width: 35px; height: 35px;">
                                            @if($isPast)
                                                <i class="bi bi-check"></i>
                                            @else
                                                {{ $step }}
                                            @endif
                                        </div>
                                        <small class="d-block {{ $textColor }}" style="white-space: nowrap;">{{ $label }}</small>
                                    </li>
                                @php $step++; @endphp
                            @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            @if(Auth::id() == $serviceRequest->user_id && $serviceRequest->status == 'pending')
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 root-radius bg-primary text-white mb-4">
                    <div class="card-body p-4 text-center">
                        <i class="bi bi-hourglass-top display-4 d-block mb-3"></i>
                        <h5 class="fw-bold">بانتظار العروض</h5>
                        <p class="small mb-0">سيتم تنبيهك فور وصول عروض جديدة من مقدمي الخدمات المعتمدين.</p>
                    </div>
                </div>
            </div>
            @endif

                @if ($serviceRequest->status == 'time_expired')
                    <div class="alert alert-danger mt-3">انتهى الوقت المسموح للرد على هذا الطلب.</div>
                @endif

                @if ($serviceRequest->status == 'cancelled')
                    <div class="alert alert-secondary mt-3">تم إلغاء هذا الطلب.</div>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <!-- Responses Section (For Seeker) -->
                @if (Auth::id() == $serviceRequest->user_id && $serviceRequest->status == 'pending')
                    <h4 class="mb-3">العروض المقدمة</h4>
                    @forelse($serviceRequest->responses as $response)
                        <div class="card shadow-sm border-0 mb-3 border-start border-success border-4">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-1">
                                            <a href="{{ route('member.public', $response->user->id) }}" class="text-decoration-none text-dark">
                                                {{ $response->user->name }}
                                            </a>
                                        </h5>
                                        <p class="mb-2">{{ $response->message }}</p>
                                        <div class="badge bg-light text-primary border border-primary p-2">
                                            السعر المقترح للمعاينة الأولية: {{ $response->proposed_price }} ريال | المدة:
                                            {{ $response->proposed_timeline }}
                                        </div>
                                    </div>
                                    <div>
                                        <form action="{{ route('requests.accept', $response->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success">قبول العرض</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info">لا توجد عروض حتى الآن.</div>
                    @endforelse
                @endif

                <!-- Workflow Actions -->
                @php
                    $acceptedResponse = $serviceRequest->responses->where('status', 'accepted')->first();
                    $isProvider = $acceptedResponse && $acceptedResponse->user_id == Auth::id();
                    $isSeeker = $serviceRequest->user_id == Auth::id();
                @endphp

                @if ($acceptedResponse)
                    <div class="card shadow-sm border-0 mb-4 bg-light">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <img src="{{ $acceptedResponse->user->getFirstMediaUrl('users') ?: asset('website/assets/img/logo.png') }}"
                                        class="rounded-circle" width="50" height="50">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">مقدم الخدمة المختار: 
                                        <a href="{{ route('member.public', $acceptedResponse->user->id) }}" class="text-decoration-none">
                                            {{ $acceptedResponse->user->name }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">السعر المتفق عليه: {{ $acceptedResponse->proposed_price }}
                                        ريال</small>
                                </div>
                                <div>
                                    <a href="{{ route('profile.requests') }}"
                                        class="btn btn-outline-primary btn-sm">تواصل</a>
                                </div>
                            </div>

                            <!-- Action Buttons based on Status -->
                            <div class="d-flex gap-2">
                                @if ($isSeeker && $serviceRequest->status == 'provider_accepted')
                                    <form action="{{ route('requests.confirm-seeker', $serviceRequest->id) }}"
                                        method="POST" class="w-100">
                                        @csrf
                                        <button type="submit" class="btn btn-primary w-100">تأكيد مقدم الخدمة لبدء
                                            المعاينة</button>
                                    </form>
                                @endif

                                @if ($isProvider && $serviceRequest->status == 'seeker_confirmed_provider')
                                    <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal"
                                        data-bs-target="#scheduleModal">تحديد موعد المعاينة</button>
                                @endif

                                @if ($isProvider && $serviceRequest->status == 'inspection_scheduled')
                                    <form
                                        action="{{ route('requests.inspections.complete', $serviceRequest->inspections->last()->id) }}"
                                        method="POST" class="w-100">
                                        @csrf
                                        <button type="submit" class="btn btn-success w-100">إتمام المعاينة بنجاح</button>
                                    </form>
                                @endif

                                @if ($isSeeker && $serviceRequest->status == 'inspection_done')
                                    <form action="{{ route('requests.complete-work', $serviceRequest->id) }}"
                                        method="POST" class="w-100">
                                        @csrf
                                        <button type="submit" class="btn btn-success w-100">تأكيد إتمام العمل
                                            والتققيم</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Response Form (For Provider) -->
                @if (Auth::id() != $serviceRequest->user_id && !$serviceRequest->responses->where('user_id', Auth::id())->count())
                    <div class="card shadow-sm border-0 mt-4 mb-4">
                        <div class="card-header bg-white p-4">
                            <h5 class="mb-0">تقديم عرض</h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('requests.respond', $serviceRequest->id) }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">السعر المقترح</label>
                                        <input type="number" name="proposed_price" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">المدة التقديرية</label>
                                        <input type="text" name="proposed_timeline" class="form-control"
                                            placeholder="مثال: 3 أيام" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">رسالة العرض</label>
                                        <textarea name="message" class="form-control" rows="3" required></textarea>
                                    </div>
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-primary">إرسال العرض</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Inspections History -->
                @if ($serviceRequest->inspections->count() > 0)
                    <div class="card shadow-sm border-0 mt-4 mb-4">
                        <div class="card-header bg-white p-4">
                            <h5 class="mb-0">سجل المعاينات</h5>
                        </div>
                        <div class="card-body p-4">
                            @foreach ($serviceRequest->inspections as $index => $inspection)
                                <div
                                    class="alert {{ $inspection->status == 'completed' ? 'alert-success' : 'alert-info' }} mb-3">
                                    <div class="d-flex justify-content-between">
                                        <strong>المعاينة #{{ $index + 1 }}</strong>
                                        <span class="badge bg-white text-dark">{{ $inspection->status }}</span>
                                    </div>
                                    <hr>
                                    <strong>الموعد:</strong> {{ $inspection->scheduled_at->format('Y-m-d H:i') }} <br>
                                    <strong>ملاحظات:</strong> {{ $inspection->notes ?? 'لا يوجد' }}
                                    @if ($inspection->completed_at)
                                        <br><strong>تم الإتمام:</strong>
                                        {{ $inspection->completed_at->format('Y-m-d H:i') }}
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="row">
            <!-- Request Details -->
            <div class="col-lg-12">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">{{ $serviceRequest->category->name }}</h4>
                            <div class="d-flex align-items-center">
                                @if ($serviceRequest->isTimeExpired() && $serviceRequest->status == 'pending')
                                    <span class="badge bg-danger me-2">منتهي الوقت</span>
                                @endif
                                <span class="badge bg-primary">
                                    {{ __('admin.' . $serviceRequest->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">


                        @if ($serviceRequest->latitude && $serviceRequest->longitude)
                            <div class="mb-4">
                                <h5>الموقع على الخريطة</h5>
                                <iframe 
                                    width="100%" 
                                    height="300" 
                                    style="border:1px solid #e0e0e0; border-radius: 8px;" 
                                    loading="lazy" 
                                    src="https://www.openstreetmap.org/export/embed.html?bbox={{ $serviceRequest->longitude - 0.01 }}%2C{{ $serviceRequest->latitude - 0.01 }}%2C{{ $serviceRequest->longitude + 0.01 }}%2C{{ $serviceRequest->latitude + 0.01 }}&amp;layer=mapnik&amp;marker={{ $serviceRequest->latitude }}%2C{{ $serviceRequest->longitude }}">
                                </iframe>
                                <div class="mt-2 text-end">
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ $serviceRequest->latitude }},{{ $serviceRequest->longitude }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-google"></i> فتح في جوجل ماب</a>
                                </div>
                            </div>
                        @endif

                        @php
                            $upcomingInspection = $serviceRequest->inspections->where('status', 'scheduled')->last();
                        @endphp
                        @if ($upcomingInspection)
                            <div class="alert alert-warning mb-4">
                                <strong><i class="bi bi-calendar-event"></i> موعد المعاينة القادم:</strong> 
                                {{ $upcomingInspection->scheduled_at->format('Y-m-d h:i A') }}
                                @if($upcomingInspection->notes)
                                    <br><small class="text-muted">ملاحظات: {{ $upcomingInspection->notes }}</small>
                                @endif
                            </div>
                        @endif

                        @if ($serviceRequest->voice_record)
                            <div class="mb-4">
                                <h5>تسجيل صوتي</h5>
                                <audio controls class="w-100">
                                    <source src="{{ asset('storage/' . $serviceRequest->voice_record) }}" type="audio/webm">
                                    <source src="{{ asset('storage/' . $serviceRequest->voice_record) }}" type="audio/mpeg">
                                    متصفحك لا يدعم تشغيل الصوت.
                                </audio>
                            </div>
                        @endif

                        <div class="mb-4">
                            <h5>وصف الطلب</h5>
                            <div class="request-description p-3 bg-light border rounded">
                                {!! $serviceRequest->description !!}
                            </div>
                        </div>

                        @if ($serviceRequest->attachment_link)
                            <div class="mb-4">
                                <h5>رابط المرفقات</h5>
                                <a href="{{ $serviceRequest->attachment_link }}" target="_blank" class="btn btn-outline-info">
                                    <i class="bi bi-link-45deg"></i> فتح رابط المرفقات
                                </a>
                            </div>
                        @endif

                        @if ($serviceRequest->hasMedia('blueprints'))
                            <div class="mb-4">
                                <h5>الرسم الكروكي</h5>
                                @foreach ($serviceRequest->getMedia('blueprints') as $media)
                                    <a href="{{ $media->getUrl() }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-file-earmark-pdf"></i> عرض الملف
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        @if ($serviceRequest->hasMedia('site_photos'))
                            <div class="mb-4">
                                <h5>{{ __('website.site_photos_lbl') }}</h5>
                                <div class="row g-2">
                                    @foreach ($serviceRequest->getMedia('site_photos') as $media)
                                        <div class="col-md-3">
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                <img src="{{ $media->getUrl() }}" class="img-fluid rounded"
                                                    alt="Site Photo">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($serviceRequest->neighbors_description)
                            <div class="mb-4">
                                <h5>وصف الجيران</h5>
                                <p class="text-muted">{!! nl2br(e($serviceRequest->neighbors_description)) !!}</p>
                            </div>
                        @endif
                    </div>
                </div>



            </div>

    </div>

    <!-- Schedule Modal -->
    <div class="modal fade" id="scheduleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('requests.schedule', $serviceRequest->id) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">تحديد موعد المعاينة</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">الموعد</label>
                            <input type="datetime-local" name="scheduled_at" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ملاحظات إضافية</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">تأكيد الموعد</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>
    </div>
@endsection

