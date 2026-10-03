<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\Availability;
use Carbon\Carbon;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use App\Services\BookingService;
use App\Services\HolidayService;

class BookingController extends Controller
{
    /**
     * Display the customer's bookings.
     */
    public function index(Request $request)
    {
        $bookings = Booking::with(['service.provider', 'service.category'])
            ->where('customer_id', $request->user()->id)
            ->latest('booking_date')
            ->latest('booking_time')
            ->paginate(6);

        return view('customer.bookings.index', compact('bookings'));
    }

    /**
     * Show the booking form.
     */
    public function create(Service $service)
    {
        abort_unless($service->is_active, 404);

        $service->load(['provider', 'category']);

        return view('customer.bookings.create', compact('service'));
    }

    /**
     * Store a new booking.
     */
    public function store(
        Request $request,
        Service $service,
        BookingService $bookingService
    ) {
        $validated = $request->validate([
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'booking_time' => ['required', 'date_format:H:i'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $bookingService->create(
            $request->user(),
            $service,
            $validated
        );

        return redirect()
            ->route('customer.bookings.index')
            ->with('success', 'Booking created successfully.');
    }

    public function checkHoliday(Request $request, HolidayService $holidayService)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
        ]);

        $holiday = $holidayService->checkDate($validated['date']);

        if ($holiday === null) {
            return response()->json([
                'available' => false,
                'message' => 'Holiday information is temporarily unavailable.',
            ], 503);
        }

        return response()->json([
            'available' => true,
            'holiday' => $holiday,
        ]);
    }

    public function availableSlots(Request $request, Service $service)
    {
        // Do not expose booking slots for inactive services
        abort_unless($service->is_active, 404);

        $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $bookingDate = Carbon::parse($request->date);
        $dayOfWeek = $bookingDate->dayOfWeek;

        // Find the provider's availability for the selected day
        $availability = Availability::where('provider_id', $service->provider_id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->first();

        // Provider is unavailable on this day
        if (!$availability) {
            return response()->json([
                'slots' => [],
                'message' => 'The provider is not available on this day.',
            ]);
        }

        $availableStart = Carbon::createFromFormat(
            'H:i:s',
            $availability->start_time
        );

        $availableEnd = Carbon::createFromFormat(
            'H:i:s',
            $availability->end_time
        );

        $slots = [];

        // Generate slots every 30 minutes
        $currentTime = $availableStart->copy();

        while (
            $currentTime->copy()
                ->addMinutes($service->duration_minutes)
                ->lte($availableEnd)
        ) {
            $slotStart = $currentTime->copy();

            $slotEnd = $slotStart->copy()
                ->addMinutes($service->duration_minutes);

            /*
             * Check whether this slot overlaps an existing
             * pending or confirmed booking.
             */
            $existingBookings = Booking::where(
                'service_id',
                $service->id
            )
                ->whereDate('booking_date', $request->date)
                ->whereIn('status', ['pending', 'confirmed'])
                ->get();

            $hasConflict = $existingBookings->contains(
                function ($booking) use ($slotStart, $slotEnd, $service) {

                    $existingStart = Carbon::createFromFormat(
                        'H:i:s',
                        $booking->booking_time
                    );

                    $existingEnd = $existingStart->copy()
                        ->addMinutes($service->duration_minutes);

                    return $slotStart->lt($existingEnd)
                        && $slotEnd->gt($existingStart);
                }
            );

            if (!$hasConflict) {
                $slots[] = [
                    'value' => $slotStart->format('H:i'),
                    'label' => $slotStart->format('h:i A'),
                ];
            }

            $currentTime->addMinutes(30);
        }

        return response()->json([
            'slots' => $slots,
        ]);
    }

    public function qr(Booking $booking)
    {
        // Make sure the booking belongs to the logged-in customer.
        abort_unless($booking->customer_id === auth()->id(), 403);  //Customer A cannot view Customer B's QR.

        // QR codes are only available for confirmed bookings.
        abort_unless($booking->status === 'confirmed', 403);  //so pending/rejected/cancelled bookings cannot get a valid booking QR.

        $booking->load(['service.provider']);

        $qrData = implode("\n", [
            'SlotHub Booking',
            'Booking ID: ' . $booking->id,
            'Service: ' . $booking->service->name,
            'Customer: ' . auth()->user()->name,
            'Provider: ' . $booking->service->provider->name,
            'Date: ' . $booking->booking_date->format('Y-m-d'),
            'Time: ' . Carbon::parse($booking->booking_time)->format('h:i A'),
            'Status: Confirmed',
        ]);

        $renderer = new ImageRenderer(
            new RendererStyle(300),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);

        $qrCode = $writer->writeString($qrData);

        return response($qrCode, 200)
            ->header('Content-Type', 'image/svg+xml');
    }

    public function cancel(
        Request $request,
        Booking $booking,
        BookingService $bookingService
    ) {
        try {
            $bookingService->cancel(
                $request->user(),
                $booking
            );
        } catch (\Illuminate\Validation\ValidationException $exception) {
            return redirect()
                ->route('customer.bookings.index')
                ->with('error', $exception->errors()['booking'][0]);
        }

        return redirect()
            ->route('customer.bookings.index')
            ->with('success', 'Booking cancelled successfully.');
    }
}