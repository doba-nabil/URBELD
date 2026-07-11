@extends('dashboard.layout.master')
@section('title', __('admin.view_service_request'))
@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">{{ __('admin.view_service_request') }}</h4>
            <div>
                <a href="{{ route('service-requests.edit', $serviceRequest->id) }}" class="btn btn-primary">
                    <i class="icon-base ti tabler-edit"></i> {{ __('admin.edit') }}
                </a>
                <a href="{{ route('service-requests.index') }}" class="btn btn-secondary">
                    <i class="icon-base ti tabler-arrow-right"></i> {{ __('admin.back') }}
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                @if ($serviceRequest->status === 'under_review')
                    <div class="card mb-4 border-warning">
                        <div class="card-header bg-label-warning d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-warning"><i class="ti tabler-shield-check me-1"></i> {{ __('admin.moderation_actions') }}</h5>
                            <span class="badge bg-warning">{{ __('admin.under_review') }}</span>
                        </div>
                        <div class="card-body pt-3">
                            <p class="mb-4">{{ __('admin.moderation_notice') }}</p>
                            <div class="d-flex justify-content-center">
                                <button class="btn btn-warning btn-lg approve-request-btn" data-id="{{ $serviceRequest->id }}">
                                    <i class="ti tabler-check me-1"></i> {{ __('admin.approve_request') }}
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>معلومات الطلب</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>طالب الخدمة:</strong>
                                <p>{{ $serviceRequest->user->name ?? '-' }}</p>
                            </div>
                            <div class="col-md-4">
                                <strong>القسم الرئيسي:</strong>
                                <p>{{ $serviceRequest->category->name ?? '-' }}</p>
                            </div>
                            <div class="col-md-4">
                                <strong>القسم الفرعي:</strong>
                                <p>{{ $serviceRequest->subCategory->name ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>الحالة:</strong>
                                <p>
                                    @php
                                        $statuses = [
                                            'under_review' => ['label' => __('admin.under_review') ?? 'قيد المراجعة المبدئية', 'class' => 'badge bg-label-secondary'],
                                            'pending' => ['label' => __('admin.status_pending') ?? 'طلب جديد / بانتظار العروض', 'class' => 'badge bg-label-primary'],
                                            'provider_accepted' => ['label' => __('admin.status_provider_accepted') ?? 'تم قبول العرض من مقدم الخدمة', 'class' => 'badge bg-label-info'],
                                            'seeker_confirmed_provider' => ['label' => __('admin.status_seeker_confirmed') ?? 'تم تأكيد مقدم الخدمة من العميل', 'class' => 'badge bg-label-warning'],
                                            'inspection_scheduled' => ['label' => __('admin.status_inspection_scheduled') ?? 'موعد المعاينة مجدول', 'class' => 'badge bg-label-info'],
                                            'inspection_done' => ['label' => __('admin.status_inspection_done') ?? 'تمت المعاينة', 'class' => 'badge bg-label-primary'],
                                            'work_completed' => ['label' => __('admin.status_work_completed') ?? 'اكتمل العمل / التقييم', 'class' => 'badge bg-label-success'],
                                            'completed' => ['label' => __('admin.status_completed') ?? 'مكتمل (مؤرشف)', 'class' => 'badge bg-label-success'],
                                            'rejected' => ['label' => __('admin.status_rejected') ?? 'مرفوض', 'class' => 'badge bg-label-danger'],
                                            'time_expired' => ['label' => __('admin.status_time_expired') ?? 'منتهي الوقت', 'class' => 'badge bg-label-secondary'],
                                            'cancelled' => ['label' => __('admin.status_cancelled') ?? 'ملغى', 'class' => 'badge bg-label-dark'],
                                        ];
                                        $status = $statuses[$serviceRequest->status] ?? [
                                            'label' => $serviceRequest->status,
                                            'class' => 'badge bg-label-secondary',
                                        ];
                                    @endphp
                                    <span class="{{ $status['class'] }}">{{ $status['label'] }}</span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <strong>المدينة:</strong>
                                <p>{{ $serviceRequest->city->name ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>الحي:</strong>
                                <p>{{ $serviceRequest->neighborhood ?? '-' }}</p>
                            </div>
                        </div>
                        @if ($serviceRequest->location)
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <strong>العنوان التفصيلي:</strong>
                                    <p><i class="ti tabler-map-pin me-1"></i>{{ $serviceRequest->location }}</p>
                                </div>
                            </div>
                        @endif
                        @if ($serviceRequest->voice_record)
                            <div class="row mb-4 bg-light p-3 rounded">
                                <div class="col-md-12">
                                    <h6 class="fw-bold mb-2"><i class="ti tabler-microphone me-1"></i>التسجيل الصوتي:</h6>
                                    <audio controls class="w-100">
                                        <source src="{{ asset('storage/' . $serviceRequest->voice_record) }}" type="audio/webm">
                                        <source src="{{ asset('storage/' . $serviceRequest->voice_record) }}" type="audio/mpeg">
                                        متصفحك لا يدعم تشغيل الصوت.
                                    </audio>
                                    <div class="mt-2 text-center">
                                        <a href="{{ asset('storage/' . $serviceRequest->voice_record) }}" target="_blank" class="btn btn-sm btn-link">
                                            <i class="ti tabler-download me-1"></i> تحميل التسجيل
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($serviceRequest->latitude && $serviceRequest->longitude)
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h6 class="fw-bold mb-2"><i class="ti tabler-map me-1"></i>الموقع على الخريطة:</h6>
                                    <iframe
                                        width="100%"
                                        height="300"
                                        style="border:0; border-radius: 8px;"
                                        loading="lazy"
                                        allowfullscreen
                                        src="https://www.google.com/maps/embed/v1/place?q={{ $serviceRequest->latitude }},{{ $serviceRequest->longitude }}&key={{ config('services.google_maps.key') }}">
                                    </iframe>
                                </div>
                            </div>
                        @endif
                        @if ($serviceRequest->description)
                            <div class="row mb-3 border-top pt-3">
                                <div class="col-md-12">
                                    <strong>{{ __('admin.description') }}:</strong>
                                    <p class="mt-2 text-muted" style="white-space: pre-line;">{{ $serviceRequest->description }}</p>
                                </div>
                            </div>
                        @endif
                        @if ($serviceRequest->response_deadline)
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>موعد انتهاء الرد:</strong>
                                    <p
                                        class="{{ now()->isAfter($serviceRequest->response_deadline) ? 'text-danger' : 'text-success' }}">
                                        {{ $serviceRequest->response_deadline->format('Y-m-d H:i') }}
                                    </p>
                                </div>
                            </div>
                        @endif
                        <!-- Attachments Section -->
                        @if ($serviceRequest->hasMedia('blueprints') || $serviceRequest->hasMedia('site_photos'))
                            <div class="row mb-3 border-top pt-3">
                                <div class="col-md-12">
                                    <h5 class="mb-3">الملفات المرفقة</h5>
                                    @if ($serviceRequest->hasMedia('blueprints'))
                                        <div class="mb-3">
                                            <strong>الرسوم الكروكية / المخططات:</strong>
                                            <div class="mt-2 d-flex flex-wrap gap-2">
                                                @foreach ($serviceRequest->getMedia('blueprints') as $media)
                                                    <a href="{{ $media->getUrl() }}" target="_blank"
                                                        class="btn btn-sm btn-outline-primary shadow-sm">
                                                        <i class="ti tabler-file-download"></i> {{ $media->name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    @if ($serviceRequest->hasMedia('site_photos'))
                                        <div class="mb-3">
                                            <strong>صور الموقع:</strong>
                                            <div class="mt-2 d-flex flex-wrap gap-3">
                                                @foreach ($serviceRequest->getMedia('site_photos') as $media)
                                                    <div class="position-relative">
                                                        <a href="{{ $media->getUrl() }}" target="_blank">
                                                            <img src="{{ $media->getUrl() }}" width="120" height="120" class="rounded shadow-sm" style="object-fit: cover; border: 1px solid #eee;">
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @if ($serviceRequest->status === 'work_completed' || $serviceRequest->status === 'completed')
                    <div class="card mb-4">
                        <div class="card-header border-bottom">
                            <h5 class="card-title mb-0">التقييمات المتبادلة</h5>
                        </div>
                        <div class="card-body pt-4">
                            <div class="row">
                                <div class="col-md-6 border-end">
                                    <h6 class="fw-bold mb-3">تقييم طالب الخدمة لمقدم الخدمة</h6>
                                    @if($serviceRequest->seekerRating)
                                        <div class="mb-2">
                                            @for($i=1; $i<=5; $i++)
                                                <i class="ti tabler-star-filled {{ $i <= $serviceRequest->seekerRating->rating ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                            <span class="ms-2 fw-bold">{{ $serviceRequest->seekerRating->rating }}/5</span>
                                        </div>
                                        <p class="mb-0 text-muted">"{{ $serviceRequest->seekerRating->comment ?: 'لا يوجد تعليق' }}"</p>
                                    @else
                                        <span class="badge bg-label-info">بانتظار تقييم طالب الخدمة</span>
                                    @endif
                                </div>
                                <div class="col-md-6 ps-md-4">
                                    <h6 class="fw-bold mb-3">تقييم مقدم الخدمة لطالب الخدمة</h6>
                                    @if($serviceRequest->providerRating)
                                        <div class="mb-2">
                                            @for($i=1; $i<=5; $i++)
                                                <i class="ti tabler-star-filled {{ $i <= $serviceRequest->providerRating->rating ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                            <span class="ms-2 fw-bold">{{ $serviceRequest->providerRating->rating }}/5</span>
                                        </div>
                                        <p class="mb-0 text-muted">"{{ $serviceRequest->providerRating->comment ?: 'لا يوجد تعليق' }}"</p>
                                    @else
                                        <span class="badge bg-label-info">بانتظار تقييم مقدم الخدمة</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>الردود ({{ $serviceRequest->responses->count() }})</h5>
                    </div>
                    <div class="card-body">
                        @forelse($serviceRequest->responses as $response)
                            <div class="border-bottom pb-3 mb-3">
                                <strong>{{ $response->user->name ?? '-' }}</strong>
                                <p class="mb-1">{{ $response->message ?? '-' }}</p>
                                @if ($response->proposed_price)
                                    <small class="text-muted">السعر المقترح:
                                        {{ number_format($response->proposed_price, 2) }}
                                        {{ __('admin.currency') }}</small>
                                @endif
                                @if ($response->proposed_timeline)
                                    <small class="text-muted d-block">المدة المقترحة:
                                        {{ $response->proposed_timeline }}</small>
                                @endif
                                <div class="mt-2 d-flex justify-content-between align-items-center">
                                    <div>
                                        @php
                                            $responseStatuses = [
                                                'pending' => ['label' => 'معلق', 'class' => 'badge bg-label-warning'],
                                                'accepted' => ['label' => 'مقبول', 'class' => 'badge bg-label-success'],
                                                'rejected' => ['label' => 'مرفوض', 'class' => 'badge bg-label-danger'],
                                                'timeout' => ['label' => __('admin.timeout'), 'class' => 'badge bg-label-danger'],
                                            ];
                                            $responseStatus = $responseStatuses[$response->status] ?? [
                                                'label' => $response->status,
                                                'class' => 'badge bg-label-secondary',
                                            ];
                                        @endphp
                                        <span class="{{ $responseStatus['class'] }}">{{ $responseStatus['label'] }}</span>
                                    </div>
                                    <button class="btn btn-sm btn-icon btn-label-primary edit-response-btn" 
                                        data-id="{{ $response->id }}"
                                        data-price="{{ $response->proposed_price }}"
                                        data-timeline="{{ $response->proposed_timeline }}"
                                        data-message="{{ $response->message }}"
                                        data-bs-toggle="modal" data-bs-target="#editResponseModal">
                                        <i class="ti tabler-edit"></i>
                                    </button>
                                </div>
                                @if ($response->status === 'pending' && $serviceRequest->status === 'pending_response')
                                    <div class="mt-2">
                                        <button class="btn btn-sm btn-success accept-response-btn"
                                            data-request-id="{{ $serviceRequest->id }}"
                                            data-response-id="{{ $response->id }}">
                                            <i class="ti tabler-check"></i> قبول الرد
                                        </button>
                                    </div>
                                @endif
                                @if ($response->status === 'accepted' && $serviceRequest->status === 'accepted')
                                    <div class="mt-2">
                                        <button class="btn btn-sm btn-primary schedule-inspection-btn"
                                            data-request-id="{{ $serviceRequest->id }}"
                                            data-response-id="{{ $response->id }}" data-bs-toggle="modal"
                                            data-bs-target="#scheduleInspectionModal">
                                            <i class="ti tabler-calendar"></i> جدولة معاينة
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p class="text-muted">لا توجد ردود بعد</p>
                        @endforelse
                    </div>
                </div>
                @if ($serviceRequest->inspections->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>المعاينات ({{ $serviceRequest->inspections->count() }})</h5>
                        </div>
                        <div class="card-body">
                            @foreach ($serviceRequest->inspections as $inspection)
                                <div class="border-bottom pb-3 mb-3">
                                    <strong>موعد المعاينة:</strong>
                                    <p>{{ $inspection->scheduled_at ? $inspection->scheduled_at->format('Y-m-d H:i') : '-' }}
                                    </p>
                                    @if ($inspection->notes)
                                        <strong>ملاحظات:</strong>
                                        <p>{{ $inspection->notes }}</p>
                                    @endif
                                    <div class="mt-2">
                                        @php
                                            $inspectionStatuses = [
                                                'scheduled' => ['label' => 'مجدولة', 'class' => 'badge bg-label-info'],
                                                'completed' => [
                                                    'label' => 'مكتملة',
                                                    'class' => 'badge bg-label-success',
                                                ],
                                                'cancelled' => ['label' => 'ملغاة', 'class' => 'badge bg-label-danger'],
                                            ];
                                            $inspectionStatus = $inspectionStatuses[$inspection->status] ?? [
                                                'label' => $inspection->status,
                                                'class' => 'badge bg-label-secondary',
                                            ];
                                        @endphp
                                        <span
                                            class="{{ $inspectionStatus['class'] }}">{{ $inspectionStatus['label'] }}</span>
                                    </div>
                                    @if ($inspection->status === 'scheduled' && $serviceRequest->status === 'under_inspection')
                                        <div class="mt-2">
                                            <button class="btn btn-sm btn-success complete-inspection-btn"
                                                data-request-id="{{ $serviceRequest->id }}"
                                                data-inspection-id="{{ $inspection->id }}" data-bs-toggle="modal"
                                                data-bs-target="#completeInspectionModal">
                                                <i class="ti tabler-check"></i> إتمام المعاينة
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if ($serviceRequest->status === 'under_inspection')
                    <div class="card mb-4">
                        <div class="card-body">
                            <button class="btn btn-success w-100 agree-btn" data-request-id="{{ $serviceRequest->id }}">
                                <i class="ti tabler-handshake"></i> تم الاتفاق وبدء العمل
                            </button>
                        </div>
                    </div>
                @endif
                @if ($serviceRequest->status === 'agreed')
                    <div class="card mb-4">
                        <div class="card-body">
                            <button class="btn btn-primary w-100 complete-request-btn"
                                data-request-id="{{ $serviceRequest->id }}" data-bs-toggle="modal"
                                data-bs-target="#completeRequestModal">
                                <i class="ti tabler-check"></i> إتمام الطلب وإضافة التقييمات
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="modal fade" id="scheduleInspectionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">جدولة معاينة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="scheduleInspectionForm">
                    <div class="modal-body">
                        <input type="hidden" name="service_request_id" id="schedule_service_request_id">
                        <input type="hidden" name="response_id" id="schedule_response_id">
                        <div class="mb-3">
                            <label class="form-label">موعد المعاينة</label>
                            <input type="datetime-local" name="scheduled_at" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ملاحظات (اختياري)</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">{{ __('admin.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="completeInspectionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إتمام المعاينة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="completeInspectionForm">
                    <div class="modal-body">
                        <input type="hidden" name="inspection_id" id="complete_inspection_id">
                        <div class="mb-3">
                            <label class="form-label">ملاحظات (اختياري)</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-success">إتمام</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="completeRequestModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إتمام الطلب وإضافة التقييمات</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="completeRequestForm">
                    <div class="modal-body">
                        <input type="hidden" name="request_id" id="complete_request_id">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>تقييم طالب الخدمة لمقدم الخدمة</h6>
                                <div class="mb-3">
                                    <label class="form-label">التقييم (1-5)</label>
                                    <input type="number" name="seeker_rating" class="form-control" min="1"
                                        max="5" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">تعليق (اختياري)</label>
                                    <textarea name="seeker_comment" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6>تقييم مقدم الخدمة لطالب الخدمة</h6>
                                <div class="mb-3">
                                    <label class="form-label">التقييم (1-5)</label>
                                    <input type="number" name="provider_rating" class="form-control" min="1"
                                        max="5" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">تعليق (اختياري)</label>
                                    <textarea name="provider_comment" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">إتمام</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editResponseModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تعديل رد مقدم الخدمة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editResponseForm">
                    <div class="modal-body">
                        <input type="hidden" name="response_id" id="edit_response_id">
                        <div class="mb-3">
                            <label class="form-label">السعر المقترح</label>
                            <input type="number" name="proposed_price" id="edit_proposed_price" class="form-control" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">المدة المقترحة / موعد المعاينة</label>
                            <input type="text" name="proposed_timeline" id="edit_proposed_timeline" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الرسالة</label>
                            <textarea name="message" id="edit_response_message" class="form-control" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('dashboard-footer')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.accept-response-btn', function() {
                const requestId = $(this).data('request-id');
                const responseId = $(this).data('response-id');
                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: 'سيتم قبول هذا الرد وتفعيل المحادثة',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'نعم',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('service-requests.accept-response', [':id', ':responseId']) }}'
                                .replace(':id', requestId).replace(':responseId',
                                    responseId),
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'تم',
                                        text: response.message,
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        location.reload();
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'خطأ',
                                    text: xhr.responseJSON?.message || 'حدث خطأ'
                                });
                            }
                        });
                    }
                });
            });
            $(document).on('click', '.schedule-inspection-btn', function() {
                const requestId = $(this).data('request-id');
                const responseId = $(this).data('response-id');
                $('#schedule_service_request_id').val(requestId);
                $('#schedule_response_id').val(responseId);
            });
            $('#scheduleInspectionForm').on('submit', function(e) {
                e.preventDefault();
                const requestId = $('#schedule_service_request_id').val();
                const formData = $(this).serialize();
                $.ajax({
                    url: '{{ route('service-requests.schedule-inspection', ':id') }}'.replace(
                        ':id', requestId),
                    method: 'POST',
                    data: formData + '&_token={{ csrf_token() }}',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'تم',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: xhr.responseJSON?.message || 'حدث خطأ'
                        });
                    }
                });
            });
            $(document).on('click', '.complete-inspection-btn', function() {
                const inspectionId = $(this).data('inspection-id');
                $('#complete_inspection_id').val(inspectionId);
            });
            $('#completeInspectionForm').on('submit', function(e) {
                e.preventDefault();
                const requestId = {{ $serviceRequest->id }};
                const inspectionId = $('#complete_inspection_id').val();
                const formData = $(this).serialize();
                $.ajax({
                    url: '{{ route('service-requests.complete-inspection', [':id', ':inspectionId']) }}'
                        .replace(':id', requestId).replace(':inspectionId', inspectionId),
                    method: 'POST',
                    data: formData + '&_token={{ csrf_token() }}',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'تم',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: xhr.responseJSON?.message || 'حدث خطأ'
                        });
                    }
                });
            });
            $(document).on('click', '.agree-btn', function() {
                const requestId = $(this).data('request-id');
                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: 'سيتم تغيير حالة الطلب إلى "تم الاتفاق"',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'نعم',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('service-requests.agree', ':id') }}'.replace(
                                ':id', requestId),
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'تم',
                                        text: response.message,
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        location.reload();
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'خطأ',
                                    text: xhr.responseJSON?.message || 'حدث خطأ'
                                });
                            }
                        });
                    }
                });
            });
            $(document).on('click', '.complete-request-btn', function() {
                const requestId = $(this).data('request-id');
                $('#complete_request_id').val(requestId);
            });
            $(document).on('click', '.approve-request-btn', function() {
                const requestId = $(this).data('id');
                Swal.fire({
                    title: '{{ __('admin.approve_request') }}',
                    text: '{{ __('admin.approve_confirm_text') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{ __('admin.approve_request') }}',
                    cancelButtonText: '{{ __('admin.back') }}',
                    confirmButtonColor: '#ff9f43'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('service-requests.change-status', ':id') }}'
                                .replace(':id', requestId),
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                status: 'pending'
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'تم الاعتماد بنجاح',
                                        text: response.message,
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        location.reload();
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'خطأ',
                                    text: xhr.responseJSON?.message || 'حدث خطأ'
                                });
                            }
                        });
                    }
                });
            });
            $('#completeRequestForm').on('submit', function(e) {
                e.preventDefault();
                const requestId = $('#complete_request_id').val();
                const formData = $(this).serialize();
                $.ajax({
                    url: '{{ route('service-requests.complete', ':id') }}'.replace(':id',
                        requestId),
                    method: 'POST',
                    data: formData + '&_token={{ csrf_token() }}',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'تم',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: xhr.responseJSON?.message || 'حدث خطأ'
                        });
                    }
                });
            });
            $(document).on('click', '.edit-response-btn', function() {
                const id = $(this).data('id');
                const price = $(this).data('price');
                const timeline = $(this).data('timeline');
                const message = $(this).data('message');
                $('#edit_response_id').val(id);
                $('#edit_proposed_price').val(price);
                $('#edit_proposed_timeline').val(timeline);
                $('#edit_response_message').val(message);
            });
            $('#editResponseForm').on('submit', function(e) {
                e.preventDefault();
                const responseId = $('#edit_response_id').val();
                const requestId = '{{ $serviceRequest->id }}';
                const formData = $(this).serialize();
                $.ajax({
                    url: '{{ route('service-requests.update-response', [':id', ':responseId']) }}'
                        .replace(':id', requestId).replace(':responseId', responseId),
                    method: 'POST',
                    data: formData + '&_token={{ csrf_token() }}',
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#editResponseModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'تم التعديل',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: xhr.responseJSON?.message || 'حدث خطأ'
                        });
                    }
                });
            });
        });
    </script>
@endsection
