<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckPackageExpiry
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->package_expires_at) {
            $expiryDate = Carbon::parse($user->package_expires_at);

            if ($expiryDate->isPast() && $expiryDate->diffInDays(now()) > 30) {
                if (!$request->routeIs('billing.*')) {
                    return redirect()->route('billing.index')->with('error', 'Your package expired 30 days ago. Your dashboard access is locked. Please renew to continue.');
                }
            }
        }

        return $next($request);
    }
}
