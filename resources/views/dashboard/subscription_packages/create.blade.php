@extends('dashboard.layout.master')
@section('title', __('admin.add_subscription_package'))
@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">{{ __('admin.add_subscription_package') }}</h4>
            <a href="{{ route('subscription-packages.index') }}" class="btn btn-secondary">
                <i class="icon-base ti tabler-arrow-right"></i> {{ __('admin.back') }}
            </a>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('subscription-packages.store') }}" method="POST">
                    @csrf
                    @include('dashboard.subscription_packages._form')
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">{{ __('admin.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
