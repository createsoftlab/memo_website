<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        // Super Admin and Admin accounts must be redirected to the admin dashboard
        if ($user && in_array($user->role, ['super_admin', 'admin'], true)) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        // Standard users (role === 'user') must be redirected to the user dashboard
        if ($user && ($user->role === 'user' || $user->isMemoryUser())) {
            return redirect()->intended(route('user.dashboard', absolute: false));
        }

        // Safe fallback: NEVER default to admin.dashboard
        return redirect()->intended(route('user.dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
