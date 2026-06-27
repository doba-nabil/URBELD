<script>
    'use strict';
    Dropzone.autoDiscover = false;

    document.addEventListener('DOMContentLoaded', function () {
        const membershipType = document.getElementById('membership_type');
        const individualFields = document.getElementById('individual-fields');
        const companyFields = document.getElementById('company-fields');
        const mainCategorySelect = document.getElementById('main_category_id');
        const subCategoriesSelect = document.getElementById('sub_categories');
        const individualMainCategorySelect = document.getElementById('individual_main_category_id');
        const individualSubCategoriesSelect = document.getElementById('individual_sub_categories');

        // Toggle fields based on membership type
        if (membershipType) {
            const toggleFields = function() {
                const type = $(membershipType).val();
                if (type === 'individual') {
                    $('#individual-fields').show();
                    $('#company-fields').hide();
                    
                    // Disable company fields
                    if (mainCategorySelect) {
                        $(mainCategorySelect).prop('disabled', true).attr('name', '').trigger('change');
                    }
                    if (subCategoriesSelect) {
                        $(subCategoriesSelect).prop('disabled', true).attr('name', '').trigger('change');
                    }
                    
                    // Enable individual fields
                    if (individualMainCategorySelect) {
                        $(individualMainCategorySelect).prop('disabled', false).attr('name', 'main_category_id').trigger('change');
                    }
                    if (individualSubCategoriesSelect) {
                        $(individualSubCategoriesSelect).prop('disabled', false).attr('name', 'sub_categories[]').trigger('change');
                    }
                } else {
                    $('#individual-fields').hide();
                    $('#company-fields').show();
                    
                    // Disable individual fields
                    if (individualMainCategorySelect) {
                        $(individualMainCategorySelect).prop('disabled', true).attr('name', '').trigger('change');
                    }
                    if (individualSubCategoriesSelect) {
                        $(individualSubCategoriesSelect).prop('disabled', true).attr('name', '').trigger('change');
                    }
                    
                    // Enable company fields
                    if (mainCategorySelect) {
                        $(mainCategorySelect).prop('disabled', false).attr('name', 'main_category_id').trigger('change');
                    }
                    if (subCategoriesSelect) {
                        $(subCategoriesSelect).prop('disabled', false).attr('name', 'sub_categories[]').trigger('change');
                    }
                }
            };
            
            $(membershipType).on('change', toggleFields);
            // Call on page load to set initial state
            toggleFields();
        }

        // Function to load subcategories
        function loadSubCategories(mainCategorySelect, subCategoriesSelect) {
            if (mainCategorySelect && subCategoriesSelect) {
                $(mainCategorySelect).on('change', function(e) {
                    // Don't trigger AJAX if the element was changed programmatically via trigger('change') 
                    // without a specific flag, OR if it's disabled.
                    if ($(this).prop('disabled')) return;
                    
                    const categoryId = $(this).val();
                    const $subSelect = $(subCategoriesSelect);
                    
                    if (categoryId) {
                        const url = '{{ route("categories.children", ":id") }}'.replace(':id', categoryId);
                        fetch(url)
                            .then(response => response.json())
                            .then(data => {
                                $subSelect.empty();
                                if (data && data.children && Array.isArray(data.children)) {
                                    data.children.forEach(category => {
                                        const option = new Option(category.name, category.id, false, false);
                                        $subSelect.append(option);
                                    });
                                }
                                $subSelect.trigger('change');
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                $subSelect.empty().trigger('change');
                            });
                    } else {
                        $subSelect.empty().trigger('change');
                    }
                });
            }
        }

        // Load subcategories for company
        loadSubCategories(mainCategorySelect, subCategoriesSelect);
        
        // Load subcategories for individual
        loadSubCategories(individualMainCategorySelect, individualSubCategoriesSelect);

        // Initialize Dropzones
        initializeDropzone('dropzone-personal', 'personal_photo');
        initializeDropzone('dropzone-id-front', 'id_front_image');
        initializeDropzone('dropzone-id-back', 'id_back_image');
        initializeDropzone('dropzone-commercial', 'commercial_registration');

        // Initialize certificate dropzones
        document.querySelectorAll('.certificate-dropzone').forEach((dropzoneEl, index) => {
            initializeCertificateDropzone(dropzoneEl.id, index);
        });

        // Add certificate
        document.getElementById('add-certificate')?.addEventListener('click', function() {
            const container = document.getElementById('certificates-container');
            const index = container.querySelectorAll('.certificate-item').length;
            const newItem = document.createElement('div');
            newItem.className = 'certificate-item card border shadow-none p-3 mb-3';
            newItem.setAttribute('data-index', index);
            newItem.innerHTML = `
                <div class="row align-items-center">
                    <div class="col-md-5 mb-3 mb-md-0">
                        <label class="form-label">{{ __('admin.certificate_name') }}</label>
                        <input type="text" name="certificates[${index}][name]" class="form-control" placeholder="{{ __('admin.certificate_name') }}" required>
                    </div>
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label class="form-label">{{ __('admin.certificate_image') }}</label>
                        <div class="dropzone needsclick certificate-dropzone p-2" id="dropzone-cert-${index}" style="min-height: 80px;">
                            <div class="dz-message needsclick py-2 text-center">
                                <small class="text-muted">{{ __('admin.Drop files here or click to upload') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1 text-center">
                        <button type="button" class="btn btn-icon btn-label-danger remove-certificate mt-md-4">
                            <i class="ti tabler-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(newItem);
            
            // Initialize Dropzone after a small delay to ensure DOM is ready
            setTimeout(function() {
                initializeCertificateDropzone(`dropzone-cert-${index}`, index);
            }, 100);
        });

        // Remove certificate
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-certificate')) {
                const certificateItem = e.target.closest('.certificate-item');
                certificateItem.remove();
            }
            if (e.target.closest('.remove-work')) {
                const workItem = e.target.closest('.work-item');
                workItem.remove();
            }
        });

        // Add works
        document.getElementById('add-work')?.addEventListener('click', function() {
            const container = document.getElementById('works-container');
            const index = container.querySelectorAll('.work-item').length;
            const newItem = document.createElement('div');
            newItem.className = 'work-item card border shadow-none p-3 mb-3';
            newItem.setAttribute('data-index', index);
            newItem.innerHTML = `
                <div class="row align-items-start">
                    <div class="col-md-5 mb-3 mb-md-0">
                        <label class="form-label">{{ __('admin.work_title') ?? 'اسم العمل' }}</label>
                        <input type="text" name="works[${index}][title]" class="form-control mb-3" placeholder="{{ __('admin.work_title') ?? 'اسم العمل' }}" required>
                        
                        <label class="form-label">{{ __('admin.work_description') ?? 'وصف العمل' }}</label>
                        <textarea name="works[${index}][description]" class="form-control" rows="3" placeholder="{{ __('admin.work_description') ?? 'وصف العمل' }}"></textarea>
                    </div>
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label class="form-label">{{ __('admin.work_images') ?? 'صور العمل (يمكن رفع أكثر من صورة)' }}</label>
                        <input type="file" name="works[${index}][images][]" multiple class="form-control mb-3" accept="image/*">
                    </div>
                    <div class="col-md-1 text-center">
                        <button type="button" class="btn btn-icon btn-label-danger remove-work mt-md-4">
                            <i class="ti tabler-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(newItem);
        });

        // Load cities based on country selection
        const countrySelect = document.getElementById('membership_country_id');
        const citySelect = document.getElementById('membership_city_id');
        
        if (countrySelect && citySelect) {
            $(countrySelect).on('change', function() {
                if ($(this).prop('disabled')) return;
                
                const countryId = $(this).val();
                const $citySelect = $(citySelect);
                $citySelect.empty().append('<option value="">{{ __("admin.select_city") }}</option>');
                
                if (countryId) {
                    const url = '{{ route("cities.by-country", ":id") }}'.replace(':id', countryId);
                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success' && data.cities && Array.isArray(data.cities)) {
                                data.cities.forEach(city => {
                                    const option = new Option(city.name, city.id, false, false);
                                    $citySelect.append(option);
                                });
                            }
                            $citySelect.trigger('change');
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            $citySelect.trigger('change');
                        });
                } else {
                    $citySelect.trigger('change');
                }
            });
        }
    });

    function initializeDropzone(dropzoneId, inputName) {
        const dropzoneEl = document.querySelector(`#${dropzoneId}`);
        if (!dropzoneEl) return;

        // Get existing image URL from data attribute
        const existingImageUrl = dropzoneEl.getAttribute('data-image-url');

        const myDropzone = new Dropzone(dropzoneEl, {
            url: "#",
            autoProcessQueue: false,
            uploadMultiple: false,
            maxFiles: 1,
            acceptedFiles: "image/*",
            addRemoveLinks: true,
            dictDefaultMessage: "{{ __('admin.Drop files here or click to upload') }}"
        });

        // If editing and an image already exists, display it inside dropzone
        if (existingImageUrl && existingImageUrl.trim() !== '') {
            let mockFile = { name: "Current Image", size: 100, accepted: true };
            myDropzone.emit("addedfile", mockFile);
            myDropzone.emit("thumbnail", mockFile, existingImageUrl);
            myDropzone.emit("complete", mockFile);
            myDropzone.files.push(mockFile);

            const previewImg = dropzoneEl.querySelector('.dz-preview img');
            if (previewImg) {
                previewImg.style.width = '100%';
                previewImg.style.height = 'auto';
                previewImg.style.objectFit = 'contain';
            }
        }

        myDropzone.on("addedfile", function (file) {
            if (myDropzone.files.length > 1) {
                myDropzone.removeFile(myDropzone.files[0]);
            }
            let inputFile = document.querySelector(`input[name='${inputName}']`);
            if (!inputFile) {
                inputFile = document.createElement("input");
                inputFile.type = "file";
                inputFile.name = inputName;
                inputFile.classList.add("d-none");
                dropzoneEl.closest("form").appendChild(inputFile);
            }
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            inputFile.files = dataTransfer.files;
        });

        myDropzone.on("removedfile", function (file) {
            const inputFile = document.querySelector(`input[name='${inputName}']`);
            if (inputFile) {
                inputFile.value = "";
            }
            if (file && file.name === "Current Image") {
                // If removing the existing image, we might want to add a hidden input to indicate deletion
                // This depends on your backend logic
            }
        });
    }

    function initializeCertificateDropzone(dropzoneId, index) {
        const dropzoneEl = document.querySelector(`#${dropzoneId}`);
        if (!dropzoneEl) {
            console.error('Dropzone element not found:', dropzoneId);
            return;
        }

        // Check if Dropzone is already initialized on this element
        if (dropzoneEl.dropzone) {
            dropzoneEl.dropzone.destroy();
        }

        // Get existing image URL from data attribute
        const existingImageUrl = dropzoneEl.getAttribute('data-image-url');

        const myDropzone = new Dropzone(dropzoneEl, {
            url: "#",
            autoProcessQueue: false,
            uploadMultiple: false,
            maxFiles: 1,
            acceptedFiles: "image/*",
            addRemoveLinks: true,
            dictDefaultMessage: "{{ __('admin.Drop files here or click to upload') }}"
        });

        // If editing and an image already exists, display it inside dropzone
        if (existingImageUrl && existingImageUrl.trim() !== '') {
            let mockFile = { name: "Current Image", size: 100, accepted: true };
            myDropzone.emit("addedfile", mockFile);
            myDropzone.emit("thumbnail", mockFile, existingImageUrl);
            myDropzone.emit("complete", mockFile);
            myDropzone.files.push(mockFile);

            const previewImg = dropzoneEl.querySelector('.dz-preview img');
            if (previewImg) {
                previewImg.style.width = '100%';
                previewImg.style.height = 'auto';
                previewImg.style.objectFit = 'contain';
            }
        }

        myDropzone.on("addedfile", function (file) {
            if (myDropzone.files.length > 1) {
                myDropzone.removeFile(myDropzone.files[0]);
            }
            let inputFile = document.querySelector(`input[name='certificates[${index}][image]']`);
            if (!inputFile) {
                inputFile = document.createElement("input");
                inputFile.type = "file";
                inputFile.name = `certificates[${index}][image]`;
                inputFile.classList.add("d-none");
                dropzoneEl.closest("form").appendChild(inputFile);
            }
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            inputFile.files = dataTransfer.files;
        });

        myDropzone.on("removedfile", function (file) {
            const inputFile = document.querySelector(`input[name='certificates[${index}][image]']`);
            if (inputFile) {
                inputFile.value = "";
            }
            if (file && file.name === "Current Image") {
                // If removing the existing image, we might want to add a hidden input to indicate deletion
                // This depends on your backend logic
            }
        });
    }
</script>
