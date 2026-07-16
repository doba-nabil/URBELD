@extends('website.layouts.profile', ['user' => $user])

@section('title', $user->name . ' - ' . __('website.profile'))

@section('profile-content')
    <div class="container py-4">
        <div class="row">
            <div class="col-lg-8">
                <!-- Profile Data Content -->
                <div class="profile-data">
                    <!-- About Section / Bio -->
                    @if ($user->bio)
                            <div class="info-card mb-4 bg-white p-4 rounded shadow-sm border">
                                <h5 class="info-card-title mb-3 fw-bold border-bottom pb-2">
                                    <i class="bi bi-chat-quote me-2 text-primary"></i>{{ __('website.about_me') }}
                                </h5>
                                <p class="text-muted mb-0" style="line-height: 1.8; text-align: justify;">
                                    {{ $user->bio }}</p>
                            </div>
                        @endif

                        <!-- Basic Information -->
                        <div class="info-card bg-white p-4 rounded shadow-sm border">
                            <h5 class="info-card-title mb-3 fw-bold border-bottom pb-2">
                                <i class="bi bi-info-circle me-2 text-primary"></i>{{ __('website.general_info') }}
                            </h5>
                            <div class="info-list">
                                <div class="info-item d-flex align-items-center mb-3 border-bottom pb-2">
                                    <div class="info-icon me-3 bg-light rounded-circle p-2"
                                        style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-person text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">{{ __('website.full_name') }}</div>
                                        <div class="fw-bold">{{ $user->name }}</div>
                                    </div>
                                </div>

                                @if ($user->city)
                                    <div class="info-item d-flex align-items-center mb-3 border-bottom pb-2">
                                        <div class="info-icon me-3 bg-light rounded-circle p-2"
                                            style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-geo-alt text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="text-muted small">{{ __('website.city') }}</div>
                                            <div class="fw-bold">{{ $user->city->name }}</div>
                                        </div>
                                    </div>
                                @endif

                                @if ($user->email)
                                    <div class="info-item d-flex align-items-center mb-3 border-bottom pb-2">
                                        <div class="info-icon me-3 bg-light rounded-circle p-2"
                                            style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-envelope text-primary"></i>
                                        </div>
                                        <div style="word-break: break-all;">
                                            <div class="text-muted small">{{ __('website.email') ?? 'البريد الإلكتروني' }}</div>
                                            <div class="fw-bold">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                @endif

                                @if ($user->phone)
                                    <div class="info-item d-flex align-items-center mb-3 border-bottom pb-2">
                                        <div class="info-icon me-3 bg-light rounded-circle p-2"
                                            style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-telephone text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="text-muted small">{{ __('website.phone') ?? 'رقم الجوال' }}</div>
                                            <div class="fw-bold" dir="ltr">{{ $user->phone }}</div>
                                        </div>
                                    </div>
                                @endif

                                @if ($user->id_number)
                                    <div class="info-item d-flex align-items-center mb-3 border-bottom pb-2">
                                        <div class="info-icon me-3 bg-light rounded-circle p-2"
                                            style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-card-heading text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="text-muted small">{{ __('website.id_number') ?? 'السجل التجاري / الهوية' }}</div>
                                            <div class="fw-bold">{{ $user->id_number }}</div>
                                        </div>
                                    </div>
                                @endif

                                @if ($user->representative_name)
                                    <div class="info-item d-flex align-items-center mb-3 border-bottom pb-2">
                                        <div class="info-icon me-3 bg-light rounded-circle p-2"
                                            style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-person-badge text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="text-muted small">{{ __('website.representative_name') ?? 'اسم الممثل' }}</div>
                                            <div class="fw-bold">{{ $user->representative_name }}</div>
                                        </div>
                                    </div>
                                @endif

                                @if ($user->isServiceProvider())
                                    <div class="info-item d-flex align-items-center mb-3 border-bottom pb-2">
                                        <div class="info-icon me-3 bg-light rounded-circle p-2"
                                            style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-briefcase text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="text-muted small">{{ __('website.membership_type') }}</div>
                                            <div class="fw-bold">{{ $user->isCompany() ? __('website.company_office') : __('website.freelance_engineer') }}
                                            </div>
                                        </div>
                                    </div>

                                    @if ($user->years_of_experience)
                                        <div class="info-item d-flex align-items-center mb-3 border-bottom pb-2">
                                            <div class="info-icon me-3 bg-light rounded-circle p-2"
                                                style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-calendar-check text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="text-muted small">{{ __('website.years_of_experience') }}</div>
                                                <div class="fw-bold">{{ $user->years_of_experience }} {{ __('website.year') }}</div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <!-- Categories -->
                        @if ($user->categories->isNotEmpty())
                            <div class="info-card mt-4 bg-white p-4 rounded shadow-sm border">
                                <h5 class="info-card-title mb-3 fw-bold border-bottom pb-2">
                                    <i class="bi bi-tags me-2 text-primary"></i>{{ __('website.specialties_and_fields') }}
                                </h5>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($user->categories as $category)
                                        <span
                                            class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill border border-primary border-opacity-25">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
            </div>

            <div class="col-lg-4">
                <!-- Sidebar Actions -->
                @if ($user->isServiceProvider())
                    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                        <div class="card-header bg-primary text-white py-3 border-0">
                            <h5 class="card-title fw-bold mb-0 text-center text-white">
                                <i class="bi bi-send me-2"></i>{{ __('website.contact_provider') }}
                            </h5>
                        </div>
                        <div class="card-body text-center p-4">
                            <p class="text-muted mb-4">{{ __('website.contact_provider_desc') }}</p>
                            @auth
                                @if(auth()->id() !== $user->id)
                                <a href="{{ route('requests.create', ['provider_id' => $user->id, 'category' => $user->categories->whereNull('parent_id')->first()?->id]) }}"
                                    class="btn btn-primary w-100 py-3 rounded-3 fw-bold shadow-sm">
                                    <i class="bi bi-envelope-paper me-2"></i> {{ __('website.request_quote_now') }}
                                </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 py-3 rounded-3 fw-bold">
                                    <i class="bi bi-box-arrow-in-right me-2"></i> {{ __('website.login_to_request_service') }}
                                </a>
                            @endauth
                        </div>
                    </div>
                @endif

                <!-- Additional Stats/Badges -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4 text-center">
                        <div class="rating-stars mb-2" style="font-size: 1.8rem; color: #ffc107;">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= floor($averageRating))
                                    <i class="bi bi-star-fill"></i>
                                @elseif($i - $averageRating < 1 && $i - $averageRating > 0)
                                    <i class="bi bi-star-half"></i>
                                @else
                                    <i class="bi bi-star"></i>
                                @endif
                            @endfor
                        </div>
                        <div class="h3 fw-bold mb-1">{{ number_format($averageRating, 1) }}</div>
                        <div class="text-muted small border-bottom pb-3 mb-3">{{ __('website.based_on_avg') }} {{ $ratingsCount }} {{ __('website.rating') }}</div>

                        @if ($user->created_at)
                            <div class="d-flex justify-content-between text-muted small">
                                <span>{{ __('website.member_since') }}:</span>
                                <strong>{{ $user->created_at->format('M Y') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .hover-shadow:hover {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .1) !important;
        }

        .transition-all {
            transition: all .3s ease-in-out;
        }
    </style>
@endpush
