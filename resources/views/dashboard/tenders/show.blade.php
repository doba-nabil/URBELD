@extends('dashboard.layout.master')
@section('title', __('admin.tender_details'))
@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <h5 class="card-header d-flex justify-content-between border-b">
                {{ __('admin.tender_details_title', ['title' => $tender->title]) }}
                <a href="{{ route('tenders.index') }}" class="btn btn-secondary btn-sm">{{ __('admin.back_to_list') }}</a>
            </h5>
            
            @if(session('success'))
                <div class="alert alert-success m-3">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger m-3">{{ session('error') }}</div>
            @endif

            <div class="card-body mt-3">
                @if ($tender->ends_at)
                    @php
                        $deadline = \Carbon\Carbon::parse($tender->ends_at);
                        $isPast = $deadline->isPast();
                        $diff = $deadline->diffForHumans();
                    @endphp
                    <div class="alert {{ $isPast ? 'alert-danger' : 'alert-info' }} d-flex align-items-center mb-4">
                        <i class="ti tabler-clock me-2 fs-3"></i>
                        <div>
                            <h6 class="alert-heading mb-1 fw-bold">موعد انتهاء الرد</h6>
                            <span dir="ltr">{{ $deadline->format('Y-m-d h:i A') }}</span>
                            <span class="badge {{ $isPast ? 'bg-danger' : 'bg-info' }} ms-2">{{ $isPast ? 'انتهى ' . $diff : 'ينتهي ' . $diff }}</span>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-8">
                        <h6 class="fw-bold">{{ __('admin.tender_description') }}</h6>
                        <p class="text-muted">{{ $tender->description }}</p>

                        @if($tender->qualification_requirements)
                        <h6 class="fw-bold mt-4">{{ __('admin.qualification_requirements') }}</h6>
                        @if(is_array($tender->qualification_requirements))
                            <ul class="text-muted">
                                @foreach($tender->qualification_requirements as $req)
                                    <li>{{ $req }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">{{ $tender->qualification_requirements }}</p>
                        @endif
                        @endif

                        <h6 class="fw-bold mt-4">{{ __('admin.attached_files') }}</h6>
                        @if($tender->getMedia('tender_files')->count() > 0)
                            <ul class="list-group mb-3">
                            @foreach($tender->getMedia('tender_files') as $media)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $media->getCustomProperty('title', $media->name) }}
                                    <a href="{{ $media->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">{{ __('admin.download') }} <i class="bi bi-download"></i></a>
                                </li>
                            @endforeach
                            </ul>
                        @else
                            <p class="text-muted">{{ __('admin.no_attached_files') }}</p>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <div class="bg-light p-3 rounded">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><strong>{{ __('admin.tender_status') }}</strong> 
                                    @if($tender->status === \App\Models\Tender::STATUS_PENDING_REVIEW)
                                        <span class="badge bg-warning">{{ __('admin.pending_review') }}</span>
                                    @elseif($tender->status === \App\Models\Tender::STATUS_ACTIVE)
                                        <span class="badge bg-success">{{ __('admin.approved_active') }}</span>
                                    @elseif($tender->status === \App\Models\Tender::STATUS_CLOSED)
                                        <span class="badge bg-danger">{{ __('admin.closed') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $tender->status }}</span>
                                    @endif
                                </li>
                                <li class="mb-2"><strong>{{ __('admin.requester') }}</strong> <a href="{{ route('users.show', $tender->user_id) }}">{{ $tender->user->name ?? __('admin.unknown') }}</a></li>
                                <li class="mb-2"><strong>{{ __('admin.category') }}</strong> {{ $tender->category->name ?? __('admin.not_specified') }}</li>
                                <li class="mb-2"><strong>{{ __('admin.city') }}</strong> {{ $tender->city->name ?? __('admin.not_specified') }}</li>
                                <li class="mb-2"><strong>{{ __('admin.added_date') }}</strong> {{ $tender->created_at->format('Y-m-d H:i') }}</li>
                                <li class="mb-2"><strong>{{ __('admin.closing_date') }}</strong> {{ $tender->ends_at ? $tender->ends_at->format('Y-m-d H:i') : __('admin.not_specified') }}</li>
                                @if($tender->budget)
                                <li class="mb-2"><strong>{{ __('admin.estimated_budget') }}</strong> {{ number_format($tender->budget) }} {{ __('admin.sar') }}</li>
                                @endif
                                <li class="mb-2"><strong>{{ __('admin.type') }}</strong> {{ $tender->project_type == 'engineering' ? __('admin.engineering_plans') : __('admin.other') }}</li>
                                <li><strong>{{ __('admin.urgent') }}</strong> {!! $tender->is_urgent ? '<span class="text-danger fw-bold">'.__('admin.yes').'</span>' : __('admin.no') !!}</li>
                            </ul>
                        </div>

                        <!-- Action Buttons -->
                        @if($tender->status === \App\Models\Tender::STATUS_PENDING_REVIEW)
                        <div class="mt-4 d-grid gap-2">
                            <form action="{{ route('tenders.approve', $tender->id) }}" method="POST" id="approveForm">
                                @csrf
                                <button type="button" class="btn btn-success w-100" onclick="confirmAction('approveForm', '{{ __('admin.confirm_approve_tender') }}', 'success')">
                                    <i class="ti tabler-check"></i> {{ __('admin.approve_publish_tender') }}
                                </button>
                            </form>
                            
                            <form action="{{ route('tenders.reject', $tender->id) }}" method="POST" id="rejectForm">
                                @csrf
                                <button type="button" class="btn btn-danger w-100" onclick="confirmAction('rejectForm', '{{ __('admin.confirm_reject_tender') }}', 'error')">
                                    <i class="ti tabler-x"></i> {{ __('admin.reject_tender') }}
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Bids / Applications Section if active/closed -->
        @if($tender->status !== \App\Models\Tender::STATUS_PENDING_REVIEW)
        <div class="card mt-4">
            <h5 class="card-header border-b">{{ __('admin.supplier_offers_count', ['count' => $tender->applications->count()]) }}</h5>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('admin.offer_provider') }}</th>
                            <th>{{ __('admin.offer_value') }}</th>
                            <th>{{ __('admin.execution_duration') }}</th>
                            <th>{{ __('admin.submission_date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tender->applications as $app)
                        <tr>
                            <td><a href="{{ route('users.show', $app->user_id) }}">{{ $app->user->name ?? __('admin.unknown') }}</a></td>
                            <td>{{ number_format($app->price) }} {{ __('admin.sar') }}</td>
                            <td>{{ $app->delivery_days }} {{ __('admin.days') }}</td>
                            <td>{{ $app->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">{{ __('admin.no_offers_yet') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
@endsection

@section('dashboard-footer')
@parent
<script>
function confirmAction(formId, message, icon) {
    Swal.fire({
        title: 'تأكيد العملية',
        text: message,
        icon: icon == 'error' ? 'warning' : 'question',
        showCancelButton: true,
        confirmButtonColor: icon == 'error' ? '#d33' : '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'نعم، متأكد!',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}
</script>
@endsection
