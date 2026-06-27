@extends('dashboard.layout.master')

@section('title', __('admin.available_requests'))

@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold">{{ __('admin.available_requests') }}</h4>
                <p class="text-muted">
                    @php
                        $membershipName = $membership->name;
                        if (is_array($membershipName)) {
                            $locale = app()->getLocale();
                            $membershipName = $membershipName[$locale] ?? $membershipName['ar'] ?? $membershipName['en'] ?? '-';
                        }
                    @endphp
                    {{ __('admin.service_provider') }}: <strong>{{ $membershipName }}</strong>
                </p>
            </div>
            <a href="{{ route('memberships.index') }}" class="btn btn-secondary">
                <i class="icon-base ti tabler-arrow-right"></i> {{ __('admin.back') }}
            </a>
        </div>

        <div class="row">
            @forelse($serviceRequests as $request)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                @php
                                    $categoryName = $request->category->name ?? '-';
                                    if (is_array($categoryName)) {
                                        $locale = app()->getLocale();
                                        $categoryName = $categoryName[$locale] ?? $categoryName['ar'] ?? $categoryName['en'] ?? '-';
                                    }
                                @endphp
                                {{ $categoryName }}
                            </h6>
                            @php
                                                $statuses = [
                                    'new' => ['label' => __('admin.status_new'), 'class' => 'badge bg-label-primary'],
                                    'pending_response' => ['label' => __('admin.status_pending_response'), 'class' => 'badge bg-label-warning'],
                                ];
                                $status = $statuses[$request->status] ?? ['label' => $request->status, 'class' => 'badge bg-label-secondary'];
                            @endphp
                            <span class="{{ $status['class'] }}">{{ $status['label'] }}</span>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <strong>{{ __('admin.service_seeker') }}:</strong>
                                <span>{{ $request->user->name ?? '-' }}</span>
                            </div>
                            
                            @if($request->description)
                                <div class="mb-2">
                                    <strong>{{ __('admin.description') }}:</strong>
                                    <p class="text-muted mb-0">{{ \Illuminate\Support\Str::limit($request->description, 100) }}</p>
                                </div>
                            @endif
                            
                            @if($request->location)
                                <div class="mb-2">
                                    <strong>{{ __('admin.location') }}:</strong>
                                    <span class="text-muted">{{ $request->location }}</span>
                                </div>
                            @endif
                            
                            @if($request->area)
                                <div class="mb-2">
                                    <strong>{{ __('admin.area') }}:</strong>
                                    <span class="text-muted">{{ $request->area }} {{ __('admin.m2') }}</span>
                                </div>
                            @endif
                            
                            @if($request->response_deadline)
                                <div class="mb-2">
                                    <strong>{{ __('admin.response_deadline') }}:</strong>
                                    <span class="{{ now()->isAfter($request->response_deadline) ? 'text-danger' : 'text-success' }}">
                                        {{ $request->response_deadline->format('Y-m-d H:i') }}
                                    </span>
                                </div>
                            @endif
                            
                            <div class="mb-2">
                                <strong>{{ __('admin.created_at') }}:</strong>
                                <span class="text-muted">{{ $request->created_at->format('Y-m-d H:i') }}</span>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('service-requests.show', $request->id) }}" class="btn btn-primary btn-sm w-100">
                                <i class="icon-base ti tabler-eye"></i> {{ __('admin.view') }}
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="icon-base ti tabler-info-circle"></i>
                        {{ __('admin.no_available_requests') }}
                    </div>
                </div>
            @endforelse
        </div>

        @if($serviceRequests->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $serviceRequests->links() }}
            </div>
        @endif
    </div>
@endsection
