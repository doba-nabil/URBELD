@extends('dashboard.layout.master')
@section('title', isset($page) ? __('admin.edit') . ' ' . $page->title : __('admin.add_new'))

@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <h5 class="card-header">{{ __('admin.edit') . ' ' . $page->title }}</h5>
                    <div class="card-body">
                        <form id="formValidationExamples" class="row g-6" method="POST"
                            action="{{ isset($page) ? route('pages.update', $page->id) : route('pages.store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            @if (isset($page))
                                @method('PUT')
                            @endif
                            @if (count(LaravelLocalization::getSupportedLocales()) > 1)
                                <!-- Tabs for languages -->
                                <ul class="nav nav-tabs" id="langTabs" role="tablist">
                                    @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                                                id="{{ $localeCode }}-tab" data-bs-toggle="tab"
                                                data-bs-target="#{{ $localeCode }}" type="button" role="tab">
                                                {{ __('admin.' . $properties['name']) }}
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            <div class="tab-content mt-3 p-3" id="langTabsContent">
                                @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                        id="{{ $localeCode }}" role="tabpanel">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">{{ __('admin.title') }}</label>
                                                <input type="text" class="form-control" name="title[{{ $localeCode }}]"
                                                    value="{{ old("title.$localeCode", isset($page) ? $page->getTranslation('title', $localeCode) : '') }}">
                                                @error("title.$localeCode")
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                 <label class="form-label">{{ __('admin.page_type') }}</label>
                                                <select name="type" class="form-select page-type-select">
                                                    <option value="content"
                                                        {{ old('type', isset($page) ? $page->type : '') == 'content' ? 'selected' : '' }}>
                                                        {{ __('admin.page_type_content') }}</option>
                                                    <option value="link"
                                                        {{ old('type', isset($page) ? $page->type : '') == 'link' ? 'selected' : '' }}>
                                                        {{ __('admin.page_type_link') }}</option>
                                                </select>
                                            </div>

                                            <div
                                                class="col-md-12 target-url-container {{ old('type', isset($page) ? $page->type : 'content') == 'link' ? '' : 'd-none' }}">
                                                <label class="form-label">{{ __('admin.target_url') }}</label>
                                                <input type="text" name="target_url" class="form-control"
                                                    placeholder="https://..."
                                                    value="{{ old('target_url', isset($page) ? $page->target_url : '') }}">
                                                <small class="text-muted">{{ __('admin.target_url_hint') }}</small>
                                            </div>

                                            <div
                                                class="col-md-12 content-editor-container {{ old('type', isset($page) ? $page->type : 'content') == 'content' ? '' : 'd-none' }}">
                                                <label class="form-label">{{ __('admin.content') }}</label>
                                                <textarea class="form-control editor2" name="content[{{ $localeCode }}]">{{ old("content.$localeCode", isset($page) ? $page->getTranslation('content', $localeCode) : '') }}</textarea>
                                                @error("content.$localeCode")
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-12 mt-10">
                                                <label for="video"
                                                    class="form-label d-flex justify-content-between">{{ __('admin.video') }}
                                                </label>
                                                <div class="input-group">
                                                    <input class="form-control" name="page_video" id="file-input"
                                                        type="file" accept="video/*">
                                                    <label class="input-group-text" for="file-input">{{ __('admin.choose_video') }}</label>
                                                </div>
                                                <div class="text-center">
                                                    <video
                                                        src="{{ $page->getFirstMediaUrl('page_video') ? $page->getFirstMediaUrl('page_video') : '' }}"
                                                        id="video"
                                                        class="{{ $page->getFirstMediaUrl('page_video') ? '' : 'd-none' }}"
                                                        style="width: 100%" height="200" controls></video>
                                                </div>
                                                @if ($page->getFirstMediaUrl('page_video'))
                                                    <a href="{{ route('admin.delete_page_video', $page->id) }}"
                                                        class="btn btn-danger">{{ __('admin.delete_video') }}</a>
                                                @endif
                                                @error('page_video')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
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
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <style>
        .note-editor {
            overflow: visible !important;
        }

        .note-editor .modal {
            z-index: 99999 !important;
        }

        .note-editor .modal-backdrop {
            z-index: 99998 !important;
        }

        .note-popover {
            z-index: 99999 !important;
        }

        .note-modal-content {
            margin: 25vh auto;
        }
    </style>
@endsection

@section('dashboard-footer')
    @include('dashboard.partials.create.js')
    @php
        $messages = [
            'name_required' => __('admin.name_required'),
            'required' => __('admin.required'),
            'name_length' => __('admin.name_length'),
            'email_required' => __('admin.email_required'),
            'email_valid' => __('admin.email_valid'),
            'password_required' => __('admin.password_required'),
            'password_length' => __('admin.password_length'),
            'password_confirm' => __('admin.password_confirm'),
        ];
    @endphp
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/lang/summernote-ar-AR.js"></script>
    <script>
        $(document).ready(function() {
            $('.editor2').summernote({
                height: 300,
                dialogsInBody: true,
                lang: "ar-AR",
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'unlink', 'picture', 'hr', 'codeview']]
                ]
            });

            // Handle Page Type Toggling
            $(document).on('change', '.page-type-select', function() {
                const val = $(this).val();
                $('.page-type-select').val(val);

                if (val === 'link') {
                    $('.content-editor-container').addClass('d-none');
                    $('.target-url-container').removeClass('d-none');
                } else {
                    $('.content-editor-container').removeClass('d-none');
                    $('.target-url-container').addClass('d-none');
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pageForm = document.getElementById('formValidationExamples');

            if (pageForm) {
                const fv = FormValidation.formValidation(pageForm, {
                    fields: {
                        @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            "title[{{ $localeCode }}]": {
                                validators: {
                                    notEmpty: {
                                        message: '{{ $properties['name'] }} title is required'
                                    }
                                }
                            },
                            "content[{{ $localeCode }}]": {
                                validators: {
                                    callback: {
                                        message: '{{ $properties['name'] }} content is required',
                                        callback: function(input) {
                                            const type = document.querySelector('.page-type-select')
                                                .value;
                                            return type !== 'content' || input.value !== '';
                                        }
                                    }
                                }
                            },
                        @endforeach
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap5: new FormValidation.plugins.Bootstrap5(),
                        submitButton: new FormValidation.plugins.SubmitButton(),
                        autoFocus: new FormValidation.plugins.AutoFocus(),
                        defaultSubmit: new FormValidation.plugins.DefaultSubmit()
                    }
                });

                // Alert for invalid fields in a tab
                fv.on('core.form.invalid', function() {
                    const firstInvalidField = pageForm.querySelector('.is-invalid');
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
                                    text: ` "${tabName}"{{ __('admin.validation_error_lang') }} `,
                                    confirmButtonText: '{{ __('admin.ok') }}'
                                });
                            }
                        }
                    }
                });
            }
        });
    </script>
    <script>
        const input = document.getElementById('file-input');
        const video = document.getElementById('video');

        input.addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;

            if (!file.type.startsWith("video/")) {
                alert("{{ __('admin.video_only_alert') }}");
                return;
            }

            const reader = new FileReader();

            reader.onload = function(e) {
                video.classList.add('d-inline-block');
                video.classList.remove('d-none');
                video.src = e.target.result;
                video.load();
                video.play();
            };

            reader.readAsDataURL(file);
        });
    </script>
@endsection
