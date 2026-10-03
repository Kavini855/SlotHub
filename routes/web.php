<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\ServiceController as CustomerServiceController;
use App\Http\Controllers\Provider\ServiceController as ProviderServiceController;
use App\Http\Controllers\Customer\BookingController as CustomerBookingController;
use App\Http\Controllers\Provider\BookingController as ProviderBookingController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Provider\DashboardController as ProviderDashboardController;
use App\Http\Controllers\Provider\AvailabilityController as ProviderAvailabilityController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', function () {
        $user = auth()->user();

        return match ($user->role) {
            'customer' => redirect()->route('customer.dashboard'),
            'provider' => redirect()->route('provider.dashboard'),
            default => abort(403, 'Invalid account role.'),
        };
    })->name('dashboard');


    /*
    | Customer Routes
    */
    Route::middleware('role:customer')
        ->prefix('customer')
        ->name('customer.')
        ->group(function () {

            Route::get('/dashboard', [CustomerDashboardController::class, 'index'])
                ->name('dashboard');

            Route::get('/services', [CustomerServiceController::class, 'index'])
                ->name('services.index');

            Route::get('/services/{service}', [CustomerServiceController::class, 'show'])
                ->name('services.show');

            Route::get('/bookings', [CustomerBookingController::class, 'index'])
                ->name('bookings.index');

            Route::get('/services/{service}/book', [CustomerBookingController::class, 'create'])
                ->name('bookings.create');

            Route::post('/services/{service}/book', [CustomerBookingController::class, 'store'])
                ->name('bookings.store');

            Route::patch('/bookings/{booking}/cancel', [CustomerBookingController::class, 'cancel'])
                ->name('bookings.cancel');

            Route::get('/services/{service}/available-slots', [\App\Http\Controllers\Customer\BookingController::class, 'availableSlots'])
                ->name('services.available-slots');

            Route::get('/bookings/{booking}/qr', [CustomerBookingController::class, 'qr'])
                ->name('bookings.qr');

            Route::get('/holidays/check', [CustomerBookingController::class, 'checkHoliday'])
                ->name('holidays.check');

        });


    /*
    | Service Provider Routes
    */
    Route::middleware('role:provider')
        ->prefix('provider')
        ->name('provider.')
        ->group(function () {

            Route::get('/dashboard', [ProviderDashboardController::class, 'index'])
                ->name('dashboard');

            Route::resource('services', ProviderServiceController::class);

            Route::get('/bookings', [ProviderBookingController::class, 'index'])
                ->name('bookings.index');

            Route::patch('/bookings/{booking}/status', [ProviderBookingController::class, 'updateStatus'])
                ->name('bookings.update-status');

            Route::get('/availability', [ProviderAvailabilityController::class, 'index'])
                ->name('availabilities.index');

            Route::put('/availability', [ProviderAvailabilityController::class, 'update'])
                ->name('availabilities.update');

        });

    Route::patch('/notifications/{notification}/read', function (Illuminate\Http\Request $request, string $notification) {
        $userNotification = $request->user()
            ->notifications()
            ->where('id', $notification)
            ->firstOrFail();

        $userNotification->markAsRead();

        return match ($request->user()->role) {
            'customer' => redirect()->route('customer.bookings.index'),
            'provider' => redirect()->route('provider.bookings.index'),
            default => abort(403),
        };
    })->name('notifications.read');
});