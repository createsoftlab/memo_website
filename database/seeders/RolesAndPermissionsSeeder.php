<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions across the system
        $permissions = [
            // Memories
            'view-memories',
            'create-memories',
            'edit-memories',
            'delete-memories',
            'approve-memories',

            // Events
            'view-events',
            'create-events',
            'edit-events',
            'delete-events',
            'approve-events',

            // Memorial Features / Tributes
            'manage-tributes',

            // User Management (Super Admin only)
            'manage-users',

            // Operations & Packages
            'manage-packages',
            'view-payments',
            'view-reports',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        // Define Roles & assign permissions
        // 1. Super Admin Role
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdminRole->syncPermissions(Permission::all());

        // 2. Admin Role (Moderation only; prohibited from content creation)
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions([
            'view-memories',
            'edit-memories',
            'approve-memories',
            'view-events',
            'edit-events',
            'approve-events',
            'view-reports',
        ]);

        // 3. Employee Role
        $employeeRole = Role::firstOrCreate(['name' => 'employee', 'guard_name' => 'web']);
        $employeeRole->syncPermissions([
            'view-events',
            'edit-events',
            'view-memories',
        ]);

        // 4. Memory User Role (Memorial / Tribute creators)
        $memoryUserRole = Role::firstOrCreate(['name' => 'memory_user', 'guard_name' => 'web']);
        $memoryUserRole->syncPermissions([
            'view-memories',
            'create-memories',
            'edit-memories',
            'view-events',
            'create-events',
            'edit-events',
            'manage-tributes',
        ]);

        // 5. General User Role
        $generalUserRole = Role::firstOrCreate(['name' => 'general_user', 'guard_name' => 'web']);
        $generalUserRole->syncPermissions([
            'view-memories',
            'view-events',
        ]);

        // 6. User Role (Standard Registered User)
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $userRole->syncPermissions([
            'view-memories',
            'create-memories',
            'edit-memories',
            'view-events',
            'create-events',
            'edit-events',
            'manage-tributes',
        ]);

        // Assign role to existing users if present
        $superAdminUser = User::where('role', 'super_admin')->orWhere('email', 'dpmalinda99@gmail.com')->first();
        if ($superAdminUser && !$superAdminUser->hasRole('super_admin')) {
            $superAdminUser->assignRole($superAdminRole);
        }

        $memoryCreatorUser = User::where('role', 'memory_user')->orWhere('email', 'memorycreator@gmail.com')->first();
        if ($memoryCreatorUser && !$memoryCreatorUser->hasRole('memory_user')) {
            $memoryCreatorUser->assignRole($memoryUserRole);
        }
    }
}

