<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEmployeeRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Mail\StaffAccountCreatedMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display the central user directory & moderation dashboard.
     */
    public function index(Request $request): View
    {
        $query = User::query();

        // Search by name, username, email, or phone
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by Account Status
        if ($request->filled('status')) {
            $status = $request->input('status');
            if (in_array($status, ['active', 'suspended', 'banned'])) {
                $query->where('status', $status);
            }
        }

        // Filter by User Role / Type
        if ($request->filled('role')) {
            $role = $request->input('role');
            if (in_array($role, ['super_admin', 'admin', 'employee', 'memory_user', 'general_user'])) {
                $query->where('role', $role);
            }
        }

        $users = $query->latest()->paginate(12)->withQueryString();

        return view('admin.all_users', compact('users'));
    }

    /**
     * Show form to provision a new staff member (Admin or Employee).
     * Strictly restricted to Super Admin.
     */
    public function createEmployee(): View
    {
        if (!Auth::check() || !Auth::user()->isSuperAdmin()) {
            abort(403, 'Unauthorized access: Only Super Admins can provision staff accounts.');
        }

        return view('admin.add_employee');
    }

    /**
     * Store a newly created employee/admin in storage.
     */
    public function storeEmployee(StoreEmployeeRequest $request): RedirectResponse
    {
        if (!Auth::check() || !Auth::user()->isSuperAdmin()) {
            abort(403, 'Unauthorized access: Only Super Admins can provision staff accounts.');
        }

        $validated = $request->validated();
        $plainPassword = $validated['password'];

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($plainPassword),
            'status' => 'active',
            'permissions' => $validated['permissions'] ?? [],
        ]);

        // Dispatch email notification with credentials
        try {
            $loginUrl = route('login');
            Mail::to($user->email)->send(new StaffAccountCreatedMail($user, $plainPassword, $loginUrl));
            $mailSent = true;
        } catch (\Exception $e) {
            Log::error('Staff account created but failed to dispatch credentials email: ' . $e->getMessage());
            $mailSent = false;
        }

        $message = "Staff account for '{$user->name}' was created successfully.";
        if ($mailSent) {
            $message .= " An email with login credentials has been dispatched to {$user->email}.";
        } else {
            $message .= " Note: Credentials email could not be sent due to mail settings. Temporary password: {$plainPassword}";
        }

        return redirect()->route('admin.users.index')->with('success', $message);
    }

    /**
     * Show the edit user profile and access levels form.
     */
    public function edit($id): View
    {
        $user = $id instanceof User ? $id : User::findOrFail($id);

        return view('admin.edit_user', compact('user'));
    }

    /**
     * Update user details, role assignment, and module permissions.
     */
    public function update(UpdateUserRequest $request, $id): RedirectResponse
    {
        $user = $id instanceof User ? $id : User::findOrFail($id);
        $validated = $request->validated();

        $updateData = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? $user->phone,
            'country' => $validated['country'] ?? $user->country,
            'state' => $validated['state'] ?? $user->state,
            'role' => $validated['role'],
            'status' => $validated['status'],
            'permissions' => $validated['permissions'] ?? [],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')
            ->with('success', "User '{$user->name}' was updated successfully.");
    }

    /**
     * Quick status toggling between active, suspended, and banned.
     */
    public function updateStatus(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:active,suspended,banned'],
        ]);

        // Prevent banning or suspending the super admin if user is self
        if ($user->isSuperAdmin() && Auth::id() === $user->id && $validated['status'] !== 'active') {
            return back()->with('error', 'You cannot suspend or ban your own Super Admin account.');
        }

        $user->update(['status' => $validated['status']]);

        return back()->with('success', 'Status updated successfully');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        if (Auth::check() && !Auth::user()->canDelete()) {
            abort(403, 'Unauthorized: Only Super Administrators have permission to delete users.');
        }

        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Super Administrator accounts cannot be deleted.');
        }

        if (Auth::id() === $user->id) {
            return back()->with('error', 'You cannot delete your own logged-in account.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "User '{$userName}' has been deleted successfully.");
    }
}
