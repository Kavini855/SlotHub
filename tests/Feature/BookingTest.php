<?php

use App\Models\User;
use App\Models\Service;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Booking;
use App\Models\Availability;
use App\Notifications\NewBookingNotification;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BookingStatusUpdatedNotification;

uses(RefreshDatabase::class);

//Test that a customer can create a booking

test('customer can create a booking', function () {

    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

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
        'name' => 'Web Development Consultation',
        'description' => 'Web development consultation service.',
        'duration_minutes' => 60,
        'price' => 4500,
        'is_active' => true,
    ]);

    $bookingDate = now()->addDays(2);

    Availability::create([
        'provider_id' => $provider->id,
        'day_of_week' => $bookingDate->dayOfWeek,
        'start_time' => '09:00:00',
        'end_time' => '17:00:00',
        'is_active' => true,
    ]);

    $response = $this
        ->actingAs($customer)
        ->post(route('customer.bookings.store', $service), [
            'booking_date' => $bookingDate->format('Y-m-d'),
            'booking_time' => '12:50',
            'notes' => 'Test booking',
        ]);

    $response->assertRedirect(route('customer.bookings.index'));

    $this->assertDatabaseHas('bookings', [
        'customer_id' => $customer->id,
        'service_id' => $service->id,
        'status' => 'pending',
        'notes' => 'Test booking',
    ]);
});

//Test 2: invalid booking data is rejected. This proves your Laravel back-end validation works.

test('customer cannot create a booking with invalid data', function () {

    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $category = Category::create([
        'name' => 'Fitness',
        'slug' => 'fitness',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'Personal Training',
        'description' => 'Personal training session.',
        'duration_minutes' => 60,
        'price' => 3000,
        'is_active' => true,
    ]);

    $response = $this
        ->actingAs($customer)
        ->post(route('customer.bookings.store', $service), [
            'booking_date' => now()->subDay()->format('Y-m-d'),
            'booking_time' => 'invalid-time',
            'notes' => str_repeat('A', 1001),
        ]);

    $response->assertSessionHasErrors([
        'booking_date',
        'booking_time',
        'notes',
    ]);

    $this->assertDatabaseCount('bookings', 0);
});

//Test 3: prevent double booking. This verifies the conflict protection we added earlier
test('customer cannot double book the same service time slot', function () {

    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $category = Category::create([
        'name' => 'Education',
        'slug' => 'education',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'Programming Tutoring',
        'description' => 'Programming tutoring session.',
        'duration_minutes' => 60,
        'price' => 2500,
        'is_active' => true,
    ]);

    $bookingDate = now()->addDays(3);

    Availability::create([
        'provider_id' => $provider->id,
        'day_of_week' => $bookingDate->dayOfWeek,
        'start_time' => '09:00:00',
        'end_time' => '17:00:00',
        'is_active' => true,
    ]);

    $bookingDate = $bookingDate->format('Y-m-d');

    // First booking
    $this->actingAs($customer)
        ->post(route('customer.bookings.store', $service), [
            'booking_date' => $bookingDate,
            'booking_time' => '10:00',
        ]);

    // Try booking the exact same slot again
    $response = $this->actingAs($customer)
        ->post(route('customer.bookings.store', $service), [
            'booking_date' => $bookingDate,
            'booking_time' => '10:00',
        ]);

    $response->assertSessionHasErrors('booking_time');

    $this->assertDatabaseCount('bookings', 1);
});

//authorization/security rule: one customer must not be able to cancel another customer's booking.

test('customer cannot cancel another customers booking', function () {

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
        'name' => 'Photography',
        'slug' => 'photography',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'Photography Session',
        'description' => 'Professional photography session.',
        'duration_minutes' => 60,
        'price' => 5000,
        'is_active' => true,
    ]);

    $booking = Booking::create([
        'customer_id' => $otherCustomer->id,
        'service_id' => $service->id,
        'booking_date' => now()->addDays(5)->format('Y-m-d'),
        'booking_time' => '14:00',
        'status' => 'pending',
    ]);

    $response = $this
        ->actingAs($customer)
        ->patch(route('customer.bookings.cancel', $booking));

    $response->assertForbidden();

    $this->assertDatabaseHas('bookings', [
        'id' => $booking->id,
        'customer_id' => $otherCustomer->id,
        'status' => 'pending',
    ]);
});

//security test: a provider cannot confirm/reject a booking belonging to another provider's service.

test('provider cannot update another providers booking', function () {

    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $otherProvider = User::factory()->create([
        'role' => 'provider',
    ]);

    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $category = Category::create([
        'name' => 'Beauty',
        'slug' => 'beauty',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $otherProvider->id,
        'category_id' => $category->id,
        'name' => 'Hair Styling',
        'description' => 'Professional hair styling service.',
        'duration_minutes' => 60,
        'price' => 3500,
        'is_active' => true,
    ]);

    $booking = Booking::create([
        'customer_id' => $customer->id,
        'service_id' => $service->id,
        'booking_date' => now()->addDays(5)->format('Y-m-d'),
        'booking_time' => '11:00',
        'status' => 'pending',
    ]);

    $response = $this
        ->actingAs($provider)
        ->patch(route('provider.bookings.update-status', $booking), [
            'status' => 'confirmed',
        ]);

    $response->assertForbidden();

    $this->assertDatabaseHas('bookings', [
        'id' => $booking->id,
        'status' => 'pending',
    ]);
});

//test: a provider can confirm a booking for their own service.

test('provider can confirm their own service booking', function () {

    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $category = Category::create([
        'name' => 'Consulting',
        'slug' => 'consulting',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'Career Consultation',
        'description' => 'Professional career consultation.',
        'duration_minutes' => 60,
        'price' => 4000,
        'is_active' => true,
    ]);

    $booking = Booking::create([
        'customer_id' => $customer->id,
        'service_id' => $service->id,
        'booking_date' => now()->addDays(4)->format('Y-m-d'),
        'booking_time' => '13:00',
        'status' => 'pending',
    ]);

    $response = $this
        ->actingAs($provider)
        ->patch(route('provider.bookings.update-status', $booking), [
            'status' => 'confirmed',
        ]);

    $response->assertRedirect(route('provider.bookings.index'));

    $this->assertDatabaseHas('bookings', [
        'id' => $booking->id,
        'status' => 'confirmed',
    ]);
});

// Test 7: customer cannot book on a day when the provider is unavailable.

test('customer cannot book on provider unavailable day', function () {

    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $category = Category::create([
        'name' => 'Beauty',
        'slug' => 'beauty-availability-test',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'Hair Styling',
        'description' => 'Professional hair styling.',
        'duration_minutes' => 45,
        'price' => 2500,
        'is_active' => true,
    ]);

    // Pick a future date.
    $bookingDate = now()->addDays(7);

    // Create availability for that day, but mark it unavailable.
    Availability::create([
        'provider_id' => $provider->id,
        'day_of_week' => $bookingDate->dayOfWeek,
        'start_time' => '09:00:00',
        'end_time' => '17:00:00',
        'is_active' => false,
    ]);

    $response = $this
        ->actingAs($customer)
        ->post(route('customer.bookings.store', $service), [
            'booking_date' => $bookingDate->format('Y-m-d'),
            'booking_time' => '10:00',
        ]);

    $response->assertSessionHasErrors('booking_date');

    $this->assertDatabaseCount('bookings', 0);
});


// Test 8: customer cannot book before the provider's available hours.

test('customer cannot book outside provider available hours', function () {

    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $category = Category::create([
        'name' => 'Consulting',
        'slug' => 'consulting-availability-test',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'Business Consultation',
        'description' => 'Business consultation service.',
        'duration_minutes' => 60,
        'price' => 4500,
        'is_active' => true,
    ]);

    $bookingDate = now()->addDays(8);

    Availability::create([
        'provider_id' => $provider->id,
        'day_of_week' => $bookingDate->dayOfWeek,
        'start_time' => '09:00:00',
        'end_time' => '17:00:00',
        'is_active' => true,
    ]);

    // 08:00 is before the provider starts at 09:00.
    $response = $this
        ->actingAs($customer)
        ->post(route('customer.bookings.store', $service), [
            'booking_date' => $bookingDate->format('Y-m-d'),
            'booking_time' => '08:00',
        ]);

    $response->assertSessionHasErrors('booking_time');

    $this->assertDatabaseCount('bookings', 0);
});


// Test 9: service cannot finish after the provider's available hours.

test('customer cannot book when service ends after provider availability', function () {

    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $category = Category::create([
        'name' => 'Wellness',
        'slug' => 'wellness-duration-test',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'Hair Styling Session',
        'description' => 'Professional hair styling.',
        'duration_minutes' => 45,
        'price' => 2500,
        'is_active' => true,
    ]);

    $bookingDate = now()->addDays(9);

    Availability::create([
        'provider_id' => $provider->id,
        'day_of_week' => $bookingDate->dayOfWeek,
        'start_time' => '09:00:00',
        'end_time' => '17:00:00',
        'is_active' => true,
    ]);

    // 16:30 + 45 minutes = 17:15.
    // This exceeds the provider's 17:00 end time.
    $response = $this
        ->actingAs($customer)
        ->post(route('customer.bookings.store', $service), [
            'booking_date' => $bookingDate->format('Y-m-d'),
            'booking_time' => '16:30',
        ]);

    $response->assertSessionHasErrors('booking_time');

    $this->assertDatabaseCount('bookings', 0);
});


// Test 10: booking is allowed when the service ends exactly at closing time.

test('customer can book when service ends exactly at provider availability end time', function () {

    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $category = Category::create([
        'name' => 'Personal Care',
        'slug' => 'personal-care-boundary-test',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'Hair Styling Session',
        'description' => 'Professional hair styling.',
        'duration_minutes' => 45,
        'price' => 2500,
        'is_active' => true,
    ]);

    $bookingDate = now()->addDays(10);

    Availability::create([
        'provider_id' => $provider->id,
        'day_of_week' => $bookingDate->dayOfWeek,
        'start_time' => '09:00:00',
        'end_time' => '17:00:00',
        'is_active' => true,
    ]);

    // 16:15 + 45 minutes = exactly 17:00.
    $response = $this
        ->actingAs($customer)
        ->post(route('customer.bookings.store', $service), [
            'booking_date' => $bookingDate->format('Y-m-d'),
            'booking_time' => '16:15',
            'notes' => 'Boundary availability test',
        ]);

    $response->assertRedirect(
        route('customer.bookings.index')
    );

    $this->assertDatabaseHas('bookings', [
        'customer_id' => $customer->id,
        'service_id' => $service->id,
        'booking_time' => '16:15',
        'status' => 'pending',
    ]);
});

test('customer can view qr code for their confirmed booking', function () {
    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $category = Category::create([
        'name' => 'QR Test Category',
        'description' => 'Test category',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'QR Test Service',
        'description' => 'Test service',
        'duration_minutes' => 60,
        'price' => 2000,
        'is_active' => true,
    ]);

    $booking = Booking::create([
        'customer_id' => $customer->id,
        'service_id' => $service->id,
        'booking_date' => now()->addDay()->format('Y-m-d'),
        'booking_time' => '10:00:00',
        'status' => 'confirmed',
    ]);

    $response = $this
        ->actingAs($customer)
        ->get(route('customer.bookings.qr', $booking));

    $response->assertOk();
    $response->assertHeader('Content-Type', 'image/svg+xml');
});

test('customer cannot view qr code for another customers booking', function () {
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
        'name' => 'Private QR Category',
        'description' => 'Test category',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'Private QR Service',
        'description' => 'Test service',
        'duration_minutes' => 60,
        'price' => 2000,
        'is_active' => true,
    ]);

    $booking = Booking::create([
        'customer_id' => $otherCustomer->id,
        'service_id' => $service->id,
        'booking_date' => now()->addDay()->format('Y-m-d'),
        'booking_time' => '10:00:00',
        'status' => 'confirmed',
    ]);

    $response = $this
        ->actingAs($customer)
        ->get(route('customer.bookings.qr', $booking));

    $response->assertForbidden();
});

test('customer cannot view qr code for a pending booking', function () {
    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $category = Category::create([
        'name' => 'Pending QR Category',
        'description' => 'Test category',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'Pending QR Service',
        'description' => 'Test service',
        'duration_minutes' => 60,
        'price' => 2000,
        'is_active' => true,
    ]);

    $booking = Booking::create([
        'customer_id' => $customer->id,
        'service_id' => $service->id,
        'booking_date' => now()->addDay()->format('Y-m-d'),
        'booking_time' => '10:00:00',
        'status' => 'pending',
    ]);

    $response = $this
        ->actingAs($customer)
        ->get(route('customer.bookings.qr', $booking));

    $response->assertForbidden();
});

test('provider cannot change status of non pending booking', function () {

    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $category = Category::create([
        'name' => 'Security Test',
        'slug' => 'security-test',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'Security Test Service',
        'description' => 'Service used for booking security testing.',
        'duration_minutes' => 60,
        'price' => 3000,
        'is_active' => true,
    ]);

    $booking = Booking::create([
        'customer_id' => $customer->id,
        'service_id' => $service->id,
        'booking_date' => now()->addDays(5)->format('Y-m-d'),
        'booking_time' => '10:00',
        'status' => 'confirmed',
    ]);

    $response = $this
        ->actingAs($provider)
        ->patch(route('provider.bookings.update-status', $booking), [
            'status' => 'rejected',
        ]);

    $response->assertStatus(422);

    $this->assertDatabaseHas('bookings', [
        'id' => $booking->id,
        'status' => 'confirmed',
    ]);
});

test('customer cannot view available slots for inactive service', function () {
    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $category = Category::create([
        'name' => 'Inactive Service Test',
        'slug' => 'inactive-service-test',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'Inactive Test Service',
        'description' => 'Service used to test inactive service security.',
        'duration_minutes' => 60,
        'price' => 2500,
        'is_active' => false,
    ]);

    $response = $this->actingAs($customer)
        ->get(route('customer.services.available-slots', [
            'service' => $service,
            'date' => now()->addDays(5)->format('Y-m-d'),
        ]));

    $response->assertNotFound();
});

test('provider receives notification when customer creates booking', function () {

    Notification::fake();

    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $category = Category::create([
        'name' => 'Notification Test',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'Notification Test Service',
        'description' => 'Service used to test booking notifications.',
        'duration_minutes' => 60,
        'price' => 3000,
        'is_active' => true,
    ]);

    $bookingDate = now()->addDays(6);

    Availability::create([
        'provider_id' => $provider->id,
        'day_of_week' => $bookingDate->dayOfWeek,
        'start_time' => '09:00:00',
        'end_time' => '17:00:00',
        'is_active' => true,
    ]);

    $response = $this
        ->actingAs($customer)
        ->post(route('customer.bookings.store', $service), [
            'booking_date' => $bookingDate->format('Y-m-d'),
            'booking_time' => '11:00',
            'notes' => 'Notification test booking',
        ]);

    $response->assertRedirect(
        route('customer.bookings.index')
    );

    Notification::assertSentTo(
        $provider,
        NewBookingNotification::class,
        function ($notification) use ($service, $customer) {
            return $notification->booking->service_id === $service->id
                && $notification->booking->customer_id === $customer->id;
        }
    );

    Notification::assertSentToTimes(
        $provider,
        NewBookingNotification::class,
        1
    );
});

test('provider does not receive notification when booking validation fails', function () {

    Notification::fake();

    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $category = Category::create([
        'name' => 'Failed Notification Test',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'Failed Notification Service',
        'description' => 'Service used to test failed booking notifications.',
        'duration_minutes' => 60,
        'price' => 3000,
        'is_active' => true,
    ]);

    $response = $this
        ->actingAs($customer)
        ->post(route('customer.bookings.store', $service), [
            'booking_date' => now()->subDay()->format('Y-m-d'),
            'booking_time' => 'invalid-time',
        ]);

    $response->assertSessionHasErrors([
        'booking_date',
        'booking_time',
    ]);

    Notification::assertNothingSent();

    $this->assertDatabaseCount('bookings', 0);
});

test('customer receives notification when provider confirms booking', function () {

    Notification::fake();

    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $category = Category::create([
        'name' => 'Confirmation Test',
        'slug' => 'confirmation-test',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'Confirmation Service',
        'description' => 'Service used for confirmation notification testing.',
        'duration_minutes' => 60,
        'price' => 4000,
        'is_active' => true,
    ]);

    $booking = Booking::create([
        'customer_id' => $customer->id,
        'service_id' => $service->id,
        'booking_date' => now()->addDays(4)->format('Y-m-d'),
        'booking_time' => '13:00',
        'status' => 'pending',
    ]);

    $response = $this
        ->actingAs($provider)
        ->patch(route('provider.bookings.update-status', $booking), [
            'status' => 'confirmed',
        ]);

    $response->assertRedirect(
        route('provider.bookings.index')
    );

    $this->assertDatabaseHas('bookings', [
        'id' => $booking->id,
        'status' => 'confirmed',
    ]);

    Notification::assertSentTo(
        $customer,
        BookingStatusUpdatedNotification::class,
        function ($notification) use ($booking) {
            return $notification->booking->id === $booking->id
                && $notification->booking->status === 'confirmed';
        }
    );
});

test('customer receives notification when provider rejects booking', function () {
    Notification::fake();

    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $category = Category::create([
        'name' => 'Rejection Test',
        'slug' => 'rejection-test',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'Rejection Service',
        'description' => 'Service used for rejection notification testing.',
        'duration_minutes' => 60,
        'price' => 4000,
        'is_active' => true,
    ]);

    $booking = Booking::create([
        'customer_id' => $customer->id,
        'service_id' => $service->id,
        'booking_date' => now()->addDays(5)->format('Y-m-d'),
        'booking_time' => '14:00',
        'status' => 'pending',
    ]);

    $response = $this
        ->actingAs($provider)
        ->patch(route('provider.bookings.update-status', $booking), [
            'status' => 'rejected',
        ]);

    $response->assertRedirect(
        route('provider.bookings.index')
    );

    $this->assertDatabaseHas('bookings', [
        'id' => $booking->id,
        'status' => 'rejected',
    ]);

    Notification::assertSentTo(
        $customer,
        BookingStatusUpdatedNotification::class,
        function ($notification) use ($booking) {
            return $notification->booking->id === $booking->id
                && $notification->booking->status === 'rejected';
        }
    );
});