@extends('dashboard.layout.master')
@section('title', __('admin.edit_subscription_type'))
@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">{{ __('admin.edit_subscription_type') }}</h4>
            <a href="{{ route('subscription-types.index') }}" class="btn btn-secondary">
                <i class="icon-base ti tabler-arrow-right"></i> {{ __('admin.back') }}
            </a>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('subscription-types.update', $type->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('dashboard.subscription_types._form', ['type' => $type])
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">{{ __('admin.update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
