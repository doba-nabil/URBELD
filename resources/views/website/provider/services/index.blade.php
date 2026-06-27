@extends('website.layouts.profile')

@section('title', __('website.my_services'))

@section('profile-content')
    <div class="about-me-section">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="about-me-title mb-0">{{ __('website.my_services') }}</h2>
                <a href="{{ route('provider.services.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> {{ __('website.add_service') }}
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover bg-white shadow-sm rounded">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('website.image') }}</th>
                            <th>{{ __('website.title') }}</th>
                            <th>{{ __('website.category') }}</th>
                            <th>{{ __('website.status') }}</th>
                            <th>{{ __('website.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                            <tr>
                                <td class="text-center align-middle">
                                    @if($service->getFirstMediaUrl('services'))
                                        <img src="{{ $service->getFirstMediaUrl('services') }}" width="60" class="rounded" alt="{{ __('website.service_image') }}">
                                    @else
                                        <span class="text-muted"><i class="bi bi-image" style="font-size: 2rem;"></i></span>
                                    @endif
                                </td>
                                <td class="align-middle fw-bold">{{ $service->getTranslation('title', app()->getLocale(), false) ?? $service->getTranslation('title', 'ar') }}</td>
                                <td class="align-middle">
                                    <span class="badge bg-primary">{{ $service->category->name ?? __('website.not_specified') }}</span>
                                    @if($service->subCategory)
                                        <span class="badge bg-secondary">{{ $service->subCategory->name }}</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if($service->is_active)
                                        <span class="badge bg-success">{{ __('website.active') }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ __('website.inactive') }}</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <a href="{{ route('provider.services.edit', $service->id) }}" class="btn btn-sm btn-info text-white"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('provider.services.destroy', $service->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('website.confirm_delete') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-briefcase mb-3 d-block" style="font-size: 3rem;"></i>
                                    {{ __('website.no_services_added') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
