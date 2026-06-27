@extends('dashboard.layout.master')

@section('title', __('admin.edit_success_partner'))

@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">{{ __('admin.edit_success_partner') }}</h4>
            <a href="{{ route('success-partners.index') }}" class="btn btn-secondary">
                <i class="icon-base ti tabler-arrow-right"></i> {{ __('admin.back') }}
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('success-partners.update', $partner->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('dashboard.success_partners._form', ['partner' => $partner])
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
@endsection

@section('dashboard-footer')
    @include('dashboard.partials.edit.js')
    <script>
        // Dropzone for partner image
        Dropzone.autoDiscover = false;
        const dropzonePartner = document.querySelector('#dropzone-partner');
        if (dropzonePartner) {
            const myDropzone = new Dropzone(dropzonePartner, {
                url: "#",
                autoProcessQueue: false,
                uploadMultiple: false,
                maxFiles: 1,
                acceptedFiles: "image/*",
                addRemoveLinks: true,
                dictDefaultMessage: "{{ __('admin.Drop files here or click to upload') }}"
            });

            @if(isset($partner) && $partner->getFirstMediaUrl('partners'))
            let mockFile = { name: "Current Image", size: 100 };
            myDropzone.emit("addedfile", mockFile);
            myDropzone.emit("thumbnail", mockFile, "{{ $partner->getFirstMediaUrl('partners') }}");
            myDropzone.emit("complete", mockFile);
            myDropzone.files.push(mockFile);

            const previewImg = dropzonePartner.querySelector('.dz-preview img');
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
                    dropzonePartner.closest("form").appendChild(inputFile);
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
    </script>
@endsection
