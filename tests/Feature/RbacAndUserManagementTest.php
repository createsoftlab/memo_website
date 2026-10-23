<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\StaffCredentialsNotification;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RbacAndUserManagementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Seed roles & permissions
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_super_admin_can_provision_new_employee_with_auto_generated_password_and_notification(): void
    {
        Notification::fake();

        $superAdmin = User::where('email', 'superadmin@example.com')->first();

        $response = $this->actingAs($superAdmin)->post(route('admin.employees.store'), [
            'name' => 'Alice Staff',
            'email' => 'alice@example.com',
            'role' => 'Employee',
            'country' => 'United States',
            'state' => 'New York',
            'permissions' => ['moderate-users', 'approve-memories'],
        ]);

        $response->assertRedirect(route('admin.employees.index'));
        $response->assertSessionHas('temporary_credentials');

        $createdUser = User::where('email', 'alice@example.com')->first();
        $this->assertNotNull($createdUser);
        $this->assertTrue($createdUser->hasRole('Employee'));
        $this->assertTrue($createdUser->hasPermissionTo('moderate-users'));
        $this->assertTrue($createdUser->isActive());

        Notification::assertSentTo($createdUser, StaffCredentialsNotification::class);
    }

    public function test_suspended_or_banned_user_is_blocked_by_ensure_account_is_active_middleware(): void
    {
        $suspendedUser = User::where('email', 'suspended@example.com')->first();

        // Attempt accessing protected dashboard while suspended
        $response = $this->actingAs($suspendedUser)->get(route('admin.dashboard'));

        // Should be logged out and redirected to login with error
        $response->assertRedirect(route('login'));
        $this->assertGuest();
        $response->assertSessionHas('account_status_error');
    }

    public function test_admin_can_moderate_user_status(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();
        $generalUser = User::where('email', 'generaluser@example.com')->first();

        $this->assertTrue($generalUser->isActive());

        // Suspend user
        $response = $this->actingAs($admin)->patch(route('admin.users.status', $generalUser->id), [
            'status' => 'suspended',
        ]);

        $response->assertRedirect();
        $generalUser->refresh();
        $this->assertTrue($generalUser->isSuspended());

        // Reinstate user
        $response = $this->actingAs($admin)->patch(route('admin.users.status', $generalUser->id), [
            'status' => 'active',
        ]);

        $response->assertRedirect();
        $generalUser->refresh();
        $this->assertTrue($generalUser->isActive());
    }

    public function test_can_toggle_memory_creator_flag(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();
        $generalUser = User::where('email', 'generaluser@example.com')->first();

        $this->assertFalse((bool) $generalUser->is_memory_creator);

        $response = $this->actingAs($admin)->post(route('admin.users.toggle_memory_creator', $generalUser->id));

        $response->assertRedirect();
        $generalUser->refresh();
        $this->assertTrue((bool) $generalUser->is_memory_creator);
        $this->assertTrue($generalUser->isMemoryCreator());
    }

    public function test_user_can_update_profile_info(): void
    {
        $user = User::where('email', 'generaluser@example.com')->first();

        $response = $this->actingAs($user)->put(route('admin.profile.update'), [
            'name' => 'Updated Marcus',
            'country' => 'Canada',
            'state' => 'Ontario',
        ]);

        $response->assertRedirect();
        $user->refresh();
        $this->assertEquals('Updated Marcus', $user->name);
        $this->assertEquals('Canada', $user->country);
        $this->assertEquals('Ontario', $user->state);
    }

    public function test_user_can_change_password_with_current_password_verification(): void
    {
        $user = User::where('email', 'generaluser@example.com')->first();

        // Wrong current password should fail
        $response = $this->actingAs($user)->put(route('admin.profile.password'), [
            'current_password' => 'WrongPassword123',
            'password' => 'NewSecretPassword!456',
            'password_confirmation' => 'NewSecretPassword!456',
        ]);

        $response->assertSessionHasErrors(['current_password']);

        // Correct current password succeeds
        $response = $this->actingAs($user)->put(route('admin.profile.password'), [
            'current_password' => 'Admin@12345',
            'password' => 'NewSecretPassword!456',
            'password_confirmation' => 'NewSecretPassword!456',
        ]);

        $response->assertRedirect();
        $user->refresh();
        $this->assertTrue(Hash::check('NewSecretPassword!456', $user->password));
    }
}
