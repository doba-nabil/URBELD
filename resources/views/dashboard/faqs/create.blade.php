@extends('dashboard.layout.master')
@section('title', __('admin.add_faq'))

@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <h5 class="card-header">{{ __('admin.add_faq') }}</h5>
                    <div class="card-body">
                        <form id="formValidationExamples" class="row g-6" method="POST" action="{{ route('faqs.store') }}">
                            @csrf

                            @if (count(LaravelLocalization::getSupportedLocales()) > 1)
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
                                            <div class="col-md-12">
                                                <label class="form-label">{{ __('admin.question') }}</label>
                                                <input type="text" class="form-control"
                                                    name="question[{{ $localeCode }}]"
                                                    value="{{ old("question.$localeCode") }}">
                                                @error("question.$localeCode")
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-label">{{ __('admin.answer') }}</label>
                                                <textarea class="form-control" name="answer[{{ $localeCode }}]" rows="4">{{ old("answer.$localeCode") }}</textarea>
                                                @error("answer.$localeCode")
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="row mt-3 p-3">
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('admin.status') }}</label>
                                    <select name="is_active" class="form-select">
                                        <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>{{ __('admin.active') }}
                                        </option>
                                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>{{ __('admin.inactive') }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('admin.sort_order') }}</label>
                                    <input type="number" name="sort_order" class="form-control"
                                        value="{{ old('sort_order', 0) }}">
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

@section('dashboard-footer')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formValidationExamples');
            if (form) {
                // Add validation if needed, but simple server side is usually enough for FAQs
            }
        });
    </script>
@endsection
