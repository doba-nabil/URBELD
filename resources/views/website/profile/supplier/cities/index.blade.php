@extends('website.layouts.profile')

@section('title', 'مدن التوصيل')

@section('profile-content')
    <div class="about-me-section">
        <div class="container">
            <h2 class="about-me-title mb-4">مدن التوصيل</h2>
            
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('supplier.cities.store') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="form-label mb-3 fw-bold">اختر المدن التي توفر التوصيل إليها:</label>
                    <div class="row">
                        @foreach($allCities as $city)
                            <div class="col-md-3 col-sm-4 col-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="cities[]" value="{{ $city->id }}" id="city_{{ $city->id }}" {{ in_array($city->id, $selectedCities) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="city_{{ $city->id }}">
                                        {{ app()->getLocale() == 'ar' ? $city->name_ar : $city->name_en }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
@endsection
