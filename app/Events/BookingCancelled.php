<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingCancelled
{
    use Dispatchable, SerializesModels;

    public function __construct(public Booking $booking)
    {
        //
    }
}

//This event carries the cancelled Booking so the listener will know which booking was cancelled and which provider needs to be notified.