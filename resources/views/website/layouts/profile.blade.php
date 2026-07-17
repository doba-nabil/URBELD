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
    @if(in_array(request()->route()->getName(), ['profile.edit', 'member.public', 'member.certificates', 'member.services', 'member.works']))
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
                            class="profile-image img-thumbnail object-fit-cover">
                        @if (auth()->check() && auth()->id() == $pUser->id)
                            <div class="position-absolute d-flex align-items-center justify-content-center shadow-sm"
                                style="bottom: 15px; left: 15px; width: 38px; height: 38px; background-color: var(--primary); border-radius: 50%; color: #fff; border: 3px solid #fff; transition: all 0.3s ease;" 
                                title="{{ __('website.change_photo') ?? 'تغيير الصورة' }}">
                                <i class="fas fa-camera" style="font-size: 0.9rem;"></i>
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
                            @if($pUser->is_trusted)
                                <i class="bi bi-patch-check-fill text-primary ms-2" title="{{ __('admin.is_trusted') ?? 'موثوق' }}"></i>
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
                            @php
                                $memberType = $pUser->membership_type ?? $pUser->provider_type ?? null;
                                $memberLabel = match($memberType) {
                                    'supplier'    => ['label' => 'مورد',         'icon' => 'bi-box-seam',      'color' => '#d97706'],
                                    'company'     => ['label' => 'شركة / مؤسسة', 'icon' => 'bi-building',      'color' => '#0284c7'],
                                    'individual'  => ['label' => 'مقدم خدمة',    'icon' => 'bi-person-badge',  'color' => '#059669'],
                                    default       => null,
                                };
                            @endphp
                            @if($memberLabel)
                                <div class="profile-detail-item">
                                    <i class="bi {{ $memberLabel['icon'] }}" style="color: {{ $memberLabel['color'] }};"></i>
                                    <span style="color: {{ $memberLabel['color'] }}; font-weight: 600;">{{ $memberLabel['label'] }}</span>
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
    @endif

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
                            <a href="{{ route('member.works', $pUser->id) }}" class="profile-tab-link">{{ $pUser->isCompanyProvider() ? 'المشاريع' : (__('admin.works_portfolio') ?? 'الأعمال السابقة') }}</a>
                        </li>
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
