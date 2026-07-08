@extends('dashboard.layout.master')
@section('title', 'إضافة بانر جديد')
@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <h5 class="card-header border-b">إضافة بانر جديد</h5>
            <div class="card-body mt-3">
                <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">عنوان البانر</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                            @error('title')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">نطاق الظهور</label>
                            <select name="page_scope" class="form-select" id="page_scope" required>
                                @foreach(\App\Models\Banner::scopeLabels() as $key => $label)
                                    <option value="{{ $key }}" {{ old('page_scope') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('page_scope')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3 d-none" id="category_wrapper">
                            <label class="form-label">التصنيف المحدد</label>
                            <select name="category_id" class="form-select">
                                <option value="">اختر التصنيف...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3 d-none" id="custom_page_wrapper">
                            <label class="form-label">رابط أو اسم الصفحة المخصصة</label>
                            <input type="text" name="custom_page" class="form-control" value="{{ old('custom_page') }}">
                            @error('custom_page')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">ربط بعرض مورد (اختياري)</label>
                            <select name="supplier_offer_id" class="form-select">
                                <option value="">لا يوجد ربط</option>
                                @foreach($supplierOffers as $offer)
                                    <option value="{{ $offer->id }}" {{ old('supplier_offer_id') == $offer->id ? 'selected' : '' }}>
                                        {{ $offer->title }} - {{ $offer->user->name ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">إذا تم الربط ولم يتم رفع صورة مخصصة للبانر، سيتم استخدام صورة العرض تلقائياً.</small>
                            @error('supplier_offer_id')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">صورة مخصصة للبانر</label>
                            <input type="file" name="banner_image" class="form-control" accept="image/*">
                            @error('banner_image')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">الترتيب</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                            @error('sort_order')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">تفعيل البانر</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">حفظ البانر</button>
                        <a href="{{ route('banners.index') }}" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('dashboard-footer')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const scopeSelect = document.getElementById('page_scope');
        const categoryWrapper = document.getElementById('category_wrapper');
        const customPageWrapper = document.getElementById('custom_page_wrapper');

        function toggleFields() {
            const scope = scopeSelect.value;
            if (scope === 'specific_category') {
                categoryWrapper.classList.remove('d-none');
                customPageWrapper.classList.add('d-none');
            } else if (scope === 'custom') {
                customPageWrapper.classList.remove('d-none');
                categoryWrapper.classList.add('d-none');
            } else {
                categoryWrapper.classList.add('d-none');
                customPageWrapper.classList.add('d-none');
            }
        }

        scopeSelect.addEventListener('change', toggleFields);
        toggleFields(); // Initial run
    });
</script>
@endsection
