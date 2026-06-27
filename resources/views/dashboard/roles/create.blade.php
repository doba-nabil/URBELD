@extends('dashboard.layout.master')
@section('title', __('admin.create') .' . '. __('admin.role'))

@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <h5 class="card-header">{{ __('admin.create') .' '. __('admin.role') }}</h5>
                    <div class="card-body">
                        <form id="formValidationExamples" class="row g-6" method="POST"
                              action="{{ route('roles.store') }}">
                            @csrf
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
                                                <input value="{{ old("display_name.$localeCode") }}" type="text" class="form-control @error("display_name.$localeCode") is-invalid @enderror" name="display_name[{{$localeCode}}]"
                                                       placeholder="{{ __('admin.name') }}">
                                                @error("display_name.$localeCode")
                                                <div class="invalid-feedback d-block">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                                @error("display_name")
                                                @if($loop->first)
                                                <div class="invalid-feedback d-block">
                                                    {{ $message }}
                                                </div>
                                                @endif
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="form-group">
                                <div class="d-flex justify-content-between">
                                    <label>{{ __('admin.permissions') }}</label>
                                    @if($permissions->count() > 0)
                                    <div class="form-check mb-3">
                                        <input type="checkbox" class="form-check-input" id="select-all">
                                        <label class="form-check-label" for="select-all">{{ __('admin.select_all') }}</label>
                                    </div>
                                    @endif
                                </div>
                                <div class="row">
                                    @if($permissions->count() > 0)
                                        @foreach($permissions as $group => $groupPermissions)
                                            @if($groupPermissions->count() > 0)
                                                <div class="card mt-4">
                                                    <div class="card-header">
                                                        <h6 class="text-success mb-0">
                                                            @php
                                                                $firstPermission = $groupPermissions->first();
                                                                $groupName = $firstPermission->getTranslation('group_name', app()->getLocale());
                                                                if (!$groupName && is_array($firstPermission->group_name)) {
                                                                    $groupName = $firstPermission->group_name[app()->getLocale()] ?? $firstPermission->group_name['ar'] ?? $firstPermission->group_name['en'] ?? null;
                                                                }
                                                                echo $groupName ?? $firstPermission->group ?? $group;
                                                            @endphp
                                                        </h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            @foreach($groupPermissions as $permission)
                                                                <div class="col-md-3">
                                                                    <div class="form-check form-check-info mt-2">
                                                                        <input class="form-check-input permission-checkbox"
                                                                               type="checkbox"
                                                                               value="{{ $permission->id }}"
                                                                               id="addon-{{ $permission->id }}"
                                                                               name="permissions[]">
                                                                        <label class="form-check-label"
                                                                               for="addon-{{ $permission->id }}">
                                                                            @php
                                                                                $displayName = $permission->getTranslation('display_name', app()->getLocale());
                                                                                if (!$displayName && is_array($permission->display_name)) {
                                                                                    $displayName = $permission->display_name[app()->getLocale()] ?? $permission->display_name['ar'] ?? $permission->display_name['en'] ?? null;
                                                                                }
                                                                                echo $displayName ?? $permission->name;
                                                                            @endphp
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @else
                                        <div class="alert alert-warning mt-3">
                                            <i class="fa fa-exclamation-triangle"></i> لا توجد صلاحيات متاحة. يرجى تشغيل seeder للصلاحيات.
                                        </div>
                                    @endif
                                </div>
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
    @include('dashboard.partials.create.js')
    <script>
        'use strict';

        document.addEventListener('DOMContentLoaded', function () {
            const categoryForm = document.getElementById('formValidationExamples');

            if (categoryForm) {
                // Get all locale codes
                const localeCodes = [];
                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    localeCodes.push('{{ $localeCode }}');
                @endforeach

                // Build fields object for all locales
                const fields = {};
                localeCodes.forEach(locale => {
                    fields[`display_name[${locale}]`] = {
                        validators: {
                            notEmpty: {
                                message: locale === 'ar' ? 'الاسم بالعربية مطلوب' : 'Name is required'
                            },
                            stringLength: {
                                min: 3,
                                max: 255,
                                message: locale === 'ar' ? 'الاسم يجب أن يكون بين 3 و 255 حرف' : 'Name must be between 3 and 255 characters'
                            }
                        }
                    };
                });

                const fv = FormValidation.formValidation(categoryForm, {
                    fields: fields,
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
                                    text: ` {{ __('admin.please_check_inputs') }} `,
                                    confirmButtonText: '{{ __('admin.ok') }}'
                                });
                            }
                        }
                    }
                });
            }
        });
        document.addEventListener("DOMContentLoaded", function () {
            const selectAll = document.getElementById("select-all");
            const checkboxes = document.querySelectorAll(".permission-checkbox");

            selectAll.addEventListener("change", function () {
                checkboxes.forEach(cb => cb.checked = this.checked);
            });
            checkboxes.forEach(cb => {
                cb.addEventListener("change", function () {
                    if (document.querySelectorAll(".permission-checkbox:checked").length === checkboxes.length) {
                        selectAll.checked = true;
                    } else {
                        selectAll.checked = false;
                    }
                });
            });
        });
    </script>
@endsection
