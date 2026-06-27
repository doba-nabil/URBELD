@extends('website.layouts.profile')

@section('title', __('admin.add_work') ?? 'إضافة عمل')

@section('profile-content')
    <div class="about-me-section">
        <div class="container">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 text-primary"><i class="bi bi-plus-circle me-2"></i> {{ __('admin.add_work') ?? 'إضافة عمل' }}</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('provider.works.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label">{{ __('admin.work_title') ?? 'اسم العمل' }} <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">{{ __('admin.work_description') ?? 'وصف العمل' }}</label>
                                <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                            </div>

                            <div class="col-12 mb-4">
                                <label class="form-label">{{ __('admin.work_images') ?? 'صور العمل' }} <span class="text-danger">*</span></label>
                                <input type="file" name="images[]" class="form-control" multiple accept="image/*" required>
                                <small class="text-muted d-block mt-1">يمكنك رفع أكثر من صورة واحدة</small>
                            </div>

                            <div class="col-12 text-end mt-4 pt-3 border-top">
                                <a href="{{ route('provider.works.index') }}" class="btn btn-light me-2">{{ __('website.back') ?? 'رجوع' }}</a>
                                <button type="submit" class="btn btn-primary px-4">{{ __('website.save_changes') ?? 'حفظ' }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
