<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingCancelledNotification extends Notification
{
    use Queueable;

    public function __construct(public Booking $booking)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'service_name' => $this->booking->service->name,
            'customer_name' => $this->booking->customer->name,
            'booking_date' => $this->booking->booking_date->format('Y-m-d'),
            'booking_time' => $this->booking->booking_time,
            'status' => $this->booking->status,
            'message' => $this->booking->customer->name
                . ' cancelled their booking for '
                . $this->booking->service->name . '.',
        ];
    }
}

//creating a database notification for the provider containing the booking, service, customer, date, time and cancelled status