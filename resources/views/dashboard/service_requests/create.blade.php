@extends('dashboard.layout.master')

@section('title', __('admin.add_service_request'))

@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">{{ __('admin.add_service_request') }}</h4>
            <a href="{{ route('service-requests.index') }}" class="btn btn-secondary">
                <i class="icon-base ti tabler-arrow-right"></i> {{ __('admin.back') }}
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('service-requests.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('dashboard.service_requests._form', ['categories' => $categories, 'activityTypes' => $activityTypes, 'users' => $users])
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">{{ __('admin.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('dashboard-footer')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const providerSelect = document.getElementById('provider_id');
            const categorySelect = document.getElementById('category_id');
            const subCategorySelect = document.getElementById('sub_category_id');
            const contractingFields = document.getElementById('contracting-fields');
            const engineeringFields = document.getElementById('engineering-fields');
            const environmentFields = document.getElementById('environment-fields');

            // Store original categories for reset
            const originalCategories = Array.from(categorySelect.options).map(opt => ({
                value: opt.value,
                text: opt.text,
                slug: opt.dataset.slug
            }));

            function toggleFields() {
                const categorySlug = categorySelect.options[categorySelect.selectedIndex]?.dataset?.slug || '';

                // Hide all
                if (contractingFields) contractingFields.style.display = 'none';
                if (engineeringFields) engineeringFields.style.display = 'none';
                if (environmentFields) environmentFields.style.display = 'none';

                // Show relevant
                if (categorySlug === 'contracting' && contractingFields) {
                    contractingFields.style.display = 'block';
                } else if (categorySlug === 'engineering-consulting' && engineeringFields) {
                    engineeringFields.style.display = 'block';
                } else if (categorySlug === 'environment' && environmentFields) {
                    environmentFields.style.display = 'block';
                }
            }

            // AJAX to filter categories by provider
            if (providerSelect) {
                providerSelect.addEventListener('change', function() {
                    const providerId = this.value;
                    if (!providerId) {
                        // Reset to original categories if no provider selected
                        updateCategoryOptions(originalCategories);
                        return;
                    }

                    fetch(`{{ route('service-requests.provider-categories', ':id') }}`.replace(':id', providerId))
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                updateCategoryOptions(data.main_categories);
                                // Store subcategories for later filtering
                                window.providerSubCategories = data.sub_categories;
                            }
                        });
                });
            }

            function updateCategoryOptions(categories) {
                const currentVal = categorySelect.value;
                categorySelect.innerHTML = '<option value="">{{ __('admin.choose_category') }}</option>';
                
                categories.forEach(cat => {
                    const option = new Option(cat.name, cat.id);
                    if (cat.slug) option.dataset.slug = cat.slug;
                    categorySelect.add(option);
                });

                categorySelect.value = currentVal;
                $(categorySelect).trigger('change');
            }

            // Load subcategories when category changes
            if (categorySelect) {
                categorySelect.addEventListener('change', function() {
                    toggleFields();
                    const categoryId = this.value;
                    subCategorySelect.innerHTML = '<option value="">{{ __('admin.choose_sub_category') ?? 'اختر القسم الفرعي' }}</option>';
                    
                    if (!categoryId) return;

                    // If provider is selected, filter from pre-fetched provider subcategories
                    if (window.providerSubCategories) {
                        const filtered = window.providerSubCategories.filter(s => s.parent_id == categoryId);
                        filtered.forEach(s => {
                            subCategorySelect.add(new Option(s.name, s.id));
                        });
                    } else {
                        // Otherwise fetch normally
                        fetch(`{{ route('categories.children', ':id') }}`.replace(':id', categoryId))
                            .then(response => response.json())
                            .then(data => {
                                if (data.children) {
                                    data.children.forEach(s => {
                                        subCategorySelect.add(new Option(s.name, s.id));
                                    });
                                }
                            });
                    }
                });
            }
        });
    </script>
@endsection
