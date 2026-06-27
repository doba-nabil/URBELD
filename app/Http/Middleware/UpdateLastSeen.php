<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateLastSeen
{
    /**
     * Update the authenticated user's last_seen_at timestamp.
     * Throttled to once per minute via session to avoid DB spam.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $lastUpdate = session('last_seen_updated_at');

            // Only update once per minute
            if (!$lastUpdate || now()->diffInSeconds($lastUpdate) >= 60) {
                Auth::user()->update(['last_seen_at' => now()]);
                session(['last_seen_updated_at' => now()]);
            }
        }

        return $next($request);
    }
}
