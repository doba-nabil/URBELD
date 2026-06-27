<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip check if the current route is login or logout to prevent infinite loops
        if ($request->routeIs('login') || $request->routeIs('logout') || $request->is('login') || $request->is('logout')) {
            return $next($request);
        }

        // Check if a user is logged in via the web guard (normal members)
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            
            // If the user being checked IS an admin, skip blocking
            if ($user->is_admin) {
                return $next($request);
            }

            // If the user is blocked
            if ($user->active === 'blocked' || $user->active === '0' || $user->active === 0) {
                // We MUST logout to break the redirect loop
                Auth::guard('web')->logout();
                
                return redirect()->route('login')->withErrors([
                    'email' => __('admin.account_blocked'),
                ]);
            }
        }

        return $next($request);
    }
}
