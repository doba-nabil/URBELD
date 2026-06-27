@extends('dashboard.layout.master')

@section('title', __('admin.add_success_partner'))

@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">{{ __('admin.add_success_partner') }}</h4>
            <a href="{{ route('success-partners.index') }}" class="btn btn-secondary">
                <i class="icon-base ti tabler-arrow-right"></i> {{ __('admin.back') }}
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('success-partners.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('dashboard.success_partners._form')
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
    @include('dashboard.partials.create.js')
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
            });
        }
    </script>
@endsection
