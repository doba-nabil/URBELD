@extends('dashboard.layout.master')
@section('title', __('admin.admin-panel'))
@section('dashboard-main')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-6">
            <!-- Website Analytics -->
            <div class="col-xl-6 col">
                <div class="swiper-container swiper-container-horizontal swiper swiper-card-advance-bg"
                    id="swiper-with-pagination-cards">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="text-white mb-0">{{ __('admin.service_requests_statistics') }}</h5>
                                </div>
                                <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1 pt-md-9">
                                    <h6 class="text-white mt-0 mt-md-3 mb-4">{{ __('admin.requests_inside_site') }}</h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <ul class="list-unstyled mb-0">
                                                <li class="d-flex mb-4 align-items-center">
                                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg">
                                                        {{ number_format($newRequests) }}</p>
                                                    <p class="mb-0">{{ __('admin.new_requests') }}</p>
                                                </li>
                                                <li class="d-flex align-items-center">
                                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg">
                                                        {{ number_format($pendingResponseRequests) }}</p>
                                                    <p class="mb-0">{{ __('admin.pending_response') }}</p>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-6">
                                            <ul class="list-unstyled mb-0">
                                                <li class="d-flex mb-4 align-items-center">
                                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg">
                                                        {{ number_format($acceptedRequests) }}</p>
                                                    <p class="mb-0">{{ __('admin.accepted') }}</p>
                                                </li>
                                                <li class="d-flex align-items-center">
                                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg">
                                                        {{ number_format($completedRequests) }}</p>
                                                    <p class="mb-0">{{ __('admin.completed') }}</p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
                                    <img src="{{ asset('dashboard') }}/assets/img/illustrations/card-website-analytics-2.png"
                                        alt="إحصائيات الطلبات" height="150" class="card-website-analytics-img" />
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="text-white mb-0">{{ __('admin.users_statistics') }}</h5>
                                </div>
                                <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1 pt-md-9">
                                    <h6 class="text-white mt-0 mt-md-3 mb-4">{{ __('admin.users_inside_site') }}</h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <ul class="list-unstyled mb-0">
                                                <li class="d-flex mb-4 align-items-center">
                                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg">
                                                        {{ number_format($serviceSeekers) }}</p>
                                                    <p class="mb-0">{{ __('admin.service_seekers') }}</p>
                                                </li>
                                                <li class="d-flex align-items-center">
                                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg">
                                                        {{ number_format($serviceProviders) }}</p>
                                                    <p class="mb-0">{{ __('admin.service_providers') }}</p>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-6">
                                            <ul class="list-unstyled mb-0">
                                                <li class="d-flex mb-4 align-items-center">
                                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg">
                                                        {{ number_format($individualProviders) }}</p>
                                                    <p class="mb-0">{{ __('admin.individual') }}</p>
                                                </li>
                                                <li class="d-flex align-items-center">
                                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg">
                                                        {{ number_format($companyProviders) }}</p>
                                                    <p class="mb-0">{{ __('admin.company') }}</p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
                                    <img src="{{ asset('dashboard') }}/assets/img/illustrations/card-website-analytics-3.png"
                                        alt="إحصائيات المستخدمين" height="150" class="card-website-analytics-img" />
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="text-white mb-0">{{ __('admin.suppliers_statistics') }}</h5>
                                </div>
                                <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1 pt-md-9">
                                    <h6 class="text-white mt-0 mt-md-3 mb-4">{{ __('admin.suppliers_statistics') }}</h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <ul class="list-unstyled mb-0">
                                                <li class="d-flex mb-4 align-items-center">
                                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg">
                                                        {{ number_format($totalSuppliers) }}</p>
                                                    <p class="mb-0">{{ __('admin.suppliers') }}</p>
                                                </li>
                                                <li class="d-flex align-items-center">
                                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg">
                                                        {{ number_format($totalProducts) }}</p>
                                                    <p class="mb-0">{{ __('admin.supplier_products') }}</p>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-6">
                                            <ul class="list-unstyled mb-0">
                                                <li class="d-flex mb-4 align-items-center">
                                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg">
                                                        {{ number_format($totalSupplierOffers) }}</p>
                                                    <p class="mb-0">{{ __('admin.supplier_offers') }}</p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
                                    <img src="{{ asset('dashboard') }}/assets/img/illustrations/card-website-analytics-1.png"
                                        alt="إحصائيات الموردين" height="150" class="card-website-analytics-img" />
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="text-white mb-0">{{ __('admin.tenders') }}</h5>
                                </div>
                                <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1 pt-md-9">
                                    <h6 class="text-white mt-0 mt-md-3 mb-4">{{ __('admin.tenders') }}</h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <ul class="list-unstyled mb-0">
                                                <li class="d-flex mb-4 align-items-center">
                                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg">
                                                        {{ number_format($totalTenders) }}</p>
                                                    <p class="mb-0">{{ __('admin.all') }}</p>
                                                </li>
                                                <li class="d-flex align-items-center">
                                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg">
                                                        {{ number_format($activeTenders) }}</p>
                                                    <p class="mb-0">{{ __('admin.active') ?? 'نشطة' }}</p>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-6">
                                            <ul class="list-unstyled mb-0">
                                                <li class="d-flex mb-4 align-items-center">
                                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg">
                                                        {{ number_format($pendingTenders) }}</p>
                                                    <p class="mb-0">{{ __('admin.under_review') }}</p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
                                    <img src="{{ asset('dashboard') }}/assets/img/illustrations/card-website-analytics-2.png"
                                        alt="إحصائيات المناقصات" height="150" class="card-website-analytics-img" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
            <!--/ Website Analytics -->
            <div class="col-xl-3 col-sm-6">
                <div class="card h-100">
                    <div class="card-header pb-0">
                        <h5 class="mb-3 card-title">{{ __('admin.monthly_requests') }}</h5>
                        <p class="mb-0 text-body">{{ __('admin.requests_this_month') }}</p>
                        <h4 class="mb-0">{{ number_format($monthlyRequests) }}</h4>
                        @if($lastMonthRequests > 0)
                            <small class="text-{{ $monthlyRequests >= $lastMonthRequests ? 'success' : 'danger' }}">
                                <i class="ti ti-chevron-{{ $monthlyRequests >= $lastMonthRequests ? 'up' : 'down' }}"></i>
                                {{ round((($monthlyRequests - $lastMonthRequests) / $lastMonthRequests) * 100) }}%
                                {{ __('admin.vs_last_month') }}
                            </small>
                        @endif
                    </div>
                    <div class="card-body px-0">
                        <div id="averageDailySales"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card h-100">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <p class="mb-0 text-body">{{ __('admin.requests_vs_responses') }}</p>
                            <p class="card-text fw-medium text-success">+{{ $totalResponses > 0 ? round(($acceptedResponses / $totalResponses) * 100) : 0 }}%</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="d-flex gap-2 align-items-center mb-2">
                                    <span class="badge bg-label-info p-1 rounded">
                                        <i class="icon-base ti tabler-file-text icon-sm"></i>
                                    </span>
                                    <p class="mb-0">{{ __('admin.requests') }}</p>
                                </div>
                                <h5 class="mb-0 pt-1">
                                    {{ $totalRequests > 0 ? round(($totalRequests / ($totalRequests + $totalResponses)) * 100) : 0 }}%
                                </h5>
                                <small class="text-body-secondary">{{ number_format($totalRequests) }}</small>
                            </div>
                            <div class="col-4">
                                <div class="divider divider-vertical">
                                    <div class="divider-text">
                                        <span class="badge-divider-bg bg-label-secondary">VS</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="d-flex gap-2 justify-content-end align-items-center mb-2">
                                    <p class="mb-0">{{ __('admin.responses') }}</p>
                                    <span class="badge bg-label-primary p-1 rounded">
                                        <i class="icon-base ti tabler-message-dots icon-sm"></i>
                                    </span>
                                </div>
                                <h5 class="mb-0 pt-1">
                                    {{ $totalResponses > 0 ? round(($totalResponses / ($totalRequests + $totalResponses)) * 100) : 0 }}%
                                </h5>
                                <small class="text-body-secondary">{{ number_format($totalResponses) }}</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-6">
                            <div class="progress w-100" style="height: 10px">
                                <div class="progress-bar bg-info" style="width: {{ $totalRequests > 0 ? round(($totalRequests / ($totalRequests + $totalResponses)) * 100) : 0 }}%" role="progressbar"></div>
                                <div class="progress-bar bg-primary" style="width: {{ $totalResponses > 0 ? round(($totalResponses / ($totalRequests + $totalResponses)) * 100) : 0 }}%" role="progressbar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="row">
                    <div class="col-xl-4 col-sm-12">
                        <div class="card h-100 px-3">
                            <div class="card-header pb-0">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0 card-title">{{ __('admin.service_requests') }}</h5>
                                    <span class="badge bg-label-primary">
                                        <i class="icon-base ti tabler-file-text icon-sm"></i>
                                    </span>
                                </div>
                                <p class="mb-0 text-body">{{ __('admin.total_requests') }}</p>
                                <h4 class="mb-0 mt-2">{{ number_format($totalRequests) }}</h4>
                            </div>
                            <div class="card-body px-0">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <small class="text-body-secondary">{{ __('admin.this_month') }}</small>
                                        <p class="mb-0 fw-medium">{{ number_format($monthlyRequests) }}</p>
                                    </div>
                                    <div class="badge bg-label-success">
                                        +{{ $lastMonthRequests > 0 ? round((($monthlyRequests - $lastMonthRequests) / $lastMonthRequests) * 100) : 0 }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-12">
                        <div class="card h-100 px-3">
                            <div class="card-header pb-0">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0 card-title">{{ __('admin.users') }}</h5>
                                    <span class="badge bg-label-success">
                                        <i class="icon-base ti tabler-user-plus icon-sm"></i>
                                    </span>
                                </div>
                                <p class="mb-0 text-body">{{ __('admin.total_users') }}</p>
                                <h4 class="mb-0 mt-2">{{ number_format($totalUsers) }}</h4>
                            </div>
                            <div class="card-body px-0">
                                <div class="row g-3">
                                    <div class="col-4">
                                        <small class="text-body-secondary">{{ __('admin.this_month') }}</small>
                                        <p class="mb-0 fw-medium">{{ number_format($monthlyUsers) }}</p>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-body-secondary">{{ __('admin.this_year') }}</small>
                                        <p class="mb-0 fw-medium">{{ number_format($yearlyUsers) }}</p>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-body-secondary">{{ __('admin.active') }}</small>
                                        <p class="mb-0 fw-medium">{{ number_format($activeUsers) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-12">
                        <div class="card h-100 px-3">
                            <div class="card-header pb-0">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0 card-title">{{ __('admin.visitors') ?? 'الزوار' }}</h5>
                                    <span class="badge bg-label-info">
                                        <i class="icon-base ti tabler-eye icon-sm"></i>
                                    </span>
                                </div>
                                <p class="mb-0 text-body">{{ __('admin.total_visitors') ?? 'إجمالي الزوار' }}</p>
                                <h4 class="mb-0 mt-2">{{ number_format($totalVisitors ?? 0) }}</h4>
                            </div>
                            <div class="card-body px-0">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <small class="text-body-secondary">{{ __('admin.today_visitors') ?? 'زوار اليوم' }}</small>
                                        <p class="mb-0 fw-medium">{{ number_format($todayVisitors ?? 0) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="row">
                    <div class="col-xl-6 col-sm-6">
                        <div class="card h-100 px-3">
                            <div class="card-header pb-0">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0 card-title">{{ __('admin.responses') }}</h5>
                                    <span class="badge bg-label-warning">
                                        <i class="icon-base ti tabler-message-dots icon-sm"></i>
                                    </span>
                                </div>
                                <p class="mb-0 text-body">{{ __('admin.total_responses') }}</p>
                                <h4 class="mb-0 mt-2">{{ number_format($totalResponses) }}</h4>
                            </div>
                            <div class="card-body px-0">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <small class="text-body-secondary">{{ __('admin.pending') }}</small>
                                        <p class="mb-0 fw-medium">{{ number_format($pendingResponses) }}</p>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-body-secondary">{{ __('admin.accepted') }}</small>
                                        <p class="mb-0 fw-medium">{{ number_format($acceptedResponses) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-sm-6">
                        <div class="card h-100 px-3">
                            <div class="card-header pb-0">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0 card-title">{{ __('admin.agreed_revenues') ?? 'الإيرادات المتفق عليها' }}</h5>
                                    <span class="badge bg-label-success">
                                        <i class="icon-base ti tabler-currency-dollar icon-sm"></i>
                                    </span>
                                </div>
                                <p class="mb-0 text-body">{{ __('admin.total_contracts_value') ?? 'إجمالي قيمة العقود' }}</p>
                                <h4 class="mb-0 mt-2">{{ number_format($totalRevenue, 2) }} <small>{{ __('website.rs') }}</small></h4>
                            </div>
                            <div class="card-body px-0">
                                <div class="d-flex align-items-center">
                                    <div class="badge bg-label-success me-2">
                                        <i class="ti ti-chart-bar"></i>
                                    </div>
                                    <small class="text-muted">{{ __('admin.based_on_accepted_responses') ?? 'مبني على الردود المقبولة' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header pb-0 d-flex justify-content-between">
                        <div class="card-title mb-0">
                            <h5 class="mb-1">{{ __('admin.requests_reports') }}</h5>
                            <p class="card-subtitle">{{ __('admin.weekly_overview') }}</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center g-md-8">
                            <div class="col-12 col-md-5 d-flex flex-column">
                                <div class="d-flex gap-2 align-items-center mb-3 flex-wrap">
                                    <h2 class="mb-0">{{ number_format($monthlyRequests) }}</h2>
                                    <div class="badge rounded bg-label-success">
                                        +{{ $lastMonthRequests > 0 ? round((($monthlyRequests - $lastMonthRequests) / $lastMonthRequests) * 100) : 0 }}%
                                    </div>
                                </div>
                                <small class="text-body">{{ __('admin.vs_last_month') }}</small>
                            </div>
                            <div class="col-12 col-md-7 ps-xl-8">
                                <div id="weeklyEarningReports"></div>
                            </div>
                        </div>
                        <div class="border rounded p-5 mt-5">
                            <div class="row gap-4 gap-sm-0">
                                <div class="col-12 col-sm-4">
                                    <div class="d-flex gap-2 align-items-center">
                                        <div class="badge rounded bg-label-primary p-1">
                                            <i class="icon-base ti tabler-file-text icon-18px"></i>
                                        </div>
                                        <h6 class="mb-0 fw-normal">{{ __('admin.total_requests') }}</h6>
                                    </div>
                                    <h4 class="my-2">{{ number_format($totalRequests) }}</h4>
                                    <div class="progress w-75" style="height: 4px">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $totalRequests > 0 ? min(100, ($totalRequests / 1000) * 100) : 0 }}%"></div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="d-flex gap-2 align-items-center">
                                        <div class="badge rounded bg-label-info p-1">
                                            <i class="icon-base ti tabler-check icon-18px"></i>
                                        </div>
                                        <h6 class="mb-0 fw-normal">{{ __('admin.completed') }}</h6>
                                    </div>
                                    <h4 class="my-2">{{ number_format($completedRequests) }}</h4>
                                    <div class="progress w-75" style="height: 4px">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ $totalRequests > 0 ? round(($completedRequests / $totalRequests) * 100) : 0 }}%"></div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="d-flex gap-2 align-items-center">
                                        <div class="badge rounded bg-label-danger p-1">
                                            <i class="icon-base ti tabler-clock icon-18px"></i>
                                        </div>
                                        <h6 class="mb-0 fw-normal">{{ __('admin.pending') }}</h6>
                                    </div>
                                    <h4 class="my-2">{{ number_format($pendingResponseRequests) }}</h4>
                                    <div class="progress w-75" style="height: 4px">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $totalRequests > 0 ? round(($pendingResponseRequests / $totalRequests) * 100) : 0 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                @include('dashboard.partials.activity_timeline')
            </div>
            <!-- Recent Requests & Top Providers -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('admin.recent_requests') }}</h5>
                        <a href="{{ route('service-requests.index') }}" class="btn btn-sm btn-outline-primary">
                            {{ __('admin.view_all') }}
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('admin.user') }}</th>
                                        <th>{{ __('admin.category') }}</th>
                                        <th>{{ __('admin.status') }}</th>
                                        <th>{{ __('admin.date') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentRequests as $request)
                                        <tr>
                                            <td>
                                                <a href="{{ route('users.show', $request->user_id) }}">
                                                    {{ $request->user->name }}
                                                </a>
                                            </td>
                                            <td>{{ $request->category->name ?? '-' }}</td>
                                            <td>
                                                <span class="badge bg-label-{{ $request->status == 'completed' ? 'success' : ($request->status == 'new' ? 'info' : 'warning') }}">
                                                    {{ __('admin.' . $request->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $request->created_at->format('Y-m-d') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">{{ __('admin.no_data') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12 col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('admin.top_service_providers_performance') }}</h5>
                        <a href="{{ route('memberships.index') }}" class="btn btn-sm btn-outline-primary">
                            {{ __('admin.view_all') }}
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('admin.name') }}</th>
                                        <th>{{ __('admin.type') }}</th>
                                        <th>{{ __('admin.completed_projects') }}</th>
                                        <th>{{ __('admin.average_rating') }}</th>
                                        <th>{{ __('admin.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topProviders as $provider)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-3">
                                                        <img src="{{ $provider->profile_photo_url }}" alt="Avatar" class="rounded-circle">
                                                    </div>
                                                    <span class="fw-bold">{{ $provider->name }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-label-{{ $provider->provider_type == 'company' ? 'info' : 'primary' }}">
                                                    {{ __('admin.' . $provider->provider_type) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="fw-bold text-primary">{{ $provider->completed_projects }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2">{{ number_format($provider->average_rating, 1) }}</span>
                                                    <div class="text-warning">
                                                        <i class="ti ti-star-filled"></i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('users.show', $provider->id) }}" class="btn btn-icon btn-sm btn-label-primary">
                                                    <i class="icon-base ti tabler-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">{{ __('admin.no_data') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Charts Section -->
            <div class="row g-4 mb-4">
                <!-- Requests by Category Chart -->
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('admin.requests_by_category') }}</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="requestsByCategoryChart" class="chartjs" data-height="300"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Requests by Status Chart -->
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('admin.requests_by_status') }}</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="requestsByStatusChart" class="chartjs" data-height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Users Distribution Chart -->
            <div class="row g-4 mb-4">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('admin.users_statistics') }}</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="usersDistributionChart" class="chartjs" data-height="300"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Service Providers Chart -->
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('admin.service_providers') }}</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="providersChart" class="chartjs" data-height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Requests Status Timeline Chart -->
            <div class="row g-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">{{ __('admin.general_trend_30_days') }}</h5>
                            <small class="text-muted">{{ __('admin.daily_comparison_new_requests') }}</small>
                        </div>
                        <div class="card-body">
                            <canvas id="requests30DaysChart" class="chartjs" data-height="400"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Content -->
@endsection
@section('dashboard-footer')
    <script src="{{ asset('dashboard') }}/assets/js/dashboard-analytics.js"></script>
    <script src="{{ asset('dashboard') }}/assets/js/charts-chartjs.js"></script>
    <script>
        // Pass data to JS
        window.weeklyRequestsData = @json($weeklyRequestsData);
        window.monthlyRequestsData = @json($monthlyRequestsData);
        window.last30DaysRequestsData = @json($last30DaysRequestsData);
        window.totalRequests = {{ $totalRequests }};
        window.completedRequests = {{ $completedRequests }};
        window.completedRequestsLabel = '{{ __('admin.completed') }}';
        document.addEventListener('DOMContentLoaded', function() {
            // Color Variables
            const primaryColor = '#014D40',
                secondaryColor = '#61888d',
                successColor = '#28C76F',
                warningColor = '#FF9F43',
                dangerColor = '#EA5455',
                infoColor = '#00CFE8',
                purpleColor = '#836AF9',
                cyanColor = '#28dac6';
            // Get CSS Variables
            let cardColor, headingColor, labelColor, borderColor, legendColor;
            if (typeof isDarkStyle !== 'undefined' && isDarkStyle) {
                cardColor = window.Helpers.getCssVar('paper-bg', true);
                headingColor = window.Helpers.getCssVar('heading-color', true);
                labelColor = window.Helpers.getCssVar('secondary-color', true);
                legendColor = window.Helpers.getCssVar('body-color', true);
                borderColor = window.Helpers.getCssVar('border-color', true);
            } else {
                cardColor = '#fff';
                headingColor = '#5d596c';
                labelColor = '#6f6b7d';
                legendColor = '#6f6b7d';
                borderColor = '#e7e7e7';
            }
            // Requests by Category Chart (Doughnut)
            const categoryChartEl = document.getElementById('requestsByCategoryChart');
            if (categoryChartEl) {
                const categoryData = @json($requestsByCategory);
                const categoryLabels = categoryData.map(item => {
                    if (item.category_name) {
                        return item.category_name;
                    }
                    if (item.category && item.category.name) {
                        if (typeof item.category.name === 'object') {
                            return item.category.name.ar || item.category.name.en || '-';
                        }
                        return item.category.name;
                    }
                    return '-';
                });
                const categoryValues = categoryData.map(item => item.total);
                new Chart(categoryChartEl, {
                    type: 'doughnut',
                    data: {
                        labels: categoryLabels,
                        datasets: [{
                            data: categoryValues,
                            backgroundColor: [primaryColor, secondaryColor, successColor, warningColor, infoColor, purpleColor, cyanColor],
                            borderColor: cardColor,
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: legendColor,
                                    padding: 15,
                                    usePointStyle: true
                                }
                            },
                            tooltip: {
                                backgroundColor: cardColor,
                                titleColor: headingColor,
                                bodyColor: legendColor,
                                borderWidth: 1,
                                borderColor: borderColor
                            }
                        }
                    }
                });
            }
            // Requests by Status Chart (Bar)
            const statusChartEl = document.getElementById('requestsByStatusChart');
            if (statusChartEl) {
                const statusData = @json($requestsByStatus);
                const statusLabels = statusData.map(item => {
                    const statusMap = {
                        'new': '{{ __('admin.new') }}',
                        'pending_response': '{{ __('admin.pending_response') }}',
                        'accepted': '{{ __('admin.accepted') }}',
                        'rejected': '{{ __('admin.rejected') }}',
                        'time_expired': '{{ __('admin.time_expired') }}',
                        'under_inspection': '{{ __('admin.under_inspection') }}',
                        'agreed': '{{ __('admin.agreed') }}',
                        'completed': '{{ __('admin.completed') }}'
                    };
                    return statusMap[item.status] || item.status;
                });
                const statusValues = statusData.map(item => item.total);
                new Chart(statusChartEl, {
                    type: 'bar',
                    data: {
                        labels: statusLabels,
                        datasets: [{
                            label: '{{ __('admin.count') }}',
                            data: statusValues,
                            backgroundColor: [infoColor, warningColor, successColor, dangerColor, dangerColor, infoColor, successColor, successColor],
                            borderColor: 'transparent',
                            maxBarThickness: 15,
                            borderRadius: {
                                topRight: 15,
                                topLeft: 15
                            }
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: cardColor,
                                titleColor: headingColor,
                                bodyColor: legendColor,
                                borderWidth: 1,
                                borderColor: borderColor
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    color: borderColor,
                                    drawBorder: false
                                },
                                ticks: {
                                    color: labelColor
                                }
                            },
                            y: {
                                grid: {
                                    color: borderColor,
                                    drawBorder: false
                                },
                                ticks: {
                                    color: labelColor,
                                    stepSize: 1
                                },
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
            // Users Distribution Chart (Pie)
            const usersChartEl = document.getElementById('usersDistributionChart');
            if (usersChartEl) {
                new Chart(usersChartEl, {
                    type: 'pie',
                    data: {
                        labels: [
                            '{{ __('admin.service_seekers') }}',
                            '{{ __('admin.service_providers') }}'
                        ],
                        datasets: [{
                            data: [{{ $serviceSeekers }}, {{ $serviceProviders }}],
                            backgroundColor: [primaryColor, secondaryColor],
                            borderColor: cardColor,
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: legendColor,
                                    padding: 15,
                                    usePointStyle: true
                                }
                            },
                            tooltip: {
                                backgroundColor: cardColor,
                                titleColor: headingColor,
                                bodyColor: legendColor,
                                borderWidth: 1,
                                borderColor: borderColor
                            }
                        }
                    }
                });
            }
            // Providers Chart (Doughnut)
            const providersChartEl = document.getElementById('providersChart');
            if (providersChartEl) {
                new Chart(providersChartEl, {
                    type: 'doughnut',
                    data: {
                        labels: [
                            '{{ __('admin.individual') }}',
                            '{{ __('admin.company') }}'
                        ],
                        datasets: [{
                            data: [{{ $individualProviders }}, {{ $companyProviders }}],
                            backgroundColor: [primaryColor, infoColor],
                            borderColor: cardColor,
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: legendColor,
                                    padding: 15,
                                    usePointStyle: true
                                }
                            },
                            tooltip: {
                                backgroundColor: cardColor,
                                titleColor: headingColor,
                                bodyColor: legendColor,
                                borderWidth: 1,
                                borderColor: borderColor
                            }
                        }
                    }
                });
            }
            // Requests Status Chart (Line)
            const requestsStatusChartEl = document.getElementById('requestsStatusChart');
            if (requestsStatusChartEl) {
                new Chart(requestsStatusChartEl, {
                    type: 'line',
                    data: {
                        labels: [
                            '{{ __('admin.new') }}',
                            '{{ __('admin.pending_response') }}',
                            '{{ __('admin.accepted') }}',
                            '{{ __('admin.under_inspection') }}',
                            '{{ __('admin.agreed') }}',
                            '{{ __('admin.completed') }}',
                            '{{ __('admin.time_expired') }}'
                        ],
                        datasets: [{
                            label: '{{ __('admin.service_requests') }}',
                            data: [
                                {{ $newRequests }},
                                {{ $pendingResponseRequests }},
                                {{ $acceptedRequests }},
                                {{ $underInspectionRequests }},
                                {{ $agreedRequests }},
                                {{ $completedRequests }},
                                {{ $expiredRequests }}
                            ],
                            borderColor: primaryColor,
                            backgroundColor: primaryColor + '20',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            pointBackgroundColor: primaryColor,
                            pointBorderColor: cardColor,
                            pointBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    color: legendColor,
                                    usePointStyle: true
                                }
                            },
                            tooltip: {
                                backgroundColor: cardColor,
                                titleColor: headingColor,
                                bodyColor: legendColor,
                                borderWidth: 1,
                                borderColor: borderColor
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    color: borderColor,
                                    drawBorder: false
                                },
                                ticks: {
                                    color: labelColor
                                }
                            },
                            y: {
                                grid: {
                                    color: borderColor,
                                    drawBorder: false
                                },
                                ticks: {
                                    color: labelColor,
                                    stepSize: 1
                                },
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
            // Requests 30 Days Trend Chart (Line)
            const requests30DaysChartEl = document.getElementById('requests30DaysChart');
            if (requests30DaysChartEl) {
                const trendData = window.last30DaysRequestsData;
                const trendLabels = trendData.map(item => item.date);
                const trendValues = trendData.map(item => item.count);
                new Chart(requests30DaysChartEl, {
                    type: 'line',
                    data: {
                        labels: trendLabels,
                        datasets: [{
                            label: 'عدد الطلبات',
                            data: trendValues,
                            borderColor: primaryColor,
                            backgroundColor: 'rgba(1, 77, 64, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 3,
                            pointBackgroundColor: primaryColor,
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: cardColor,
                                titleColor: headingColor,
                                bodyColor: legendColor,
                                borderWidth: 1,
                                borderColor: borderColor
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: labelColor,
                                    maxTicksLimit: 10
                                }
                            },
                            y: {
                                grid: {
                                    color: borderColor,
                                    drawBorder: false
                                },
                                ticks: {
                                    color: labelColor,
                                    stepSize: 1
                                },
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        });
    </script>
@endsection
