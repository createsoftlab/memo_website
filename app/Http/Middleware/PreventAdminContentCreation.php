<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PreventAdminContentCreation
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && in_array(Auth::user()->role, ['super_admin', 'admin'])) {
            abort(403, 'Administrators cannot create memories or events. You can only view, approve, or reject user submissions.');
        }

        return $next($request);
    }
}
