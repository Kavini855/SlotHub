<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingStatusUpdatedNotification extends Notification
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
        $status = $this->booking->status;

        return [
            'booking_id' => $this->booking->id,
            'service_id' => $this->booking->service_id,
            'service_name' => $this->booking->service->name,
            'booking_date' => $this->booking->booking_date->format('Y-m-d'),
            'booking_time' => $this->booking->booking_time,
            'status' => $status,
            'message' => $status === 'confirmed'
                ? 'Your booking has been confirmed.'
                : 'Your booking has been rejected.',
        ];
    }
}