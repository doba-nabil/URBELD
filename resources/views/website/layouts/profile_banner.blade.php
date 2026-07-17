@auth
    @php
        $authUser = auth()->user();
        $isProvider = $authUser->user_type === 'service_provider';
        $profileIncomplete = false;
        if ($isProvider) {
            $hasId =
                $authUser->getFirstMediaUrl('id_front') ||
                $authUser->getFirstMediaUrl('commercial_registration') ||
                !empty($authUser->id_number) ||
                !empty($authUser->company_registration_number);
            $hasCategory = $authUser->categories()->exists();
            $hasCity = !empty($authUser->city_id);
            $hasExperience = !is_null($authUser->years_of_experience);
            $profileIncomplete = !$hasId || !$hasCategory || !$hasCity || !$hasExperience;
        }
    @endphp
    @if ($isProvider && $profileIncomplete)
        <style>
            @keyframes pulse-banner {
                0% { transform: scale(1); }
                50% { transform: scale(1.005); }
                100% { transform: scale(1); }
            }
            .premium-banner {
                background: rgba(255, 243, 205, 0.95) !important;
                backdrop-filter: blur(10px);
                border-right: 5px solid #ffc107 !important;
                z-index: 999999 !important;
                position: fixed;
                width: 100%;
                bottom: 0;
                box-shadow: 0 -5px 20px rgba(0,0,0,0.1) !important;
                animation: pulse-banner 3s infinite ease-in-out;
                transition: all 0.3s ease;
            }
            .premium-banner:hover {
                background: rgba(255, 243, 205, 1) !important;
                animation-play-state: paused;
            }
        </style>
        <div class="alert alert-warning alert-dismissible fade show mb-0 rounded-0 border-0 premium-banner"
            role="alert">
            <div class="container d-flex align-items-center justify-content-between flex-wrap gap-2 py-2">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-octagon-fill text-warning me-3 fs-3"></i>
                    <div>
                        <h6 class="mb-0 fw-bold text-dark">{{ __('website.please_complete_profile') }}</h6>
                        <small class="text-muted">{{ __('website.complete_profile_desc') }}</small>
                    </div>
                </div>
                <a href="{{ route('profile.complete') }}" class="btn btn-warning btn-sm fw-bold px-4 rounded-pill shadow-sm">
                    <i class="bi bi-arrow-left-circle-fill me-1"></i> {{ __('website.complete_data_now') }}
                </a>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" style="top: 50%; transform: translateY(-50%);"></button>
        </div>
    @endif
@endauth
