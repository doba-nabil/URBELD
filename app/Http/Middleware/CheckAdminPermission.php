<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $admin = auth()->guard('admin')->user();
        
        // Ensure user is authenticated and is an admin
        if (! $admin || ! $admin->is_admin) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            return redirect('admin-panel/login');
        }

        $routeName = $request->route()->getName();
        if (! $routeName) {
            return $next($request);
        }

        // 1. Check explicit mapping first using the full route name
        $explicitMap = [
            'admin-panel' => null, // Dashboard home - Always allowed
            'admin.reports.index' => 'reports',
            'settings.get' => 'settings.get',
            'settings.post' => 'settings.post',
            'profile.get' => null, // Always allowed
            'profile.post' => null, // Always allowed
            'theme.change' => null, // Always allowed
            'admin.logout' => null, // Always allowed
            'admin.lang' => null, // Always allowed
            'notifications.unread-count' => null, // Always allowed
            'notifications.mark-read' => null, // Always allowed
            'notifications.mark-all-read' => null, // Always allowed
        ];

        if (array_key_exists($routeName, $explicitMap)) {
            $permissionName = $explicitMap[$routeName];
            if ($permissionName === null) {
                return $next($request);
            }
        } else {
            // 2. Default mapping logic
            $permissionName = $routeName;
            
            // Remove 'admin.' prefix from route names if present to match permissions
            if (str_starts_with($permissionName, 'admin.')) {
                $permissionName = substr($permissionName, 6);
            }

            $map = [
                '.store' => '.create',
                '.update' => '.edit',
                '.destroy' => '.delete',
            ];

            foreach ($map as $suffix => $replacement) {
                if (str_ends_with($permissionName, $suffix)) {
                    $permissionName = str_replace($suffix, $replacement, $permissionName);
                    break;
                }
            }
        }

        if ($permissionName === null) {
            return $next($request);
        }

        // Check if permission exists in Spatie's cache/database for 'admin' guard
        // We use can() which works with the registered permissions for the guard
        if (! $admin->can($permissionName)) {
            $message = __('admin.error_403_message') ?: 'Unauthorized action.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => $message], 403);
            }
            return redirect('admin-panel')->with('error', $message);
        }

        return $next($request);
    }
}
