@extends('dashboard.layout.master')

@section('title', __('admin.edit_category'))

@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">{{ __('admin.edit_category') }}</h4>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                <i class="icon-base ti tabler-arrow-right"></i> {{ __('admin.back') }}
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('dashboard.categories._form', ['parents' => $parents, 'category' => $category])
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">{{ __('admin.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('dashboard-head')
    @include('dashboard.partials.create.css')
@endsection

@section('dashboard-footer')
    @include('dashboard.partials.edit.js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const iconInput = document.getElementById('icon-input');
            const iconPreview = document.getElementById('icon-preview');
            const parentSelect = document.getElementById('parent_id');

            // Update icon preview on input change
            if (iconInput && iconPreview) {
                iconInput.addEventListener('input', function () {
                    if (this.value && this.value.trim()) {
                        iconPreview.innerHTML = '<i class="' + this.value.trim() + ' fs-3"></i>';
                    } else {
                        iconPreview.innerHTML = '';
                    }
                });
            }

            // Show/hide hint based on parent selection
            if (parentSelect) {
                const hintElement = parentSelect.parentElement.querySelector('.text-muted');
                parentSelect.addEventListener('change', function () {
                    if (this.value) {
                        if (hintElement) {
                            hintElement.innerHTML = '<i class="ti tabler-info-circle"></i> سيتم إضافة هذا القسم كقسم فرعي تحت: <strong>' + this.options[this.selectedIndex].text.split(' - ')[0] + '</strong>';
                        }
                    } else {
                        if (hintElement) {
                            hintElement.innerHTML = '<i class="ti tabler-info-circle"></i> سيتم إضافة هذا القسم كقسم رئيسي';
                        }
                    }
                });
            }

            // Dropzone for category image
            Dropzone.autoDiscover = false;
            const dropzoneCategory = document.querySelector('#dropzone-category');
            if (dropzoneCategory) {
                const myDropzone = new Dropzone(dropzoneCategory, {
                    url: "#",
                    autoProcessQueue: false,
                    uploadMultiple: false,
                    maxFiles: 1,
                    acceptedFiles: "image/*",
                    addRemoveLinks: true,
                    dictDefaultMessage: "{{ __('admin.Drop files here or click to upload') }}"
                });

                @if(isset($category) && $category->getFirstMediaUrl('categories'))
                let mockFile = { name: "Current Image", size: 100 };
                myDropzone.emit("addedfile", mockFile);
                myDropzone.emit("thumbnail", mockFile, "{{ $category->getFirstMediaUrl('categories') }}");
                myDropzone.emit("complete", mockFile);
                myDropzone.files.push(mockFile);

                const previewImg = dropzoneCategory.querySelector('.dz-preview img');
                if (previewImg) {
                    previewImg.style.width = '100%';
                    previewImg.style.height = 'auto';
                    previewImg.style.objectFit = 'contain';
                }
                @endif

                myDropzone.on("addedfile", function (file) {
                    if (myDropzone.files.length > 1) {
                        myDropzone.removeFile(myDropzone.files[0]);
                    }
                    let inputFile = document.querySelector("input[name='image']");
                    if (!inputFile) {
                        inputFile = document.createElement("input");
                        inputFile.type = "file";
                        inputFile.name = "image";
                        inputFile.classList.add("d-none");
                        dropzoneCategory.closest("form").appendChild(inputFile);
                    }
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    inputFile.files = dataTransfer.files;
                });

                myDropzone.on("removedfile", function (file) {
                    const inputFile = document.querySelector("input[name='image']");
                    if (inputFile) {
                        inputFile.value = "";
                    }
                    if(file && file.name === "Current Image"){
                    }
                });
            }
        });
    </script>
@endsection

