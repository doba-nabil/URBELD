@extends('website.layouts.profile')

@section('profile-content')
    <style>
        .reports-section {
            padding: 40px 0;
            background-color: #f8f9fa;
            min-height: 60vh;
        }

        .kpi-card {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            height: 100%;
            display: flex;
            align-items: center;
            transition: transform 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .kpi-card:hover {
            transform: translateY(-5px);
        }

        .kpi-icon-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-left: 20px;
            flex-shrink: 0;
        }

        .kpi-info {
            flex-grow: 1;
        }

        .kpi-title {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .kpi-value {
            font-size: 24px;
            font-weight: 700;
            color: #2b3044;
            margin: 0;
        }

        .print-btn {
            background: #503545;
            color: #fff;
            border: none;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .print-btn:hover {
            background: #f585a0;
            color: #fff;
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
            color: #503545;
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
                w-100;
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

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold" style="color: #503545;">
                    <i class="bi bi-bar-chart-fill me-2"></i> {{ __('website.account_report') }}
                </h3>
                <button onclick="window.print()" class="print-btn">
                    <i class="bi bi-printer me-2"></i> {{ __('website.print_report') }}
                </button>
            </div>

            <div class="row g-4 mb-4">
                @foreach ($kpis as $kpi)
                    <div class="col-xl-3 col-md-6">
                        <div class="kpi-card">
                            <div class="kpi-icon-wrapper bg-{{ $kpi['color'] }} bg-opacity-10 text-{{ $kpi['color'] }}">
                                <i class="bi {{ $kpi['icon'] }}"></i>
                            </div>
                            <div class="kpi-info">
                                <div class="kpi-title">{{ $kpi['title'] }}</div>
                                <h4 class="kpi-value">{{ $kpi['value'] }}</h4>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="table-container">
                <h5 class="fw-bold mb-4" style="color: #503545;">{{ __('website.recent_activities') }}</h5>
                @if (count($recentActivity) > 0)
                    <div class="table-responsive">
                        <table class="table custom-table mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('website.request_title_lbl') }}</th>
                                    <th>{{ __('website.date') }}</th>
                                    <th>{{ __('website.status') }}</th>
                                    <th>{{ __('website.amount_cost') }}</th>
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
                                            <a href="{{ route('requests.show', $activity['request_id']) }}"
                                                class="text-decoration-none text-dark" target="_blank"
                                                title="عرض تفاصيل الطلب">
                                                {{ $activity['title'] }}
                                            </a>
                                        </td>
                                        <td>{{ $activity['date'] }}</td>
                                        <td>
                                            <span class="badge {{ $statusClass }} bg-opacity-10 px-3 py-2 rounded-pill">
                                                {{ $activity['status'] }}
                                            </span>
                                        </td>
                                        <td class="fw-bold text-dark">{{ $activity['amount'] }}</td>
                                        <td>
                                            <a href="{{ route('requests.show', $activity['request_id']) }}"
                                                class="btn btn-sm btn-outline-primary rounded-pill" target="_blank">
                                                <i class="bi bi-eye"></i> {{ __('website.view_request') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-light border text-center py-4 mb-0">
                        <i class="bi bi-info-circle text-muted fs-4 d-block mb-2"></i>
                        {{ __('website.no_activities_found') }}
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
