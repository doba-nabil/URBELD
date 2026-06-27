@extends('dashboard.layout.master')
@section('title', isset($model) ? __('admin.edit') .' '. $model->name : __('admin.create_country'))

@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <h5 class="card-header">{{ isset($model) ? __('admin.edit') .' '. $model->name : __('admin.create') .' '. __('admin.country') }}</h5>
                    <div class="card-body">
                        <form id="formValidationExamples" class="row g-6" method="POST"
                              action="{{ isset($model) ? route('countries.update', $model->id) : route('countries.store') }}" enctype="multipart/form-data">
                            @csrf
                            @if(isset($model))
                                @method('PUT')
                            @endif
                            @if(count(LaravelLocalization::getSupportedLocales()) > 1)
                                <ul class="nav nav-tabs" id="langTabs" role="tablist">
                                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                                                    id="{{$localeCode}}-tab" data-bs-toggle="tab"
                                                    data-bs-target="#{{$localeCode}}" type="button"
                                                    role="tab">{{ __('admin.'.$properties['name']) }}
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            <div class="tab-content mt-3 p-3" id="langTabsContent">
                                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                         id="{{$localeCode}}" role="tabpanel">
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <label class="form-label">{{ __('admin.name') }}</label>
                                                <input
                                                    value="{{ old("name.$localeCode", isset($model) ? $model->getTranslation('name',$localeCode) : '') }}"
                                                    type="text" class="form-control" name="name[{{$localeCode}}]"
                                                    placeholder="{{ __('admin.name') }}">
                                                @error("name.$localeCode")
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="col-12">

                                <div class="dropzone needsclick" id="dropzone-basic">
                                    <div class="dz-message needsclick">
                                        {{ __('admin.Drop files here or click to upload') }}
                                    </div>
                                </div>
                                @error("image")
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mt-4 text-end">
                                <button type="submit" class="btn btn-primary">{{ __('admin.save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('dashboard-head')
    @include('dashboard.partials.create.css')
@endsection

@section('dashboard-footer')
    @if(isset($model))
        @include('dashboard.partials.edit.js')
        <script>
            'use strict';

            (function () {
                Dropzone.autoDiscover = false;

                const dropzoneBasic = document.querySelector('#dropzone-basic');
                if (dropzoneBasic) {
                    const myDropzone = new Dropzone(dropzoneBasic, {
                        url: "#",
                        autoProcessQueue: false,
                        uploadMultiple: false,
                        maxFiles: 1,
                        acceptedFiles: "image/*",
                        addRemoveLinks: true,
                        dictDefaultMessage: "Drop files here or click to upload"
                    });

                    @if(isset($model) && $model->getFirstMediaUrl('countries'))
                    let mockFile = { name: "{{ __('admin.current_image') }}", size: 100 };
                    myDropzone.emit("addedfile", mockFile);
                    myDropzone.emit("thumbnail", mockFile, "{{ $model->getFirstMediaUrl('countries') }}");
                    myDropzone.emit("complete", mockFile);
                    myDropzone.files.push(mockFile);

                    const previewImg = dropzoneBasic.querySelector('.dz-preview img');
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
                            dropzoneBasic.closest("form").appendChild(inputFile);
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
            })();
        </script>
    @else
        @include('dashboard.partials.create.js')
    @endif
    @php
        $messages = [
            'name_required' => __('admin.name_required'),
            'required' => __('admin.required'),
            'name_length' => __('admin.name_length'),
        ];
    @endphp
    <script>
        'use strict';

        document.addEventListener('DOMContentLoaded', function () {
            const categoryForm = document.getElementById('formValidationExamples');

            if (categoryForm) {
                const messages = @json($messages);

                const fv = FormValidation.formValidation(categoryForm, {
                    fields: {
                        "name[ar]": {
                            validators: {
                                notEmpty: {message: messages.required},
                                stringLength: {
                                    min: 3,
                                    max: 50,
                                    message: messages.name_length
                                }
                            }
                        },
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap5: new FormValidation.plugins.Bootstrap5(),
                        submitButton: new FormValidation.plugins.SubmitButton(),
                        autoFocus: new FormValidation.plugins.AutoFocus(),
                        defaultSubmit: new FormValidation.plugins.DefaultSubmit()
                    }
                });

                fv.on('core.form.invalid', function () {
                    const firstInvalidField = categoryForm.querySelector('.is-invalid');

                    if (firstInvalidField) {
                        const tabPane = firstInvalidField.closest('.tab-pane');
                        if (tabPane) {
                            const tabId = tabPane.getAttribute('id');
                            const tabTrigger = document.querySelector(`[data-bs-target="#${tabId}"]`);

                            if (tabTrigger) {
                                const tabName = tabTrigger.innerText.trim();

                                Swal.fire({
                                    icon: 'error',
                                    title: '{{ __('admin.validation_error') }}',
                                    text: `{{ __('admin.please_check_inputs') }} `,
                                    confirmButtonText: '{{ __('admin.ok') }}'
                                });
                            }
                        }
                    }
                });
            }
        });
    </script>
@endsection
