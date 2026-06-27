@extends('website.layouts.profile', ['user' => $user])

@section('title', $user->name . ' - ' . __('website.nav_services'))

@section('profile-content')
    <div class="container py-4">
        <div class="row g-4">
            @forelse ($services as $service)
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="wyn-card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative">
                        <a href="{{ route('website.services.show', $service->id) }}" class="text-decoration-none">
                            <div class="wyn-card-image position-relative" style="height: 200px; overflow: hidden;">
                                <img src="{{ $service->getFirstMediaUrl('services') ?: asset('website/assets/img/service-placeholder.png') }}"
                                    alt="{{ $service->title }}" class="w-100 h-100 object-fit-cover transition-all" style="transition: transform 0.3s;">
                                <div class="wyn-card-overlay position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.6) 100%);"></div>
                            </div>
                            <div class="wyn-card-content p-4 bg-white position-relative">
                                <div class="card-content-header mb-2">
                                    <span style="z-index: 999;" class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">{{ $service->category->name ?? '' }}</span>
                                </div>
                                <h5 class="wyn-card-title text-dark fw-bold mb-0" style="font-size: 1.1rem; line-height: 1.5;">{{ $service->title }}</h5>
                            </div>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 bg-white rounded-4 shadow-sm">
                    <div class="empty-state-icon mb-3">
                        <i class="bi bi-briefcase text-muted" style="font-size: 4rem; opacity: 0.5;"></i>
                    </div>
                    <h4 class="text-muted mb-2">{{ __('website.no_services_available') }}</h4>
                </div>
            @endforelse
            
            @if($services->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $services->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('css')
    <style>
        .wyn-card:hover .wyn-card-image img {
            transform: scale(1.05);
        }
        .transition-all {
            transition: all .3s ease-in-out;
        }
    </style>
@endpush
