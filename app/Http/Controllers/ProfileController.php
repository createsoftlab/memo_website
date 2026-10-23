<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the admin profile settings view.
     */
    public function settings(Request $request): View
    {
        return view('admin.profile_settings', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the Breeze default profile form.
     */
    public function edit(Request $request): View
    {
        return view('admin.profile_settings', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update basic user info (name, country, state).
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->fill($request->validated());
        $user->save();

        return back()->with('success', 'Profile information updated successfully.');
    }

    /**
     * Update user password requiring current password verification.
     */
    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('password_success', 'Your password has been changed securely.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if ($user->id === 1 || $user->hasRole('Super Admin')) {
            return back()->withErrors(['error' => 'Super Administrator account cannot be self-deleted.']);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
