<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewBookingNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Booking $booking
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'service_id' => $this->booking->service_id,
            'service_name' => $this->booking->service->name,
            'customer_name' => $this->booking->customer->name,
            'booking_date' => $this->booking->booking_date->format('Y-m-d'),
            'booking_time' => $this->booking->booking_time,
            'status' => $this->booking->status,
            'message' => 'You have received a new booking request.',
        ];
    }
}