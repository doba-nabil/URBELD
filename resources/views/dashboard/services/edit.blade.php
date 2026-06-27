@extends('dashboard.layout.master')

@section('title', __('admin.edit_service'))

@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">{{ __('admin.edit_service') }}</h4>
            <a href="{{ route('services.index') }}" class="btn btn-secondary">
                <i class="icon-base ti tabler-arrow-right"></i> {{ __('admin.back') }}
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('services.update', $service->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('dashboard.services._form', ['service' => $service])
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">{{ __('admin.update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('dashboard-head')
    @include('dashboard.partials.create.css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endsection

@section('dashboard-footer')
    @include('dashboard.partials.edit.js')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.editor').summernote({
                height: 300,
                lang: 'ar-AR'
            });
        });

        // Icon preview
        document.addEventListener('DOMContentLoaded', function() {
            const iconInput = document.getElementById('icon-input');
            const iconPreview = document.getElementById('icon-preview');

            if (iconInput && iconPreview) {
                iconInput.addEventListener('input', function() {
                    if (this.value && this.value.trim()) {
                        iconPreview.innerHTML = '<i class="' + this.value.trim() + ' fs-3"></i>';
                    } else {
                        iconPreview.innerHTML = '';
                    }
                });
            }
        });

        // Dropzone for service image
        Dropzone.autoDiscover = false;
        document.addEventListener('DOMContentLoaded', function() {
            const dropzoneService = document.querySelector('#dropzone-service');
            if (dropzoneService) {
                const existingImageUrl = dropzoneService.getAttribute('data-image-url');
                
                const myDropzone = new Dropzone(dropzoneService, {
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

                    const previewImg = dropzoneService.querySelector('.dz-preview img');
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
                    let inputFile = document.querySelector("input[name='image']");
                    if (!inputFile) {
                        inputFile = document.createElement("input");
                        inputFile.type = "file";
                        inputFile.name = "image";
                        inputFile.classList.add("d-none");
                        dropzoneService.closest("form").appendChild(inputFile);
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
                    if (file && file.name === "Current Image") {
                        // If removing the existing image, we might want to add a hidden input to indicate deletion
                        // This depends on your backend logic
                    }
                });
            }
        });
    </script>
@endsection
