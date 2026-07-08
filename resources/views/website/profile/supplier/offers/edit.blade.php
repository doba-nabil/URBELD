@extends('website.layouts.profile')

@section('title', 'تعديل عرض')

@section('profile-content')
    <div class="about-me-section">
        <div class="container">
            <h2 class="about-me-title mb-4">تعديل العرض</h2>

            <form action="{{ route('supplier.offers.update', $offer->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="title" class="form-label">عنوان العرض <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $offer->title) }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">وصف العرض / تفاصيل الخصم</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $offer->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="discount_percentage" class="form-label">نسبة الخصم (%)</label>
                        <input type="number" min="0" max="100" class="form-control @error('discount_percentage') is-invalid @enderror" id="discount_percentage" name="discount_percentage" value="{{ old('discount_percentage', $offer->discount_percentage) }}">
                        @error('discount_percentage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="badge_text" class="form-label">نص الشارة (مثال: خصم خاص، جديد)</label>
                        <input type="text" class="form-control @error('badge_text') is-invalid @enderror" id="badge_text" name="badge_text" value="{{ old('badge_text', $offer->badge_text) }}">
                        @error('badge_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">الصورة الحالية</label>
                    @if($offer->getFirstMediaUrl('offer_images'))
                        <div class="mb-2">
                            <img src="{{ $offer->getFirstMediaUrl('offer_images') }}" width="150" class="rounded border">
                        </div>
                    @else
                        <p class="text-muted">لا توجد صورة</p>
                    @endif
                    
                    <label for="image" class="form-label">تغيير الصورة</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('supplier.offers.index') }}" class="btn btn-secondary">إلغاء</a>
                    <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>
@endsection
