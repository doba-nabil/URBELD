@extends('dashboard.layout.master')
@section('title', 'Users - Edit')

@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <h5 class="card-header">{{ __('admin.edit') .' . ' . $user->name }}</h5>
                    <div class="card-body">
                        <form id="userForm" class="row g-6" method="POST" action="{{ route('admins.update', $user) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('admin.name') }}</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control">
                                @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('admin.email') }}</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control">
                                @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('admin.phone') }}</label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
                                @error('phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label d-block text-primary">{{ __('admin.notification_settings') }}</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" id="admin_email_notif" name="receive_email_notifications" value="1" {{ old('receive_email_notifications', $user->receive_email_notifications) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="admin_email_notif">
                                        {{ __('admin.receive_email_notifications') }}
                                    </label>
                                </div>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" id="admin_push_notif" name="receive_push_notifications" value="1" {{ old('receive_push_notifications', $user->receive_push_notifications) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="admin_push_notif">
                                        {{ __('admin.receive_push_notifications') }}
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('admin.password') }} <small class="text-muted">(leave blank to keep current)</small></label>
                                <input type="password" name="password" class="form-control">
                                @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('admin.confirm_password') }}</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                            @if($user->id != 1)
                            <div class="col-md-12 mb-3" id="branchesField">
                                <label for="select2Multiple" class="form-label">{{ __('admin.roles') }}</label>
                                <select id="select2Multiple" class="select2 form-select" name="roles[]" multiple>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'selected' : '' }}>
                                            {{ $role->getTranslation('display_name', app()->getLocale()) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

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
                                <button type="submit" class="btn btn-primary">{{ __('admin.update') }}</button>
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
    @include('dashboard.partials.edit.js')
    @php
        $messages = [
            'name_required' => __('admin.name_required'),
            'name_length' => __('admin.name_length'),
            'email_required' => __('admin.email_required'),
            'email_valid' => __('admin.email_valid'),
            'password_length' => __('admin.password_length'),
            'password_confirm' => __('admin.password_confirm'),
        ];
    @endphp

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const userForm = document.getElementById('userForm');
            if (userForm) {
                const messages = @json($messages);

                FormValidation.formValidation(userForm, {
                    fields: {
                        name: {
                            validators: {
                                notEmpty: { message: messages.name_required },
                                stringLength: { min: 3, max: 50, message: messages.name_length }
                            }
                        },
                        email: {
                            validators: {
                                notEmpty: { message: messages.email_required },
                                emailAddress: { message: messages.email_valid }
                            }
                        },
                        password: {
                            validators: {
                                stringLength: { min: 6, message: messages.password_length }
                            }
                        },
                        password_confirmation: {
                            validators: {
                                identical: {
                                    compare: () => userForm.querySelector('[name="password"]').value,
                                    message: messages.password_confirm
                                }
                            }
                        }
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap5: new FormValidation.plugins.Bootstrap5(),
                        submitButton: new FormValidation.plugins.SubmitButton(),
                        autoFocus: new FormValidation.plugins.AutoFocus(),
                        defaultSubmit: new FormValidation.plugins.DefaultSubmit()
                    }
                });
            }
        });
    </script>


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

                @if(isset($user) && $user->getFirstMediaUrl('users'))
                let mockFile = { name: "Current Image", size: 100 };
                myDropzone.emit("addedfile", mockFile);
                myDropzone.emit("thumbnail", mockFile, "{{ $user->getFirstMediaUrl('users') }}");
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
@endsection
