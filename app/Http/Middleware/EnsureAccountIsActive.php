<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->isActive()) {
            $status = $user->status;
            $message = match ($status) {
                User::STATUS_BANNED => 'Your account has been banned due to a violation of platform policies. Access is permanently revoked.',
                User::STATUS_SUSPENDED => 'Your account is currently suspended. Please contact the platform administration for assistance.',
                default => 'Your account is currently inactive. Please contact support.',
            };

            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Account Deactivated',
                    'message' => $message,
                    'status' => $status,
                ], Response::HTTP_FORBIDDEN);
            }

            return redirect()->route('login')->withErrors([
                'email' => $message,
            ])->with('account_status_error', $message);
        }

        return $next($request);
    }
}
