<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define Granular Permissions
        $permissionGroups = [
            'Staff & RBAC Management' => [
                'manage-admins' => 'Create, edit, delete admin/employee accounts and manage permissions',
                'view-admins' => 'View staff, employee, and administrator directories',
            ],
            'User Moderation' => [
                'view-users' => 'View registered platform users and memory creators',
                'moderate-users' => 'Moderate user states (suspend, ban, reinstate)',
                'delete-users' => 'Permanently delete user accounts',
            ],
            'Memorials & Approvals' => [
                'view-memories' => 'Browse all memorial listings',
                'create-memories' => 'Create and publish memorial listings',
                'edit-memories' => 'Edit memorial details and content',
                'approve-memories' => 'Review and approve/reject memorial submissions',
                'delete-memories' => 'Delete memorial listings',
            ],
            'Events Management' => [
                'view-events' => 'View platform events and memorials',
                'manage-events' => 'Create, update, and manage memorial events',
            ],
            'Packages & Billing' => [
                'manage-packages' => 'Create, edit, and configure subscription/memorial packages',
                'view-payments' => 'View payment histories and transactions',
            ],
            'Communications & Analytics' => [
                'send-bulk-emails' => 'Dispatch bulk announcements and newsletters',
                'view-reports' => 'Access administrative reports and analytics',
            ],
            'Tributes & Marketplace' => [
                'post-tributes' => 'Post condolences, stories, and tribute messages',
                'purchase-items' => 'Purchase virtual candles, flowers, and memorials',
            ],
        ];

        $allPermissionNames = [];

        foreach ($permissionGroups as $group => $permissions) {
            foreach ($permissions as $name => $description) {
                Permission::firstOrCreate(
                    ['name' => $name, 'guard_name' => 'web']
                );
                $allPermissionNames[] = $name;
            }
        }

        // 2. Create Roles
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $employeeRole = Role::firstOrCreate(['name' => 'Employee', 'guard_name' => 'web']);
        $memoryUserRole = Role::firstOrCreate(['name' => 'Memory Created User', 'guard_name' => 'web']);
        $generalUserRole = Role::firstOrCreate(['name' => 'General User', 'guard_name' => 'web']);

        // 3. Assign Permissions to Roles
        // Super Admin gets all permissions
        $superAdminRole->syncPermissions($allPermissionNames);

        // Admin gets management permissions (excluding managing root super admins)
        $adminRole->syncPermissions([
            'view-admins',
            'view-users',
            'moderate-users',
            'view-memories',
            'create-memories',
            'edit-memories',
            'approve-memories',
            'delete-memories',
            'view-events',
            'manage-events',
            'manage-packages',
            'view-payments',
            'send-bulk-emails',
            'view-reports',
            'post-tributes',
            'purchase-items',
        ]);

        // Employee gets operational permissions (moderation, memory approvals, event management, reports)
        $employeeRole->syncPermissions([
            'view-users',
            'moderate-users',
            'view-memories',
            'approve-memories',
            'view-events',
            'view-reports',
        ]);

        // Memory Created User can create and manage their memorial listings, purchase packages, events, tributes
        $memoryUserRole->syncPermissions([
            'view-memories',
            'create-memories',
            'edit-memories',
            'post-tributes',
            'purchase-items',
            'view-events',
            'manage-events',
        ]);

        // General User can browse, post tributes, and purchase candles/flowers
        $generalUserRole->syncPermissions([
            'view-memories',
            'post-tributes',
            'purchase-items',
            'view-events',
        ]);

        // 4. Create or Update Super Admin Account
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Platform Super Admin',
                'role' => 'super_admin',
                'password' => Hash::make('Admin@12345'),
                'country' => 'United States',
                'state' => 'California',
                'registration_ip' => '127.0.0.1',
                'status' => User::STATUS_ACTIVE,
                'is_memory_creator' => false,
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->syncRoles(['Super Admin']);

        // 5. Seed Sample Staff & Users for Demonstration & Testing
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Operations Admin',
                'role' => 'admin',
                'password' => Hash::make('Admin@12345'),
                'country' => 'United Kingdom',
                'state' => 'Greater London',
                'registration_ip' => '192.168.1.10',
                'status' => User::STATUS_ACTIVE,
                'is_memory_creator' => false,
                'email_verified_at' => now(),
            ]
        );
        $admin->syncRoles(['Admin']);

        $employee = User::updateOrCreate(
            ['email' => 'employee@example.com'],
            [
                'name' => 'Moderation Officer',
                'role' => 'employee',
                'password' => Hash::make('Admin@12345'),
                'country' => 'Canada',
                'state' => 'Ontario',
                'registration_ip' => '192.168.1.15',
                'status' => User::STATUS_ACTIVE,
                'is_memory_creator' => false,
                'email_verified_at' => now(),
            ]
        );
        $employee->syncRoles(['Employee']);

        $memoryCreator = User::updateOrCreate(
            ['email' => 'memorycreator@example.com'],
            [
                'name' => 'Eleanor Vance',
                'role' => 'Memory Created User',
                'password' => Hash::make('Admin@12345'),
                'country' => 'United States',
                'state' => 'New York',
                'registration_ip' => '203.0.113.19',
                'status' => User::STATUS_ACTIVE,
                'is_memory_creator' => true,
                'email_verified_at' => now(),
            ]
        );
        $memoryCreator->syncRoles(['Memory Created User']);

        $generalUser = User::updateOrCreate(
            ['email' => 'generaluser@example.com'],
            [
                'name' => 'Marcus Holloway',
                'role' => 'General User',
                'password' => Hash::make('Admin@12345'),
                'country' => 'Australia',
                'state' => 'New South Wales',
                'registration_ip' => '198.51.100.42',
                'status' => User::STATUS_ACTIVE,
                'is_memory_creator' => false,
                'email_verified_at' => now(),
            ]
        );
        $generalUser->syncRoles(['General User']);

        $suspendedUser = User::updateOrCreate(
            ['email' => 'suspended@example.com'],
            [
                'name' => 'Suspended Account Demo',
                'role' => 'General User',
                'password' => Hash::make('Admin@12345'),
                'country' => 'United States',
                'state' => 'Texas',
                'registration_ip' => '198.51.100.88',
                'status' => User::STATUS_SUSPENDED,
                'is_memory_creator' => false,
                'email_verified_at' => now(),
            ]
        );
        $suspendedUser->syncRoles(['General User']);

        $bannedUser = User::updateOrCreate(
            ['email' => 'banned@example.com'],
            [
                'name' => 'Banned Spammer Demo',
                'role' => 'General User',
                'password' => Hash::make('Admin@12345'),
                'country' => 'Unknown',
                'state' => 'Unknown',
                'registration_ip' => '198.51.100.99',
                'status' => User::STATUS_BANNED,
                'is_memory_creator' => false,
                'email_verified_at' => now(),
            ]
        );
        $bannedUser->syncRoles(['General User']);
    }
}
