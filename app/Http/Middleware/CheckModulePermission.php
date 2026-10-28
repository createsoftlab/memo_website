<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckModulePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $module
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Super Admin has unrestricted access to all modules
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Check if user has permission for the specified module
        if ($user->hasModulePermission($module)) {
            return $next($request);
        }

        abort(403, "Unauthorized: You do not have permission to access the '{$module}' module.");
    }
}
