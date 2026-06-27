<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserType
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $type)
    {
        $user = Auth::guard('web')->user();

        if ($user && $user->user_type !== $type) {
            return abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
