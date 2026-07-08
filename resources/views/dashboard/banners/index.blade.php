@extends('dashboard.layout.master')
@section('title', 'إدارة البانرات')
@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h5 class="card-header d-flex justify-content-between border-b">
                إدارة البانرات
                <a href="{{ route('banners.create') }}" class="btn btn-primary"><i class="ti tabler-plus"></i> إضافة بانر جديد</a>
            </h5>
            
            @if(session('success'))
                <div class="alert alert-success m-3">{{ session('success') }}</div>
            @endif

            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الصورة</th>
                            <th>العنوان</th>
                            <th>نطاق الظهور</th>
                            <th>الحالة</th>
                            <th>الترتيب</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($banners as $banner)
                            <tr>
                                <td>{{ $banner->id }}</td>
                                <td>
                                    @if($banner->image_url)
                                        <img src="{{ $banner->image_url }}" alt="Banner" class="rounded" width="80" height="40" style="object-fit: cover;">
                                    @else
                                        <span class="text-muted">لا يوجد</span>
                                    @endif
                                </td>
                                <td>{{ $banner->title }}</td>
                                <td>{{ \App\Models\Banner::scopeLabels()[$banner->page_scope] ?? $banner->page_scope }}</td>
                                <td>
                                    @if($banner->is_active)
                                        <span class="badge bg-label-success">مفعل</span>
                                    @else
                                        <span class="badge bg-label-danger">معطل</span>
                                    @endif
                                </td>
                                <td>{{ $banner->sort_order }}</td>
                                <td>
                                    <a href="{{ route('banners.edit', $banner->id) }}" class="btn btn-sm btn-primary">تعديل</a>
                                    <form action="{{ route('banners.destroy', $banner->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف البانر؟')">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">لا توجد بانرات حالياً</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $banners->links() }}
            </div>
        </div>
    </div>
@endsection
