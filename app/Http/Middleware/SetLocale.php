<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $type = 'website'): Response
    {
        $sessionKey = $type === 'admin' ? 'admin_locale' : 'website_locale';
        
        // Default to Arabic if never set
        $locale = session($sessionKey, config('app.locale', 'ar'));
        
        App::setLocale($locale);
        // Also set Carbon locale
        \Carbon\Carbon::setLocale($locale);

        return $next($request);
    }
}
