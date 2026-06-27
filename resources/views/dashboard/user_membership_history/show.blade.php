@extends('dashboard.layout.master')
@section('title', __('admin.user_membership_history'))
@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h5 class="card-header">
                {{__('admin.user_membership_history')}}
                <a href="{{ route('user-membership-history.index') }}" class="btn btn-secondary btn-sm float-end">
                    <i class="icon-base ti tabler-arrow-right"></i> {{ __('admin.back') }}
                </a>
            </h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('admin.user') }}:</strong>
                        <p>{{ $history->user->name ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('admin.membership') }}:</strong>
                        <p>
                            @php
                                $membershipName = $history->membership->name ?? '-';
                                if (is_array($membershipName)) {
                                    $locale = app()->getLocale();
                                    $membershipName = $membershipName[$locale] ?? $membershipName['ar'] ?? $membershipName['en'] ?? '-';
                                }
                            @endphp
                            {{ $membershipName }}
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('admin.price_paid') }}:</strong>
                        <p>{{ number_format($history->price_paid, 2) }} {{ __('admin.currency') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('admin.status') }}:</strong>
                        <p>
                            @php
                                $statuses = [
                                    'active' => ['label' => __('admin.active'), 'class' => 'badge bg-label-success'],
                                    'expired' => ['label' => __('admin.expired'), 'class' => 'badge bg-label-danger'],
                                    'cancelled' => ['label' => __('admin.cancelled'), 'class' => 'badge bg-label-secondary'],
                                ];
                                $status = $statuses[$history->status] ?? ['label' => $history->status, 'class' => 'badge bg-label-secondary'];
                            @endphp
                            <span class="{{ $status['class'] }}">{{ $status['label'] }}</span>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('admin.started_at') }}:</strong>
                        <p>{{ $history->started_at ? $history->started_at->format('Y-m-d H:i') : '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('admin.expires_at') }}:</strong>
                        <p class="{{ $history->expires_at && now()->isAfter($history->expires_at) ? 'text-danger' : 'text-success' }}">
                            {{ $history->expires_at ? $history->expires_at->format('Y-m-d H:i') : '-' }}
                        </p>
                    </div>
                    @if($history->notes)
                    <div class="col-12 mb-3">
                        <strong>{{ __('admin.notes') }}:</strong>
                        <p>{{ $history->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
