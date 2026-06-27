@extends('website.layouts.profile')

@section('profile-content')
    <!-- About Me Section -->
    <div class="about-me-section">
        <div class="container">
            <h2 class="about-me-title">{{ __('website.about_me') }}</h2>
            <div class="about-me-content">
                <p class="about-me-text">
                    {{ auth()->user()->bio ?? __('website.no_bio') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Update Forms Section -->
    <div class="leave-reply-section">
        <div class="container">
            <div class="row justify-content-center">
                    <!-- Read-Only View -->
                    <div class="profile-readonly-container">
                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white border-0">
                                <h5 class="mb-0 fw-bold text-primary">{{ __('website.personal_info') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <strong class="text-muted d-block mb-1">{{ __('website.name') }}:</strong>
                                        <div class="p-2 bg-light rounded">{{ auth()->user()->name }}</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong class="text-muted d-block mb-1">{{ __('website.email') }}:</strong>
                                        <div class="p-2 bg-light rounded">{{ auth()->user()->email }}</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong class="text-muted d-block mb-1">{{ __('website.phone') }}:</strong>
                                        <div class="p-2 bg-light rounded">{{ auth()->user()->phone ?? __('website.none') }}</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong class="text-muted d-block mb-1">{{ __('website.city') }}:</strong>
                                        <div class="p-2 bg-light rounded">{{ auth()->user()->city->name ?? __('website.none') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Forms View -->
                    <div class="profile-edit-form-container" style="display: none;">
                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white border-0">
                                <h5 class="mb-0 fw-bold text-primary">{{ __('website.update_personal_info') }}</h5>
                            </div>
                        <div class="card-body">
                            @include('website.profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <div class="profile-edit-form-container" style="display: none;">
                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white border-0">
                                <h5 class="mb-0 fw-bold text-primary">{{ __('website.update_password') }}</h5>
                            </div>
                        <div class="card-body">
                            @include('website.profile.partials.update-password-form')
                        </div>
                    </div>

                    <div class="profile-edit-form-container" style="display: none;">
                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white border-0">
                                <h5 class="mb-0 fw-bold text-danger">{{ __('website.delete_account') }}</h5>
                            </div>
                        <div class="card-body">
                            @include('website.profile.partials.delete-user-form')
                        </div>
                    </div>

                    <div class="card mb-4 shadow-sm border-0 mt-5">
                        <div class="card-header bg-white border-0">
                            <h5 class="mb-0 fw-bold text-primary">{{ __('website.notification_settings') }}</h5>
                        </div>
                        <div class="card-body">
                            @include('website.profile.partials.notification-settings-form')
                        </div>
                    </div>
                    </div> <!-- End Edit Forms View container -->
                </div>
            </div>
        </div>
    </div>
@endsection

