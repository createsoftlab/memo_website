<?php

namespace Tests\Feature\Admin;

use App\Mail\StaffAccountCreatedMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdmin = User::factory()->create([
            'name' => 'Super Administrator',
            'username' => 'superadmin',
            'email' => 'superadmin@example.com',
            'role' => 'super_admin',
            'status' => 'active',
        ]);
    }

    public function test_super_admin_can_view_user_directory(): void
    {
        User::factory()->create([
            'name' => 'Test Memory User',
            'role' => 'memory_user',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->superAdmin)->get(route('admin.users.index'));

        $response->assertOk();
        $response->assertSee('Test Memory User');
        $response->assertSee('All Users');
    }

    public function test_user_filtering_by_status_and_role(): void
    {
        User::factory()->create([
            'name' => 'Banned User',
            'role' => 'general_user',
            'status' => 'banned',
        ]);

        User::factory()->create([
            'name' => 'Active Admin',
            'role' => 'admin',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->superAdmin)
            ->get(route('admin.users.index', ['status' => 'banned']));

        $response->assertOk();
        $response->assertSee('Banned User');
        $response->assertDontSee('Active Admin');
    }

    public function test_super_admin_can_open_employee_provisioning_page(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('admin.employees.create'));

        $response->assertOk();
        $response->assertSee('Register New Employee / Sub-Admin');
    }

    public function test_non_super_admin_cannot_open_employee_provisioning_page(): void
    {
        $employee = User::factory()->create([
            'role' => 'employee',
            'status' => 'active',
        ]);

        $response = $this->actingAs($employee)->get(route('admin.employees.create'));

        $response->assertForbidden();
    }

    public function test_super_admin_can_provision_new_staff_member_with_credentials_email(): void
    {
        Mail::fake();

        $payload = [
            'name' => 'John Staff',
            'username' => 'john_staff',
            'email' => 'john.staff@example.com',
            'role' => 'employee',
            'password' => 'SecurePass123!',
            'permissions' => [
                'events' => ['view' => '1', 'add' => '1'],
                'memories' => ['view' => '1'],
            ],
        ];

        $response = $this->actingAs($this->superAdmin)
            ->post(route('admin.employees.store'), $payload);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'username' => 'john_staff',
            'email' => 'john.staff@example.com',
            'role' => 'employee',
            'status' => 'active',
        ]);

        $newUser = User::where('email', 'john.staff@example.com')->first();
        $this->assertTrue($newUser->hasPermission('events', 'view'));
        $this->assertTrue($newUser->hasPermission('events', 'add'));
        $this->assertFalse($newUser->hasPermission('events', 'delete'));

        Mail::assertSent(StaffAccountCreatedMail::class, function ($mail) use ($payload) {
            return $mail->hasTo('john.staff@example.com') &&
                   $mail->temporaryPassword === 'SecurePass123!';
        });
    }

    public function test_cannot_provision_user_with_invalid_role(): void
    {
        $payload = [
            'name' => 'Invalid Role User',
            'username' => 'invalidrole',
            'email' => 'invalid@example.com',
            'role' => 'general_user', // Only admin or employee allowed
            'password' => 'SecurePass123!',
        ];

        $response = $this->actingAs($this->superAdmin)
            ->post(route('admin.employees.store'), $payload);

        $response->assertSessionHasErrors(['role']);
    }

    public function test_quick_status_toggling(): void
    {
        $user = User::factory()->create([
            'role' => 'general_user',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->superAdmin)
            ->patch(route('admin.users.status', $user->id), [
                'status' => 'suspended',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Status updated successfully');

        $this->assertEquals('suspended', $user->fresh()->status);
    }

    public function test_updating_user_profile_and_permissions(): void
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'username' => 'orig_username',
            'email' => 'original@example.com',
            'role' => 'employee',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->superAdmin)
            ->put(route('admin.users.update', $user->id), [
                'name' => 'Updated Name',
                'username' => 'updated_username',
                'email' => 'updated@example.com',
                'phone' => '+1555123456',
                'country' => 'United States',
                'state' => 'New York',
                'role' => 'admin',
                'status' => 'active',
                'permissions' => [
                    'settings' => ['view' => '1'],
                ],
            ]);

        $response->assertRedirect(route('admin.users.index'));

        $updated = $user->fresh();
        $this->assertEquals('Updated Name', $updated->name);
        $this->assertEquals('updated_username', $updated->username);
        $this->assertEquals('admin', $updated->role);
        $this->assertTrue($updated->hasPermission('settings', 'view'));
    }

    public function test_edit_user_page_displays_target_user_data_and_not_logged_in_admin(): void
    {
        $targetUser = User::factory()->create([
            'name' => 'Target User Name',
            'username' => 'target_unique_username',
            'email' => 'target_user@example.com',
            'phone' => '+94771234567',
            'country' => 'Sri Lanka',
            'state' => 'Western Province',
            'role' => 'employee',
            'status' => 'active',
            'permissions' => [
                'events' => ['view' => '1', 'edit' => '1'],
            ],
        ]);

        $response = $this->actingAs($this->superAdmin)->get(route('admin.users.edit', $targetUser->id));

        $response->assertOk();
        $response->assertSee('value="Target User Name"', false);
        $response->assertSee('value="target_unique_username"', false);
        $response->assertSee('value="target_user@example.com"', false);
        $response->assertSee('value="+94771234567"', false);
        $response->assertSee('value="Sri Lanka"', false);
        $response->assertSee('id="edit_perm_events_view"', false);
        $response->assertSee('id="edit_perm_memories_view"', false);
    }

    public function test_super_admin_cannot_be_deleted(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->delete(route('admin.users.destroy', $this->superAdmin->id));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->superAdmin->id]);
    }
}
