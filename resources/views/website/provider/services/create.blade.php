@extends('website.layouts.profile')

@section('title', __('website.add_new_service'))

@section('profile-content')
    <div class="about-me-section">
        <div class="container">
            <h2 class="about-me-title mb-4">{{ __('website.add_new_service') }}</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form action="{{ route('provider.services.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('website.service_title_ar') }} <span class="text-danger">*</span></label>
                                <input type="text" name="title[ar]" class="form-control" value="{{ old('title.ar') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('website.service_title_en') }}</label>
                                <input type="text" name="title[en]" class="form-control" value="{{ old('title.en') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('website.main_category') }} <span class="text-danger">*</span></label>
                                @if($mainCategories->isNotEmpty())
                                    <input type="hidden" name="category_id" value="{{ $mainCategories->first()->id }}">
                                    <select id="category_id" class="form-select border-primary bg-light" disabled>
                                        <option value="{{ $mainCategories->first()->id }}" selected>{{ $mainCategories->first()->name }}</option>
                                    </select>
                                @endif
                                
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('website.sub_category') }} <span class="text-danger">*</span></label>
                                <select name="sub_category_id" id="sub_category_id" class="form-select border-primary" required>
                                    <option value="">{{ __('website.choose_sub_category') }}</option>
                                    @foreach($subCategories as $cat)
                                        <option value="{{ $cat->id }}" data-parent="{{ $cat->parent_id }}" {{ old('sub_category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">{{ __('website.sub_categories_limit_note') }}</small>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">{{ __('website.service_content_ar') }} <span class="text-danger">*</span></label>
                                <textarea name="content[ar]" class="form-control" rows="5" required>{{ old('content.ar') }}</textarea>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">{{ __('website.service_content_en') }}</label>
                                <textarea name="content[en]" class="form-control" rows="4">{{ old('content.en') }}</textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('website.service_image_optional') }}</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>

                            <div class="col-md-6 mb-3 d-flex align-items-center">
                                <div class="form-check form-switch mt-4 fs-5">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">{{ __('website.activate_service') }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save"></i> {{ __('website.save_service') }}</button>
                            <a href="{{ route('provider.services.index') }}" class="btn btn-secondary px-4 ms-2">{{ __('website.cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('category_id');
        const subCategorySelect = document.getElementById('sub_category_id');
        const defaultOption = subCategorySelect.querySelector('option[value=""]').cloneNode(true);
        const allSubOptions = Array.from(subCategorySelect.options).filter(opt => opt.value !== "").map(opt => opt.cloneNode(true));
        
        // Store the initial selected value
        const initialSelectedValue = subCategorySelect.value;

        function filterSubCategories() {
            const selectedParentId = categorySelect.value;
            
            // Clear current options
            subCategorySelect.innerHTML = '';
            subCategorySelect.appendChild(defaultOption);

            let validOptionsCount = 0;
            let firstValidOption = null;

            allSubOptions.forEach(opt => {
                if (opt.getAttribute('data-parent') === selectedParentId) {
                    const clonedOpt = opt.cloneNode(true);
                    subCategorySelect.appendChild(clonedOpt);
                    validOptionsCount++;
                    if (!firstValidOption) firstValidOption = clonedOpt;
                }
            });

            // Automatically select if there is only 1 option, or preserve initial selection
            if (validOptionsCount === 1) {
                subCategorySelect.value = firstValidOption.value;
            }
        }

        categorySelect.addEventListener('change', function() {
            filterSubCategories();
            // User changed the main category, so reset the subcategory if validOptionsCount > 1
            if (subCategorySelect.options.length > 2) {
                subCategorySelect.value = '';
            }
        });
        
        // Initial filter on page load
        if (categorySelect.value) {
            filterSubCategories();
            // Set back the originally selected value if it exists in the valid options
            const exists = Array.from(subCategorySelect.options).some(opt => opt.value === initialSelectedValue);
            if (exists && initialSelectedValue) {
                subCategorySelect.value = initialSelectedValue;
            }
        } else {
            subCategorySelect.innerHTML = '';
            subCategorySelect.appendChild(defaultOption);
        }
    });
</script>
@endpush
