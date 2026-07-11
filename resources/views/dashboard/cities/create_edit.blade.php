@extends('dashboard.layout.master')
@section('title', isset($model) ? __('admin.edit') .' '. $model->name : __('admin.create_city'))

@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <h5 class="card-header">{{ isset($model) ? __('admin.edit') .' '. $model->name : __('admin.create') .' '. __('admin.city') }}</h5>
                    <div class="card-body">
                        <form id="formValidationExamples" class="row g-6" method="POST"
                              action="{{ isset($model) ? route('cities.update', $model->id) : route('cities.store') }}">
                            @csrf
                            @if(isset($model))
                                @method('PUT')
                            @endif
                            
                            <div class="col-md-6">
                                <label class="form-label">{{ __('admin.country') }}</label>
                                <select name="country_id" id="country_id" class="form-select" required>
                                    <option value="">{{ __('admin.choose_country') }}</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" 
                                            {{ old('country_id', isset($model) ? $model->country_id : '') == $country->id ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('admin.region') ?? 'المنطقة' }}</label>
                                <select name="region_id" id="region_id" class="form-select" required>
                                    <option value="">{{ __('admin.choose_region') ?? 'اختر المنطقة' }}</option>
                                    @php
                                        $currentCountry = old('country_id', $model->country_id ?? null);
                                    @endphp
                                    @foreach($regions as $region)
                                        @if(!$currentCountry || $region->country_id == $currentCountry)
                                            <option value="{{ $region->id }}" {{ old('region_id', $model->region_id ?? '') == $region->id ? 'selected' : '' }}>
                                                {{ $region->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('region_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

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
                        'name[ar]': {
                            validators: {
                                notEmpty: {
                                    message: messages.name_required
                                },
                                stringLength: {
                                    min: 2,
                                    message: messages.name_length
                                }
                            }
                        },
                        country_id: {
                            validators: {
                                notEmpty: {
                                    message: messages.required
                                }
                            }
                        },
                        region_id: {
                            validators: {
                                notEmpty: {
                                    message: messages.required
                                }
                            }
                        }
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap5: new FormValidation.plugins.Bootstrap5({
                            eleValidClass: '',
                            rowSelector: '.col-md-12'
                        }),
                        submitButton: new FormValidation.plugins.SubmitButton(),
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

            // Region Fetching logic
            const $countrySelect = $('#country_id');
            const $regionSelect = $('#region_id');

            if ($countrySelect.length && $regionSelect.length) {
                $countrySelect.on('change', function() {
                    const countryId = $(this).val();
                    $regionSelect.empty().append('<option value="">{{ __('admin.choose_region') ?? 'اختر المنطقة' }}</option>');
                    $regionSelect.trigger('change');
                    
                    if (countryId) {
                        $.ajax({
                            url: `{{ url('/admin-panel/regions/by-country') }}/${countryId}`,
                            type: 'GET',
                            success: function(data) {
                                if (data.status === 'success') {
                                    data.regions.forEach(region => {
                                        $regionSelect.append(new Option(region.name, region.id));
                                    });
                                    $regionSelect.trigger('change');
                                }
                            },
                            error: function(err) {
                                console.error('Error fetching regions:', err);
                            }
                        });
                    }
                });
            }
        });
    </script>
@endsection
