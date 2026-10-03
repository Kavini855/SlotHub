<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $provider = $request->user();

        // Total services belonging to this provider
        $totalServices = Service::where('provider_id', $provider->id)->count();

        // Base query for bookings made for this provider's services
        $bookingQuery = Booking::whereHas('service', function ($query) use ($provider) {
            $query->where('provider_id', $provider->id);
        });

        $totalBookings = (clone $bookingQuery)->count();

        $pendingBookings = (clone $bookingQuery)
            ->where('status', 'pending')
            ->count();

        $confirmedBookings = (clone $bookingQuery)
            ->where('status', 'confirmed')
            ->count();

        // Latest booking requests
        $recentBookings = (clone $bookingQuery)
            ->with(['customer', 'service'])
            ->latest()
            ->take(5)
            ->get();

        return view('provider.dashboard', compact(
            'totalServices',
            'totalBookings',
            'pendingBookings',
            'confirmedBookings',
            'recentBookings'
        ));
    }
}