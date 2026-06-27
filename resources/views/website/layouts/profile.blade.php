@extends('website.layouts.master')

@section('content')
    <!-- Header Start -->
    <div class="services-header-section without-search">
        <div class="container p-md-5 p-4 mb-md-5">
            <div class="row align-items-center">
                <div class="col-lg-12">

                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Profile Header Section -->
    <div class="profile-header-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-3 col-md-4">
                    @php
                        $pUser = $user ?? auth()->user();
                        $profilePhoto =
                            $pUser->getFirstMediaUrl('personal_photo') ?: $pUser->getFirstMediaUrl('users') ?: null;
                    @endphp
                    <div class="profile-image-wrapper wow fadeInUp position-relative overflow-hidden" data-wow-delay="0.1s"
                        @if (auth()->check() && auth()->id() == $pUser->id) style="cursor: pointer;" onclick="document.getElementById('profilePhotoInput').click()" @endif>
                        <img src="{{ $profilePhoto ?: asset('website/assets/img/logo.png') }}" alt="{{ $pUser->name }}"
                            class="profile-image img-thumbnail w-100 h-100 object-fit-cover">
                        @if (auth()->check() && auth()->id() == $pUser->id)
                            <div class="position-absolute bottom-0 w-100 text-center bg-dark bg-opacity-50 text-white py-1"
                                style="font-size: 0.8rem;">
                                {{ __('website.change_photo') }}
                            </div>
                        @endif
                    </div>
                    @if (auth()->check() && auth()->id() == $pUser->id)
                        <form id="profilePhotoForm" action="{{ route('profile.photo.update') }}" method="POST"
                            enctype="multipart/form-data" class="d-none">
                            @csrf
                            <input type="file" id="profilePhotoInput" name="personal_photo" accept="image/*"
                                onchange="document.getElementById('profilePhotoForm').submit()">
                        </form>
                    @endif
                </div>
                <div class="col-lg-9 col-md-8">
                    <div class="profile-info wow fadeInUp" data-wow-delay="0.2s">
                        <h1 class="profile-name">
                            {{ $pUser->name }}
                            <!-- Edit button could be link to settings or modal trigger -->
                            @if (Route::is('profile.edit') && auth()->id() == $pUser->id)
                                <button class="btn-edit-profile" type="button" id="btn-toggle-edit" title="{{ __('website.edit_data') }}"
                                    style="border: none; background: transparent;">
                                    <i class="bi bi-pencil shadow-sm bg-white text-primary rounded-circle p-2"
                                        id="edit-icon-toggle"></i>
                                </button>
                            @endif
                        </h1>
                        <div class="profile-details">
                            @if (auth()->check() && (auth()->id() == $pUser->id || auth()->user()->is_admin))
                                <div class="profile-detail-item">
                                    <i class="bi bi-envelope"></i>
                                    <span>{{ $pUser->email }}</span>
                                </div>
                            @endif
                            @if ($pUser->city)
                                <div class="profile-detail-item">
                                    <i class="bi bi-geo-alt"></i>
                                    <span>{{ $pUser->city->name }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- NEW METRICS ROW -->
                        <div class="row mt-4">
                            @if ($pUser->isServiceProvider())
                                <div class="col-auto mb-2">
                                    <span class="badge bg-light text-dark border p-2 px-3">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        {{ __('website.rating_lbl') }}: <strong>{{ number_format($pUser->average_rating, 1) }} / 5</strong>
                                    </span>
                                </div>
                            @endif
                            <div class="col-auto mb-2">
                                <span class="badge bg-light text-dark border p-2 px-3">
                                    <i class="bi bi-check-circle text-success"></i>
                                    {{ __('website.completed_requests_count') }}: <strong>{{ $pUser->total_completed_requests }}</strong>
                                </span>
                            </div>
                            <div class="col-auto mb-2">
                                <span class="badge bg-light text-dark border p-2 px-3">
                                    <i class="bi bi-hourglass-split text-primary"></i>
                                    {{ __('website.active_requests_count') }}: <strong>{{ $pUser->active_requests_count }}</strong>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="profile-tabs-section">
        <div class="container">
            @php
                $tabUser = $pUser ?? auth()->user();
                $tabIsProvider = $tabUser->isServiceProvider();
                $tabProfileIncomplete = false;
                if ($tabIsProvider && $tabUser->id === auth()->id()) {
                    $tabHasId =
                        $tabUser->getFirstMediaUrl('id_front') || $tabUser->getFirstMediaUrl('commercial_registration');
                    $tabHasCategory = $tabUser->categories()->exists();
                    $tabHasCity = !empty($tabUser->city_id);
                    $tabHasExperience = !is_null($tabUser->years_of_experience);
                    $tabProfileIncomplete = !$tabHasId || !$tabHasCategory || !$tabHasCity || !$tabHasExperience;
                }
            @endphp
            <ul class="profile-tabs">
                @if (isset($pUser) && $pUser->id !== auth()->id())
                    <!-- Public View Tabs -->
                    <li class="profile-tab-item {{ Route::is('member.public') ? 'active' : '' }}">
                        <a href="{{ route('member.public', $pUser->id) }}" class="profile-tab-link">{{ __('website.about_member') }}</a>
                    </li>
                    @if ($tabIsProvider)
                        <li class="profile-tab-item {{ Route::is('member.certificates') ? 'active' : '' }}">
                            <a href="{{ route('member.certificates', $pUser->id) }}" class="profile-tab-link">{{ __('website.certificates') }}</a>
                        </li>
                        <li class="profile-tab-item {{ Route::is('member.services') ? 'active' : '' }}">
                            <a href="{{ route('member.services', $pUser->id) }}" class="profile-tab-link">{{ __('admin.services') ?? __('website.nav_services') }}</a>
                        </li>
                        <li class="profile-tab-item {{ Route::is('member.works') ? 'active' : '' }}">
                            <a href="{{ route('member.works', $pUser->id) }}" class="profile-tab-link">{{ __('admin.works_portfolio') ?? 'الأعمال السابقة' }}</a>
                        </li>
                    @endif
                @else
                    <!-- Private View Tabs (Owner) -->
                    <li class="profile-tab-item {{ Route::is('profile.edit') ? 'active' : '' }}">
                        <a href="{{ route('profile.edit') }}" class="profile-tab-link">{{ __('website.about_me') }}</a>
                    </li>
                    @if ($tabIsProvider)
                        <li class="profile-tab-item {{ Route::is('profile.complete') ? 'active' : '' }}">
                            <a href="{{ route('profile.complete') }}" class="profile-tab-link"
                                @if ($tabProfileIncomplete) style="color: #d4af37; font-weight: bold;" @endif>
                                @if ($tabProfileIncomplete)
                                    <i class="bi bi-exclamation-circle-fill text-warning me-1"></i>
                                    {{ __('website.complete_data') }}
                                    <span class="badge bg-warning text-dark ms-1"
                                        style="font-size: 0.7rem; animation: pulse 2s infinite;">{{ __('website.required_lbl') }}</span>
                                @else
                                    {{ __('website.my_data') }}
                                @endif
                            </a>
                        </li>
                    @endif
                    <li class="profile-tab-item {{ Route::is('profile.requests') ? 'active' : '' }}">
                        <a href="{{ route('profile.requests') }}" class="profile-tab-link">
                            @if ($tabIsProvider)
                                {{ __('website.my_requests_sent') }}
                            @else
                                {{ __('website.nav_requests') }}
                            @endif
                        </a>
                    </li>
                    <li class="profile-tab-item {{ Route::is('profile.reports') ? 'active' : '' }}">
                        <a href="{{ route('profile.reports') }}" class="profile-tab-link">{{ __('website.reports') }}</a>
                    </li>
                    @if ($tabIsProvider)
                        <li class="profile-tab-item {{ Route::is('provider.requests.index') ? 'active' : '' }}">
                            <a href="{{ route('provider.requests.index') }}" class="profile-tab-link">
                                <i class="bi bi-inbox me-1"></i> {{ __('website.incoming_requests') }}
                            </a>
                        </li>
                        @if ($tabUser->provider_type === 'company')
                            <li class="profile-tab-item {{ Route::is('provider.services.*') ? 'active' : '' }}">
                                <a href="{{ route('provider.services.index') }}" class="profile-tab-link">
                                    <i class="bi bi-briefcase me-1"></i> {{ __('website.my_services') }}
                                </a>
                            </li>
                        @endif
                        <li class="profile-tab-item {{ Route::is('provider.works.*') ? 'active' : '' }}">
                            <a href="{{ route('provider.works.index') }}" class="profile-tab-link">
                                <i class="bi bi-images me-1"></i> {{ __('admin.works_portfolio') ?? 'الأعمال السابقة' }}
                            </a>
                        </li>
                        @if ($tabUser->provider_type === 'company')
                            <li class="profile-tab-item {{ Route::is('profile.subscription') ? 'active' : '' }}">
                                <a href="{{ route('profile.subscription') }}" class="profile-tab-link">
                                    <i class="bi bi-card-checklist me-1"></i> {{ __('website.my_subscription') ?? 'اشتراكي' }}
                                    @if(auth()->user()->subscription_end_at && auth()->user()->subscription_end_at->isPast())
                                        <span class="badge bg-danger ms-1" style="font-size: 0.6rem;">{{ __('website.expired') ?? 'منتهي' }}</span>
                                    @elseif(auth()->user()->subscription_end_at && auth()->user()->subscription_end_at->diffInDays(now()) < 7)
                                        <span class="badge bg-warning text-dark ms-1" style="font-size: 0.6rem;">{{ __('website.expiring_soon') ?? 'يوشك على الانتهاء' }}</span>
                                    @endif
                                </a>
                            </li>
                        @endif
                    @endif
                @endif
            </ul>
        </div>
    </div>

    <!-- Profile Content -->
    @yield('profile-content')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('btn-toggle-edit');
            const editIcon = document.getElementById('edit-icon-toggle');
            const readOnlyContainers = document.querySelectorAll('.profile-readonly-container');
            const editFormContainers = document.querySelectorAll('.profile-edit-form-container');

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    let isEditing = editFormContainers[0] && editFormContainers[0].style.display !== 'none';

                    if (isEditing) {
                        // Switch to Read-Only
                        editFormContainers.forEach(el => el.style.display = 'none');
                        readOnlyContainers.forEach(el => el.style.display = 'block');
                        editIcon.classList.remove('bi-x-circle-fill', 'text-danger');
                        editIcon.classList.add('bi-pencil', 'text-primary');
                    } else {
                        // Switch to Edit
                        editFormContainers.forEach(el => el.style.display = 'block');
                        readOnlyContainers.forEach(el => el.style.display = 'none');
                        editIcon.classList.remove('bi-pencil', 'text-primary');
                        editIcon.classList.add('bi-x-circle-fill', 'text-danger');
                    }
                });
            }

            // Sync Tab active class to parent li for Public View
            const tabLinks = document.querySelectorAll('.profile-tab-link[data-bs-toggle="tab"]');
            tabLinks.forEach(link => {
                link.addEventListener('shown.bs.tab', function (event) {
                    // Remove active from all siblings
                    document.querySelectorAll('.profile-tab-item').forEach(li => li.classList.remove('active'));
                    // Add to current target's parent
                    event.target.closest('.profile-tab-item').classList.add('active');
                });
            });

            // Handle URL hash to open correct tab
            const hash = window.location.hash;
            if (hash) {
                const targetTab = document.querySelector(`.profile-tab-link[href="${hash}"], .profile-tab-link[data-bs-target="${hash}"]`);
                if (targetTab) {
                    const bsTab = new bootstrap.Tab(targetTab);
                    bsTab.show();
                }
            }
        });
    </script>
@endsection
