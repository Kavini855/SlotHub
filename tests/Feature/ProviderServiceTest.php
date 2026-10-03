<?php

use App\Models\Category;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests cannot access provider services', function () {
    $response = $this->get('/provider/services');

    $response->assertRedirect('/login');
});

test('customers cannot access provider services', function () {
    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $response = $this
        ->actingAs($customer)
        ->get('/provider/services');

    $response->assertForbidden();
});

test('service providers can access provider services', function () {
    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $response = $this
        ->actingAs($provider)
        ->get('/provider/services');

    $response->assertStatus(200);
});

test('provider cannot edit another providers service', function () {
    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $otherProvider = User::factory()->create([
        'role' => 'provider',
    ]);

    $category = Category::create([
        'name' => 'Testing Category',
        'slug' => 'testing-category',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $otherProvider->id,
        'category_id' => $category->id,
        'name' => 'Other Provider Service',
        'description' => 'This service belongs to another provider.',
        'price' => 2500,
        'duration_minutes' => 60,
        'is_active' => true,
    ]);

    $response = $this
        ->actingAs($provider)
        ->get(route('provider.services.edit', $service));

    $response->assertForbidden();
});

test('provider can create a service', function () {
    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $category = Category::create([
        'name' => 'Consulting',
        'slug' => 'consulting',
        'is_active' => true,
    ]);

    $response = $this
        ->actingAs($provider)
        ->post(route('provider.services.store'), [
            'name' => 'Business Consultation',
            'category_id' => $category->id,
            'description' => 'Professional business consultation service.',
            'price' => 3000,
            'duration_minutes' => 60,
        ]);

    $response->assertRedirect(route('provider.services.index'));

    $this->assertDatabaseHas('services', [
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'Business Consultation',
        'price' => 3000,
        'duration_minutes' => 60,
        'is_active' => true,
    ]);
});

test('provider cannot create a service with invalid data', function () {
    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $response = $this
        ->actingAs($provider)
        ->post(route('provider.services.store'), [
            'name' => '',
            'category_id' => 999,
            'description' => '',
            'price' => -100,
            'duration_minutes' => 0,
        ]);

    $response->assertSessionHasErrors([
        'name',
        'category_id',
        'description',
        'price',
        'duration_minutes',
    ]);

    $this->assertDatabaseCount('services', 0);
});

test('provider can update their own service', function () {
    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $category = Category::create([
        'name' => 'Consulting',
        'slug' => 'consulting',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'Old Service',
        'description' => 'Old description.',
        'price' => 2000,
        'duration_minutes' => 30,
        'is_active' => true,
    ]);

    $response = $this
        ->actingAs($provider)
        ->put(route('provider.services.update', $service), [
            'name' => 'Updated Service',
            'category_id' => $category->id,
            'description' => 'Updated description.',
            'price' => 4500,
            'duration_minutes' => 60,
            'is_active' => true,
        ]);

    $response->assertRedirect(route('provider.services.index'));

    $this->assertDatabaseHas('services', [
        'id' => $service->id,
        'provider_id' => $provider->id,
        'name' => 'Updated Service',
        'description' => 'Updated description.',
        'price' => 4500,
        'duration_minutes' => 60,
    ]);
});

test('provider can delete their own service', function () {
    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $category = Category::create([
        'name' => 'Photography',
        'slug' => 'photography',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'Photography Session',
        'description' => 'Professional photography session.',
        'price' => 5000,
        'duration_minutes' => 90,
        'is_active' => true,
    ]);

    $response = $this
        ->actingAs($provider)
        ->delete(route('provider.services.destroy', $service));

    $response->assertRedirect(route('provider.services.index'));

    $this->assertDatabaseMissing('services', [
        'id' => $service->id,
    ]);
});