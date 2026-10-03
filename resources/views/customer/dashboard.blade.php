<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-[#192A51]">
                    Customer Dashboard
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Overview of your SlotHub activity
                </p>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-[#FAF8FB] py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Welcome Banner --}}
            <div class="relative overflow-hidden bg-[#192A51] rounded-3xl p-7 md:p-9 mb-8 shadow-sm">

                {{-- Decorative circles --}}
                <div class="absolute -right-16 -top-20 w-64 h-64 rounded-full bg-[#AAA1C8]/20"></div>
                <div class="absolute right-32 -bottom-24 w-48 h-48 rounded-full bg-[#D5C6E0]/10"></div>

                <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div>
                        <p class="text-[#D5C6E0] text-sm font-medium mb-2">
                            Welcome to SlotHub
                        </p>

                        <h1 class="text-3xl md:text-4xl font-bold text-white">
                            Hello, {{ auth()->user()->name }}! 👋
                        </h1>

                        <p class="mt-3 text-[#D5C6E0] max-w-xl">
                            Find services, manage your bookings and keep track of
                            your upcoming appointments all in one place.
                        </p>
                    </div>

                    <a href="{{ route('customer.services.index') }}" class="relative inline-flex items-center justify-center gap-2
                              px-5 py-3 bg-white text-[#192A51] font-semibold
                              rounded-xl hover:bg-[#F5E6E8] transition shadow-sm">

                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"></circle>
                            <path stroke-linecap="round" d="m20 20-4-4"></path>
                        </svg>

                        Find a Service
                    </a>
                </div>
            </div>


            {{-- Booking Overview --}}
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-[#192A51]">
                            Booking Overview
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">
                            A quick look at your bookings
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">

                    {{-- Total --}}
                    <div class="bg-white border border-[#D5C6E0]/60 rounded-2xl p-6 shadow-sm">
                        <div class="flex items-center justify-between">

                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    Total Bookings
                                </p>

                                <p class="mt-2 text-3xl font-bold text-[#192A51]">
                                    {{ $totalBookings }}
                                </p>
                            </div>

                            <div class="w-12 h-12 rounded-2xl bg-[#D5C6E0]/60
                                        flex items-center justify-center text-[#192A51]">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1.8">
                                    <rect x="4" y="5" width="16" height="15" rx="2"></rect>
                                    <path d="M8 3v4M16 3v4M4 10h16"></path>
                                </svg>
                            </div>

                        </div>
                    </div>


                    {{-- Pending --}}
                    <div class="bg-white border border-[#D5C6E0]/60 rounded-2xl p-6 shadow-sm">
                        <div class="flex items-center justify-between">

                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    Pending
                                </p>

                                <p class="mt-2 text-3xl font-bold text-[#967AA1]">
                                    {{ $pendingBookings }}
                                </p>
                            </div>

                            <div class="w-12 h-12 rounded-2xl bg-[#F5E6E8]
                                        flex items-center justify-center text-[#967AA1]">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1.8">
                                    <circle cx="12" cy="12" r="9"></circle>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"></path>
                                </svg>
                            </div>

                        </div>
                    </div>


                    {{-- Confirmed --}}
                    <div class="bg-white border border-[#D5C6E0]/60 rounded-2xl p-6 shadow-sm">
                        <div class="flex items-center justify-between">

                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    Confirmed
                                </p>

                                <p class="mt-2 text-3xl font-bold text-[#192A51]">
                                    {{ $confirmedBookings }}
                                </p>
                            </div>

                            <div class="w-12 h-12 rounded-2xl bg-[#AAA1C8]/30
                                        flex items-center justify-center text-[#192A51]">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1.8">
                                    <circle cx="12" cy="12" r="9"></circle>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.5 12 2.2 2.2 4.8-5">
                                    </path>
                                </svg>
                            </div>

                        </div>
                    </div>

                </div>
            </div>


            {{-- Main Dashboard Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Recent Bookings --}}
                <div class="lg:col-span-2 bg-white border border-[#D5C6E0]/60
                            rounded-2xl shadow-sm overflow-hidden">

                    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                        <div>
                            <h2 class="text-lg font-bold text-[#192A51]">
                                Recent Bookings
                            </h2>

                            <p class="text-sm text-gray-500 mt-1">
                                Your latest booking activity
                            </p>
                        </div>

                        <a href="{{ route('customer.bookings.index') }}" class="text-sm font-semibold text-[#967AA1]
                                  hover:text-[#192A51] transition">
                            View All →
                        </a>
                    </div>


                    <div class="divide-y divide-gray-100">

                        @forelse ($recentBookings as $booking)

                            <div class="p-6 hover:bg-[#F5E6E8]/30 transition">
                                <div class="flex flex-col sm:flex-row
                                                    sm:items-center sm:justify-between gap-4">

                                    <div class="flex items-start gap-4">

                                        <div class="w-11 h-11 shrink-0 rounded-xl
                                                            bg-[#D5C6E0]/50 flex items-center
                                                            justify-center text-[#192A51]">

                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M4 6h16M4 12h16M4 18h10"></path>
                                            </svg>
                                        </div>

                                        <div>
                                            <h3 class="font-semibold text-[#192A51]">
                                                {{ $booking->service->name }}
                                            </h3>

                                            <p class="text-sm text-gray-500 mt-1">
                                                {{ $booking->service->provider->name }}
                                            </p>

                                            <div class="flex flex-wrap items-center gap-2
                                                                mt-2 text-xs text-gray-500">

                                                <span>
                                                    {{ $booking->booking_date->format('d M Y') }}
                                                </span>

                                                <span>•</span>

                                                <span>
                                                    {{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}
                                                </span>

                                            </div>
                                        </div>
                                    </div>


                                    <div>
                                        @if ($booking->status === 'confirmed')

                                            <span class="inline-flex px-3 py-1.5 text-xs
                                                                         font-semibold rounded-full
                                                                         bg-[#D5C6E0]/60 text-[#192A51]">
                                                Confirmed
                                            </span>

                                        @elseif ($booking->status === 'pending')

                                            <span class="inline-flex px-3 py-1.5 text-xs
                                                                         font-semibold rounded-full
                                                                         bg-[#F5E6E8] text-[#967AA1]">
                                                Pending
                                            </span>

                                        @elseif ($booking->status === 'cancelled')

                                            <span class="inline-flex px-3 py-1.5 text-xs
                                                                         font-semibold rounded-full
                                                                         bg-red-50 text-red-600">
                                                Cancelled
                                            </span>

                                        @elseif ($booking->status === 'rejected')

                                            <span class="inline-flex px-3 py-1.5 text-xs
                                                                         font-semibold rounded-full
                                                                         bg-red-50 text-red-600">
                                                Rejected
                                            </span>

                                        @else

                                            <span class="inline-flex px-3 py-1.5 text-xs
                                                                         font-semibold rounded-full
                                                                         bg-gray-100 text-gray-600">
                                                {{ ucfirst($booking->status) }}
                                            </span>

                                        @endif
                                    </div>

                                </div>
                            </div>

                        @empty

                            <div class="text-center px-6 py-12">

                                <div class="w-14 h-14 mx-auto rounded-2xl
                                                    bg-[#D5C6E0]/50 flex items-center
                                                    justify-center text-[#192A51] mb-4">

                                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.7">
                                        <rect x="4" y="5" width="16" height="15" rx="2"></rect>
                                        <path d="M8 3v4M16 3v4M4 10h16"></path>
                                    </svg>
                                </div>

                                <h3 class="font-semibold text-[#192A51]">
                                    No bookings yet
                                </h3>

                                <p class="text-sm text-gray-500 mt-1 mb-5">
                                    Discover a service and make your first booking.
                                </p>

                                <a href="{{ route('customer.services.index') }}" class="inline-flex px-5 py-2.5 bg-[#192A51]
                                                  text-white text-sm font-semibold rounded-xl
                                                  hover:bg-[#273d70] transition">
                                    Browse Services
                                </a>

                            </div>

                        @endforelse

                    </div>
                </div>


                {{-- Quick Actions --}}
                <div class="bg-white border border-[#D5C6E0]/60
                            rounded-2xl shadow-sm p-6 h-fit">

                    <h2 class="text-lg font-bold text-[#192A51]">
                        Quick Actions
                    </h2>

                    <p class="text-sm text-gray-500 mt-1 mb-5">
                        What would you like to do?
                    </p>


                    <div class="space-y-3">

                        <a href="{{ route('customer.services.index') }}" class="group flex items-center gap-4 p-4 rounded-xl
                                  bg-[#F5E6E8]/70 hover:bg-[#D5C6E0]/60 transition">

                            <div class="w-10 h-10 rounded-xl bg-white
                                        flex items-center justify-center
                                        text-[#192A51] shadow-sm">

                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1.8">
                                    <circle cx="11" cy="11" r="7"></circle>
                                    <path stroke-linecap="round" d="m20 20-4-4"></path>
                                </svg>

                            </div>

                            <div>
                                <p class="font-semibold text-[#192A51]">
                                    Browse Services
                                </p>

                                <p class="text-xs text-gray-500 mt-0.5">
                                    Find available services
                                </p>
                            </div>

                            <span class="ml-auto text-[#967AA1]
                                         group-hover:translate-x-1 transition">
                                →
                            </span>
                        </a>


                        <a href="{{ route('customer.bookings.index') }}" class="group flex items-center gap-4 p-4 rounded-xl
                                  bg-[#F5E6E8]/70 hover:bg-[#D5C6E0]/60 transition">

                            <div class="w-10 h-10 rounded-xl bg-white
                                        flex items-center justify-center
                                        text-[#192A51] shadow-sm">

                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1.8">
                                    <rect x="4" y="5" width="16" height="15" rx="2"></rect>
                                    <path d="M8 3v4M16 3v4M4 10h16"></path>
                                </svg>

                            </div>

                            <div>
                                <p class="font-semibold text-[#192A51]">
                                    My Bookings
                                </p>

                                <p class="text-xs text-gray-500 mt-0.5">
                                    View and manage bookings
                                </p>
                            </div>

                            <span class="ml-auto text-[#967AA1]
                                         group-hover:translate-x-1 transition">
                                →
                            </span>
                        </a>

                    </div>


                    {{-- Small information card --}}
                    <div class="mt-6 p-5 rounded-2xl bg-[#192A51] text-white">

                        <div class="w-9 h-9 rounded-lg bg-white/10
                                    flex items-center justify-center mb-3">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z"></path>
                            </svg>
                        </div>

                        <h3 class="font-semibold">
                            Book with ease
                        </h3>

                        <p class="text-sm text-[#D5C6E0] mt-1 leading-relaxed">
                            Choose a service, select an available time and manage
                            your booking directly through SlotHub.
                        </p>

                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>