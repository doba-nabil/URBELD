@extends('website.layouts.profile')

@section('title', 'إضافة عرض')

@section('profile-content')
    <div class="about-me-section">
        <div class="container">
            <h2 class="about-me-title mb-4">إضافة عرض أو خصم جديد</h2>

            <form action="{{ route('supplier.offers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label for="title" class="form-label">عنوان العرض <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">وصف العرض / تفاصيل الخصم</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="discount_percentage" class="form-label">نسبة الخصم (%)</label>
                        <input type="number" min="0" max="100" class="form-control @error('discount_percentage') is-invalid @enderror" id="discount_percentage" name="discount_percentage" value="{{ old('discount_percentage') }}">
                        @error('discount_percentage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="badge_text" class="form-label">نص الشارة (مثال: خصم خاص، جديد)</label>
                        <input type="text" class="form-control @error('badge_text') is-invalid @enderror" id="badge_text" name="badge_text" value="{{ old('badge_text') }}">
                        @error('badge_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">صورة العرض</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('supplier.offers.index') }}" class="btn btn-secondary">إلغاء</a>
                    <button type="submit" class="btn btn-primary">حفظ العرض</button>
                </div>
            </form>
        </div>
    </div>
@endsection
