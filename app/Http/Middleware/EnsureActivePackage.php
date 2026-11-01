<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureActivePackage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $feature  ('memory' or 'event')
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $feature = 'memory'): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Hard restriction: Admins & Super Admins cannot create content
        if (in_array($user->role, ['super_admin', 'admin'])) {
            abort(403, 'Administrators cannot create memories or events. You can only view, approve, or reject user submissions.');
        }

        // Validate package capability based on requested feature
        if ($feature === 'event') {
            if (!$user->canCreateEvent()) {
                $targetUrl = route('packages.index', ['country' => strtolower($user->country ?? 'all')]);

                if (!$user->hasActivePackage()) {
                    return redirect($targetUrl)->with('warning', 'You need an active package to create events. Please select a plan to get started.');
                }

                return redirect($targetUrl)->with('warning', 'You have reached the event creation limit for your current package. Please upgrade or renew to create more events.');
            }
        } else {
            if (!$user->canCreateMemory()) {
                $targetUrl = route('packages.index', ['country' => strtolower($user->country ?? 'all')]);

                if (!$user->hasActivePackage()) {
                    return redirect($targetUrl)->with('warning', 'You need an active package to create a memorial. Please select a plan to get started.');
                }

                return redirect($targetUrl)->with('warning', 'You have reached the memorial creation limit for your current package. Please upgrade or renew to create more memories.');
            }
        }

        return $next($request);
    }
}

