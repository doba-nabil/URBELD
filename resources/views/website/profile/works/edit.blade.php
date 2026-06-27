@extends('website.layouts.profile')

@section('title', __('admin.edit_work') ?? 'تعديل عمل')

@section('profile-content')
    <div class="about-me-section">
        <div class="container">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 text-primary"><i class="bi bi-pencil me-2"></i> {{ __('admin.edit_work') ?? 'تعديل عمل' }}</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('provider.works.update', $work->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label">{{ __('admin.work_title') ?? 'اسم العمل' }} <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" required value="{{ old('title', $work->title) }}">
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">{{ __('admin.work_description') ?? 'وصف العمل' }}</label>
                                <textarea name="description" class="form-control" rows="4">{{ old('description', $work->description) }}</textarea>
                            </div>

                            <div class="col-12 mb-4">
                                <label class="form-label">{{ __('admin.work_images') ?? 'صور العمل' }}</label>
                                <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                                <small class="text-muted d-block mt-1">سيتم إضافة هذه الصور إلى الصور الحالية (يمكنك تركها فارغة)</small>
                            </div>

                            @if($work->hasMedia('work_images'))
                                <div class="col-12 mb-4">
                                    <h6 class="mb-3">الصور الحالية:</h6>
                                    <div class="row g-3">
                                        @foreach($work->getMedia('work_images') as $media)
                                            <div class="col-auto position-relative" id="media-{{ $media->id }}">
                                                <div class="border rounded p-1" style="width: 120px; height: 120px;">
                                                    <img src="{{ $media->getUrl() }}" alt="Work Image" class="w-100 h-100 object-fit-cover rounded">
                                                </div>
                                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 start-0 m-2 delete-media" data-id="{{ $media->id }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="col-12 text-end mt-4 pt-3 border-top">
                                <a href="{{ route('provider.works.index') }}" class="btn btn-light me-2">{{ __('website.back') ?? 'رجوع' }}</a>
                                <button type="submit" class="btn btn-primary px-4">{{ __('website.save_changes') ?? 'حفظ التعديلات' }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    document.querySelectorAll('.delete-media').forEach(button => {
        button.addEventListener('click', function() {
            const mediaId = this.getAttribute('data-id');
            const workId = '{{ $work->id }}';
            
            Swal.fire({
                title: '{{ __("website.are_you_sure") ?? "هل أنت متأكد؟" }}',
                text: '{{ __("website.delete_request_warning") ?? "سيتم حذف هذه الصورة نهائياً." }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '{{ __("website.yes_delete_request") ?? "نعم، احذف" }}',
                cancelButtonText: '{{ __("website.cancel") ?? "إلغاء" }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`{{ url('provider/works') }}/${workId}/media/${mediaId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            document.getElementById('media-' + mediaId).remove();
                            Swal.fire(
                                'تم الحذف!',
                                'تم حذف الصورة بنجاح.',
                                'success'
                            );
                        } else {
                            Swal.fire(
                                'خطأ!',
                                data.message || 'حدث خطأ أثناء الحذف.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        Swal.fire(
                            'خطأ!',
                            'حدث خطأ في الاتصال بالسيرفر.',
                            'error'
                        );
                    });
                }
            });
        });
    });
</script>
@endpush
