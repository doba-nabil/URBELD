@extends('dashboard.layout.master')

@section('title', __('admin.add_membership'))

@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <form action="{{ route('memberships.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">
                    <span class="text-muted fw-light">{{ __('admin.memberships') }} /</span> {{ __('admin.add_membership') }}
                </h4>
                <div class="d-flex gap-2">
                    <a href="{{ route('memberships.index') }}" class="btn btn-label-secondary">
                        <i class="ti tabler-arrow-left me-1"></i> {{ __('admin.back') }}
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti tabler-device-floppy me-1"></i> {{ __('admin.save') }}
                    </button>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    @include('dashboard.memberships._form', [
                        'categories' => $categories ?? [],
                        'countries' => $countries ?? [],
                        'cities' => $cities ?? [],
                        'provider' => null,
                        'membership' => null
                    ])
                </div>
            </div>
        </form>
    </div>
@endsection

@section('dashboard-head')
    @include('dashboard.partials.create.css')
@endsection

@section('dashboard-footer')
    @include('dashboard.partials.create.js')
    @include('dashboard.memberships._scripts')
@endsection
