<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-[#192A51]">
                My Bookings
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                View and manage your service bookings.
            </p>
        </div>
    </x-slot>

    <div class="min-h-screen bg-[#FAF8FB] py-8">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Page Heading --}}
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-7">

                <div>
                    <h1 class="text-2xl font-bold text-[#192A51]">
                        Your Bookings
                    </h1>

                    <p class="text-sm text-gray-500 mt-1">
                        Keep track of your appointments and booking status.
                    </p>
                </div>

                <a href="{{ route('customer.services.index') }}" class="inline-flex items-center justify-center gap-2
                          px-5 py-3 bg-[#192A51] text-white
                          text-sm font-semibold rounded-xl
                          hover:bg-[#967AA1] transition">

                    Browse Services
                    <span>→</span>
                </a>
            </div>

            {{-- Success Message --}}
            @if (session('success'))

                <div class="mb-6 flex items-center gap-3
                                p-4 bg-green-50
                                border border-green-200
                                text-green-700 rounded-xl">

                    <div class="w-8 h-8 shrink-0 rounded-lg
                                    bg-green-100 flex items-center justify-center">

                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">

                            <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6">
                            </path>
                        </svg>
                    </div>

                    <span class="text-sm font-medium">
                        {{ session('success') }}
                    </span>
                </div>
            @endif

            {{-- Booking Cards --}}
            <div class="space-y-5">

                @forelse ($bookings as $booking)

                    <div class="bg-white
                                    border border-[#D5C6E0]/70
                                    rounded-2xl shadow-sm overflow-hidden">

                        {{-- Accent --}}
                        <div class="h-1 bg-[#967AA1]"></div>

                        <div class="p-6">

                            {{-- Top Section --}}
                            <div class="flex flex-col sm:flex-row
                                            sm:items-start sm:justify-between
                                            gap-4">
                                <div>

                                    {{-- Category --}}
                                    <span class="inline-flex items-center
                                                     px-3 py-1 mb-3
                                                     bg-[#F5E6E8]
                                                     text-[#967AA1]
                                                     text-xs font-semibold
                                                     rounded-full">

                                        {{ $booking->service->category->name }}
                                    </span>

                                    {{-- Service --}}
                                    <h3 class="text-xl font-bold text-[#192A51]">
                                        {{ $booking->service->name }}
                                    </h3>

                                    {{-- Provider --}}
                                    <div class="flex items-center gap-3 mt-4">

                                        <div class="w-9 h-9 rounded-xl
                                                        bg-[#D5C6E0]/60
                                                        flex items-center justify-center
                                                        text-[#192A51]">

                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="1.8">

                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0">
                                                </path>
                                            </svg>
                                        </div>

                                        <div>
                                            <p class="text-xs text-gray-400">
                                                Provider
                                            </p>

                                            <p class="text-sm font-semibold text-[#192A51]">
                                                {{ $booking->service->provider->name }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Status --}}
                                <div class="flex flex-col sm:items-end gap-3">

                                    @if ($booking->status === 'pending')

                                        <span class="inline-flex items-center gap-2
                                                             px-3 py-1.5
                                                             bg-amber-50 text-amber-700
                                                             text-xs font-semibold rounded-full">

                                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                            Pending
                                        </span>
                                    @elseif ($booking->status === 'confirmed')

                                        <span class="inline-flex items-center gap-2
                                                             px-3 py-1.5
                                                             bg-green-50 text-green-700
                                                             text-xs font-semibold rounded-full">

                                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                            Confirmed
                                        </span>
                                    @elseif ($booking->status === 'completed')
                                        <span class="inline-flex items-center gap-2
                                                             px-3 py-1.5
                                                             bg-blue-50 text-blue-700
                                                             text-xs font-semibold rounded-full">

                                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                            Completed
                                        </span>
                                    @elseif ($booking->status === 'cancelled')

                                        <span class="inline-flex items-center gap-2
                                                             px-3 py-1.5
                                                             bg-red-50 text-red-600
                                                             text-xs font-semibold rounded-full">

                                            <span class="w-2 h-2 rounded-full bg-red-400"></span>
                                            Cancelled
                                        </span>

                                    @elseif ($booking->status === 'rejected')

                                        <span class="inline-flex items-center gap-2
                                                             px-3 py-1.5
                                                             bg-red-50 text-red-600
                                                             text-xs font-semibold rounded-full">

                                            <span class="w-2 h-2 rounded-full bg-red-400"></span>
                                            Rejected
                                        </span>
                                    @endif

                                    {{-- QR --}}
                                    @if ($booking->status === 'confirmed')

                                        <a href="{{ route('customer.bookings.qr', $booking) }}" target="_blank" class="inline-flex items-center justify-center gap-2
                                                          px-4 py-2
                                                          bg-[#192A51] text-white
                                                          text-sm font-semibold rounded-xl
                                                          hover:bg-[#967AA1] transition">

                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="1.8">

                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M3 3h6v6H3V3Zm12 0h6v6h-6V3ZM3 15h6v6H3v-6Zm12 0h2v2h-2v-2Zm4 0h2v6h-6v-2">
                                                </path>
                                            </svg>
                                            View QR Code
                                        </a>
                                    @endif
                                </div>
                            </div>

                            {{-- Booking Information --}}
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mt-6">

                                {{-- Date --}}
                                <div class="bg-[#FAF8FB]
                                                border border-[#D5C6E0]/50
                                                rounded-xl p-4">

                                    <p class="text-xs text-gray-400 mb-1">
                                        Date
                                    </p>

                                    <p class="text-sm font-semibold text-[#192A51]">
                                        {{ $booking->booking_date->format('d M Y') }}
                                    </p>
                                </div>

                                {{-- Time --}}
                                <div class="bg-[#FAF8FB]
                                                border border-[#D5C6E0]/50
                                                rounded-xl p-4">

                                    <p class="text-xs text-gray-400 mb-1">
                                        Time
                                    </p>

                                    <p class="text-sm font-semibold text-[#192A51]">
                                        {{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}
                                    </p>
                                </div>

                                {{-- Duration --}}
                                <div class="bg-[#FAF8FB]
                                                border border-[#D5C6E0]/50
                                                rounded-xl p-4">

                                    <p class="text-xs text-gray-400 mb-1">
                                        Duration
                                    </p>

                                    <p class="text-sm font-semibold text-[#192A51]">
                                        {{ $booking->service->duration_minutes }} minutes
                                    </p>
                                </div>

                                {{-- Price --}}
                                <div class="bg-[#FAF8FB]
                                                border border-[#D5C6E0]/50
                                                rounded-xl p-4">

                                    <p class="text-xs text-gray-400 mb-1">
                                        Price
                                    </p>

                                    <p class="text-sm font-semibold text-[#192A51]">
                                        LKR {{ number_format($booking->service->price, 2) }}
                                    </p>
                                </div>
                            </div>

                            {{-- Notes --}}
                            @if ($booking->notes)

                                <div class="mt-4 p-4
                                                    bg-[#F5E6E8]/50
                                                    rounded-xl">

                                    <p class="text-xs font-semibold
                                                      text-[#967AA1] mb-1">
                                        Notes
                                    </p>

                                    <p class="text-sm text-gray-600">
                                        {{ $booking->notes }}
                                    </p>
                                </div>
                            @endif

                            {{-- Cancel --}}
                            @if (in_array($booking->status, ['pending', 'confirmed']))

                                <div class="mt-5 pt-5 border-t border-gray-100">

                                    <form method="POST" action="{{ route('customer.bookings.cancel', $booking) }}"
                                        onsubmit="return confirm('Are you sure you want to cancel this booking?');">

                                        @csrf
                                        @method('PATCH')

                                        <button type="submit" class="inline-flex items-center justify-center
                                                               px-4 py-2
                                                               border border-red-200
                                                               bg-red-50 text-red-600
                                                               text-sm font-semibold
                                                               rounded-xl
                                                               hover:bg-red-100 transition">

                                            Cancel Booking

                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty

                    {{-- Empty State --}}
                    <div class="bg-white
                                    border border-[#D5C6E0]/70
                                    rounded-2xl
                                    text-center py-14 px-6">

                        <div class="w-14 h-14 mx-auto mb-4
                                        bg-[#D5C6E0]/50
                                        rounded-2xl
                                        flex items-center justify-center
                                        text-[#192A51]">

                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">

                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 2v3m12-3v3M3 9h18M5 4h14a2 2 0 0 1 2 2v14H3V6a2 2 0 0 1 2-2Z">
                                </path>

                            </svg>

                        </div>

                        <h3 class="text-lg font-bold text-[#192A51]">
                            No bookings yet
                        </h3>

                        <p class="text-sm text-gray-500 mt-2 mb-5">
                            Browse available services and make your first booking.
                        </p>

                        <a href="{{ route('customer.services.index') }}" class="inline-flex items-center gap-2
                                      px-5 py-3
                                      bg-[#192A51] text-white
                                      text-sm font-semibold rounded-xl
                                      hover:bg-[#967AA1] transition">

                            Browse Services
                            <span>→</span>

                        </a>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($bookings->hasPages())

                <div class="mt-7">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>