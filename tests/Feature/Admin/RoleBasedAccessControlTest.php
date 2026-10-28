<?php

namespace Tests\Feature\Admin;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleBasedAccessControlTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $admin;
    protected User $employee;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdmin = User::factory()->create([
            'name' => 'Super Admin User',
            'username' => 'superadmin_test',
            'email' => 'superadmin_test@example.com',
            'role' => 'super_admin',
            'status' => 'active',
        ]);

        $this->admin = User::factory()->create([
            'name' => 'Admin User',
            'username' => 'admin_test',
            'email' => 'admin_test@example.com',
            'role' => 'admin',
            'status' => 'active',
            'permissions' => ['dashboard', 'memories', 'events', 'reports'],
        ]);

        $this->employee = User::factory()->create([
            'name' => 'Employee User',
            'username' => 'employee_test',
            'email' => 'employee_test@example.com',
            'role' => 'employee',
            'status' => 'active',
            'permissions' => ['dashboard', 'events'],
        ]);
    }

    public function test_super_admin_has_full_user_management_access(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('admin.users.index'));
        $response->assertOk();
    }

    public function test_admin_is_strictly_forbidden_from_user_management(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.index'));
        $response->assertForbidden();

        $createResponse = $this->actingAs($this->admin)->get(route('admin.employees.create'));
        $createResponse->assertForbidden();
    }

    public function test_admin_cannot_delete_any_records(): void
    {
        $event = Event::create([
            'user_id' => $this->superAdmin->id,
            'title' => 'Test Memorial Event',
            'event_type' => 'upcoming',
            'status' => 'approved',
            'start_date' => now()->toDateString(),
            'start_time' => '10:00 AM',
        ]);

        $this->assertFalse($this->admin->canDelete());

        $response = $this->actingAs($this->admin)->delete(route('admin.events.destroy', $event->id));
        $response->assertForbidden();

        $this->assertDatabaseHas('events', ['id' => $event->id]);
    }

    public function test_super_admin_can_delete_records(): void
    {
        $event = Event::create([
            'user_id' => $this->superAdmin->id,
            'title' => 'Super Admin Test Event',
            'event_type' => 'upcoming',
            'status' => 'approved',
            'start_date' => now()->toDateString(),
            'start_time' => '10:00 AM',
        ]);

        $this->assertTrue($this->superAdmin->canDelete());

        $response = $this->actingAs($this->superAdmin)->delete(route('admin.events.destroy', $event->id));
        $response->assertRedirect();

        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }

    public function test_super_admin_and_admin_cannot_create_events_or_memories(): void
    {
        // Super Admin blocked from creating event
        $superResponse = $this->actingAs($this->superAdmin)->get(route('admin.events.create'));
        $superResponse->assertForbidden();

        // Admin blocked from creating event
        $adminResponse = $this->actingAs($this->admin)->get(route('admin.events.create'));
        $adminResponse->assertForbidden();

        // Super Admin blocked from creating memory
        $superMemResponse = $this->actingAs($this->superAdmin)->get(route('admin.create_memory'));
        $superMemResponse->assertForbidden();

        // Admin blocked from creating memory
        $adminMemResponse = $this->actingAs($this->admin)->get(route('admin.create_memory'));
        $adminMemResponse->assertForbidden();
    }

    public function test_granular_module_permission_enforcement(): void
    {
        // Employee has 'events' permission -> Allowed
        $eventResponse = $this->actingAs($this->employee)->get(route('admin.events.all'));
        $eventResponse->assertOk();

        // Employee DOES NOT have 'memories' permission -> Forbidden (403)
        $memResponse = $this->actingAs($this->employee)->get(route('admin.all_memories'));
        $memResponse->assertForbidden();

        // Admin has 'memories' -> Allowed
        $adminMemResponse = $this->actingAs($this->admin)->get(route('admin.all_memories'));
        $adminMemResponse->assertOk();

        // Admin DOES NOT have 'payments' -> Forbidden (403)
        $paymentsResponse = $this->actingAs($this->admin)->get(route('admin.payments'));
        $paymentsResponse->assertForbidden();
    }

    public function test_dynamic_sidebar_visibility_based_on_permissions(): void
    {
        // For Admin (has memories, events, reports; NO user management)
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertOk();
        $response->assertSee('All Memories');
        $response->assertSee('Events');
        $response->assertSee('Reports');
        $response->assertDontSee('User Management');
        $response->assertDontSee('Payments');

        // For Super Admin (sees User Management, All Memories, etc.)
        $superResponse = $this->actingAs($this->superAdmin)->get(route('admin.dashboard'));
        $superResponse->assertOk();
        $superResponse->assertSee('User Management');
    }

    public function test_multidimensional_permissions_matrix_and_can_access_module(): void
    {
        $matrixStaff = User::factory()->create([
            'role' => 'admin',
            'permissions' => [
                'memories' => ['view' => 1, 'approve' => 1, 'reject' => 1],
                'events' => ['view' => 1, 'edit' => 1],
                'packages' => ['view' => 1, 'add' => 1],
                'reports' => [],
            ],
        ]);

        $this->assertTrue($matrixStaff->canAccessModule('memories'));
        $this->assertTrue($matrixStaff->hasPermission('memories', 'view'));
        $this->assertTrue($matrixStaff->hasPermission('memories', 'approve'));
        $this->assertTrue($matrixStaff->hasPermission('memories', 'reject'));
        $this->assertFalse($matrixStaff->hasPermission('memories', 'delete'));

        $this->assertTrue($matrixStaff->canAccessModule('events'));
        $this->assertTrue($matrixStaff->hasPermission('events', 'edit'));
        $this->assertFalse($matrixStaff->hasPermission('events', 'approve'));

        $this->assertTrue($matrixStaff->canAccessModule('packages'));
        $this->assertTrue($matrixStaff->hasPermission('packages', 'add'));

        // reports is empty -> canAccessModule should be false
        $this->assertFalse($matrixStaff->canAccessModule('reports'));
        $this->assertFalse($matrixStaff->canAccessModule('payments'));
        $this->assertFalse($matrixStaff->canAccessModule('settings'));

        // Super Admin always has full access
        $this->assertTrue($this->superAdmin->canAccessModule('memories'));
        $this->assertTrue($this->superAdmin->hasPermission('memories', 'delete'));
        $this->assertTrue($this->superAdmin->canDelete());
    }

    public function test_hard_restriction_admins_and_employees_can_never_delete(): void
    {
        // Even if malicious/accidental permission set includes 'delete' => 1
        $adminWithDeleteFlag = User::factory()->create([
            'role' => 'admin',
            'permissions' => [
                'events' => ['view' => 1, 'delete' => 1],
            ],
        ]);

        $this->assertFalse($adminWithDeleteFlag->canDelete());
        $this->assertFalse($adminWithDeleteFlag->hasPermission('events', 'delete'));
    }
}
