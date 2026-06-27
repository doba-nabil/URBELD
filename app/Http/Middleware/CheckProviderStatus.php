<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckProviderStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('web')->user();

        if ($user && $user->isServiceProvider()) {
            if (!$user->active || $user->active == 0 || $user->active === 'pending') {
                // Not approved yet
                // The user says "If complete but not yet approved... show a prominent alert on their dashboard"
                // This means we shouldn't redirect them away from the dashboard entirely, 
                // but we should block them from receiving/acting on provider requests.
                // We'll redirect to the regular profile/dashboard and show an error/warning.
                return redirect()->route('profile.edit')->with('warning', __('admin.provider_under_review'));
            }
        }

        return $next($request);
    }
}
