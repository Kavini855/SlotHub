<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-[#2D2E2E]">
                Provider Dashboard
            </h2>
            <p class="mt-1 text-sm text-[#716969]">
                Manage your services and customer bookings.
            </p>
        </div>
    </x-slot>

    <div class="bg-[#FBFBFB] py-10 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Welcome Banner --}}
            <div class="relative overflow-hidden rounded-2xl bg-[#2D2E2E] px-8 py-8 mb-10 shadow-sm">

                {{-- Decorative circles --}}
                <div class="absolute -right-12 -top-16 w-52 h-52 rounded-full bg-[#716969] opacity-30"></div>
                <div class="absolute right-28 -bottom-24 w-48 h-48 rounded-full bg-[#BCABAE] opacity-10"></div>

                <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-6">

                    <div>
                        <p class="text-sm font-semibold text-[#BCABAE]">
                            Welcome to your workspace
                        </p>

                        <h1 class="mt-2 text-3xl font-bold text-white">
                            Hello, {{ auth()->user()->name }}! 👋
                        </h1>

                        <p class="mt-3 text-[#BCABAE] max-w-2xl">
                            Manage your services, review customer bookings and keep
                            track of your appointments all in one place.
                        </p>
                    </div>

                    <a href="{{ route('provider.services.create') }}" class="relative inline-flex items-center justify-center gap-2 bg-white text-[#2D2E2E]
                               font-semibold px-5 py-3 rounded-xl hover:bg-[#FBFBFB] transition shadow-sm">

                        <span class="text-lg">+</span>
                        Add Service
                    </a>
                </div>
            </div>

            {{-- Overview Heading --}}
            <div class="mb-5">
                <h2 class="text-2xl font-bold text-[#2D2E2E]">
                    Business Overview
                </h2>

                <p class="text-sm text-[#716969] mt-1">
                    A quick look at your services and bookings
                </p>
            </div>

            {{-- Statistics --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-9">

                {{-- Total Services --}}
                <div class="bg-white border border-[#BCABAE]/40 rounded-2xl p-6 shadow-sm">
                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-sm text-[#716969]">
                                Total Services
                            </p>

                            <p class="text-3xl font-bold text-[#2D2E2E] mt-2">
                                {{ $totalServices }}
                            </p>
                        </div>

                        <div class="w-12 h-12 rounded-xl bg-[#BCABAE]/30 flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#2D2E2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Total Bookings --}}
                <div class="bg-white border border-[#BCABAE]/40 rounded-2xl p-6 shadow-sm">
                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-sm text-[#716969]">
                                Total Bookings
                            </p>

                            <p class="text-3xl font-bold text-[#2D2E2E] mt-2">
                                {{ $totalBookings }}
                            </p>
                        </div>

                        <div class="w-12 h-12 rounded-xl bg-[#BCABAE]/30 flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#2D2E2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Pending --}}
                <div class="bg-white border border-[#BCABAE]/40 rounded-2xl p-6 shadow-sm">
                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-sm text-[#716969]">
                                Pending
                            </p>

                            <p class="text-3xl font-bold text-[#716969] mt-2">
                                {{ $pendingBookings }}
                            </p>
                        </div>

                        <div class="w-12 h-12 rounded-xl bg-[#BCABAE]/30 flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#716969]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Confirmed --}}
                <div class="bg-white border border-[#BCABAE]/40 rounded-2xl p-6 shadow-sm">
                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-sm text-[#716969]">
                                Confirmed
                            </p>

                            <p class="text-3xl font-bold text-[#2D2E2E] mt-2">
                                {{ $confirmedBookings }}
                            </p>
                        </div>

                        <div class="w-12 h-12 rounded-xl bg-[#BCABAE]/30 flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#2D2E2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Dashboard Content --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Recent Booking Requests --}}
                <div class="lg:col-span-2 bg-white border border-[#BCABAE]/40 rounded-2xl shadow-sm overflow-hidden">

                    <div class="flex justify-between items-center px-6 py-5 border-b border-[#BCABAE]/20">

                        <div>
                            <h2 class="text-xl font-bold text-[#2D2E2E]">
                                Recent Booking Requests
                            </h2>

                            <p class="text-sm text-[#716969] mt-1">
                                Latest customer booking activity
                            </p>
                        </div>

                        <a href="{{ route('provider.bookings.index') }}"
                            class="text-sm font-semibold text-[#716969] hover:text-[#2D2E2E]">
                            View All →
                        </a>
                    </div>

                    @forelse ($recentBookings as $booking)

                        <div class="px-6 py-5 border-b border-[#BCABAE]/20 last:border-b-0">

                            <div class="flex items-center justify-between gap-5">

                                <div class="flex items-center gap-4">

                                    {{-- Booking Icon --}}
                                    <div class="w-11 h-11 rounded-xl bg-[#BCABAE]/30
                                                        flex items-center justify-center flex-shrink-0">

                                        <svg class="w-5 h-5 text-[#2D2E2E]" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                                        </svg>
                                    </div>

                                    <div>
                                        <h3 class="font-bold text-[#2D2E2E]">
                                            {{ $booking->service->name }}
                                        </h3>

                                        <p class="text-sm text-[#716969] mt-1">
                                            Customer:
                                            <span class="font-medium text-[#2D2E2E]">
                                                {{ $booking->customer->name }}
                                            </span>
                                        </p>

                                        <p class="text-sm text-[#716969] mt-1">
                                            {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                                            <span class="mx-1">•</span>
                                            {{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}
                                        </p>
                                    </div>

                                </div>

                                {{-- Status --}}
                                @if ($booking->status === 'confirmed')

                                    <span
                                        class="inline-flex items-center gap-2 px-3 py-1.5
                                                                 text-xs font-semibold bg-green-50 text-green-700 rounded-full">
                                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                        Confirmed
                                    </span>

                                @elseif ($booking->status === 'cancelled')

                                    <span class="inline-flex items-center gap-2 px-3 py-1.5
                                                                 text-xs font-semibold bg-red-50 text-red-600 rounded-full">
                                        <span class="w-2 h-2 bg-red-400 rounded-full"></span>
                                        Cancelled
                                    </span>

                                @elseif ($booking->status === 'rejected')

                                    <span class="inline-flex items-center gap-2 px-3 py-1.5
                                                                 text-xs font-semibold bg-red-50 text-red-600 rounded-full">
                                        <span class="w-2 h-2 bg-red-400 rounded-full"></span>
                                        Rejected
                                    </span>

                                @else

                                    <span
                                        class="inline-flex items-center gap-2 px-3 py-1.5
                                                                 text-xs font-semibold bg-amber-50 text-amber-700 rounded-full">
                                        <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                                        Pending
                                    </span>
                                @endif
                            </div>
                        </div>

                    @empty

                        <div class="px-6 py-12 text-center">
                            <p class="text-[#716969]">
                                No booking requests yet.
                            </p>
                        </div>

                    @endforelse
                </div>

                {{-- Quick Actions --}}
                <div class="bg-white border border-[#BCABAE]/40 rounded-2xl shadow-sm p-6 self-start">

                    <h2 class="text-xl font-bold text-[#2D2E2E]">
                        Quick Actions
                    </h2>

                    <p class="text-sm text-[#716969] mt-1 mb-6">
                        Manage your SlotHub services
                    </p>

                    {{-- My Services --}}
                    <a href="{{ route('provider.services.index') }}" class="flex items-center justify-between p-4 rounded-xl
                               bg-[#BCABAE]/20 hover:bg-[#BCABAE]/30 transition mb-3">

                        <div>
                            <p class="font-semibold text-[#2D2E2E]">
                                My Services
                            </p>

                            <p class="text-sm text-[#716969]">
                                View and manage services
                            </p>
                        </div>

                        <span class="text-[#716969]">→</span>
                    </a>

                    {{-- Add Service --}}
                    <a href="{{ route('provider.services.create') }}" class="flex items-center justify-between p-4 rounded-xl
                               bg-[#BCABAE]/20 hover:bg-[#BCABAE]/30 transition mb-3">

                        <div>
                            <p class="font-semibold text-[#2D2E2E]">
                                Add Service
                            </p>

                            <p class="text-sm text-[#716969]">
                                Create a new service
                            </p>
                        </div>

                        <span class="text-[#716969]">→</span>
                    </a>

                    {{-- Service Bookings --}}
                    <a href="{{ route('provider.bookings.index') }}" class="flex items-center justify-between p-4 rounded-xl
                               bg-[#BCABAE]/20 hover:bg-[#BCABAE]/30 transition">

                        <div>
                            <p class="font-semibold text-[#2D2E2E]">
                                Service Bookings
                            </p>

                            <p class="text-sm text-[#716969]">
                                Manage booking requests
                            </p>
                        </div>

                        <span class="text-[#716969]">→</span>
                    </a>

                    {{-- Provider Info --}}
                    <div class="mt-6 bg-[#2D2E2E] rounded-xl p-5">

                        <div class="w-10 h-10 rounded-lg bg-[#716969]
                                    flex items-center justify-center mb-4">

                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5.121 17.804A4 4 0 019 16h6a4 4 0 013.879 1.804M15 11a3 3 0 10-6 0 3 3 0 006 0z" />
                            </svg>
                        </div>

                        <h3 class="font-bold text-white">
                            Manage with ease
                        </h3>

                        <p class="text-sm text-[#BCABAE] mt-2 leading-6">
                            Keep your services updated and respond to customer
                            booking requests directly through SlotHub.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>