<form method="post" action="{{ route('profile.notifications.update') }}" class="mt-4 login-form">
    @csrf
    <div class="form-check form-switch mb-4">
        <input class="form-check-input flex-shrink-0" type="checkbox" id="receive_email_notifications" name="receive_email_notifications" value="1" {{ auth()->user()->receive_email_notifications ? 'checked' : '' }} style="width: 2.5em; height: 1.25em;">
        <label class="form-check-label ms-3 d-flex align-items-center" for="receive_email_notifications" style="font-size: 1.1rem;">
            {{ __('website.receive_email_notifications') }}
        </label>
    </div>

    <div class="form-check form-switch mb-4">
        <input class="form-check-input flex-shrink-0" type="checkbox" id="receive_push_notifications" name="receive_push_notifications" value="1" {{ auth()->user()->receive_push_notifications ? 'checked' : '' }} style="width: 2.5em; height: 1.25em;">
        <label class="form-check-label ms-3 d-flex align-items-center" for="receive_push_notifications" style="font-size: 1.1rem;">
            {{ __('website.receive_push_notifications') }}
        </label>
    </div>

    <div class="d-flex align-items-center gap-4 mt-5">
        <button type="submit" class="auth btn btn-primary px-4 py-2" style="border-radius: 50px;">{{ __('website.save_notification_settings') }}</button>
    </div>
</form>
