<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class EnsureProfileIsCompleted
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('web')->user();
        if (!$user) {
            return redirect()->route('website.login');
        }
        if ($user->active === 'blocked') {
            return redirect()->route('website.login')
                ->with('error', __('admin.account_blocked'));
        }

        if ($user->active === 'pending') {
            return redirect()->route('home')
                ->with('error', __('admin.account_inactive'));
        }

        $exceptRoutes = [
            'profile.edit',
            'profile.complete',
            'profile.complete.store',
            'profile.update',
            'profile.destroy',
            'profile.requests',      // Allow seekers to view their own requests
            'profile.reports',       // Allow reports page
            'requests.show',         // Allow viewing a specific request details
            'dashboard.chat.index',  // Allow chat
            'dashboard.chat.show',   // Allow chat
            'chat.send',             // Allow sending messages
            'logout',
        ];

        if (in_array($request->route()->getName(), $exceptRoutes)) {
            return $next($request);
        }
        
        // Service Provider Profile Completion Check
        if ($user->user_type === 'service_provider') {
            $hasId = $user->getFirstMediaUrl('id_front') || $user->getFirstMediaUrl('commercial_registration');
            $hasCategory = $user->categories()->exists();
            $hasCity = !empty($user->city_id);
            $hasExperience = !is_null($user->years_of_experience);
            
            if (!$hasId || !$hasCategory || !$hasCity || !$hasExperience) {
                return redirect()->route('profile.complete')
                    ->with('warning', __('admin.complete_profile_warning'));
            }
        }

        return $next($request);
    }
}
