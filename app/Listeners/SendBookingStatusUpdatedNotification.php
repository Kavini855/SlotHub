<?php

namespace App\Listeners;

use App\Events\BookingStatusUpdated;
use App\Notifications\BookingStatusUpdatedNotification;

class SendBookingStatusUpdatedNotification
{
    public function handle(BookingStatusUpdated $event): void
    {
        $booking = $event->booking->loadMissing([
            'service',
            'customer',
        ]);

        $booking->customer->notify(
            new BookingStatusUpdatedNotification($booking)
        );
    }
}