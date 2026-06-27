@extends('dashboard.layout.master')

@section('title', __('admin.service_details'))

@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">{{ __('admin.service_details') }}</h4>
            <a href="{{ route('services.index') }}" class="btn btn-secondary">
                <i class="icon-base ti tabler-arrow-right"></i> {{ __('admin.back') }}
            </a>
        </div>

<div class="row">
    <div class="col-xl-8 col-lg-7 col-md-7">
        <div class="card mb-4">
            <div class="card-body">
                <div class="user-avatar-section">
                    <div class="d-flex align-items-center flex-column">
                        @if($service->getFirstMediaUrl('services'))
                            <img class="img-fluid rounded mb-3 pt-1" src="{{ $service->getFirstMediaUrl('services') }}" height="100" width="100" alt="Service Image">
                        @else
                            <div class="avatar avatar-xl mb-3">
                                <span class="avatar-initial rounded bg-label-primary"><i class="{{ $service->icon ?? 'ti ti-category' }} s-4"></i></span>
                            </div>
                        @endif
                        <div class="user-info text-center">
                            <h4 class="mb-2">{{ $service->title }}</h4>
                            <span class="badge bg-label-info mt-1">{{ $service->category ? $service->category->name : __('admin.no_category') }}</span>
                            @if($service->subCategory)
                                <span class="badge bg-label-secondary mt-1">{{ $service->subCategory->name }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <h5 class="pb-1 border-bottom mb-3 mt-4">{{ __('admin.details') }}</h5>
                <div class="info-container">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <span class="fw-medium me-1">{{ __('admin.status') }}:</span>
                            @if($service->is_active)
                                <span class="badge bg-label-success">{{ __('admin.active') }}</span>
                            @else
                                <span class="badge bg-label-danger">{{ __('admin.inactive') }}</span>
                            @endif
                        </li>
                        <li class="mb-2">
                            <span class="fw-medium me-1">{{ __('admin.provider') }}:</span>
                            <span>{{ $service->user ? $service->user->name : __('admin.not_specified') }}</span>
                        </li>
                        <li class="mb-2">
                            <span class="fw-medium me-1">{{ __('admin.sort_order') }}:</span>
                            <span>{{ $service->sort_order }}</span>
                        </li>
                        <li class="mb-2 pt-1">
                            <span class="fw-medium me-1">{{ __('admin.created_at') }}:</span>
                            <span>{{ $service->created_at->format('Y-m-d H:i') }}</span>
                        </li>
                    </ul>
                </div>
                
                <h5 class="pb-1 border-bottom mb-3 mt-4">{{ __('admin.content') }}</h5>
                <div class="service-content mt-3">
                    {!! $service->content !!}
                </div>
                
                <div class="d-flex justify-content-center pt-4">
                    <a href="{{ route('services.edit', $service->id) }}" class="btn btn-primary me-3">{{ __('admin.edit') }}</a>
                    <a href="{{ route('services.index') }}" class="btn btn-label-secondary">{{ __('admin.back') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
