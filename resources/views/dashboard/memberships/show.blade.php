@extends('dashboard.layout.master')
@php
    $pageTitle = __('admin.review_service_provider');
    if ($provider->provider_type === 'supplier') {
        $pageTitle = __('admin.review_supplier') ?? 'مراجعة بيانات المورد';
    } elseif ($provider->provider_type === 'company') {
        $pageTitle = __('admin.review_company') ?? 'مراجعة بيانات الشركة';
    }
@endphp
@section('title', $pageTitle . ' - ' . $provider->name)

@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">
                    <i class="icon-base ti tabler-user-check me-2"></i>
                    {{ $pageTitle }}
                </h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('memberships.index') }}">{{ __('admin.service_providers') }}</a></li>
                        <li class="breadcrumb-item active">{{ $provider->name }}</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('memberships.index') }}" class="btn btn-secondary">
                <i class="icon-base ti tabler-arrow-right me-1"></i> {{ __('admin.back') }}
            </a>
        </div>

        <div class="row">
            {{-- Left Column: Provider Info --}}
            <div class="col-lg-4 col-md-5">
                {{-- Profile Card --}}
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <div class="mx-auto mb-3"
                            style="width: 120px; height: 120px; border-radius: 50%; overflow: hidden; border: 3px solid #e9ecef;">
                            <img src="{{ $provider->getFirstMediaUrl('personal_photo') ?: $provider->getFirstMediaUrl('users') ?: asset('website/assets/img/logo.png') }}"
                                alt="{{ $provider->name }}" class="w-100 h-100" style="object-fit: cover;">
                        </div>
                        <h5 class="fw-bold mb-1">{{ $provider->name }}</h5>
                        <span
                            class="badge {{ $provider->provider_type === 'company' ? 'bg-label-info' : ($provider->provider_type === 'supplier' ? 'bg-label-success' : 'bg-label-primary') }} mb-2">
                            {{ $provider->provider_type === 'company' ? __('admin.company') : ($provider->provider_type === 'supplier' ? (__('admin.supplier') ?? 'مورد') : __('admin.individual')) }}
                        </span>

                        {{-- Status Badge --}}
                        <div class="mt-2">
                            @if ($provider->active === 'active')
                                <span class="badge bg-success px-3 py-2"><i class="ti tabler-check me-1"></i> {{ __('admin.active') }}</span>
                            @elseif($provider->active === 'pending')
                                <span class="badge bg-warning px-3 py-2"><i class="ti tabler-clock me-1"></i> {{ __('admin.pending_review') }}</span>
                            @else
                                <span class="badge bg-danger px-3 py-2"><i class="ti tabler-ban me-1"></i> {{ __('admin.blocked') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Contact Info --}}
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6 class="fw-bold mb-0">{{ __('admin.contact_info') }}</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="d-flex align-items-center mb-3">
                                <i class="ti tabler-mail text-primary me-2 fs-5"></i>
                                <span>{{ $provider->email }}</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="ti tabler-phone text-primary me-2 fs-5"></i>
                                <span dir="ltr">{{ $provider->phone ?? __('admin.not_specified') }}</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="ti tabler-map-pin text-primary me-2 fs-5"></i>
                                <span>{{ $provider->city->name ?? __('admin.not_specified') }} {{ $provider->city && $provider->city->country ? ' - ' . $provider->city->country->name : '' }}</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="ti tabler-id text-primary me-2 fs-5"></i>
                                <span>{{ $provider->id_number ?? __('admin.not_specified') }}</span>
                            </li>
                            @if($provider->provider_type === 'company')
                            <li class="d-flex align-items-center mb-3">
                                <i class="ti tabler-building-bank text-primary me-2 fs-5"></i>
                                <span>{{ __('admin.commercial_registration') }}: {{ $provider->company_registration_number ?? __('admin.not_specified') }}</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="ti tabler-users text-primary me-2 fs-5"></i>
                                <span>{{ __('admin.representative_name') }}: {{ $provider->representative_name ?? __('admin.not_specified') }}</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="ti tabler-chart-pie text-primary me-2 fs-5"></i>
                                @php
                                    $classModel = $provider->classification;
                                    $className = $classModel ? (is_string($classModel->name) ? $classModel->name : (is_array($classModel->name) ? ($classModel->name[app()->getLocale()] ?? current($classModel->name)) : $classModel->getTranslation('name', app()->getLocale()))) : __('admin.not_specified');
                                @endphp
                                <span>{{ __('admin.company_size') ?? 'حجم الشركة' }}: {{ $className }}</span>
                            </li>
                            @elseif($provider->provider_type === 'supplier')
                            <li class="d-flex align-items-center mb-3">
                                <i class="ti tabler-chart-pie text-primary me-2 fs-5"></i>
                                @php
                                    $classModel = $provider->classification;
                                    $className = $classModel ? (is_string($classModel->name) ? $classModel->name : (is_array($classModel->name) ? ($classModel->name[app()->getLocale()] ?? current($classModel->name)) : $classModel->getTranslation('name', app()->getLocale()))) : __('admin.not_specified');
                                @endphp
                                <span>{{ __('admin.supply_volume') ?? 'حجم التوريد' }}: {{ $className }}</span>
                            </li>
                            @endif
                            <li class="d-flex align-items-center">
                                <i class="ti tabler-calendar text-primary me-2 fs-5"></i>
                                <span>{{ __('admin.registration_date') }} {{ $provider->created_at->format('Y-m-d') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Approve/Reject Actions --}}
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6 class="fw-bold mb-0">{{ __('admin.review_actions') }}</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('memberships.update-status', $provider->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">{{ __('admin.membership_status') }}</label>
                                <select name="active" class="form-select">
                                    <option value="active" {{ $provider->active === 'active' ? 'selected' : '' }}>{{ __('admin.active_accept') }}</option>
                                    <option value="pending" {{ $provider->active === 'pending' ? 'selected' : '' }}>{{ __('admin.pending_awaiting') }}</option>
                                    <option value="blocked" {{ $provider->active === 'blocked' ? 'selected' : '' }}>{{ __('admin.blocked_reject') }}</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('admin.notes_optional') }}</label>
                                <textarea name="admin_notes" class="form-control" rows="3" placeholder="{{ __('admin.add_notes_placeholder') }}">{{ $provider->admin_notes ?? '' }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti tabler-check me-1"></i> {{ __('admin.save_action') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Right Column: Details & Documents --}}
            <div class="col-lg-8 col-md-7">
                {{-- Professional Info --}}
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6 class="fw-bold mb-0"><i class="ti tabler-briefcase me-1"></i> {{ __('admin.professional_info') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="border rounded p-3 text-center">
                                    <div class="fs-3 fw-bold text-primary">{{ $provider->years_of_experience ?? 0 }}</div>
                                    <small class="text-muted">{{ __('admin.years_of_experience') }}</small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="border rounded p-3 text-center">
                                    <div class="fs-3 fw-bold text-primary">
                                        {{ number_format($provider->average_rating, 1) }}</div>
                                    <small class="text-muted">{{ __('admin.rating') }}</small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="border rounded p-3 text-center">
                                    <div class="fs-3 fw-bold text-primary">{{ $provider->total_completed_requests ?? 0 }}
                                    </div>
                                    <small class="text-muted">{{ __('admin.completed_requests') }}</small>
                                </div>
                            </div>
                        </div>

                        @if ($provider->bio)
                            <div class="mt-3">
                                <label class="form-label fw-bold">{{ __('admin.bio') }}:</label>
                                <div class="bg-light rounded p-3">{{ $provider->bio }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Categories --}}
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6 class="fw-bold mb-0"><i class="ti tabler-category me-1"></i> {{ __('admin.categories_label') }}</h6>
                    </div>
                    <div class="card-body">
                        @if ($provider->categories->count() > 0)
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($provider->categories as $category)
                                    @php
                                        $catName = $category->getTranslation('name', app()->getLocale()) ?? $category->name;
                                    @endphp
                                    <span class="badge bg-label-primary px-3 py-2">
                                        <i class="ti tabler-tag me-1"></i> {{ $catName }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">{{ __('admin.no_categories_set') }}</p>
                        @endif
                    </div>
                </div>

                @if(in_array($provider->provider_type, ['supplier']) || in_array($provider->membership_type, ['supplier']))
                {{-- Delivery Cities --}}
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6 class="fw-bold mb-0"><i class="ti tabler-map-pin me-1"></i> مناطق العمل / التوصيل</h6>
                    </div>
                    <div class="card-body">
                        @if ($provider->deliveryCities && $provider->deliveryCities->count() > 0)
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($provider->deliveryCities as $city)
                                    @php
                                        $cityName = is_string($city->name) ? $city->name : (is_array($city->name) ? ($city->name[app()->getLocale()] ?? $city->name['ar'] ?? '') : $city->getTranslation('name', app()->getLocale()));
                                    @endphp
                                    <span class="badge bg-label-secondary px-3 py-2">
                                        <i class="ti tabler-map-pin me-1"></i> {{ $cityName }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">لم يتم تحديد مناطق عمل/توصيل.</p>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Identity Documents --}}
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6 class="fw-bold mb-0"><i class="ti tabler-file-text me-1"></i> {{ __('admin.identity_documents') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if ($provider->provider_type !== 'company')
                                {{-- ID Front --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">{{ __('admin.id_front') }}</label>
                                    @if ($provider->getFirstMediaUrl('id_front'))
                                        <div class="border rounded overflow-hidden">
                                            <a href="{{ $provider->getFirstMediaUrl('id_front') }}" target="_blank">
                                                <img src="{{ $provider->getFirstMediaUrl('id_front') }}" alt="ID Front"
                                                    class="w-100" style="max-height: 250px; object-fit: contain;">
                                            </a>
                                        </div>
                                    @else
                                        <div class="border rounded p-4 text-center text-muted bg-light">
                                            <i class="ti tabler-photo-off fs-1 d-block mb-2"></i>
                                            {{ __('admin.not_uploaded') }}
                                        </div>
                                    @endif
                                </div>

                                {{-- ID Back --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">{{ __('admin.id_back') }}</label>
                                    @if ($provider->getFirstMediaUrl('id_back'))
                                        <div class="border rounded overflow-hidden">
                                            <a href="{{ $provider->getFirstMediaUrl('id_back') }}" target="_blank">
                                                <img src="{{ $provider->getFirstMediaUrl('id_back') }}" alt="ID Back"
                                                    class="w-100" style="max-height: 250px; object-fit: contain;">
                                            </a>
                                        </div>
                                    @else
                                        <div class="border rounded p-4 text-center text-muted bg-light">
                                            <i class="ti tabler-photo-off fs-1 d-block mb-2"></i>
                                            {{ __('admin.not_uploaded') }}
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if ($provider->provider_type === 'company')
                                {{-- Commercial Registration --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">{{ __('admin.commercial_registration') }}</label>
                                    @if ($provider->getFirstMediaUrl('commercial_registration'))
                                        <div class="border rounded overflow-hidden">
                                            <a href="{{ $provider->getFirstMediaUrl('commercial_registration') }}"
                                                target="_blank" class="btn btn-outline-primary w-100 py-3">
                                                <i class="ti tabler-file-download fs-3 d-block mb-1"></i>
                                                {{ __('admin.view_download_cr') }}
                                            </a>
                                        </div>
                                    @else
                                        <div class="border rounded p-4 text-center text-muted bg-light">
                                            <i class="ti tabler-file-off fs-1 d-block mb-2"></i>
                                            {{ __('admin.not_uploaded') }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Certificates --}}
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0"><i class="ti tabler-certificate me-1"></i> {{ __('admin.certificates') }}</h6>
                    </div>
                    <div class="card-body">
                        @php
                            $certificates = $provider->getMedia('certificates');
                        @endphp
                        @if ($certificates->count() > 0)
                            <div class="row">
                                @foreach ($certificates as $cert)
                                    <div class="col-md-4 mb-3">
                                        <div class="border rounded overflow-hidden">
                                            <a href="{{ $cert->getUrl() }}" target="_blank">
                                                @if (in_array($cert->mime_type, ['image/jpeg', 'image/png', 'image/webp', 'image/jpg']))
                                                    <img src="{{ $cert->getUrl() }}" alt="{{ $cert->name }}"
                                                        class="w-100" style="max-height: 200px; object-fit: contain;">
                                                @else
                                                    <div class="p-4 text-center">
                                                        <i
                                                            class="ti tabler-file-type-pdf fs-1 text-danger d-block mb-2"></i>
                                                        <small>{{ $cert->name }}</small>
                                                    </div>
                                                @endif
                                            </a>
                                        </div>
                                        <small class="text-muted d-block mt-1">{{ $cert->name }}</small>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">{{ __('admin.no_certificates') }}</p>
                        @endif
                    </div>
                </div>

                {{-- Works (Portfolio) --}}
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0"><i class="ti tabler-briefcase me-1"></i> {{ __('admin.works_portfolio') ?? 'الأعمال السابقة' }}</h6>
                    </div>
                    <div class="card-body">
                        @if ($provider->works->count() > 0)
                            <div class="row">
                                @foreach ($provider->works as $work)
                                    <div class="col-12 mb-4 border-bottom pb-3 last-child-border-0">
                                        <h6 class="fw-bold text-primary mb-2">{{ $work->title }}</h6>
                                        <p class="mb-3 text-muted">{{ $work->description }}</p>
                                        
                                        @if($work->hasMedia('work_images'))
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($work->getMedia('work_images') as $media)
                                                    <div class="position-relative border p-1 rounded overflow-hidden" style="width: 100px; height: 100px;">
                                                        <a href="{{ $media->getUrl() }}" target="_blank">
                                                            <img src="{{ $media->getUrl() }}" alt="Work Image" class="w-100 h-100 object-fit-cover">
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">{{ __('admin.no_works') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
