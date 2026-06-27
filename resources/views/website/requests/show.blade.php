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
        <div class="row">
            <!-- Request Details -->
            <div class="col-lg-8">
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
                        <div class="row mb-3">
                            <div class="col-md-6 mb-2">
                                <strong>نوع العقار:</strong> {{ $serviceRequest->property_type }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>المساحة:</strong> {{ $serviceRequest->area }} م²
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>المدينة:</strong> {{ $serviceRequest->city ? $serviceRequest->city->name : 'غير محدد' }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>الحي:</strong> {{ $serviceRequest->neighborhood ?? 'غير محدد' }}
                            </div>
                            <div class="col-12 mb-2">
                                <strong>العنوان التفصيلي:</strong> {{ $serviceRequest->location ?? 'غير محدد' }}
                            </div>
                        </div>

                        @if ($serviceRequest->latitude && $serviceRequest->longitude)
                            <div class="mb-4">
                                <h5>الموقع على الخريطة</h5>
                                <iframe
                                    width="100%"
                                    height="300"
                                    style="border:0; border-radius: 8px;"
                                    loading="lazy"
                                    allowfullscreen
                                    src="https://www.google.com/maps/embed/v1/place?q={{ $serviceRequest->latitude }},{{ $serviceRequest->longitude }}&key={{ config('services.google_maps.key') }}">
                                </iframe>
                                <div class="mt-2 text-end">
                                    <a href="https://www.google.com/maps?q={{ $serviceRequest->latitude }},{{ $serviceRequest->longitude }}"
                                        target="_blank" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-geo-alt"></i> فتح في خرائط جوجل
                                    </a>
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
                                <h5>صور الموقع</h5>
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
                    <!-- Check if provider logic needed here -->
                    <div class="card shadow-sm border-0 mt-4">
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
                <!-- Inspection Section -->
                @php
                    $acceptedResponse = $serviceRequest->responses->where('status', 'accepted')->first();
                    $isProvider = $acceptedResponse && $acceptedResponse->user_id == Auth::id();
                    $isSeeker = $serviceRequest->user_id == Auth::id();
                @endphp

                <!-- Inspections History -->
                @if ($serviceRequest->inspections->count() > 0)
                    <div class="card shadow-sm border-0 mt-4">
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

            <!-- Sidebar / Status Timeline -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title mb-4 pb-2 border-bottom">تتبع الطلب</h5>
                        <div class="vertical-timeline">
                            @php
                                $statuses = [
                                    'pending' => 'تم تقديم الطلب',
                                    'provider_accepted' => 'قبول العرض المبدئي',
                                    'seeker_confirmed_provider' => 'تأكيد مقدم الخدمة',
                                    'inspection_scheduled' => 'تحديد موعد المعاينة',
                                    'inspection_done' => 'إتمام المعاينة',
                                    'completed' => 'مكتمل',
                                ];
                                $activeStatus = $serviceRequest->status;
                                $foundActive = false;
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
                                @endphp
                                <div class="timeline-item d-flex mb-4">
                                    <div class="timeline-icon me-3">
                                        @if ($isCompleted && !$isCurrent)
                                            <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                        @elseif($isCurrent)
                                            <i class="bi bi-play-circle-fill text-primary fs-5"></i>
                                        @else
                                            <i class="bi bi-circle text-muted fs-5"></i>
                                        @endif
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="mb-0 {{ $isCurrent ? 'text-primary' : '' }}">{{ $label }}</h6>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @if ($serviceRequest->status == 'time_expired')
                    <div class="alert alert-danger mt-3">انتهى الوقت المسموح للرد على هذا الطلب.</div>
                @endif

                @if ($serviceRequest->status == 'cancelled')
                    <div class="alert alert-secondary mt-3">تم إلغاء هذا الطلب.</div>
                @endif
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
