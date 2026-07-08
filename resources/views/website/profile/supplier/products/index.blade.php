@extends('website.layouts.profile')

@section('title', 'المنتجات')

@section('profile-content')
    <div class="about-me-section">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="about-me-title mb-0">المنتجات</h2>
                <a href="{{ route('supplier.products.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> إضافة منتج
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
                            <th>الصورة</th>
                            <th>العنوان</th>
                            <th>العنوان الفرعي</th>
                            <th>السعر</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td class="text-center align-middle">
                                    @if($product->getFirstMediaUrl('product_images'))
                                        <img src="{{ $product->getFirstMediaUrl('product_images') }}" width="60" class="rounded" alt="Product Image">
                                    @else
                                        <span class="text-muted"><i class="bi bi-image" style="font-size: 2rem;"></i></span>
                                    @endif
                                </td>
                                <td class="align-middle fw-bold">{{ $product->title }}</td>
                                <td class="align-middle">{{ $product->subtitle }}</td>
                                <td class="align-middle">{{ $product->price }}</td>
                                <td class="align-middle">
                                    <a href="{{ route('supplier.products.edit', $product->id) }}" class="btn btn-sm btn-info text-white"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('supplier.products.destroy', $product->id) }}" method="POST" class="d-inline delete-work-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger delete-work-btn"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-box mb-3 d-block" style="font-size: 3rem;"></i>
                                    لا توجد منتجات مضافة
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
                text: 'سيتم حذف هذا المنتج نهائياً.',
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
