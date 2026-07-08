@extends('website.layouts.profile')

@section('title', 'العروض والخصومات')

@section('profile-content')
    <div class="about-me-section">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="about-me-title mb-0">العروض والخصومات</h2>
                @if(auth()->user()->hasActiveSubscription())
                    <a href="{{ route('supplier.offers.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> إضافة عرض
                    </a>
                @else
                    <button class="btn btn-secondary" disabled title="يجب تفعيل اشتراك لإضافة عروض">
                        <i class="bi bi-plus-circle"></i> إضافة عرض
                    </button>
                @endif
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
                            <th>الصورة</th>
                            <th>العنوان</th>
                            <th>الوصف</th>
                            <th>نسبة الخصم</th>
                            <th>نص الشارة (Badge)</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($offers as $offer)
                            <tr>
                                <td class="text-center align-middle">
                                    @if($offer->getFirstMediaUrl('offer_images'))
                                        <img src="{{ $offer->getFirstMediaUrl('offer_images') }}" width="60" class="rounded" alt="Offer Image">
                                    @else
                                        <span class="text-muted"><i class="bi bi-tag" style="font-size: 2rem;"></i></span>
                                    @endif
                                </td>
                                <td class="align-middle fw-bold">{{ $offer->title }}</td>
                                <td class="align-middle">{{ Str::limit($offer->description, 50) }}</td>
                                <td class="align-middle">{{ $offer->discount_percentage ? $offer->discount_percentage . '%' : '-' }}</td>
                                <td class="align-middle">
                                    @if($offer->badge_text)
                                        <span class="badge bg-primary">{{ $offer->badge_text }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <a href="{{ route('supplier.offers.edit', $offer->id) }}" class="btn btn-sm btn-info text-white"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('supplier.offers.destroy', $offer->id) }}" method="POST" class="d-inline delete-work-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger delete-work-btn"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-tags mb-3 d-block" style="font-size: 3rem;"></i>
                                    لا توجد عروض مضافة
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
                title: 'هل أنت متأكد؟',
                text: 'سيتم حذف هذا العرض نهائياً.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء'
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
