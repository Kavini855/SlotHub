<?php

use App\Models\User;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Service;
use Laravel\Sanctum\Sanctum;

/*
|--------------------------------------------------------------------------
| My Bookings API Tests
|--------------------------------------------------------------------------
|
| These tests verify authentication, role-based authorization,
| Sanctum token abilities, pagination and booking data.
|
*/

test('unauthenticated user cannot access my bookings api', function () {
    $response = $this->getJson('/api/my-bookings');

    $response->assertUnauthorized();
});


test('authenticated customer can access my bookings api', function () {
    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    Sanctum::actingAs($customer, ['bookings:read']);

    $response = $this->getJson('/api/my-bookings');

    $response
        ->assertOk()
        ->assertJsonStructure([
            'current_page',
            'data',
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'links',
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total',
        ]);
});


test('provider cannot access customer bookings api', function () {
    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    Sanctum::actingAs($provider, ['bookings:read']);

    $response = $this->getJson('/api/my-bookings');

    $response->assertForbidden();
});


test('customer without bookings read ability cannot access bookings api', function () {
    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    Sanctum::actingAs($customer, ['profile:read']);

    $response = $this->getJson('/api/my-bookings');

    $response->assertForbidden();
});


test('customer api returns their booking data', function () {
    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $category = Category::create([
        'name' => 'Beauty',
        'description' => 'Beauty services',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'Hair Styling',
        'description' => 'Professional hair styling',
        'duration_minutes' => 45,
        'price' => 2500,
        'is_active' => true,
    ]);

    Booking::create([
        'customer_id' => $customer->id,
        'service_id' => $service->id,
        'booking_date' => '2026-10-10',
        'booking_time' => '10:00:00',
        'status' => 'confirmed',
        'notes' => null,
    ]);

    Sanctum::actingAs($customer, ['bookings:read']);

    $response = $this->getJson('/api/my-bookings');

    $response
        ->assertOk()
        ->assertJsonFragment([
            'service' => 'Hair Styling',
            'date' => '2026-10-10',
            'time' => '10:00:00',
            'status' => 'confirmed',
        ]);
});


test('customer bookings api is paginated', function () {
    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    Sanctum::actingAs($customer, ['bookings:read']);

    $response = $this->getJson('/api/my-bookings');

    $response
        ->assertOk()
        ->assertJsonPath('per_page', 10)
        ->assertJsonPath('current_page', 1);
});

test('my bookings api is rate limited', function () {
    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    Sanctum::actingAs($customer, ['bookings:read']);

    for ($i = 0; $i < 60; $i++) {
        $this->getJson('/api/my-bookings')
            ->assertOk();
    }

    $response = $this->getJson('/api/my-bookings');

    $response->assertStatus(429);
});

test('customer can view their own booking through api', function () {
    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $category = Category::create([
        'name' => 'Consulting',
        'description' => 'Consulting services',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'Career Consultation',
        'description' => 'Career guidance',
        'duration_minutes' => 45,
        'price' => 3000,
        'is_active' => true,
    ]);

    $booking = Booking::create([
        'customer_id' => $customer->id,
        'service_id' => $service->id,
        'booking_date' => '2026-10-15',
        'booking_time' => '10:00:00',
        'status' => 'confirmed',
        'notes' => 'API test booking',
    ]);

    Sanctum::actingAs($customer, ['bookings:read']);

    $response = $this->getJson("/api/bookings/{$booking->id}");

    $response
        ->assertOk()
        ->assertJsonPath('id', $booking->id)
        ->assertJsonPath('service.name', 'Career Consultation')
        ->assertJsonPath('date', '2026-10-15')
        ->assertJsonPath('status', 'confirmed');
});


test('customer cannot view another customers booking through api', function () {
    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $otherCustomer = User::factory()->create([
        'role' => 'customer',
    ]);

    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $category = Category::create([
        'name' => 'Fitness',
        'description' => 'Fitness services',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'Personal Training',
        'description' => 'Personal training session',
        'duration_minutes' => 60,
        'price' => 3500,
        'is_active' => true,
    ]);

    $booking = Booking::create([
        'customer_id' => $otherCustomer->id,
        'service_id' => $service->id,
        'booking_date' => '2026-10-16',
        'booking_time' => '11:00:00',
        'status' => 'pending',
        'notes' => null,
    ]);

    Sanctum::actingAs($customer, ['bookings:read']);

    $this->getJson("/api/bookings/{$booking->id}")
        ->assertForbidden();
});


test('unauthenticated user cannot view individual booking through api', function () {
    $this->getJson('/api/bookings/99999')
        ->assertUnauthorized();
});

test('customer can create booking through api', function () {
    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $category = Category::create([
        'name' => 'API Booking',
        'description' => 'API booking category',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'API Consultation',
        'description' => 'API test service',
        'duration_minutes' => 60,
        'price' => 3000,
        'is_active' => true,
    ]);

    \App\Models\Availability::create([
        'provider_id' => $provider->id,
        'day_of_week' => 1,
        'start_time' => '09:00:00',
        'end_time' => '17:00:00',
        'is_active' => true,
    ]);

    Sanctum::actingAs($customer, ['bookings:create']);

    $response = $this->postJson('/api/bookings', [
        'service_id' => $service->id,
        'booking_date' => '2026-10-05',
        'booking_time' => '10:00',
        'notes' => 'Created using API',
    ]);

    $response
        ->assertCreated()
        ->assertJsonPath('message', 'Booking created successfully.')
        ->assertJsonPath('booking.service.name', 'API Consultation')
        ->assertJsonPath('booking.date', '2026-10-05')
        ->assertJsonPath('booking.status', 'pending');

    $this->assertDatabaseHas('bookings', [
        'customer_id' => $customer->id,
        'service_id' => $service->id,
        'booking_date' => '2026-10-05 00:00:00',
        'status' => 'pending',
    ]);
});


test('customer without create ability cannot create booking through api', function () {
    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    Sanctum::actingAs($customer, ['bookings:read']);

    $this->postJson('/api/bookings', [])
        ->assertForbidden();
});


test('provider cannot create customer booking through api', function () {
    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    Sanctum::actingAs($provider, ['bookings:create']);

    $this->postJson('/api/bookings', [])
        ->assertForbidden();
});


test('unauthenticated user cannot create booking through api', function () {
    $this->postJson('/api/bookings', [])
        ->assertUnauthorized();
});


test('booking api validates required fields', function () {
    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    Sanctum::actingAs($customer, ['bookings:create']);

    $this->postJson('/api/bookings', [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'service_id',
            'booking_date',
            'booking_time',
        ]);
});

test('customer can cancel their own booking through api', function () {
    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $category = Category::create([
        'name' => 'Cancellation',
        'description' => 'Cancellation test',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'Cancellation Service',
        'description' => 'API cancellation test',
        'duration_minutes' => 30,
        'price' => 2000,
        'is_active' => true,
    ]);

    $booking = Booking::create([
        'customer_id' => $customer->id,
        'service_id' => $service->id,
        'booking_date' => '2026-10-20',
        'booking_time' => '10:00',
        'status' => 'pending',
    ]);

    Sanctum::actingAs($customer, ['bookings:cancel']);

    $response = $this->deleteJson("/api/bookings/{$booking->id}");

    $response
        ->assertOk()
        ->assertJsonPath('message', 'Booking cancelled successfully.')
        ->assertJsonPath('booking.id', $booking->id)
        ->assertJsonPath('booking.status', 'cancelled');

    $this->assertDatabaseHas('bookings', [
        'id' => $booking->id,
        'status' => 'cancelled',
    ]);
});


test('customer cannot cancel another customers booking through api', function () {
    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $otherCustomer = User::factory()->create([
        'role' => 'customer',
    ]);

    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $category = Category::create([
        'name' => 'Ownership Test',
        'description' => 'Ownership test',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'Ownership Service',
        'description' => 'Ownership API test',
        'duration_minutes' => 30,
        'price' => 2000,
        'is_active' => true,
    ]);

    $booking = Booking::create([
        'customer_id' => $otherCustomer->id,
        'service_id' => $service->id,
        'booking_date' => '2026-10-21',
        'booking_time' => '10:00',
        'status' => 'pending',
    ]);

    Sanctum::actingAs($customer, ['bookings:cancel']);

    $this->deleteJson("/api/bookings/{$booking->id}")
        ->assertForbidden();

    $this->assertDatabaseHas('bookings', [
        'id' => $booking->id,
        'status' => 'pending',
    ]);
});


test('customer without cancel ability cannot cancel booking through api', function () {
    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $category = Category::create([
        'name' => 'Ability Test',
        'description' => 'Token ability test',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'Ability Service',
        'description' => 'Ability test service',
        'duration_minutes' => 30,
        'price' => 2000,
        'is_active' => true,
    ]);

    $booking = Booking::create([
        'customer_id' => $customer->id,
        'service_id' => $service->id,
        'booking_date' => '2026-10-22',
        'booking_time' => '10:00',
        'status' => 'pending',
    ]);

    Sanctum::actingAs($customer, ['bookings:read']);

    $this->deleteJson("/api/bookings/{$booking->id}")
        ->assertForbidden();

    // Make sure the failed API request did not change the booking.
    $this->assertDatabaseHas('bookings', [
        'id' => $booking->id,
        'status' => 'pending',
    ]);
});


test('unauthenticated user cannot cancel booking through api', function () {
    $this->deleteJson('/api/bookings/99999')
        ->assertUnauthorized();
});

test('customer can update their own booking notes through api', function () {
    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $category = Category::create([
        'name' => 'Update Test',
        'description' => 'API update testing',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'Update Service',
        'description' => 'Service for API update testing',
        'duration_minutes' => 60,
        'price' => 2500,
        'is_active' => true,
    ]);

    $booking = Booking::create([
        'customer_id' => $customer->id,
        'service_id' => $service->id,
        'booking_date' => '2026-10-25',
        'booking_time' => '10:00',
        'status' => 'pending',
        'notes' => 'Original note',
    ]);

    Sanctum::actingAs($customer, ['update']);

    $response = $this->putJson(
        "/api/bookings/{$booking->id}",
        [
            'notes' => 'Please call me before the appointment.',
        ]
    );

    $response
        ->assertOk()
        ->assertJsonPath('message', 'Booking updated successfully.')
        ->assertJsonPath('booking.id', $booking->id)
        ->assertJsonPath(
            'booking.notes',
            'Please call me before the appointment.'
        );

    $this->assertDatabaseHas('bookings', [
        'id' => $booking->id,
        'notes' => 'Please call me before the appointment.',
    ]);
});

test('customer cannot update another customers booking through api', function () {
    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $otherCustomer = User::factory()->create([
        'role' => 'customer',
    ]);

    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $category = Category::create([
        'name' => 'Update Ownership',
        'description' => 'Update ownership test',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'Ownership Update Service',
        'description' => 'API ownership update test',
        'duration_minutes' => 30,
        'price' => 2000,
        'is_active' => true,
    ]);

    $booking = Booking::create([
        'customer_id' => $otherCustomer->id,
        'service_id' => $service->id,
        'booking_date' => '2026-10-26',
        'booking_time' => '10:00',
        'status' => 'pending',
        'notes' => 'Original note',
    ]);

    Sanctum::actingAs($customer, ['update']);

    $this->putJson(
        "/api/bookings/{$booking->id}",
        ['notes' => 'Trying to change another booking']
    )->assertForbidden();

    $this->assertDatabaseHas('bookings', [
        'id' => $booking->id,
        'notes' => 'Original note',
    ]);
});
test('customer without update ability cannot update booking through api', function () {
    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $category = Category::create([
        'name' => 'Update Ability',
        'description' => 'Update ability test',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'Update Ability Service',
        'description' => 'API update ability test',
        'duration_minutes' => 30,
        'price' => 2000,
        'is_active' => true,
    ]);

    $booking = Booking::create([
        'customer_id' => $customer->id,
        'service_id' => $service->id,
        'booking_date' => '2026-10-27',
        'booking_time' => '10:00',
        'status' => 'pending',
        'notes' => 'Original note',
    ]);

    Sanctum::actingAs($customer, ['read']);

    $this->putJson(
        "/api/bookings/{$booking->id}",
        ['notes' => 'Unauthorized update']
    )->assertForbidden();

    $this->assertDatabaseHas('bookings', [
        'id' => $booking->id,
        'notes' => 'Original note',
    ]);
});

test('unauthenticated user cannot update booking through api', function () {
    $this->putJson('/api/bookings/99999', [
        'notes' => 'Unauthorized update',
    ])->assertUnauthorized();
});

test('customer cannot update a cancelled booking through api', function () {
    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $category = Category::create([
        'name' => 'Cancelled Update',
        'description' => 'Cancelled booking update test',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'Cancelled Update Service',
        'description' => 'API cancelled update test',
        'duration_minutes' => 30,
        'price' => 2000,
        'is_active' => true,
    ]);

    $booking = Booking::create([
        'customer_id' => $customer->id,
        'service_id' => $service->id,
        'booking_date' => '2026-10-28',
        'booking_time' => '10:00',
        'status' => 'cancelled',
        'notes' => 'Original note',
    ]);

    Sanctum::actingAs($customer, ['update']);

    $this->putJson(
        "/api/bookings/{$booking->id}",
        ['notes' => 'Trying to update cancelled booking']
    )->assertUnprocessable();

    $this->assertDatabaseHas('bookings', [
        'id' => $booking->id,
        'status' => 'cancelled',
        'notes' => 'Original note',
    ]);
});