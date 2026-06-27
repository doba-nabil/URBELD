<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\OtpNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class OtpController extends Controller
{
    /**
     * Display the OTP verification form.
     */
    public function show(): View
    {
        if (!session()->has('otp_email')) {
            return redirect()->route('login')->with('error', __('admin.please_login_first'));
        }

        return view('website.auth.otp');
    }

    /**
     * Verify the OTP code.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $email = session('otp_email');
        if (!$email) {
            return redirect()->route('login')->with('error', __('admin.please_login_first'));
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors(['otp' => __('admin.user_not_found')]);
        }

        // Check if OTP matches and is not expired
        if ($user->otp_code !== $request->otp) {
            return back()->withErrors(['otp' => __('admin.invalid_otp')]);
        }

        if ($user->otp_expires_at < now()) {
            return back()->withErrors(['otp' => __('admin.otp_expired')]);
        }

        // Mark email as verified
        $user->email_verified_at = now();
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        // Log the user in
        Auth::login($user);

        // Clear session
        session()->forget('otp_email');

        return redirect()->route('home')->with('success', __('admin.otp_verified_success'));
    }

    /**
     * Resend the OTP code.
     */
    public function resend(Request $request): RedirectResponse
    {
        $email = session('otp_email');
        if (!$email) {
            return redirect()->route('login')->with('error', __('admin.please_login_first'));
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors(['error' => __('admin.user_not_found')]);
        }

        // Generate new OTP
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Update user with new OTP
        $user->otp_code = $otpCode;
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();

        // Send OTP email
        $user->notify(new OtpNotification($otpCode));

        return back()->with('success', __('admin.otp_resent_success'));
    }
}
