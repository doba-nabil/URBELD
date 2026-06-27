<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            \App\Models\Visitor::firstOrCreate([
                'ip_address' => $request->ip(),
                'visited_date' => now()->toDateString(),
            ]);
        } catch (\Exception $e) {
            // Log or ignore
        }

        return $next($request);
    }
}
