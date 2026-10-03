<?php

namespace App\Listeners;

use App\Events\BookingCancelled;
use App\Notifications\BookingCancelledNotification;

class SendBookingCancelledNotification
{
    public function handle(BookingCancelled $event): void
    {
        $booking = $event->booking->load([
            'service.provider',
            'customer',
        ]);

        $booking->service->provider->notify(
            new BookingCancelledNotification($booking)
        );
    }
}