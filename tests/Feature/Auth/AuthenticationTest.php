<?php

use App\Models\User;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen and redirect according to role', function () {
    // 1. Standard user redirects to user dashboard
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('user.dashboard', absolute: false));

    $this->post('/logout');

    // 2. Admin redirects to admin dashboard
    $admin = User::factory()->create(['role' => 'admin']);

    $adminResponse = $this->post('/login', [
        'email' => $admin->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $adminResponse->assertRedirect(route('admin.dashboard', absolute: false));

    $this->post('/logout');

    // 3. Super Admin redirects to admin dashboard
    $superAdmin = User::factory()->create(['role' => 'super_admin']);

    $superResponse = $this->post('/login', [
        'email' => $superAdmin->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $superResponse->assertRedirect(route('admin.dashboard', absolute: false));
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
