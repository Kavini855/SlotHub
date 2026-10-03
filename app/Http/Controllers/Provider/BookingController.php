<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Events\BookingStatusUpdated;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Booking::query()
            ->with(['customer', 'service'])
            ->whereHas('service', function ($query) use ($request) {
                $query->where('provider_id', $request->user()->id);
            })
            ->latest()
            ->paginate(10);

        return view('provider.bookings.index', compact('bookings'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => ['required', 'in:confirmed,rejected'],
        ]);

        // Make sure this booking belongs to this provider
        abort_unless(
            $booking->service->provider_id === $request->user()->id,
            403
        );

        // Only pending bookings can be accepted or rejected
        abort_unless($booking->status === 'pending', 422);

        $booking->update([
            'status' => $request->status,
        ]);

        BookingStatusUpdated::dispatch($booking);

        return redirect()
            ->route('provider.bookings.index')
            ->with('success', 'Booking status updated successfully.');
    }
}
//This is important for security: a provider only retrieves bookings belonging to their own services.