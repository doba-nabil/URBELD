@extends('website.layouts.master')
@section('title', 'إضافة طلب توريد جديد')

@section('content')
<div class="container-xxl py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-5">
                        <div class="text-center mb-5">
                            <h2 class="fw-bold text-primary">إضافة طلب توريد جديد</h2>
                            <p class="text-muted">قم بتعبئة النموذج التالي لنشر طلب التوريد للموردين</p>
                        </div>

                        <form action="{{ route('website.supply-requests.store') }}" method="POST">
                            @csrf
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">عنوان الطلب <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control form-control-lg bg-light border-0" required placeholder="مثال: توريد 50 جهاز كمبيوتر مكتبي">
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">تفاصيل الطلب <span class="text-danger">*</span></label>
                                <textarea name="description" rows="5" class="form-control form-control-lg bg-light border-0" required placeholder="يرجى كتابة كافة المواصفات والكميات المطلوبة بدقة..."></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">المدينة <span class="text-danger">*</span></label>
                                    <select name="city_id" class="form-select form-select-lg bg-light border-0" required>
                                        <option value="" disabled selected>اختر المدينة</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">آخر موعد للتسليم (اختياري)</label>
                                    <input type="date" name="delivery_date" class="form-control form-control-lg bg-light border-0">
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold">
                                    <i class="bi bi-send me-2"></i> نشر الطلب
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
