<section>
    <header>
        <h2 class="h4 text-dark">
            {{ __('website.personal_info') }}
        </h2>

        <p class="text-muted">
            {{ __('website.update_profile_info_desc') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4 login-form">
        @csrf
        @method('patch')

        <div class="form-group login-form-group mb-4">
            <label for="name" class="form-label">{{ __('website.name') }}</label>
            <input id="name" name="name" type="text" class="form-control login-input" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            @error('name')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group login-form-group mb-4">
            <label for="email" class="form-label">{{ __('website.email') }}</label>
            <input id="email" name="email" type="email" class="form-control login-input" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            @error('email')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-dark">
                        {{ __('website.email_unverified') }}

                        <button form="send-verification" class="btn btn-link p-0 align-baseline">
                            {{ __('website.resend_verification_email') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success mt-2" role="alert">
                            {{ __('website.verification_link_sent') }}
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="auth btn btn-primary px-4 py-2" style="border-radius: 50px;">{{ __('website.save') }}</button>

            @if (session('status') === 'profile-updated')
                <div class="alert alert-success mb-0 py-1 px-3" role="alert" style="border-radius: 50px;">
                    {{ __('website.saved_successfully') }}
                </div>
            @endif
        </div>
    </form>
</section>
