@extends('website.layouts.profile', ['user' => $user])

@section('title', __('website.certificates_for') . ' ' . $user->name)

@section('profile-content')
    <div class="container py-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="info-card bg-white p-4 rounded shadow-sm border">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                        <h5 class="info-card-title mb-0 fw-bold">
                            <i class="bi bi-patch-check me-2 text-primary"></i>{{ __('website.certificates_for') }} {{ $user->name }}
                        </h5>
                        <a href="{{ route('member.public', $user->id) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-arrow-right me-1"></i> {{ __('website.back_to_profile') }}
                        </a>
                    </div>

                    <div class="row g-4">
                        @forelse ($certificates as $cert)
                            <div class="col-md-4">
                                <div class="cert-item p-3 border rounded hover-shadow transition-all bg-light bg-opacity-50 h-100 d-flex flex-column">
                                    <div class="cert-preview mb-3 text-center py-4 bg-white rounded border">
                                        @if (str_contains($cert->mime_type, 'pdf'))
                                            <i class="bi bi-file-earmark-pdf text-danger" style="font-size: 4rem;"></i>
                                        @else
                                            <img src="{{ $cert->getUrl() }}" alt="{{ $cert->name }}" class="img-fluid rounded shadow-sm" style="max-height: 150px;">
                                        @endif
                                    </div>
                                    <div class="cert-info flex-grow-1">
                                        <h6 class="fw-bold mb-1">{{ $cert->getCustomProperty('certificate_name') ?: $cert->name }}</h6>
                                        <p class="text-muted small mb-3">
                                            {{ strtoupper($cert->extension) }} | {{ number_format($cert->size / 1024, 1) }} KB
                                        </p>
                                    </div>
                                    <div class="cert-actions mt-auto">
                                        <a href="{{ $cert->getUrl() }}" target="_blank" class="btn btn-primary w-100 rounded-pill">
                                            <i class="bi bi-eye me-1"></i> {{ __('website.view_certificate') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <div class="mb-3">
                                    <i class="bi bi-patch-check text-muted" style="font-size: 4rem;"></i>
                                </div>
                                <h5 class="text-muted">{{ __('website.no_certificates_found') }}</h5>
                                <p class="text-muted">{{ __('website.encourage_complete_profile') }}</p>
                                <a href="{{ route('member.public', $user->id) }}" class="btn btn-primary mt-3 px-4">{{ __('website.back_to_profile') }}</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .cert-item:hover {
            transform: translateY(-5px);
            border-color: var(--primary) !important;
        }
        .transition-all {
            transition: all 0.3s ease-in-out;
        }
        .hover-shadow:hover {
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.1) !important;
        }
    </style>
@endpush
