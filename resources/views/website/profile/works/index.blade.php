@extends('website.layouts.profile')

@section('title', auth()->user()->isCompanyProvider() ? 'المشاريع' : (__('admin.works_portfolio') ?? 'الأعمال السابقة'))

@section('profile-content')
    <style>
        .custom-table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            border: 1px solid rgba(0,0,0,0.04);
        }
        .custom-table th {
            background-color: #f8f9fa;
            color: var(--primary);
            font-weight: 600;
            padding: 18px 20px;
            border-bottom: 2px solid #e9ecef;
            white-space: nowrap;
        }
        .custom-table td {
            padding: 18px 20px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f3f5;
            background-color: #fff;
            color: #495057;
        }
        .custom-table tr:hover td {
            background-color: #fcfcfc;
        }
        .custom-table tr:last-child td {
            border-bottom: none;
        }
        .action-btn {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.2s;
            border: none;
        }
        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
    </style>

    <div class="incoming-requests-wrapper mt-4 mb-5" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="container bg-light rounded-4 p-4 shadow-sm" style="background-color: #f8f9fa !important;">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0 text-dark">{{ auth()->user()->isCompanyProvider() ? 'المشاريع' : (__('admin.works_portfolio') ?? 'الأعمال السابقة') }}</h3>
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('profile.edit') }}" class="text-decoration-none text-muted small fw-bold">
                        العودة للوحة التحكم <i class="bi bi-arrow-left ms-1"></i>
                    </a>
                    <a href="{{ route('provider.works.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                        <i class="fas fa-plus me-2"></i> {{ auth()->user()->isCompanyProvider() ? 'إضافة مشروع' : (__('admin.add_work') ?? 'إضافة عمل') }}
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success rounded-pill px-4 shadow-sm border-0">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger rounded-pill px-4 shadow-sm border-0">{{ session('error') }}</div>
            @endif

            <div class="table-responsive mt-2">
                <table class="table custom-table mb-0">
                    <thead>
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
                                        <img src="{{ $work->getFirstMediaUrl('work_images') }}" width="60" height="60" class="rounded-3 object-fit-cover shadow-sm border" alt="Work Image">
                                    @else
                                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center shadow-sm border mx-auto" style="width: 60px; height: 60px;">
                                            <i class="bi bi-briefcase text-muted fs-4"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="align-middle fw-bold text-dark">{{ $work->title }}</td>
                                <td class="align-middle text-muted">{{ Str::limit($work->description, 50) }}</td>
                                <td class="align-middle">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('provider.works.edit', $work->id) }}" class="action-btn bg-info text-white shadow-sm">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('provider.works.destroy', $work->id) }}" method="POST" class="d-inline delete-work-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="action-btn bg-danger text-white shadow-sm delete-work-btn">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="fas fa-briefcase mb-3 d-block opacity-25" style="font-size: 4rem;"></i>
                                    <p class="fs-5 mb-0 fw-light">لا توجد أعمال مضافة حتى الآن</p>
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
