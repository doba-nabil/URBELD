@extends('website.layouts.profile')

@section('title', 'الطلبات الواردة')

@push('css')
    <style>
        .request-card {
            border-right: 4px solid var(--primary);
            transition: transform 0.2s;
            background: #fff;
        }

        .request-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
        }

        .timer-badge {
            font-family: monospace;
            font-size: 1.1em;
        }

        .timer-danger {
            color: #dc3545;
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }
    </style>
@endpush

@section('profile-content')
    <div class="about-me-section">
        <div class="container">
            <h2 class="about-me-title">الطلبات الواردة الجديدة</h2>

            @forelse($responses as $response)
                @php
                    $request = $response->serviceRequest;
                    // Calculate remaining time
                    $createdAt = \Carbon\Carbon::parse($response->created_at);
                    $deadline = $createdAt->copy()->addHours(48);
                    // Only show if deadline is in the future
                    $isExpired = now()->isAfter($deadline);
                @endphp

                @if (!$isExpired && $request)
                    <div class="card shadow-sm mb-3 border-0 request-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0 text-primary">
                                    <i class="bi bi-tag-fill me-1"></i>
                                    {{ $request->category->name ?? 'قسم عام' }}
                                    @if ($request->subCategory)
                                        - {{ $request->subCategory->name }}
                                    @endif
                                </h6>
                                <span class="badge bg-warning text-dark timer-badge" id="timer-{{ $response->id }}"
                                    data-deadline="{{ $deadline->toIso8601String() }}">
                                    <i class="bi bi-clock-history"></i> جاري الحساب...
                                </span>
                            </div>
                            <h5 class="fw-bold mb-3 mt-3">العنوان: {{ $request->location }}</h5>

                            <p class="text-muted line-clamp-2">{{ Str::limit($request->description, 100) }}</p>

                            <div class="row align-items-center mt-4">
                                <div class="col-md-6 mb-2 mb-md-0">
                                    <small class="text-muted"><i class="bi bi-person-circle"></i> العميل:
                                        <a href="{{ route('member.public', $request->user->id) }}" class="text-decoration-none text-muted fw-bold">
                                            {{ $request->user->name ?? 'غير معروف' }}
                                        </a>
                                    </small>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <!-- Action Buttons -->
                                    <a href="{{ route('requests.show', $request->id) }}" target="_blank"
                                        class="btn btn-info btn-sm text-white px-3 me-2">
                                        <i class="bi bi-eye"></i> التفاصيل
                                    </a>
                                    <button type="button" class="btn btn-primary btn-sm px-4" data-bs-toggle="modal"
                                        data-bs-target="#acceptModal{{ $response->id }}">
                                        قبول وتقديم عرض
                                    </button>

                                    <form action="{{ route('provider.requests.reject', $response->id) }}" method="POST"
                                        class="d-inline form-reject">
                                        @csrf
                                        <button type="button"
                                            class="btn btn-outline-danger btn-sm px-4 ms-2 btn-reject">الاعتذار</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Accept Modal -->
                    <div class="modal fade" id="acceptModal{{ $response->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">تقديم عرض على الطلب</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('provider.requests.accept', $response->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">السعر المقترح للمعاينة الأولية (ر.س) - اختياري</label>
                                            <input type="number" name="proposed_price" class="form-control"
                                                placeholder="مثال: 5000">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">المدة المتوقعة للتنفيذ - اختياري</label>
                                            <input type="text" name="proposed_timeline" class="form-control"
                                                placeholder="مثال: أسبوعين">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">رسالة للعميل - اختياري</label>
                                            <textarea name="message" class="form-control" rows="3" placeholder="اكتب عرضك الفني وتشجيع للعميل لقبولك..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">إلغاء</button>
                                        <button type="submit" class="btn btn-primary">إرسال العرض</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                    <h5 class="mt-3 text-muted">لا توجد طلبات واردة جديدة.</h5>
                </div>
            @endforelse

            {{-- طلبات التوريد المفتوحة - للموردين فقط --}}
            @if(isset($supplyRequests) && $supplyRequests->isNotEmpty())
            <hr class="my-4">
            <h4 class="fw-bold mb-4 mt-3" style="color: #d97706;">
                <i class="bi bi-box-seam me-2"></i> طلبات التوريد الواردة
                <span class="badge ms-2" style="background: #d97706; font-size: 0.8rem;">{{ $supplyRequests->count() }}</span>
            </h4>
            @foreach($supplyRequests as $supplyReq)
                @php
                    $alreadyApplied = $supplyReq->responses->isNotEmpty();
                @endphp
                <div class="card shadow-sm mb-3 border-0 request-card" style="border-right-color: #d97706 !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="badge mb-2" style="background: rgba(217,119,6,0.12); color: #d97706; border: 1px solid #d97706;">
                                    <i class="bi bi-box-seam me-1"></i> طلب توريد
                                </span>
                                <h5 class="fw-bold mb-1">{{ $supplyReq->title }}</h5>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $supplyReq->city->name ?? '' }}
                                    @if($supplyReq->delivery_date)
                                        &nbsp;|&nbsp; <i class="bi bi-calendar me-1"></i> التسليم: {{ $supplyReq->delivery_date->format('Y-m-d') }}
                                    @endif
                                </p>
                            </div>
                            @if($alreadyApplied)
                                <span class="badge bg-success">تم تقديم عرض</span>
                            @else
                                <span class="badge bg-warning text-dark">جديد</span>
                            @endif
                        </div>

                        <p class="text-muted mt-2 mb-3">{{ Str::limit($supplyReq->description, 120) }}</p>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">
                                <i class="bi bi-person-circle me-1"></i>
                                <a href="{{ route('member.public', $supplyReq->user->id) }}" class="text-decoration-none text-muted fw-bold">
                                    {{ $supplyReq->user->name ?? 'غير معروف' }}
                                </a>
                                &nbsp;|&nbsp; {{ $supplyReq->created_at->diffForHumans() }}
                            </small>
                            <div>
                                <a href="{{ route('website.supply-requests.show', $supplyReq->id) }}" class="btn btn-info btn-sm text-white px-3 me-2">
                                    <i class="bi bi-eye"></i> التفاصيل
                                </a>
                                @if(!$alreadyApplied)
                                    <button type="button" class="btn btn-sm px-4" style="background: #d97706; color: white;"
                                        data-bs-toggle="modal" data-bs-target="#supplyApplyModal{{ $supplyReq->id }}">
                                        تقديم عرض توريد
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal تقديم عرض على طلب التوريد --}}
                @if(!$alreadyApplied)
                <div class="modal fade" id="supplyApplyModal{{ $supplyReq->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">تقديم عرض توريد</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('website.supply-requests.storeApplication', $supplyReq->id) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">السعر المقترح (ر.س) <span class="text-danger">*</span></label>
                                        <input type="number" name="proposed_price" class="form-control" min="0" step="0.01" required placeholder="مثال: 15000">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">ملاحظات - اختياري</label>
                                        <textarea name="notes" class="form-control" rows="3" placeholder="أي تفاصيل إضافية عن عرضك..."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                    <button type="submit" class="btn" style="background: #d97706; color: white;">إرسال العرض</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
            @endif

            <!-- Tabs for Active and Completed -->
            <ul class="nav nav-pills mb-4 mt-5 custom-nav-pills form-floating-custom" id="provider-requests-tab"
                role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="provider-active-tab" data-bs-toggle="pill"
                        data-bs-target="#provider-active" type="button" role="tab" aria-controls="provider-active"
                        aria-selected="true">الطلبات النشطة (قيد التنفيذ)</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="provider-completed-tab" data-bs-toggle="pill"
                        data-bs-target="#provider-completed" type="button" role="tab"
                        aria-controls="provider-completed" aria-selected="false">الطلبات المكتملة</button>
                </li>
            </ul>

            <div class="tab-content" id="provider-requests-tabContent">

                <!-- Active Requests Tab -->
                <div class="tab-pane fade show active" id="provider-active" role="tabpanel"
                    aria-labelledby="provider-active-tab">
                    @php
                        $activeFiltered = collect($activeResponses ?? [])->filter(function ($response) {
                            $status = $response->serviceRequest->status ?? '';
                            return !in_array($status, ['completed', 'work_completed', 'cancelled']);
                        });
                    @endphp
                    @forelse($activeFiltered as $response)
                        @php
                            $request = $response->serviceRequest;
                        @endphp
                        @if ($request)
                            <div class="card shadow-sm mb-3 border-0"
                                style="border-right: 4px solid var(--primary) !important; border-radius: 10px;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0 text-success">
                                            <i class="bi bi-check-circle-fill me-1"></i>
                                            {{ $request->category->name ?? 'قسم عام' }}
                                            @if ($request->subCategory)
                                                - {{ $request->subCategory->name }}
                                            @endif
                                        </h6>
                                        <span class="badge" style="background-color: var(--secondary); color: #fff;">
                                            {{ __($request->status) }}
                                        </span>
                                    </div>
                                    <h5 class="fw-bold mb-3 mt-3">العنوان: {{ $request->location }}</h5>

                                    <p class="text-muted">{{ $request->description }}</p>

                                    @if ($request->inspection_date)
                                        <div class="alert alert-info mt-3 p-2">
                                            <i class="bi bi-calendar-check me-2"></i>
                                            <strong>موعد المعاينة:</strong>
                                            {{ \Carbon\Carbon::parse($request->inspection_date)->format('Y-m-d h:i A') }}
                                        </div>
                                    @endif

                                    <div class="row align-items-center mt-4">
                                        <div class="col-md-6 mb-2 mb-md-0">
                                            <small class="text-muted"><i class="bi bi-person-circle"></i> العميل:
                                                {{ $request->user->name ?? 'غير معروف' }}</small>
                                        </div>
                                        <div class="col-md-6 text-md-end">
                                            <a href="{{ route('requests.show', $request->id) }}" target="_blank"
                                                class="btn btn-info btn-sm text-white px-3 me-1 mb-1">
                                                <i class="bi bi-eye"></i> التفاصيل
                                            </a>
                                            {{-- Chat link with the seeker --}}
                                            @php
                                                $requestChat = \App\Models\Chat::where(
                                                    'service_request_id',
                                                    $request->id,
                                                )
                                                    ->whereHas('participants', function ($q) {
                                                        $q->where('users.id', auth()->id());
                                                    })
                                                    ->first();
                                            @endphp
                                            @if ($requestChat)
                                                <a href="{{ route('dashboard.chat.show', ['chat' => $requestChat->id]) }}"
                                                    class="btn btn-primary btn-sm px-3"><i class="bi bi-chat-dots"></i>
                                                    مراسلة العميل</a>
                                            @else
                                                <span class="btn btn-secondary btn-sm px-3 disabled"><i
                                                        class="bi bi-chat-dots"></i>
                                                    المحادثة غير متاحة بعد</span>
                                            @endif

                                            @if ($request->status === 'provider_accepted' && !$request->inspection_date)
                                                <button type="button" class="btn btn-sm px-3 ms-2 text-white"
                                                    style="background-color: var(--secondary);" data-bs-toggle="modal"
                                                    data-bs-target="#inspectionModal{{ $response->id }}">
                                                    <i class="bi bi-calendar-plus"></i> تحديد موعد المعاينة
                                                </button>
                                            @endif

                                            @if ($request->status === 'inspection_scheduled' || $request->status === 'provider_accepted')
                                                <!-- Final Agreement Button for Provider -->
                                                <form action="{{ route('requests.complete-work', $request->id) }}"
                                                    method="POST" class="d-inline form-confirm-work">
                                                    @csrf
                                                    <button type="button" class="btn btn-primary btn-sm ms-2 btn-confirm-work"><i
                                                            class="bi bi-check-all"></i> تأكيد الاتفاق / بدء العمل</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Inspection Modal -->
                            <div class="modal fade" id="inspectionModal{{ $response->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content text-wrap">
                                        <div class="modal-header">
                                            <h5 class="modal-title">تحديد موعد المعاينة الحية</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('provider.requests.schedule', $request->id) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body text-start">
                                                <div class="mb-3 text-wrap">
                                                    <label class="form-label">تاريخ ووقت المعاينة</label>
                                                    <input type="datetime-local" name="inspection_date"
                                                        class="form-control" required
                                                        min="{{ now()->format('Y-m-d\TH:i') }}">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">إلغاء</button>
                                                <button type="submit" class="btn btn-primary">تأكيد الموعد</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="alert alert-info text-center">لا توجد طلبات نشطة حالياً.</div>
                    @endforelse
                </div>

                <!-- Completed Requests Tab -->
                <div class="tab-pane fade" id="provider-completed" role="tabpanel"
                    aria-labelledby="provider-completed-tab">
                    @php
                        $completedFiltered = collect($activeResponses ?? [])->filter(function ($response) {
                            $status = $response->serviceRequest->status ?? '';
                            return in_array($status, ['completed', 'work_completed']);
                        });
                    @endphp
                    @forelse($completedFiltered as $response)
                        @php
                            $request = $response->serviceRequest;
                        @endphp
                        @if ($request)
                            <div class="card shadow-sm mb-3 border-0"
                                style="border-right: 4px solid var(--success) !important; border-radius: 10px;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0 text-success">
                                            <i class="bi bi-check-circle-fill me-1"></i>
                                            {{ $request->category->name ?? 'قسم عام' }}
                                            @if ($request->subCategory)
                                                - {{ $request->subCategory->name }}
                                            @endif
                                        </h6>
                                        <span class="badge" style="background-color: var(--success); color: #fff;">
                                            {{ __($request->status) }}
                                        </span>
                                    </div>
                                    <h5 class="fw-bold mb-3 mt-3">العنوان: {{ $request->location }}</h5>

                                    <p class="text-muted">{{ $request->description }}</p>

                                    <div class="row align-items-center mt-4">
                                        <div class="col-md-6 mb-2 mb-md-0">
                                            <small class="text-muted"><i class="bi bi-person-circle"></i> العميل:
                                                {{ $request->user->name ?? 'غير معروف' }}</small>
                                        </div>
                                        <div class="col-md-6 text-md-end">
                                            <a href="{{ route('requests.show', $request->id) }}" target="_blank"
                                                class="btn btn-info btn-sm text-white px-3 me-1 mb-1">
                                                <i class="bi bi-eye"></i> التفاصيل
                                            </a>

                                            @php
                                                $hasRated = \App\Models\Rating::where('rater_id', auth()->id())
                                                    ->where('service_request_id', $request->id)
                                                    ->exists();
                                            @endphp
                                            @if (!$hasRated && $request->status === 'work_completed')
                                                <button type="button" class="btn btn-sm btn-warning px-3 ms-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#ratingModal{{ $request->id }}">
                                                    <i class="bi bi-star-fill"></i> قيم العميل
                                                </button>
                                            @elseif($hasRated)
                                                <span class="badge bg-primary ms-2 p-2"><i class="bi bi-check"></i> تم
                                                    التقييم</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Rating Modal -->
                            @if (!$hasRated && $request->status === 'work_completed')
                                <div class="modal fade" id="ratingModal{{ $request->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content text-wrap">
                                            <div class="modal-header">
                                                <h5 class="modal-title">تقييم تجربتك مع العميل</h5>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('requests.rate', $request->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body text-start">
                                                    <div class="mb-3">
                                                        <label class="form-label">التقييم من 5</label>
                                                    <div class="star-rating-widget fs-2">
                                                        <input type="radio" id="score5-{{ $request->id }}" name="score" value="5" required />
                                                        <label for="score5-{{ $request->id }}" title="{{ __('website.excellent') }}"><i class="bi bi-star-fill"></i></label>
                                                        
                                                        <input type="radio" id="score4-{{ $request->id }}" name="score" value="4" />
                                                        <label for="score4-{{ $request->id }}" title="{{ __('website.very_good') }}"><i class="bi bi-star-fill"></i></label>
                                                        
                                                        <input type="radio" id="score3-{{ $request->id }}" name="score" value="3" />
                                                        <label for="score3-{{ $request->id }}" title="{{ __('website.good') }}"><i class="bi bi-star-fill"></i></label>
                                                        
                                                        <input type="radio" id="score2-{{ $request->id }}" name="score" value="2" />
                                                        <label for="score2-{{ $request->id }}" title="{{ __('website.acceptable') }}"><i class="bi bi-star-fill"></i></label>
                                                        
                                                        <input type="radio" id="score1-{{ $request->id }}" name="score" value="1" />
                                                        <label for="score1-{{ $request->id }}" title="{{ __('website.bad') }}"><i class="bi bi-star-fill"></i></label>
                                                    </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">ملاحظات (اختياري)</label>
                                                        <textarea name="comment" class="form-control" rows="3"
                                                            placeholder="كيف كانت تجربتك في التعامل مع هذا العميل؟"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">إلغاء</button>
                                                    <button type="submit" class="btn btn-warning">إرسال التقييم</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @empty
                        <div class="alert alert-info text-center">لا توجد طلبات مكتملة حالياً.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const timers = document.querySelectorAll('.timer-badge');

            function updateTimers() {
                const now = new Date().getTime();

                timers.forEach(timer => {
                    const deadlineIso = timer.getAttribute('data-deadline');
                    const deadline = new Date(deadlineIso).getTime();
                    const distance = deadline - now;

                    if (distance < 0) {
                        timer.innerHTML = '<i class="bi bi-x-circle"></i> اكتملت المهلة';
                        timer.classList.remove('bg-warning', 'text-dark');
                        timer.classList.add('bg-danger', 'text-white');
                        return;
                    }

                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    timer.innerHTML =
                        `<i class="bi bi-clock-history"></i> ${hours}س ${minutes}د ${seconds}ث`;

                    if (hours < 2) {
                        timer.classList.add('timer-danger');
                    }
                });
            }

            // Update every second
            setInterval(updateTimers, 1000);
            updateTimers(); // Initial call

            // SweetAlert for reject/delete buttons
            document.querySelectorAll('.btn-reject').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'هل أنت متأكد؟',
                        text: "لن تتمكن من التراجع عن هذا الإجراء وسيتم رفض الطلب نهائياً.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'نعم، قم بالرفض',
                        cancelButtonText: 'إلغاء'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // SweetAlert for confirm work
            document.querySelectorAll('.btn-confirm-work').forEach(button => {
                button.addEventListener('click', function(e) {
                    const form = this.closest('form');
                    Swal.fire({
                        title: 'تأكيد العمل',
                        text: 'هل أنت متأكد من تأكيد الاتفاق النهائي وبدء العمل؟',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#014D40',
                        cancelButtonColor: '#secondary',
                        confirmButtonText: 'نعم، ابدأ العمل',
                        cancelButtonText: 'إلغاء',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush
