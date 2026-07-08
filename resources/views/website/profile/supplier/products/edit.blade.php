@extends('website.layouts.profile')

@section('title', 'تعديل منتج')

@section('profile-content')
    <div class="about-me-section">
        <div class="container">
            <h2 class="about-me-title mb-4">تعديل منتج</h2>

            <form action="{{ route('supplier.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="title" class="form-label">العنوان <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $product->title) }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="subtitle" class="form-label">العنوان الفرعي</label>
                    <input type="text" class="form-control @error('subtitle') is-invalid @enderror" id="subtitle" name="subtitle" value="{{ old('subtitle', $product->subtitle) }}">
                    @error('subtitle')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">السعر (مثال: 200 ريال/القطعة)</label>
                    <input type="text" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}">
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">الصور الحالية</label>
                    <div class="d-flex flex-wrap gap-2 mb-2" id="current-images">
                        @foreach($product->getMedia('product_images') as $media)
                            <div class="position-relative media-container" data-id="{{ $media->id }}">
                                <img src="{{ $media->getUrl() }}" width="100" class="rounded border">
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 delete-media-btn" data-url="{{ route('supplier.products.media.destroy', [$product->id, $media->id]) }}">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                    
                    <label for="images" class="form-label">إضافة صور جديدة</label>
                    <input type="file" class="form-control @error('images') is-invalid @enderror" id="images" name="images[]" multiple accept="image/*">
                    @error('images')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @error('images.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('supplier.products.index') }}" class="btn btn-secondary">إلغاء</a>
                    <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>
@push('js')
<script>
    document.querySelectorAll('.delete-media-btn').forEach(button => {
        button.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            const container = this.closest('.media-container');
            
            if(confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        container.remove();
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });
</script>
@endpush
@endsection
