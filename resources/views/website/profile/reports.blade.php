@extends('website.layouts.profile')

@section('profile-content')
    <style>
        .reports-section {
            padding: 40px 0;
            background-color: #f8f9fa;
            min-height: 60vh;
        }

        .kpi-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 35px 25px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.03);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            border: 1px solid rgba(0, 0, 0, 0.02);
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        .kpi-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            transform: scaleX(0);
            transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            transform-origin: right;
        }

        .kpi-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            border-color: transparent;
        }

        .kpi-card:hover::after {
            transform: scaleX(1);
            transform-origin: left;
        }

        .kpi-info {
            position: relative;
            z-index: 2;
        }

        .kpi-title {
            font-size: 14px;
            color: #8c98a4;
            margin-bottom: 12px;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .kpi-value {
            font-size: 32px;
            font-weight: 800;
            color: #2b3044;
            margin: 0;
            line-height: 1.2;
        }

        .table-container {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            margin-top: 30px;
        }

        .custom-table th {
            background-color: #f8f9fa;
            color: var(--primary);
            font-weight: 600;
            border-bottom: 2px solid #e9ecef;
            padding: 15px;
        }

        .custom-table td {
            padding: 15px;
            vertical-align: middle;
            color: #495057;
            border-bottom: 1px solid #f1f3f5;
        }

        @media print {
            body * {
                visibility: hidden;
                background: #fff !important;
            }

            .reports-section,
            .reports-section * {
                visibility: visible;
            }

            .reports-section {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 0;
            }

            .print-btn,
            .profile-tabs-section,
            .profile-header-section {
                display: none !important;
            }

            .kpi-card,
            .table-container {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }

            .kpi-title {
                color: #000 !important;
            }

            .kpi-value {
                color: #000 !important;
            }
        }
    </style>

    <div class="reports-section" id="report-content">
        <div class="container">

            <div class="d-flex justify-content-between align-items-center mb-5">
                <h3 class="fw-bold mb-0" style="color: var(--primary); letter-spacing: -0.5px;">
                    {{ __('website.account_report') ?? 'تقرير الحساب' }}
                </h3>
                <a href="{{ route('profile.edit') }}" class="text-decoration-none text-muted small fw-bold">
                    {{ __('website.back_to_profile') ?? 'العودة للوحة التحكم' }} <i class="bi bi-arrow-left ms-1"></i>
                </a>
            </div>

            <!-- Tabs Navigation -->
            <ul class="nav nav-pills mb-5 gap-3 justify-content-center" id="reportsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill px-5 py-2 shadow-sm fw-semibold" style="font-size: 15px;" id="service-requests-tab" data-bs-toggle="pill" data-bs-target="#service-requests" type="button" role="tab" aria-controls="service-requests" aria-selected="true">
                        {{ __('admin.service_requests') ?? 'طلبات الخدمات' }}
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-5 py-2 shadow-sm fw-semibold" style="font-size: 15px;" id="tenders-tab" data-bs-toggle="pill" data-bs-target="#tenders" type="button" role="tab" aria-controls="tenders" aria-selected="false">
                        {{ __('admin.tenders') ?? 'المناقصات' }}
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="reportsTabsContent">
                <!-- Service Requests Tab -->
                <div class="tab-pane fade show active" id="service-requests" role="tabpanel" aria-labelledby="service-requests-tab">
                    <div class="row g-4 mb-5">
                        @foreach ($kpis as $kpi)
                            <div class="col-xl-3 col-md-6">
                                <div class="kpi-card">
                                    <div class="kpi-info">
                                        <div class="kpi-title">{{ $kpi['title'] }}</div>
                                        <h4 class="kpi-value text-{{ $kpi['color'] }}">{{ $kpi['value'] }}</h4>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="table-container">
                        <h5 class="fw-bold mb-4" style="color: var(--primary);">{{ __('website.recent_activities') ?? 'الأنشطة الأخيرة' }}</h5>
                        @if (count($recentActivity) > 0)
                            <div class="table-responsive">
                                <table class="table custom-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>{{ __('website.request_title_lbl') ?? 'اسم الطلب' }}</th>
                                            <th>{{ __('website.date') ?? 'التاريخ' }}</th>
                                            <th>{{ __('website.status') ?? 'الحالة' }}</th>
                                            <th>{{ __('website.amount_cost') ?? 'التكلفة' }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentActivity as $activity)
                                            @php
                                                $statusClass = match ($activity['status_code'] ?? '') {
                                                    'pending' => 'bg-primary border border-primary text-primary',
                                                    'provider_accepted',
                                                    'inspection_scheduled',
                                                    'inspection_done'
                                                        => 'bg-info border border-info text-info',
                                                    'work_completed',
                                                    'completed',
                                                    'seeker_confirmed'
                                                        => 'bg-success border border-success text-success',
                                                    'cancelled',
                                                    'rejected',
                                                    'timeout'
                                                        => 'bg-danger border border-danger text-danger',
                                                    default => 'bg-secondary border border-secondary text-secondary',
                                                };
                                            @endphp
                                            <tr>
                                                <td class="fw-medium">
                                                    <a href="{{ $activity['route'] ?? '#' }}"
                                                        class="text-decoration-none text-dark">{{ $activity['title'] }}</a>
                                                </td>
                                                <td>{{ $activity['date'] }}</td>
                                                <td>
                                                    <span class="badge {{ $statusClass }} bg-opacity-10 px-3 py-2 rounded-pill">
                                                        {{ $activity['status'] }}
                                                    </span>
                                                </td>
                                                <td class="fw-bold text-dark">{{ $activity['amount'] }}</td>
                                                <td>
                                                    <a href="{{ $activity['route'] ?? '#' }}"
                                                        class="btn btn-sm btn-light rounded-circle">
                                                        <i class="fas fa-chevron-left"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <p class="text-muted fs-5 mb-0 fw-light">{{ __('website.no_recent_activity') ?? 'لا توجد أنشطة حديثة' }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tenders Tab -->
                <div class="tab-pane fade" id="tenders" role="tabpanel" aria-labelledby="tenders-tab">
                    <div class="row g-4 mb-5">
                        @foreach ($tenderKpis as $kpi)
                            <div class="col-xl-3 col-md-6">
                                <div class="kpi-card">
                                    <div class="kpi-info">
                                        <div class="kpi-title">{{ $kpi['title'] }}</div>
                                        <h4 class="kpi-value text-{{ $kpi['color'] }}">{{ $kpi['value'] }}</h4>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="table-container">
                        <h5 class="fw-bold mb-4" style="color: #503545;">{{ __('website.recent_activities') ?? 'الأنشطة الأخيرة' }}</h5>
                        @if (count($recentTenderActivity) > 0)
                            <div class="table-responsive">
                                <table class="table custom-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>{{ __('website.request_title_lbl') ?? 'اسم الطلب' }}</th>
                                            <th>{{ __('website.date') ?? 'التاريخ' }}</th>
                                            <th>{{ __('website.status') ?? 'الحالة' }}</th>
                                            <th>{{ __('website.amount_cost') ?? 'التكلفة' }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentTenderActivity as $activity)
                                            @php
                                                $statusClass = match ($activity['status_code'] ?? '') {
                                                    'pending', 'pending_review' => 'bg-primary border border-primary text-primary',
                                                    'active' => 'bg-info border border-info text-info',
                                                    'closed', 'accepted' => 'bg-success border border-success text-success',
                                                    'rejected' => 'bg-danger border border-danger text-danger',
                                                    default => 'bg-secondary border border-secondary text-secondary',
                                                };
                                            @endphp
                                            <tr>
                                                <td class="fw-medium">
                                                    <a href="{{ $activity['route'] ?? '#' }}"
                                                        class="text-decoration-none text-dark">{{ $activity['title'] }}</a>
                                                </td>
                                                <td>{{ $activity['date'] }}</td>
                                                <td>
                                                    <span class="badge {{ $statusClass }} bg-opacity-10 px-3 py-2 rounded-pill">
                                                        {{ $activity['status'] }}
                                                    </span>
                                                </td>
                                                <td class="fw-bold text-dark">{{ $activity['amount'] }}</td>
                                                <td>
                                                    <a href="{{ $activity['route'] ?? '#' }}"
                                                        class="btn btn-sm btn-light rounded-circle">
                                                        <i class="fas fa-chevron-left"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <p class="text-muted fs-5 mb-0 fw-light">{{ __('website.no_recent_activity') ?? 'لا توجد أنشطة حديثة' }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
