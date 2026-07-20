<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Notifications\OtpNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('website.auth.login');
    }

    /**
     * Handle an incoming authentication request (password-based).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $isEmail = filter_var($request->email, FILTER_VALIDATE_EMAIL);
        $loginField = $isEmail ? 'email' : 'id_number';

        if (!$isEmail) {
            $user = User::where('id_number', $request->email)
                ->orWhere('company_registration_number', $request->email)
                ->first();
            if ($user) {
                $loginField = $user->id_number === $request->email ? 'id_number' : 'company_registration_number';
            }
        } else {
            $user = User::where('email', $request->email)->first();
        }

        // If user exists and is blocked, return error immediately
        // This avoids session changes (Auth::attempt/logout) that cause 419 errors in shared browser sessions
        if ($user && ($user->active === 'blocked' || $user->active === '0' || $user->active === 0)) {
            return back()->withErrors([
                'email' => __('admin.account_blocked'),
            ])->withInput($request->only('email'));
        }

        // Attempt to authenticate with email/id_number/company_registration_number and password
        if (!Auth::attempt([$loginField => $request->email, 'password' => $request->password], $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => __('admin.invalid_credentials'),
            ])->onlyInput('email');
        }

        // Re-fetch user after successful authentication
        $user = Auth::user();

        if (is_null($user->email_verified_at)) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            session(['otp_email' => $user->email]);
            return redirect()->route('otp.show')->with('error', __('website.please_verify_account'));
        }

        // Regenerate session
        $request->session()->regenerate();

        // Update last login and last seen timestamps
        $user->update([
            'last_login_at' => now(),
            'last_seen_at' => now(),
        ]);

        return redirect()->route('home');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
