<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    
/**
 * Display the registration view.
 */
public function create(): View
{
    return view('auth.register');
}

/**
 * Handle an incoming registration request.
 *
 * @throws ValidationException
 */
public function store(Request $request): RedirectResponse
{
     // 1. Validate registration fields
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class,
            ],
            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],
            'phone' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'terms' => ['required', 'accepted'],
        ]);

        // 2. Verify CAPTCHA session
        $captcha = $request->session()->get(
            'registration_captcha_verified'
        );

        $captchaValid = is_array($captcha)
            && isset($captcha['expires_at'])
            && is_numeric($captcha['expires_at'])
            && now()->timestamp <= $captcha['expires_at'];

        if (!$captchaValid) {
            throw ValidationException::withMessages([
                'captcha' => 'Please complete CAPTCHA verification before registering.',
            ]);
        }

        // 3. Consume CAPTCHA verification
        $request->session()->forget([
            'registration_captcha',
            'registration_captcha_verified',
        ]);

        // 4. Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'country' => $validated['country'] ?? 'all',
            'state' => $validated['state'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'general_user',
            'package_id' => null,
        ]);

        // 5. Registration event
        event(new Registered($user));

        // 6. Login
        Auth::login($user);

        $request->session()->regenerate();

        // 7. Redirect
        return redirect()->route('home')
            ->with(
                'success',
                'Registration successful! Welcome to your dashboard.'
            );
    }

}
