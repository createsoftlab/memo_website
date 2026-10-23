<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminUserRequest;
use App\Http\Requests\Admin\UpdateAdminUserRequest;
use App\Models\User;
use App\Notifications\StaffCredentialsNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminUserController extends Controller
{
    /**
     * Display a listing of admin and employee staff members.
     */
    public function index(Request $request): View
    {
        $query = User::staff()->with(['roles', 'permissions']);

        if ($request->filled('search')) {
            $search = '%' . trim($request->search) . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('email', 'like', $search);
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', fn ($r) => $r->where('name', $request->role));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $employees = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total' => User::staff()->count(),
            'admins' => User::role('Admin')->count(),
            'employees' => User::role('Employee')->count(),
            'active' => User::staff()->active()->count(),
        ];

        return view('admin.view_employee', compact('employees', 'stats'));
    }

    /**
     * Show the form for creating a new admin or employee account.
     */
    public function create(): View
    {
        $roles = Role::whereIn('name', ['Admin', 'Employee'])->get();
        $permissions = Permission::all()->groupBy(function ($permission) {
            if (str_contains($permission->name, 'admin')) return 'Administration & Access';
            if (str_contains($permission->name, 'user')) return 'User Moderation';
            if (str_contains($permission->name, 'memor')) return 'Memorials & Approvals';
            if (str_contains($permission->name, 'event')) return 'Events';
            if (str_contains($permission->name, 'package') || str_contains($permission->name, 'payment')) return 'Packages & Billing';
            if (str_contains($permission->name, 'email') || str_contains($permission->name, 'report')) return 'Communications & Analytics';
            return 'Platform Operations';
        });

        return view('admin.add_employee', compact('roles', 'permissions'));
    }

    /**
     * Store a newly created admin or employee account with granular permissions.
     */
    public function store(StoreAdminUserRequest $request): RedirectResponse
    {
        // 1. Generate a secure temporary password
        $temporaryPassword = Str::password(12, true, true, false);

        // 2. Create the user within a transaction
        $user = DB::transaction(function () use ($request, $temporaryPassword) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => strtolower($request->role),
                'password' => Hash::make($temporaryPassword),
                'country' => $request->country,
                'state' => $request->state,
                'registration_ip' => $request->ip(),
                'status' => User::STATUS_ACTIVE,
                'is_memory_creator' => false,
                'email_verified_at' => now(),
            ]);

            // Assign role
            $user->assignRole($request->role);

            // Assign custom granular permissions if provided
            if ($request->filled('permissions')) {
                $user->syncPermissions($request->permissions);
            }

            return $user;
        });

        // 3. Dispatch Notification / Mailable Queue Job with credentials
        $loginUrl = route('login');
        $user->notify(new StaffCredentialsNotification(
            temporaryPassword: $temporaryPassword,
            role: $request->role,
            loginUrl: $loginUrl
        ));

        return redirect()->route('admin.employees.index')
            ->with('success', "Staff account for {$user->name} ({$request->role}) created successfully!")
            ->with('temporary_credentials', [
                'email' => $user->email,
                'password' => $temporaryPassword,
                'role' => $request->role,
            ]);
    }

    /**
     * Show the form for editing an admin or employee account.
     */
    public function edit(User $employee): View
    {
        $employee->load(['roles', 'permissions']);

        $roles = Role::whereIn('name', ['Super Admin', 'Admin', 'Employee'])->get();
        $permissions = Permission::all()->groupBy(function ($permission) {
            if (str_contains($permission->name, 'admin')) return 'Administration & Access';
            if (str_contains($permission->name, 'user')) return 'User Moderation';
            if (str_contains($permission->name, 'memor')) return 'Memorials & Approvals';
            if (str_contains($permission->name, 'event')) return 'Events';
            if (str_contains($permission->name, 'package') || str_contains($permission->name, 'payment')) return 'Packages & Billing';
            if (str_contains($permission->name, 'email') || str_contains($permission->name, 'report')) return 'Communications & Analytics';
            return 'Platform Operations';
        });

        // Direct and inherited permissions
        $assignedPermissions = $employee->permissions->pluck('name')->toArray();
        $rolePermissions = $employee->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('admin.edit_employee', compact(
            'employee',
            'roles',
            'permissions',
            'assignedPermissions',
            'rolePermissions'
        ));
    }

    /**
     * Update the specified admin or employee account.
     */
    public function update(UpdateAdminUserRequest $request, User $employee): RedirectResponse
    {
        // Prevent modifying root super admin unless done by another authorized super admin
        if ($employee->id === 1 && $request->role !== 'Super Admin') {
            return back()->withErrors(['role' => 'The root Super Admin account role cannot be downgraded.']);
        }

        DB::transaction(function () use ($request, $employee) {
            $employee->update([
                'name' => $request->name,
                'email' => $request->email,
                'role' => strtolower($request->role),
                'country' => $request->country,
                'state' => $request->state,
                'status' => $request->status,
            ]);

            // Sync role
            $employee->syncRoles([$request->role]);

            // Sync direct granular permissions
            $employee->syncPermissions($request->permissions ?? []);
        });

        return redirect()->route('admin.employees.index')
            ->with('success', "Staff account for {$employee->name} updated successfully.");
    }

    /**
     * Resend generated credentials to staff member.
     */
    public function resendCredentials(User $employee): RedirectResponse
    {
        $temporaryPassword = Str::password(12, true, true, false);

        $employee->update([
            'password' => Hash::make($temporaryPassword),
        ]);

        $roleName = $employee->roles->first()?->name ?? 'Staff';
        $loginUrl = route('login');

        $employee->notify(new StaffCredentialsNotification(
            temporaryPassword: $temporaryPassword,
            role: $roleName,
            loginUrl: $loginUrl
        ));

        return back()
            ->with('success', "New temporary credentials dispatched to {$employee->email}.")
            ->with('temporary_credentials', [
                'email' => $employee->email,
                'password' => $temporaryPassword,
                'role' => $roleName,
            ]);
    }

    /**
     * Remove the specified admin or employee account.
     */
    public function destroy(User $employee): RedirectResponse
    {
        if ($employee->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot delete your own active account.']);
        }

        if ($employee->id === 1 || $employee->hasRole('Super Admin')) {
            return back()->withErrors(['error' => 'Super Administrator accounts cannot be deleted directly.']);
        }

        $name = $employee->name;
        $employee->delete();

        return redirect()->route('admin.employees.index')
            ->with('success', "Staff account {$name} has been removed from the platform.");
    }
}
