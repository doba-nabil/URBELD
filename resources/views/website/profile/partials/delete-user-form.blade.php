<section>
    <header>
        <h2 class="h4 text-dark">
            {{ __('website.delete_account') }}
        </h2>

        <p class="text-muted">
            {{ __('website.delete_account_desc') }}
        </p>
    </header>

    <button type="button" class="auth btn btn-danger mt-3 px-4 py-2" style="border-radius: 50px;" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
        {{ __('website.delete_account') }}
    </button>

    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmUserDeletionModalLabel">{{ __('website.confirm_delete_account') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p class="text-muted">
                            {{ __('website.delete_account_warning') }}
                        </p>

                        <div class="mb-3">
                            <label for="password" class="form-label visually-hidden">{{ __('website.password') }}</label>
                            <input id="password" name="password" type="password" class="form-control" placeholder="{{ __('website.password') }}" />
                            @error('password', 'userDeletion')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('website.cancel') }}</button>
                        <button type="submit" class="btn btn-danger">{{ __('website.delete_account') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($errors->userDeletion->isNotEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var myModal = new bootstrap.Modal(document.getElementById('confirmUserDeletionModal'));
                myModal.show();
            });
        </script>
    @endif
</section>
