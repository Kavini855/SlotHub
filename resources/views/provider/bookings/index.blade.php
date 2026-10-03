<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="font-bold text-2xl text-[#2D2E2E]">
                Service Bookings
            </h2>
            <p class="text-[#716969] mt-1">
                Review and manage customer booking requests.
            </p>
        </div>
    </x-slot>

    <div class="bg-[#FBFBFB] min-h-screen py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Page Heading --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-[#2D2E2E]">
                    Customer Bookings
                </h1>

                <p class="text-[#716969] mt-1">
                    Keep track of appointments made for your services.
                </p>
            </div>

            {{-- Success Message --}}
            @if (session('success'))
                <div class="mb-7 flex items-center gap-4 rounded-xl border border-green-200
                                bg-green-50 px-5 py-4 text-green-700">

                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-green-100">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>

                    <span class="font-medium">
                        {{ session('success') }}
                    </span>
                </div>
            @endif


            {{-- Booking List --}}
            <div class="space-y-5">

                @forelse ($bookings as $booking)

                    <div class="relative overflow-hidden rounded-2xl border border-[#BCABAE]/40
                                    bg-white shadow-sm">

                        {{-- Top accent --}}
                        <div class="h-1.5 bg-[#716969]"></div>

                        <div class="p-7">

                            {{-- Booking Header --}}
                            <div class="flex flex-col gap-4 sm:flex-row
                                            sm:items-start sm:justify-between">

                                <div>
                                    <span class="inline-flex rounded-full bg-[#BCABAE]/25
                                                     px-3 py-1 text-xs font-semibold text-[#716969]">
                                        {{ $booking->service->category->name }}
                                    </span>

                                    <h2 class="mt-3 text-xl font-bold text-[#2D2E2E]">
                                        {{ $booking->service->name }}
                                    </h2>

                                    <div class="mt-3 flex items-center gap-3">

                                        <div class="flex h-10 w-10 items-center justify-center
                                                        rounded-xl bg-[#BCABAE]/30 text-[#2D2E2E]">

                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0
                                                           018 0zM12 14a7 7 0 00-7 7h14
                                                           a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>

                                        <div>
                                            <p class="text-xs text-[#716969]">
                                                Customer
                                            </p>

                                            <p class="font-semibold text-[#2D2E2E]">
                                                {{ $booking->customer->name }}
                                            </p>
                                        </div>

                                    </div>
                                </div>


                                {{-- Status --}}
                                <div>
                                    @if ($booking->status === 'confirmed')

                                        <span class="inline-flex items-center gap-2 rounded-full
                                                             bg-green-50 px-4 py-2 text-sm
                                                             font-semibold text-green-700">
                                            <span class="h-2 w-2 rounded-full bg-green-500"></span>
                                            Confirmed
                                        </span>

                                    @elseif ($booking->status === 'rejected')

                                        <span class="inline-flex items-center gap-2 rounded-full
                                                             bg-red-50 px-4 py-2 text-sm
                                                             font-semibold text-red-600">
                                            <span class="h-2 w-2 rounded-full bg-red-400"></span>
                                            Rejected
                                        </span>

                                    @elseif ($booking->status === 'cancelled')

                                        <span class="inline-flex items-center gap-2 rounded-full
                                                             bg-red-50 px-4 py-2 text-sm
                                                             font-semibold text-red-600">
                                            <span class="h-2 w-2 rounded-full bg-red-400"></span>
                                            Cancelled
                                        </span>

                                    @elseif ($booking->status === 'completed')

                                        <span class="inline-flex items-center gap-2 rounded-full
                                                             bg-blue-50 px-4 py-2 text-sm
                                                             font-semibold text-blue-700">
                                            <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                            Completed
                                        </span>

                                    @else

                                        <span class="inline-flex items-center gap-2 rounded-full
                                                             bg-amber-50 px-4 py-2 text-sm
                                                             font-semibold text-amber-700">
                                            <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                                            Pending
                                        </span>

                                    @endif
                                </div>

                            </div>


                            {{-- Booking Information --}}
                            <div class="mt-7 grid grid-cols-1 gap-4 sm:grid-cols-3">

                                {{-- Date --}}
                                <div class="rounded-xl border border-[#BCABAE]/30
                                                bg-[#FBFBFB] p-4">

                                    <div class="mb-2 flex items-center gap-2 text-[#716969]">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5
                                                       21h14a2 2 0 002-2V7a2 2
                                                       0 00-2-2H5a2 2 0
                                                       00-2 2v12a2 2 0 002 2z" />
                                        </svg>

                                        <span class="text-sm">Date</span>
                                    </div>

                                    <p class="font-semibold text-[#2D2E2E]">
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                                    </p>
                                </div>


                                {{-- Time --}}
                                <div class="rounded-xl border border-[#BCABAE]/30
                                                bg-[#FBFBFB] p-4">

                                    <div class="mb-2 flex items-center gap-2 text-[#716969]">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0
                                                       11-18 0 9 9 0 0118 0z" />
                                        </svg>

                                        <span class="text-sm">Time</span>
                                    </div>

                                    <p class="font-semibold text-[#2D2E2E]">
                                        {{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}
                                    </p>
                                </div>


                                {{-- Duration --}}
                                <div class="rounded-xl border border-[#BCABAE]/30
                                                bg-[#FBFBFB] p-4">

                                    <p class="text-sm text-[#716969]">
                                        Duration
                                    </p>

                                    <p class="mt-2 font-semibold text-[#2D2E2E]">
                                        {{ $booking->service->duration_minutes }} minutes
                                    </p>
                                </div>

                            </div>


                            {{-- Notes --}}
                            @if ($booking->notes)

                                <div class="mt-5 rounded-xl bg-[#BCABAE]/15 p-4">

                                    <p class="text-sm font-semibold text-[#2D2E2E]">
                                        Customer Notes
                                    </p>

                                    <p class="mt-1 text-sm leading-relaxed text-[#716969]">
                                        {{ $booking->notes }}
                                    </p>
                                </div>

                            @endif


                            {{-- Pending Actions --}}
                            @if ($booking->status === 'pending')

                                <div class="mt-6 flex flex-col gap-3 border-t
                                                    border-[#BCABAE]/30 pt-5
                                                    sm:flex-row sm:justify-end">

                                    {{-- Reject --}}
                                    <form method="POST" action="{{ route('provider.bookings.update-status', $booking) }}">

                                        @csrf
                                        @method('PATCH')

                                        <input type="hidden" name="status" value="rejected">

                                        <button type="submit"
                                            onclick="return confirm('Are you sure you want to reject this booking?')" class="w-full rounded-xl border border-red-200
                                                           bg-red-50 px-6 py-3 font-semibold
                                                           text-red-600 transition
                                                           hover:bg-red-100 sm:w-auto">

                                            Reject Booking
                                        </button>
                                    </form>


                                    {{-- Accept --}}
                                    <form method="POST" action="{{ route('provider.bookings.update-status', $booking) }}">

                                        @csrf
                                        @method('PATCH')

                                        <input type="hidden" name="status" value="confirmed">

                                        <button type="submit" class="w-full rounded-xl bg-[#2D2E2E]
                                                           px-6 py-3 font-semibold text-white
                                                           transition hover:bg-[#0F0F0F]
                                                           sm:w-auto">

                                            ✓ Accept Booking
                                        </button>
                                    </form>
                                </div>

                            @endif

                        </div>
                    </div>

                @empty

                    <div class="rounded-2xl border border-[#BCABAE]/40
                                    bg-white px-6 py-16 text-center shadow-sm">

                        <div class="mx-auto flex h-14 w-14 items-center justify-center
                                        rounded-2xl bg-[#BCABAE]/25 text-[#716969]">

                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5
                                           21h14a2 2 0 002-2V7a2 2 0
                                           00-2-2H5a2 2 0 00-2 2v12
                                           a2 2 0 002 2z" />
                            </svg>
                        </div>

                        <h3 class="mt-4 text-lg font-bold text-[#2D2E2E]">
                            No bookings yet
                        </h3>

                        <p class="mt-1 text-[#716969]">
                            Customer bookings for your services will appear here.
                        </p>

                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($bookings->hasPages())
                <div class="mt-8">
                    {{ $bookings->links() }}
                </div>
            @endif

        </div>
    </div>

</x-app-layout>