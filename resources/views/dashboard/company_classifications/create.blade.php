@extends('dashboard.layout.master')

@section('title', __('admin.add_new') ?? 'إضافة جديد')

@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">
                <span class="text-muted fw-light">{{ __('admin.company_classifications') ?? 'تصنيفات وحجم الشركات' }} /</span> {{ __('admin.add_new') ?? 'إضافة جديد' }}
            </h4>
            <a href="{{ route('company_classifications.index') }}" class="btn btn-label-secondary">
                <i class="ti tabler-arrow-left me-1"></i> {{ __('admin.back') ?? 'رجوع' }}
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('company_classifications.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('admin.name') ?? 'الاسم (مثل: A, B, كبير, متوسط)' }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('admin.type') ?? 'النوع' }} <span class="text-danger">*</span></label>
                            <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="company" {{ old('type') == 'company' ? 'selected' : '' }}>شركة (تصنيف)</option>
                                <option value="supplier" {{ old('type') == 'supplier' ? 'selected' : '' }}>مورد (حجم التوريد)</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">{{ __('admin.save') ?? 'حفظ' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
