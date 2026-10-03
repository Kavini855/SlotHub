<?php

use App\Models\User;

test('guests cannot access customer dashboard', function () {
    $response = $this->get('/customer/dashboard');

    $response->assertRedirect('/login');
});

test('guests cannot access provider dashboard', function () {
    $response = $this->get('/provider/dashboard');

    $response->assertRedirect('/login');
});

test('customers can access customer dashboard', function () {
    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $response = $this->actingAs($customer)
        ->get('/customer/dashboard');

    $response->assertOk();
});

test('customers cannot access provider dashboard', function () {
    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $response = $this->actingAs($customer)
        ->get('/provider/dashboard');

    $response->assertForbidden();
});

test('providers can access provider dashboard', function () {
    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $response = $this->actingAs($provider)
        ->get('/provider/dashboard');

    $response->assertOk();
});

test('providers cannot access customer dashboard', function () {
    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $response = $this->actingAs($provider)
        ->get('/customer/dashboard');

    $response->assertForbidden();
});