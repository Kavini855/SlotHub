<?php

use Laravel\Fortify\Features;
use Laravel\Jetstream\Jetstream;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
})->skip(function () {
    return !Features::enabled(Features::registration());
}, 'Registration support is not enabled.');

test('registration screen cannot be rendered if support is disabled', function () {
    $response = $this->get('/register');

    $response->assertStatus(404);
})->skip(function () {
    return Features::enabled(Features::registration());
}, 'Registration support is enabled.');

test('customers can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test Customer',
        'email' => 'customer@example.com',
        'role' => 'customer',
        'password' => 'password',
        'password_confirmation' => 'password',
        'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
    ]);

    $this->assertAuthenticated();

    $this->assertDatabaseHas('users', [
        'email' => 'customer@example.com',
        'role' => 'customer',
    ]);

    $response->assertRedirect(route('dashboard', absolute: false));
})->skip(function () {
    return !Features::enabled(Features::registration());
}, 'Registration support is not enabled.');

test('service providers can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test Provider',
        'email' => 'provider@example.com',
        'role' => 'provider',
        'password' => 'password',
        'password_confirmation' => 'password',
        'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
    ]);

    $this->assertAuthenticated();

    $this->assertDatabaseHas('users', [
        'email' => 'provider@example.com',
        'role' => 'provider',
    ]);

    $response->assertRedirect(route('dashboard', absolute: false));
})->skip(function () {
    return !Features::enabled(Features::registration());
}, 'Registration support is not enabled.');

test('users cannot register with an invalid role', function () {
    $response = $this->post('/register', [
        'name' => 'Fake Admin',
        'email' => 'admin@example.com',
        'role' => 'admin',
        'password' => 'password',
        'password_confirmation' => 'password',
        'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
    ]);

    $response->assertSessionHasErrors('role');

    $this->assertGuest();

    $this->assertDatabaseMissing('users', [
        'email' => 'admin@example.com',
    ]);
})->skip(function () {
    return !Features::enabled(Features::registration());
}, 'Registration support is not enabled.');