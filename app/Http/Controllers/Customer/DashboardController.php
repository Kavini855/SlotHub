<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //each customer only gets statistics for their own bookings
    public function index(Request $request)
    {
        //protect this route only customers can access
        $customerId = $request->user()->id;

        // Total bookings made by this customer
        $totalBookings = Booking::where('customer_id', $customerId)
            ->count();

        // Pending bookings
        $pendingBookings = Booking::where('customer_id', $customerId)
            ->where('status', 'pending')
            ->count();

        // Confirmed bookings
        $confirmedBookings = Booking::where('customer_id', $customerId)
            ->where('status', 'confirmed')
            ->count();

        // Recent bookings
        $recentBookings = Booking::with(['service.provider'])
            ->where('customer_id', $customerId)
            ->latest()
            ->take(5)
            ->get();

        return view('customer.dashboard', compact(
            'totalBookings',
            'pendingBookings',
            'confirmedBookings',
            'recentBookings'
        ));
    }
}