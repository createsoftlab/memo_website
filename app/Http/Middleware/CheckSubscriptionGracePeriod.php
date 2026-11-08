<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckSubscriptionGracePeriod
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->role === 'memory_user') {
            // Lifetime package එකක් නම් දිගටම යන්න දෙන්න
            $isLifetime = $user->package && $user->package->billing_type === 'lifetime';

            if (!$isLifetime && $user->package_expires_at) {
                $expiryDate = Carbon::parse($user->package_expires_at);
                
                // Expire වී දින 30ක් ගතවී ඇත්දැයි බැලීම
                if (now()->diffInDays($expiryDate, false) < -30) {
                    
                    // පරිශීලකයා දැනටමත් billing පිටුවට යනවා නම් එයට ඉඩදෙන්න (Loop වීම වැළැක්වීමට)
                    if (!$request->routeIs('billing.*')) {
                        return redirect()->route('billing.index')->with('error', 'Your subscription grace period of 30 days has expired. Please renew your package to access the dashboard.');
                    }
                }
            }
        }

        return $next($request);
    }
}