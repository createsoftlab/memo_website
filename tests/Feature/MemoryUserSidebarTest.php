<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Memory;
use App\Models\Package;
use App\Models\Profile;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class MemoryUserSidebarTest extends TestCase
{
    use RefreshDatabase;

    protected User $memoryUser;
    protected User $superAdmin;
    protected User $admin;
    protected Package $standardPackage;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);

        // Active test package
        $this->standardPackage = Package::create([
            'name' => 'Memorial Standard Tier',
            'country' => 'all',
            'price' => 19.99,
            'currency' => 'usd',
            'billing_type' => 'monthly',
            'duration_value' => 30,
            'duration_unit' => 'days',
            'memories_limit' => 2,
            'events_limit' => 3,
            'is_active' => true,
        ]);

        $this->memoryUser = User::factory()->create([
            'name' => 'Memorial Creator',
            'email' => 'creator_test@example.com',
            'role' => 'memory_user',
            'status' => 'active',
        ]);
        $this->memoryUser->assignRole('memory_user');

        $this->superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin_test@example.com',
            'role' => 'super_admin',
            'status' => 'active',
        ]);
        $this->superAdmin->assignRole('super_admin');

        $this->admin = User::factory()->create([
            'name' => 'Admin Moderator',
            'email' => 'admin_test@example.com',
            'role' => 'admin',
            'status' => 'active',
            'permissions' => ['dashboard', 'memories', 'events'],
        ]);
        $this->admin->assignRole('admin');
    }

    public function test_user_without_active_package_sees_locked_sidebar_and_is_redirected(): void
    {
        // 1. Dashboard rendering check
        $response = $this->actingAs($this->memoryUser)->get(route('memoryUser.dashboard'));

        $response->assertOk();
        $response->assertSee('No Active Plan');
        $response->assertSee('Buy Package');
        $response->assertSee('bi-lock-fill'); // Lock icons visible
        $response->assertSee(route('packages.index', ['country' => 'all']));

        // 2. Direct route access redirects to packages index with warning
        $eventCreateResponse = $this->actingAs($this->memoryUser)->get(route('events.create'));
        $eventCreateResponse->assertRedirect(route('packages.index', ['country' => 'all']));
        $eventCreateResponse->assertSessionHas('warning');

        $memoryCreateResponse = $this->actingAs($this->memoryUser)->get(route('memories.create'));
        $memoryCreateResponse->assertRedirect(route('packages.index', ['country' => 'all']));
        $memoryCreateResponse->assertSessionHas('warning');

        // 3. Authorization policies deny creation
        $this->assertFalse($this->memoryUser->canCreateMemory());
        $this->assertFalse($this->memoryUser->canCreateEvent());
        $this->assertFalse(Gate::forUser($this->memoryUser)->allows('create', Memory::class));
        $this->assertFalse(Gate::forUser($this->memoryUser)->allows('create', Event::class));
    }

    public function test_user_with_active_package_and_remaining_quota_sees_active_sidebar_and_can_create(): void
    {
        // Assign package to user
        $this->memoryUser->update([
            'package_id' => $this->standardPackage->id,
            'package_expires_at' => now()->addDays(25),
        ]);

        $this->assertTrue($this->memoryUser->hasActivePackage());
        $this->assertTrue($this->memoryUser->canCreateMemory());
        $this->assertTrue($this->memoryUser->canCreateEvent());
        $this->assertEquals(25, $this->memoryUser->remainingDays());
        $this->assertEquals(2, $this->memoryUser->remainingMemoriesQuota());
        $this->assertEquals(3, $this->memoryUser->remainingEventsQuota());

        // 1. Dashboard displays active plan info
        $response = $this->actingAs($this->memoryUser)->get(route('memoryUser.dashboard'));
        $response->assertOk();
        $response->assertSee('Active Plan');
        $response->assertSee('25d left');
        $response->assertSee('2 Mem | 3 Evt');
        $response->assertSee(route('memoryUser.create_memory'));
        $response->assertSee(route('memoryUser.create_event'));

        // 2. Direct creation routes are accessible (200 OK)
        $this->actingAs($this->memoryUser)->get(route('events.create'))->assertOk();
        $this->actingAs($this->memoryUser)->get(route('memories.create'))->assertOk();
        $this->actingAs($this->memoryUser)->get(route('memoryUser.create_event'))->assertOk();
        $this->actingAs($this->memoryUser)->get(route('memoryUser.create_memory'))->assertOk();

        // 3. User can create an event successfully
        $eventData = [
            'title' => 'Memorial Celebration of Life',
            'event_type' => 'upcoming',
            'start_date' => now()->addDays(5)->toDateString(),
            'start_time' => '11:00 AM',
            'location' => 'St. Peter Memorial Gardens',
            'description' => 'A gathering to remember and celebrate our beloved.',
        ];

        $postResponse = $this->actingAs($this->memoryUser)->post(route('admin.events.store'), $eventData);
        $postResponse->assertRedirect(route('admin.events.my'));
        $postResponse->assertSessionHas('success');

        $this->assertDatabaseHas('events', [
            'title' => 'Memorial Celebration of Life',
            'user_id' => $this->memoryUser->id,
            'status' => 'pending',
        ]);

        // 4. Policy checks pass
        $this->assertTrue(Gate::forUser($this->memoryUser)->allows('create', Memory::class));
        $this->assertTrue(Gate::forUser($this->memoryUser)->allows('create', Event::class));
    }

    public function test_user_with_exhausted_quota_sees_locked_sidebar_and_is_redirected(): void
    {
        // Assign package with 1 memory limit
        $package = Package::create([
            'name' => 'Single Memory Plan',
            'country' => 'all',
            'price' => 9.99,
            'currency' => 'usd',
            'billing_type' => 'monthly',
            'duration_value' => 30,
            'duration_unit' => 'days',
            'memories_limit' => 1,
            'events_limit' => 1,
            'is_active' => true,
        ]);

        $this->memoryUser->update([
            'package_id' => $package->id,
            'package_expires_at' => now()->addDays(20),
        ]);

        // Consume memory quota by creating 1 memory & profile
        $memory = Memory::create([
            'user_id' => $this->memoryUser->id,
            'memorial_type' => 'passed_away',
            'status' => 'published',
        ]);

        Profile::create([
            'user_id' => $this->memoryUser->id,
            'memory_id' => $memory->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'memorial_status' => 'passed_away',
            'gender' => 'male',
            'country' => 'us',
            'privacy' => 'public',
        ]);

        // Quota is now 0
        $this->assertEquals(0, $this->memoryUser->remainingMemoriesQuota());
        $this->assertFalse($this->memoryUser->canCreateMemory());

        // Attempting to visit create memory route redirects with quota limit message
        $response = $this->actingAs($this->memoryUser)->get(route('memories.create'));
        $response->assertRedirect(route('packages.index', ['country' => 'all']));
        $response->assertSessionHas('warning', 'You have reached the memorial creation limit for your current package. Please upgrade or renew to create more memories.');

        // Sidebar reflects locked state with Buy Package badge
        $sidebarResponse = $this->actingAs($this->memoryUser)->get(route('memoryUser.dashboard'));
        $sidebarResponse->assertOk();
        $sidebarResponse->assertSee('Buy Package');
    }

    public function test_standard_user_registration_and_login_flow_with_package_gated_actions(): void
    {
        // 1. User Registration: Role defaults to 'user', initial package_id is null
        $regResponse = $this->post('/register', [
            'name' => 'Jane Memorialist',
            'email' => 'jane@example.com',
            'phone' => '+15551234567',
            'country' => 'United States',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
        ]);

        $this->assertAuthenticated();
        // Redirects directly to user dashboard (route('user.dashboard'))
        $regResponse->assertRedirect(route('user.dashboard'));

        $registeredUser = User::where('email', 'jane@example.com')->firstOrFail();
        $this->assertEquals('user', $registeredUser->role);
        $this->assertNull($registeredUser->package_id);

        // 2. Login Redirection: Standard user redirects to user dashboard, never admin dashboard
        $this->post('/logout');
        $this->assertGuest();

        $loginResponse = $this->post('/login', [
            'email' => 'jane@example.com',
            'password' => 'secret1234',
        ]);

        $this->assertAuthenticated();
        $loginResponse->assertRedirect(route('user.dashboard', absolute: false));

        // 3. Package-Gated Creation Features:
        // When package_id is null: Sidebar shows Locked State with 'Buy Package' badge and lock icon
        $dashboardResponse = $this->actingAs($registeredUser)->get(route('user.dashboard'));
        $dashboardResponse->assertOk();
        $dashboardResponse->assertSee('No Active Plan');
        $dashboardResponse->assertSee('Buy Package');
        $dashboardResponse->assertSee('bi-lock-fill');

        // Direct URL access to create routes redirected to /packages
        $this->actingAs($registeredUser)->get(route('events.create'))
            ->assertRedirect(route('packages.index', ['country' => 'united states']))
            ->assertSessionHas('warning');

        $this->actingAs($registeredUser)->get(route('memories.create'))
            ->assertRedirect(route('packages.index', ['country' => 'united states']))
            ->assertSessionHas('warning');

        // 4. When package_id is NOT null: Authorized to create Events and Memory posts
        $registeredUser->update([
            'package_id' => $this->standardPackage->id,
            'package_expires_at' => now()->addDays(30),
        ]);

        $this->assertTrue($registeredUser->canCreateMemory());
        $this->assertTrue($registeredUser->canCreateEvent());

        $this->actingAs($registeredUser)->get(route('events.create'))->assertOk();
        $this->actingAs($registeredUser)->get(route('memories.create'))->assertOk();
    }

    public function test_admin_and_super_admin_cannot_access_creation_forms(): void
    {
        // Super Admin forbidden from creation
        $this->actingAs($this->superAdmin)->get(route('admin.events.create'))->assertForbidden();
        $this->actingAs($this->superAdmin)->get(route('admin.create_memory'))->assertForbidden();
        $this->actingAs($this->superAdmin)->get(route('events.create'))->assertForbidden();
        $this->actingAs($this->superAdmin)->get(route('memories.create'))->assertForbidden();

        // Admin forbidden from creation
        $this->actingAs($this->admin)->get(route('admin.events.create'))->assertForbidden();
        $this->actingAs($this->admin)->get(route('admin.create_memory'))->assertForbidden();
        $this->actingAs($this->admin)->get(route('events.create'))->assertForbidden();
        $this->actingAs($this->admin)->get(route('memories.create'))->assertForbidden();

        // Policy checks explicitly deny super_admin and admin
        $this->assertFalse(Gate::forUser($this->superAdmin)->allows('create', Memory::class));
        $this->assertFalse(Gate::forUser($this->superAdmin)->allows('create', Event::class));
        $this->assertFalse(Gate::forUser($this->admin)->allows('create', Memory::class));
        $this->assertFalse(Gate::forUser($this->admin)->allows('create', Event::class));
    }

    public function test_memory_user_cannot_access_staff_user_management(): void
    {
        $response = $this->actingAs($this->memoryUser)->get(route('admin.users.index'));
        $response->assertForbidden();
    }
}
