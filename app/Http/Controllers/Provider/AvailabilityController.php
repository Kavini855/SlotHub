<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    /**
     * Display the provider's weekly availability.
     */
    public function index(Request $request)
    {
        $availabilities = Availability::where(
            'provider_id',
            $request->user()->id
        )
            ->orderBy('day_of_week')
            ->get()
            ->keyBy('day_of_week');

        $days = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];

        return view(
            'provider.availabilities.index',
            compact('availabilities', 'days')
        );
    }

    /**
     * Save the provider's weekly availability.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'availabilities' => ['required', 'array'],
            'availabilities.*.is_active' => ['nullable', 'boolean'],
            'availabilities.*.start_time' => ['nullable', 'date_format:H:i'],
            'availabilities.*.end_time' => ['nullable', 'date_format:H:i'],
        ]);

        foreach ($validated['availabilities'] as $day => $times) {

            // Only allow valid days: 0-6
            if (!in_array((int) $day, range(0, 6), true)) {
                continue;
            }

            $isActive = isset($times['is_active']);

            // If provider is available, both times are required
            if ($isActive) {

                if (
                    empty($times['start_time']) ||
                    empty($times['end_time'])
                ) {
                    return back()
                        ->withInput()
                        ->withErrors([
                            "availabilities.$day.start_time" =>
                                'Start and end times are required for available days.',
                        ]);
                }

                if ($times['end_time'] <= $times['start_time']) {
                    return back()
                        ->withInput()
                        ->withErrors([
                            "availabilities.$day.end_time" =>
                                'End time must be after the start time.',
                        ]);
                }
            }

            Availability::updateOrCreate(
                [
                    'provider_id' => $request->user()->id,
                    'day_of_week' => (int) $day,
                ],
                [
                    'start_time' => $times['start_time'] ?? '09:00',
                    'end_time' => $times['end_time'] ?? '17:00',
                    'is_active' => $isActive,
                ]
            );
        }

        return redirect()
            ->route('provider.availabilities.index')
            ->with('success', 'Availability updated successfully.');
    }
}