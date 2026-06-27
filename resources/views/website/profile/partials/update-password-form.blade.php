<section>
    <header>
        <h2 class="h4 text-dark">
            {{ __('website.update_password') }}
        </h2>

        <p class="text-muted">
            {{ __('website.update_password_desc') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4 login-form">
        @csrf
        @method('put')

        <div class="form-group login-form-group mb-4">
            <label for="current_password" class="form-label">{{ __('website.current_password') }}</label>
            <input id="current_password" name="current_password" type="password" class="form-control login-input" autocomplete="current-password" />
            @error('current_password')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group login-form-group mb-4">
            <label for="password" class="form-label">{{ __('website.new_password') }}</label>
            <input id="password" name="password" type="password" class="form-control login-input" autocomplete="new-password" />
            @error('password')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group login-form-group mb-4">
            <label for="password_confirmation" class="form-label">{{ __('website.confirm_password') }}</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control login-input" autocomplete="new-password" />
            @error('password_confirmation')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="auth btn btn-primary px-4 py-2" style="border-radius: 50px;">{{ __('website.save') }}</button>

            @if (session('status') === 'password-updated')
                <div class="alert alert-success mb-0 py-1 px-3" role="alert" style="border-radius: 50px;">
                    {{ __('website.saved_successfully') }}
                </div>
            @endif
        </div>
    </form>
</section>
