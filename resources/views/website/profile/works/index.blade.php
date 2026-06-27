@extends('website.layouts.profile')

@section('title', __('admin.works_portfolio') ?? 'الأعمال السابقة')

@section('profile-content')
    <div class="about-me-section">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="about-me-title mb-0">{{ __('admin.works_portfolio') ?? 'الأعمال السابقة' }}</h2>
                <a href="{{ route('provider.works.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> {{ __('admin.add_work') ?? 'إضافة عمل' }}
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover bg-white shadow-sm rounded">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('website.image') ?? 'صورة' }}</th>
                            <th>{{ __('website.title') ?? 'العنوان' }}</th>
                            <th>{{ __('admin.work_description') ?? 'الوصف' }}</th>
                            <th>{{ __('website.actions') ?? 'إجراءات' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($works as $work)
                            <tr>
                                <td class="text-center align-middle">
                                    @if($work->getFirstMediaUrl('work_images'))
                                        <img src="{{ $work->getFirstMediaUrl('work_images') }}" width="60" class="rounded" alt="Work Image">
                                    @else
                                        <span class="text-muted"><i class="bi bi-image" style="font-size: 2rem;"></i></span>
                                    @endif
                                </td>
                                <td class="align-middle fw-bold">{{ $work->title }}</td>
                                <td class="align-middle">{{ Str::limit($work->description, 50) }}</td>
                                <td class="align-middle">
                                    <a href="{{ route('provider.works.edit', $work->id) }}" class="btn btn-sm btn-info text-white"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('provider.works.destroy', $work->id) }}" method="POST" class="d-inline delete-work-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger delete-work-btn"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bi bi-briefcase mb-3 d-block" style="font-size: 3rem;"></i>
                                    لا توجد أعمال مضافة
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@push('js')
<script>
    document.querySelectorAll('.delete-work-btn').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('.delete-work-form');
            
            Swal.fire({
                title: '{{ __("website.are_you_sure") ?? "هل أنت متأكد؟" }}',
                text: '{{ __("website.delete_request_warning") ?? "سيتم حذف هذا العمل نهائياً." }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '{{ __("website.yes_delete_request") ?? "نعم، احذف" }}',
                cancelButtonText: '{{ __("website.cancel") ?? "إلغاء" }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
@endsection
