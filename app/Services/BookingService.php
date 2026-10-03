<?php

namespace App\Services;

use App\Events\BookingCreated;
use App\Models\Availability;
use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use App\Events\BookingCancelled;

class BookingService
{
    /**
     * Create a booking after checking provider availability
     * and preventing overlapping bookings.
     */
    public function create(
        User $customer,
        Service $service,
        array $data
    ): Booking {
        if (!$service->is_active) {
            abort(404);
        }

        $bookingDate = Carbon::parse($data['booking_date']);
        $dayOfWeek = $bookingDate->dayOfWeek;

        // Find the provider's working hours for this day.
        $availability = Availability::where(
            'provider_id',
            $service->provider_id
        )
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->first();

        if (!$availability) {
            throw ValidationException::withMessages([
                'booking_date' =>
                    'The provider is not available on the selected day.',
            ]);
        }

        $bookingStart = Carbon::createFromFormat(
            'H:i',
            $data['booking_time']
        );

        $bookingEnd = $bookingStart->copy()
            ->addMinutes($service->duration_minutes);

        $availableStart = Carbon::createFromFormat(
            'H:i:s',
            $availability->start_time
        );

        $availableEnd = Carbon::createFromFormat(
            'H:i:s',
            $availability->end_time
        );

        // Booking must fit fully inside provider working hours.
        if (
            $bookingStart->lt($availableStart) ||
            $bookingEnd->gt($availableEnd)
        ) {
            throw ValidationException::withMessages([
                'booking_time' =>
                    'The selected time is outside the provider\'s available hours.',
            ]);
        }

        /*
         * Check all active bookings for this service/date
         * and prevent overlapping appointments.
         */
        $existingBookings = Booking::where(
            'service_id',
            $service->id
        )
            ->whereDate('booking_date', $data['booking_date'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        $hasConflict = $existingBookings->contains(
            function (Booking $booking) use ($bookingStart, $bookingEnd, $service) {
                $existingStart = Carbon::parse(
                    $booking->booking_time
                );

                $existingEnd = $existingStart->copy()
                    ->addMinutes($service->duration_minutes);

                return $bookingStart->lt($existingEnd)
                    && $bookingEnd->gt($existingStart);
            }
        );

        if ($hasConflict) {
            throw ValidationException::withMessages([
                'booking_time' =>
                    'This time slot conflicts with an existing booking. Please choose another time.',
            ]);
        }

        $booking = Booking::create([
            'customer_id' => $customer->id,
            'service_id' => $service->id,
            'booking_date' => $data['booking_date'],
            'booking_time' => $data['booking_time'],
            'status' => 'pending',
            'notes' => $data['notes'] ?? null,
        ]);

        BookingCreated::dispatch($booking);

        return $booking;
    }
    public function cancel(
        User $customer,
        Booking $booking
    ): Booking {
        abort_unless(
            $booking->customer_id === $customer->id,
            403,
            'You are not authorized to cancel this booking.'
        );

        if (in_array($booking->status, ['cancelled', 'rejected'])) {
            throw ValidationException::withMessages([
                'booking' => 'This booking cannot be cancelled.',
            ]);
        }

        $booking->update([
            'status' => 'cancelled',
        ]);

        BookingCancelled::dispatch($booking);

        return $booking;
    }
}

