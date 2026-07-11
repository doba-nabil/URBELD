@extends('website.layouts.profile')

@section('title', __('website.delivery_cities') ?? 'مدن التوصيل')

@section('profile-content')
<style>
    .city-card {
        background: #fff;
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 12px;
        padding: 15px 20px;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .city-card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transform: translateY(-2px);
    }
    .city-card.active {
        border-color: var(--primary);
        background: rgba(1, 77, 64, 0.02); /* Using primary color hint */
    }
    .form-switch .form-check-input {
        width: 3em;
        height: 1.5em;
        cursor: pointer;
    }
    .form-switch .form-check-input:checked {
        background-color: var(--primary);
        border-color: var(--primary);
    }
</style>

<div class="incoming-requests-wrapper mt-4 mb-5" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="container bg-light rounded-4 p-4 shadow-sm" style="background-color: #f8f9fa !important;">
        
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0 text-dark">مدن التوصيل</h3>
            <a href="{{ route('profile.edit') }}" class="text-decoration-none text-muted small fw-bold">
                العودة للوحة التحكم <i class="bi bi-arrow-left ms-1"></i>
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success rounded-pill px-4 shadow-sm border-0">{{ session('success') }}</div>
        @endif

        <div class="bg-white p-4 rounded-4 shadow-sm border border-light">
            <form action="{{ route('supplier.cities.store') }}" method="POST">
                @csrf
                
                <p class="text-muted mb-4 fs-5">يرجى تفعيل المدن التي يتوفر لديك التوصيل إليها:</p>
                
                <div class="row g-3">
                    @foreach($allCities as $city)
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <label class="w-100 mb-0" style="cursor: pointer;">
                                <div class="city-card d-flex align-items-center justify-content-between {{ in_array($city->id, $selectedCities) ? 'active' : '' }}">
                                    <span class="fw-bold text-dark">{{ $city->name }}</span>
                                    <div class="form-check form-switch m-0 p-0 d-flex align-items-center justify-content-end" style="width: auto;">
                                        <input class="form-check-input m-0 float-none" type="checkbox" role="switch" name="cities[]" value="{{ $city->id }}" {{ in_array($city->id, $selectedCities) ? 'checked' : '' }} onchange="this.closest('.city-card').classList.toggle('active')">
                                    </div>
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5 text-end">
                    <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill shadow-sm fw-bold">
                        <i class="fas fa-save ms-2"></i> حفظ الإعدادات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
