@extends('dashboard.layout.master')
@section('title', 'تفاصيل المناقصة')
@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <h5 class="card-header d-flex justify-content-between border-b">
                تفاصيل المناقصة: {{ $tender->title }}
                <a href="{{ route('tenders.index') }}" class="btn btn-secondary btn-sm">العودة للقائمة</a>
            </h5>
            
            @if(session('success'))
                <div class="alert alert-success m-3">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger m-3">{{ session('error') }}</div>
            @endif

            <div class="card-body mt-3">
                <div class="row">
                    <div class="col-md-8">
                        <h6 class="fw-bold">وصف المناقصة:</h6>
                        <p class="text-muted">{{ $tender->description }}</p>

                        @if($tender->qualification_requirements)
                        <h6 class="fw-bold mt-4">متطلبات التأهيل:</h6>
                        <p class="text-muted">{{ $tender->qualification_requirements }}</p>
                        @endif

                        <h6 class="fw-bold mt-4">الملفات المرفقة:</h6>
                        @if($tender->getMedia('tender_files')->count() > 0)
                            <ul class="list-group mb-3">
                            @foreach($tender->getMedia('tender_files') as $media)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $media->getCustomProperty('title', $media->name) }}
                                    <a href="{{ $media->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">تحميل <i class="bi bi-download"></i></a>
                                </li>
                            @endforeach
                            </ul>
                        @else
                            <p class="text-muted">لا توجد ملفات مرفقة.</p>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <div class="bg-light p-3 rounded">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><strong>حالة المناقصة:</strong> 
                                    @if($tender->status === \App\Models\Tender::STATUS_PENDING_REVIEW)
                                        <span class="badge bg-warning">بانتظار المراجعة</span>
                                    @elseif($tender->status === \App\Models\Tender::STATUS_ACTIVE)
                                        <span class="badge bg-success">معتمد ونشط</span>
                                    @elseif($tender->status === \App\Models\Tender::STATUS_CLOSED)
                                        <span class="badge bg-danger">مغلق</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $tender->status }}</span>
                                    @endif
                                </li>
                                <li class="mb-2"><strong>صاحب الطلب:</strong> <a href="{{ route('users.show', $tender->user_id) }}">{{ $tender->user->name ?? 'غير معروف' }}</a></li>
                                <li class="mb-2"><strong>التصنيف:</strong> {{ $tender->category->name ?? 'غير محدد' }}</li>
                                <li class="mb-2"><strong>المدينة:</strong> {{ $tender->city->name ?? 'غير محدد' }}</li>
                                <li class="mb-2"><strong>تاريخ الإضافة:</strong> {{ $tender->created_at->format('Y-m-d H:i') }}</li>
                                <li class="mb-2"><strong>تاريخ الإغلاق:</strong> {{ $tender->ends_at ? $tender->ends_at->format('Y-m-d H:i') : 'غير محدد' }}</li>
                                @if($tender->budget)
                                <li class="mb-2"><strong>الميزانية المقدرة:</strong> {{ number_format($tender->budget) }} ريال</li>
                                @endif
                                <li class="mb-2"><strong>النوع:</strong> {{ $tender->project_type == 'engineering' ? 'مخططات هندسية' : 'أخرى' }}</li>
                                <li><strong>عاجل:</strong> {!! $tender->is_urgent ? '<span class="text-danger fw-bold">نعم</span>' : 'لا' !!}</li>
                            </ul>
                        </div>

                        <!-- Action Buttons -->
                        @if($tender->status === \App\Models\Tender::STATUS_PENDING_REVIEW)
                        <div class="mt-4 d-grid gap-2">
                            <form action="{{ route('tenders.approve', $tender->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100" onclick="return confirm('هل أنت متأكد من اعتماد ونشر هذه المناقصة؟ سيتم إرسال إشعارات للموردين.')">
                                    <i class="ti tabler-check"></i> اعتماد المناقصة ونشرها
                                </button>
                            </form>
                            
                            <form action="{{ route('tenders.reject', $tender->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100" onclick="return confirm('هل أنت متأكد من رفض هذه المناقصة وإغلاقها؟')">
                                    <i class="ti tabler-x"></i> رفض المناقصة
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
            <h5 class="card-header border-b">عروض الموردين ({{ $tender->applications->count() }})</h5>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>مقدم العرض</th>
                            <th>قيمة العرض</th>
                            <th>مدة التنفيذ</th>
                            <th>تاريخ التقديم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tender->applications as $app)
                        <tr>
                            <td><a href="{{ route('users.show', $app->user_id) }}">{{ $app->user->name ?? 'غير معروف' }}</a></td>
                            <td>{{ number_format($app->price) }} ريال</td>
                            <td>{{ $app->delivery_days }} يوم</td>
                            <td>{{ $app->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">لا يوجد عروض حتى الآن</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
@endsection
