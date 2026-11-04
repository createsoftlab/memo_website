<?php

use App\Models\Memory;
use App\Models\Package;
use App\Models\PackagePurchase;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutMiddleware(ValidateCsrfToken::class);

    // Seed packages
    $this->freePackage = Package::create([
        'name' => 'Basic Free',
        'country' => 'all',
        'business_profile' => 'all',
        'price' => 0.00,
        'currency' => 'usd',
        'billing_type' => 'lifetime',
        'duration_value' => null,
        'duration_unit' => 'unlimited',
        'trial_days' => 0,
        'memories_limit' => 1,
        'events_limit' => 1,
        'max_photos' => 5,
        'max_videos' => 0,
        'max_audios' => 0,
        'is_active' => true,
    ]);

    $this->yearlyPackage = Package::create([
        'name' => 'Premium Yearly',
        'country' => 'all',
        'business_profile' => 'all',
        'price' => 79.95,
        'currency' => 'usd',
        'billing_type' => 'yearly',
        'duration_value' => 1,
        'duration_unit' => 'years',
        'trial_days' => 14,
        'memories_limit' => 5,
        'events_limit' => 5,
        'max_photos' => 50,
        'max_videos' => 5,
        'max_audios' => 10,
        'is_active' => true,
    ]);

    $this->lifetimePackage = Package::create([
        'name' => 'Lifetime Plan',
        'country' => 'all',
        'business_profile' => 'all',
        'price' => 159.95,
        'currency' => 'usd',
        'billing_type' => 'lifetime',
        'duration_value' => null,
        'duration_unit' => 'unlimited',
        'trial_days' => 14,
        'memories_limit' => -1,
        'events_limit' => -1,
        'max_photos' => -1,
        'max_videos' => -1,
        'max_audios' => -1,
        'is_active' => true,
    ]);

    $this->user = User::factory()->create([
        'role' => 'user',
        'email_verified_at' => now(),
    ]);
});

test('create memory page loads with dynamic packages', function () {
    $response = $this->actingAs($this->user)->get(route('home.create_memory'));

    $response->assertStatus(200);
    $response->assertSee('Basic Free');
    $response->assertSee('Premium Yearly');
    $response->assertSee('Lifetime Plan');
    $response->assertSee('Payment Details');
    $response->assertSee('Complete & Activate / Purchase', false);
});

test('user can submit memory with free plan without payment details', function () {
    $response = $this->actingAs($this->user)->post(route('home.create_memory.store'), [
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'gender' => 'Female',
        'country' => 'Sri Lanka',
        'city' => 'Colombo',
        'memorial_status' => 'passed_away',
        'privacy' => 'public',
        'status' => 'published',
        'package_id' => $this->freePackage->id,
    ]);

    $response->assertRedirect(route('dashboard'));
    $response->assertSessionHas('success', 'Memory created and plan activated successfully!');

    // Assert Memory and Profile created
    $this->assertDatabaseHas('memories', [
        'user_id' => $this->user->id,
        'status' => 'published',
    ]);

    $this->assertDatabaseHas('profiles', [
        'user_id' => $this->user->id,
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'country' => 'Sri Lanka',
    ]);

    // Assert Package Purchase created
    $this->assertDatabaseHas('package_purchases', [
        'user_id' => $this->user->id,
        'package_id' => $this->freePackage->id,
        'amount_paid' => 0.00,
        'payment_method' => 'free',
        'status' => 'active',
        'expires_at' => null,
    ]);

    // Assert User updated
    $this->user->refresh();
    expect($this->user->package_id)->toBe($this->freePackage->id);
    expect($this->user->role)->toBe('memory_user');
    expect($this->user->packagePurchases)->toHaveCount(1);
});

test('paid plan requires card details and fails validation if missing', function () {
    $response = $this->actingAs($this->user)->post(route('home.create_memory.store'), [
        'first_name' => 'John',
        'last_name' => 'Smith',
        'gender' => 'Male',
        'country' => 'United States',
        'privacy' => 'public',
        'status' => 'published',
        'package_id' => $this->yearlyPackage->id,
        // Card fields omitted
    ]);

    $response->assertSessionHasErrors(['card_name', 'card_number', 'expiry_date', 'cvv']);
    $this->assertDatabaseCount('package_purchases', 0);
});

test('user can purchase paid yearly package with card details', function () {
    $response = $this->actingAs($this->user)->post(route('home.create_memory.store'), [
        'first_name' => 'John',
        'last_name' => 'Smith',
        'gender' => 'Male',
        'country' => 'United States',
        'privacy' => 'public',
        'status' => 'published',
        'package_id' => $this->yearlyPackage->id,
        'card_name' => 'John Smith',
        'card_number' => '4242 4242 4242 4242',
        'expiry_date' => '12/28',
        'cvv' => '123',
    ]);

    $response->assertRedirect(route('dashboard'));
    $response->assertSessionHas('success', 'Memory created and plan activated successfully!');

    // Assert purchase record
    $purchase = PackagePurchase::where('user_id', $this->user->id)->first();
    expect($purchase)->not->toBeNull();
    expect($purchase->package_id)->toBe($this->yearlyPackage->id);
    expect((float) $purchase->amount_paid)->toBe(79.95);
    expect($purchase->payment_method)->toBe('card');
    expect($purchase->transaction_id)->toStartWith('TXN_');
    expect($purchase->status)->toBe('active');
    expect($purchase->trial_ends_at)->not->toBeNull();
    expect($purchase->expires_at)->not->toBeNull();

    // Assert User updated
    $this->user->refresh();
    expect($this->user->package_id)->toBe($this->yearlyPackage->id);
    expect($this->user->package_expires_at)->not->toBeNull();
});

test('lifetime plan sets expires_at to null', function () {
    $response = $this->actingAs($this->user)->post(route('home.create_memory.purchase'), [
        'first_name' => 'Alice',
        'last_name' => 'Wonderland',
        'gender' => 'Female',
        'country' => 'United Kingdom',
        'privacy' => 'public',
        'status' => 'published',
        'package_id' => $this->lifetimePackage->id,
        'card_name' => 'Alice Wonderland',
        'card_number' => '4000 0000 0000 0002',
        'expiry_date' => '11/29',
        'cvv' => '999',
    ]);

    $response->assertRedirect(route('dashboard'));

    $purchase = PackagePurchase::where('user_id', $this->user->id)->first();
    expect($purchase)->not->toBeNull();
    expect((float) $purchase->amount_paid)->toBe(159.95);
    expect($purchase->expires_at)->toBeNull();
    expect($purchase->trial_ends_at)->not->toBeNull();

    $this->user->refresh();
    expect($this->user->package_id)->toBe($this->lifetimePackage->id);
    expect($this->user->package_expires_at)->toBeNull();
});
