<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Service;
use App\Models\Booking;
use App\Services\BookingService;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

Route::post('/login', function (Request $request) {

    $validator = Validator::make($request->all(), [
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation failed.',
            'errors' => $validator->errors(),
        ], 422);
    }

    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json([
            'message' => 'Invalid email or password.',
        ], 401);
    }

    $user = $request->user();

    $token = $user->createToken('api-token', [
        'read',
        'create',
        'update',
        'delete',
        'bookings:read',
        'bookings:create',
        'bookings:cancel',
    ])->plainTextToken;

    return response()->json([
        'message' => 'Login successful.',
        'token' => $token,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ],
    ]);
})->middleware('throttle:10,1');

Route::post('/logout', function (Request $request) {

    $request->user()->currentAccessToken()?->delete();

    return response()->json([
        'message' => 'Logout successful.',
    ]);

})->middleware([
            'auth:sanctum',
            'throttle:60,1',
        ]);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware([
            'auth:sanctum',
            'throttle:60,1',
        ]);

Route::get('/services', function (Request $request) {
    $services = Service::query()
        ->active()
        ->with([
            'provider:id,name',
            'category:id,name',
        ])
        ->search($request->query('search'))
        ->category($request->integer('category') ?: null)
        ->latest()
        ->paginate(10);

    $services->through(function ($service) {
        return [
            'id' => $service->id,
            'name' => $service->name,
            'description' => $service->description,
            'duration_minutes' => $service->duration_minutes,
            'price' => $service->price,
            'formatted_price' => $service->formatted_price,
            'provider' => [
                'id' => $service->provider->id,
                'name' => $service->provider->name,
            ],
            'category' => $service->category ? [
                'id' => $service->category->id,
                'name' => $service->category->name,
            ] : null,
        ];
    });

    return response()->json($services);
})->middleware('throttle:60,1');

Route::get('/services/{service}', function (Service $service) {
    abort_unless($service->is_active, 404);

    $service->load([
        'provider:id,name',
        'category:id,name',
    ]);

    return response()->json([
        'id' => $service->id,
        'name' => $service->name,
        'description' => $service->description,
        'duration_minutes' => $service->duration_minutes,
        'price' => $service->price,
        'formatted_price' => $service->formatted_price,
        'provider' => [
            'id' => $service->provider->id,
            'name' => $service->provider->name,
        ],
        'category' => $service->category ? [
            'id' => $service->category->id,
            'name' => $service->category->name,
        ] : null,
    ]);
})->middleware('throttle:60,1');



Route::get('/my-bookings', function (Request $request) {

    abort_unless($request->user()->role === 'customer', 403);

    if (
        $request->user()->currentAccessToken() &&
        !$request->user()->tokenCan('bookings:read')
    ) {
        abort(403, 'Token does not have permission to read bookings.');
    }

    $bookings = $request->user()
        ->bookings()
        ->with('service')
        ->latest('booking_date')
        ->paginate(10);

    $bookings->through(function ($booking) {  //lets user have both pagination and a controlled API response.
        return [
            'id' => $booking->id,
            'service' => $booking->service->name,
            'date' => $booking->booking_date->format('Y-m-d'),
            'time' => $booking->booking_time,
            'status' => $booking->status,
        ];
    });

    return response()->json($bookings);

})->middleware([
            'auth:sanctum',
            'throttle:60,1',
        ]); //This means an authenticated client can make up to 60 requests per minute to this endpoint.

Route::post('/bookings', function (Request $request, BookingService $bookingService) {
    abort_unless($request->user()->role === 'customer', 403);

    if (
        $request->user()->currentAccessToken() &&
        !$request->user()->tokenCan('bookings:create')
    ) {
        abort(403, 'Token does not have permission to create bookings.');
    }

    $validated = $request->validate([
        'service_id' => [
            'required',
            'integer',
            'exists:services,id',
        ],
        'booking_date' => [
            'required',
            'date',
            'after_or_equal:today',
        ],
        'booking_time' => [
            'required',
            'date_format:H:i',
        ],
        'notes' => [
            'nullable',
            'string',
            'max:1000',
        ],
    ]);

    $service = Service::findOrFail($validated['service_id']);

    $booking = $bookingService->create(
        $request->user(),
        $service,
        $validated
    );

    $booking->load([
        'service.provider:id,name',
        'service.category:id,name',
    ]);

    return response()->json([
        'message' => 'Booking created successfully.',
        'booking' => [
            'id' => $booking->id,
            'service' => [
                'id' => $booking->service->id,
                'name' => $booking->service->name,
                'provider' => $booking->service->provider->name,
                'category' => $booking->service->category?->name,
            ],
            'date' => $booking->booking_date->format('Y-m-d'),
            'time' => $booking->booking_time,
            'status' => $booking->status,
            'notes' => $booking->notes,
        ],
    ], 201);
})->middleware([
            'auth:sanctum',
            'throttle:60,1',
        ]);

Route::get('/bookings/{booking}', function (Request $request, Booking $booking) {
    abort_unless($request->user()->role === 'customer', 403);

    if (
        $request->user()->currentAccessToken() &&
        !$request->user()->tokenCan('bookings:read')
    ) {
        abort(403, 'Token does not have permission to read bookings.');
    }

    abort_unless(
        $booking->customer_id === $request->user()->id,
        403,
        'You are not authorized to view this booking.'
    );

    $booking->load([
        'service.provider:id,name',
        'service.category:id,name',
    ]);

    return response()->json([
        'id' => $booking->id,
        'service' => [
            'id' => $booking->service->id,
            'name' => $booking->service->name,
            'provider' => $booking->service->provider->name,
            'category' => $booking->service->category?->name,
        ],
        'date' => $booking->booking_date->format('Y-m-d'),
        'time' => $booking->booking_time,
        'status' => $booking->status,
        'notes' => $booking->notes,
    ]);
})->middleware([
            'auth:sanctum',
            'throttle:60,1',
        ]);

Route::put('/bookings/{booking}', function (Request $request, Booking $booking) {
    abort_unless($request->user()->role === 'customer', 403);

    if (
        $request->user()->currentAccessToken() &&
        !$request->user()->tokenCan('update')
    ) {
        abort(403, 'Token does not have permission to update bookings.');
    }

    abort_unless(
        $booking->customer_id === $request->user()->id,
        403,
        'You are not authorized to update this booking.'
    );

    abort_if(
        in_array($booking->status, ['cancelled', 'rejected']),
        422,
        'Cancelled or rejected bookings cannot be updated.'
    );

    $validated = $request->validate([
        'notes' => [
            'nullable',
            'string',
            'max:1000',
        ],
    ]);

    $booking->update([
        'notes' => $validated['notes'] ?? null,
    ]);

    return response()->json([
        'message' => 'Booking updated successfully.',
        'booking' => [
            'id' => $booking->id,
            'date' => $booking->booking_date->format('Y-m-d'),
            'time' => $booking->booking_time,
            'status' => $booking->status,
            'notes' => $booking->notes,
        ],
    ]);
})->middleware([
            'auth:sanctum',
            'throttle:60,1',
        ]);

Route::delete('/bookings/{booking}', function (Request $request, Booking $booking, BookingService $bookingService) {
    abort_unless($request->user()->role === 'customer', 403);

    if (
        $request->user()->currentAccessToken() &&
        !$request->user()->tokenCan('bookings:cancel')
    ) {
        abort(403, 'Token does not have permission to cancel bookings.');
    }

    $bookingService->cancel(
        $request->user(),
        $booking
    );

    return response()->json([
        'message' => 'Booking cancelled successfully.',
        'booking' => [
            'id' => $booking->id,
            'status' => $booking->status,
        ],
    ]);
})->middleware([
            'auth:sanctum',
            'throttle:60,1',
        ]);