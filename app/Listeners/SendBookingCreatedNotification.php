<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Notifications\NewBookingNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendBookingCreatedNotification implements ShouldQueue
{
    public function handle(BookingCreated $event): void
    {
        $booking = $event->booking->loadMissing([
            'service.provider',
            'customer'
        ]);

        $provider = $booking->service->provider;

        $provider->notify(
            new NewBookingNotification($booking)
        );
    }
}