@extends('dashboard.layout.master')
@section('title', 'إدارة المناقصات')
@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h5 class="card-header d-flex justify-content-between border-b">
                إدارة المناقصات
            </h5>
            
            @if(session('success'))
                <div class="alert alert-success m-3">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger m-3">{{ session('error') }}</div>
            @endif

            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>العنوان</th>
                            <th>صاحب الطلب</th>
                            <th>التصنيف</th>
                            <th>تاريخ الانتهاء</th>
                            <th>الحالة</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($tenders as $tender)
                            <tr>
                                <td>{{ $tender->id }}</td>
                                <td>
                                    <strong>{{ $tender->title }}</strong>
                                    @if($tender->is_urgent)
                                        <span class="badge bg-warning ms-1">عاجل</span>
                                    @endif
                                </td>
                                <td>{{ $tender->user->name ?? 'غير معروف' }}</td>
                                <td>{{ $tender->category->name ?? 'غير محدد' }}</td>
                                <td>{{ $tender->ends_at ? $tender->ends_at->format('Y-m-d') : '-' }}</td>
                                <td>
                                    @if($tender->status === \App\Models\Tender::STATUS_PENDING_REVIEW)
                                        <span class="badge bg-label-warning">بانتظار المراجعة</span>
                                    @elseif($tender->status === \App\Models\Tender::STATUS_ACTIVE)
                                        <span class="badge bg-label-success">معتمد ونشط</span>
                                    @elseif($tender->status === \App\Models\Tender::STATUS_CLOSED)
                                        <span class="badge bg-label-danger">مغلق</span>
                                    @else
                                        <span class="badge bg-label-secondary">{{ $tender->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('tenders.show', $tender->id) }}" class="btn btn-sm btn-info">عرض التفاصيل والمراجعة</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">لا توجد مناقصات حالياً</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $tenders->links() }}
            </div>
        </div>
    </div>
@endsection
