@extends('dashboard.layout.master')
@section('title', __('admin.users'))
@section('dashboard-main')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ __('admin.platform_reports') }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin-panel') }}">{{ __('admin.home') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('admin.reports') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.reports.index') }}" method="GET" class="row align-items-end g-3">
                            <div class="col-md-4">
                                <label for="date_range" class="form-label">{{ __('admin.time_period') }}</label>
                                <select name="date_range" id="date_range" class="form-select">
                                    <option value="daily" {{ $dateRange == 'daily' ? 'selected' : '' }}>
                                        {{ __('admin.daily') }}</option>
                                    <option value="weekly" {{ $dateRange == 'weekly' ? 'selected' : '' }}>
                                        {{ __('admin.weekly') }}</option>
                                    <option value="monthly" {{ $dateRange == 'monthly' ? 'selected' : '' }}>
                                        {{ __('admin.monthly') }}</option>
                                    <option value="yearly" {{ $dateRange == 'yearly' ? 'selected' : '' }}>
                                        {{ __('admin.yearly') }}</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100"><i class="ri-filter-2-line me-1"></i>
                                    {{ __('admin.filter_report') }}</button>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('admin.reports.export', ['date_range' => $dateRange]) }}"
                                    class="btn btn-success w-100">
                                    <i class="ri-file-excel-2-line me-1"></i> {{ __('admin.export_excel') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="fw-medium text-muted mb-0">{{ __('admin.new_requests') }}</p>
                                <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value"
                                        data-target="{{ $data['newRequests'] }}">{{ $data['newRequests'] }}</span></h2>
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info rounded-circle fs-2">
                                        <i class="ri-file-list-3-line"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="fw-medium text-muted mb-0">{{ __('admin.completed_requests_label') }}</p>
                                <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value"
                                        data-target="{{ $data['completedRequests'] }}">{{ $data['completedRequests'] }}</span>
                                </h2>
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-success rounded-circle fs-2">
                                        <i class="ri-checkbox-circle-line"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="fw-medium text-muted mb-0">{{ __('admin.total_revenue') }}</p>
                                <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value"
                                        data-target="{{ $data['revenue'] }}">{{ $data['revenue'] }}</span> <small
                                        class="fs-13 text-muted">{{ __('admin.sar') }}</small></h2>
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-warning rounded-circle fs-2">
                                        <i class="ri-money-dollar-circle-line"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="fw-medium text-muted mb-0">{{ __('admin.new_users') }}</p>
                                <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value"
                                        data-target="{{ $data['newUsers'] }}">{{ $data['newUsers'] }}</span></h2>
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-primary rounded-circle fs-2">
                                        <i class="ri-user-add-line"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">{{ __('admin.top_providers') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-card">
                            <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                                <thead class="text-muted table-light">
                                    <tr>
                                        <th>{{ __('admin.provider_name') }}</th>
                                        <th>{{ __('admin.provider_type_col') }}</th>
                                        <th>{{ __('admin.accepted_offers') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data['topProviders'] as $provider)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-2">
                                                        @if($provider->getFirstMediaUrl('users'))
                                                            <img src="{{ $provider->getFirstMediaUrl('users') }}" alt=""
                                                                class="avatar-xs rounded-circle" />
                                                        @else
                                                            <div class="avatar-xs">
                                                                <span
                                                                    class="avatar-title rounded-circle bg-primary text-white">{{ substr($provider->name, 0, 1) }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1">{{ $provider->name }}</div>
                                                </div>
                                            </td>
                                            <td><span
                                                    class="badge badge-soft-primary">{{ $provider->provider_type == 'company' ? __('admin.company') : __('admin.individual') }}</span>
                                            </td>
                                            <td><span class="fw-semibold">{{ $provider->accepted_responses }}</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">{{ __('admin.no_data_period') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">{{ __('admin.top_seekers') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-card">
                            <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                                <thead class="text-muted table-light">
                                    <tr>
                                        <th>{{ __('admin.client_col') }}</th>
                                        <th>{{ __('admin.total_requests_col') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data['topSeekers'] as $seeker)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-2">
                                                        @if($seeker->getFirstMediaUrl('users'))
                                                            <img src="{{ $seeker->getFirstMediaUrl('users') }}" alt=""
                                                                class="avatar-xs rounded-circle" />
                                                        @else
                                                            <div class="avatar-xs">
                                                                <span
                                                                    class="avatar-title rounded-circle bg-success text-white">{{ substr($seeker->name, 0, 1) }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1">{{ $seeker->name }}</div>
                                                </div>
                                            </td>
                                            <td><span class="fw-semibold">{{ $seeker->total_requests }}</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center">{{ __('admin.no_data_period') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection