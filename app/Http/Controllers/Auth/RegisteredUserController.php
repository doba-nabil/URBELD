<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\OtpNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('website.auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'representative_name' => ['nullable', 'string', 'max:255', 'required_if:account_type,company'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:20', 'unique:'.User::class],
            'id_number' => ['nullable', 'string', 'max:20', 'unique:'.User::class],
            'account_type' => ['required', 'in:seeker,individual,company'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        // Generate 6-digit OTP
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $userType = 'service_seeker';
        $providerType = null;
        $membershipType = 'individual';
        
        if ($request->account_type === 'company') {
            $userType = 'service_provider';
            $providerType = 'company';
            $membershipType = 'company';
        } elseif ($request->account_type === 'individual') {
            $userType = 'service_provider';
            $providerType = 'individual';
            $membershipType = 'individual';
        }

        // Create user with password
        $user = User::create([
            'name' => $request->name,
            'representative_name' => $request->representative_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'id_number' => $request->id_number,
            'membership_type' => $membershipType,
            'user_type' => $userType,
            'provider_type' => $providerType,
            'password' => Hash::make($request->password),
            'otp_code' => $otpCode,
            'otp_expires_at' => now()->addMinutes(5),
            'active' => ($userType === 'service_seeker' ? 'active' : 'pending'), // Providers need approval
        ]);

        event(new Registered($user));

        // Send OTP via email for verification
        $user->notify(new OtpNotification($otpCode));

        // Notify Admins about new registration
        $admins = User::where('is_admin', true)->get();
        if ($admins->count() > 0) {
            \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\NewMemberNotification($user));
        }

        // Store email in session for OTP verification
        session(['otp_email' => $user->email]);

        return redirect()->route('otp.show')->with('success', __('admin.otp_sent_to_email'));
    }
}
