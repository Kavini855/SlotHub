<?php

use App\Models\Booking;
use App\Models\Category;
use App\Models\Service;
use App\Models\User;
use App\Notifications\NewBookingNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Notifications\BookingCancelledNotification;

uses(RefreshDatabase::class);

function createNotificationTestBooking(
    User $customer,
    User $provider
): Booking {
    $category = Category::create([
        'name' => 'Notification Security',
        'is_active' => true,
    ]);

    $service = Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => 'Notification Security Service',
        'description' => 'Service used for notification testing.',
        'duration_minutes' => 60,
        'price' => 3000,
        'is_active' => true,
    ]);

    return Booking::create([
        'customer_id' => $customer->id,
        'service_id' => $service->id,
        'booking_date' => now()->addDays(5)->format('Y-m-d'),
        'booking_time' => '10:00',
        'status' => 'pending',
    ]);
}

test('provider can mark their own notification as read', function () {

    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $booking = createNotificationTestBooking(
        $customer,
        $provider
    );

    $provider->notify(
        new NewBookingNotification($booking)
    );

    $notification = $provider
        ->notifications()
        ->first();

    expect($notification->read_at)->toBeNull();

    $response = $this
        ->actingAs($provider)
        ->patch(
            route(
                'notifications.read',
                $notification->id
            )
        );

    $response->assertRedirect(
        route('provider.bookings.index')
    );

    expect(
        $notification->fresh()->read_at
    )->not->toBeNull();
});

test('provider cannot mark another providers notification as read', function () {

    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $otherProvider = User::factory()->create([
        'role' => 'provider',
    ]);

    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $booking = createNotificationTestBooking(
        $customer,
        $provider
    );

    $provider->notify(
        new NewBookingNotification($booking)
    );

    $notification = $provider
        ->notifications()
        ->first();

    $response = $this
        ->actingAs($otherProvider)
        ->patch(
            route(
                'notifications.read',
                $notification->id
            )
        );

    $response->assertNotFound();

    expect(
        $notification->fresh()->read_at
    )->toBeNull();
});

test('provider receives notification when customer cancels booking', function () {

    $provider = User::factory()->create([
        'role' => 'provider',
    ]);

    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $booking = createNotificationTestBooking(
        $customer,
        $provider
    );

    $response = $this
        ->actingAs($customer)
        ->patch(
            route('customer.bookings.cancel', $booking)
        );

    $response->assertRedirect();

    expect(
        $booking->fresh()->status
    )->toBe('cancelled');

    $notification = $provider
        ->notifications()
        ->where(
            'type',
            BookingCancelledNotification::class
        )
        ->first();

    expect($notification)->not->toBeNull();

    expect(
        $notification->data['booking_id']
    )->toBe($booking->id);

    expect(
        $notification->data['status']
    )->toBe('cancelled');
});