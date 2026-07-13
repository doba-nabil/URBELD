@extends('dashboard.layout.master')
@section('title', 'عرض طلب التوريد')
@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">عرض طلب التوريد</h4>
            <div>
                <a href="{{ route('supply-requests.index') }}" class="btn btn-secondary">
                    <i class="icon-base ti tabler-arrow-right"></i> {{ __('admin.back') }}
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>معلومات الطلب</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>طالب التوريد:</strong>
                                <p>{{ $supplyRequest->user->name ?? '-' }}</p>
                            </div>
                            <div class="col-md-4">
                                <strong>المدينة:</strong>
                                <p>{{ $supplyRequest->city->name ?? '-' }}</p>
                            </div>
                            <div class="col-md-4">
                                <strong>تاريخ التوريد المطلوب:</strong>
                                <p>{{ $supplyRequest->delivery_date ? $supplyRequest->delivery_date->format('Y-m-d') : '-' }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>عنوان الطلب:</strong>
                                <p>{{ $supplyRequest->title }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>الحالة:</strong>
                                <p>
                                    @php
                                        $statuses = [
                                            'pending' => ['label' => 'قيد المراجعة', 'class' => 'badge bg-label-secondary'],
                                            'open' => ['label' => 'طلب جديد / مفتوح للعروض', 'class' => 'badge bg-label-primary'],
                                            'in_progress' => ['label' => 'تم قبول العرض (قيد التنفيذ)', 'class' => 'badge bg-label-warning'],
                                            'completed' => ['label' => 'مكتمل', 'class' => 'badge bg-label-success'],
                                            'closed' => ['label' => 'مغلق', 'class' => 'badge bg-label-danger'],
                                        ];
                                        $status = $statuses[$supplyRequest->status] ?? [
                                            'label' => $supplyRequest->status,
                                            'class' => 'badge bg-label-secondary',
                                        ];
                                    @endphp
                                    <span class="{{ $status['class'] }}">{{ $status['label'] }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <strong>الوصف / التفاصيل:</strong>
                                <div class="p-3 bg-light rounded mt-2">
                                    {!! nl2br(e($supplyRequest->description)) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Responses Section -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">العروض المقدمة <span class="badge bg-primary ms-2">{{ $supplyRequest->responses->count() }}</span></h5>
                    </div>
                    <div class="card-body">
                        @if ($supplyRequest->responses->isEmpty())
                            <div class="text-center py-4">
                                <p class="text-muted mb-0">لم يتم تقديم أي عروض لهذا الطلب حتى الآن.</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>المورد</th>
                                            <th>السعر المقترح</th>
                                            <th>ملاحظات المورد</th>
                                            <th>تاريخ العرض</th>
                                            <th>حالة العرض</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($supplyRequest->responses as $resp)
                                            <tr>
                                                <td>{{ $resp->user->name ?? '-' }}</td>
                                                <td>{{ number_format($resp->proposed_price, 2) }} </td>
                                                <td>{{ $resp->notes ?? '-' }}</td>
                                                <td>{{ $resp->created_at->format('Y-m-d H:i') }}</td>
                                                <td>
                                                    @if ($supplyRequest->awarded_provider_id == $resp->user_id)
                                                        <span class="badge bg-success">العرض المقبول</span>
                                                    @else
                                                        <span class="badge bg-secondary">مقدم</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
