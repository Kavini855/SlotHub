<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-[#2D2E2E]">
                My Availability
            </h2>

            <p class="text-sm text-[#716969] mt-1">
                Set the days and times customers can book your services.
            </p>
        </div>
    </x-slot>

    <div class="py-10 bg-[#FBFBFB] min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Page Introduction --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-[#2D2E2E]">
                    Weekly Availability
                </h1>

                <p class="text-[#716969] mt-1">
                    Manage your regular working hours for each day of the week.
                </p>
            </div>

            {{-- Success Message --}}
            @if (session('success'))
                <div class="mb-7 flex items-center gap-4 rounded-xl border border-green-200 bg-green-50 px-5 py-4">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-green-100 text-green-700">
                        ✓
                    </div>

                    <p class="font-medium text-green-700">
                        {{ session('success') }}
                    </p>
                </div>
            @endif

            <form method="POST" action="{{ route('provider.availabilities.update') }}">

                @csrf
                @method('PUT')

                {{-- Main Availability Card --}}
                <div class="overflow-hidden rounded-2xl bg-white shadow-sm">

                    {{-- Dark Header --}}
                    <div class="flex items-center gap-4 bg-[#2D2E2E] px-7 py-6">

                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#716969] text-white">

                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                            </svg>

                        </div>

                        <div>
                            <h2 class="text-lg font-bold text-white">
                                Working Hours
                            </h2>

                            <p class="text-sm text-[#BCABAE] mt-1">
                                Enable the days you are available and choose your working hours.
                            </p>
                        </div>

                    </div>

                    {{-- Days --}}
                    <div class="p-5 bg-[#EEE9EA]">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            @foreach ($days as $dayNumber => $dayName)
                                                        @php
                                                            $availability = $availabilities->get($dayNumber);
                                                            $isActive = old(
                                                                "availabilities.$dayNumber.is_active",
                                                                $availability?->is_active ?? false
                                                            );
                                                        @endphp

                                                        {{-- Day Card --}}
                                                        <div
                                                            class="rounded-xl p-5 shadow-sm transition {{ $isActive ? 'bg-[#D9CCCF]' : 'bg-[#FBFBFB]' }}">

                                                            {{-- Day + Available --}}
                                                            <div class="flex items-center justify-between mb-5">

                                                                <div>
                                                                    <p class="text-lg font-bold text-[#2D2E2E]">
                                                                        {{ $dayName }}
                                                                    </p>

                                                                    <p class="text-xs text-[#716969] mt-1">
                                                                        Weekly schedule
                                                                    </p>
                                                                </div>

                                                                <label class="inline-flex cursor-pointer items-center gap-2">

                                                                    <input type="checkbox" name="availabilities[{{ $dayNumber }}][is_active]"
                                                                        value="1" @checked($isActive)
                                                                        class="h-5 w-5 rounded border-[#BCABAE] text-[#2D2E2E] focus:ring-[#716969]">

                                                                    <span class="text-sm font-medium text-[#2D2E2E]">
                                                                        Available
                                                                    </span>

                                                                </label>

                                                            </div>

                                                            {{-- Times --}}
                                                            <div class="grid grid-cols-2 gap-4">

                                                                {{-- Start Time --}}
                                                                <div>

                                                                    <label class="mb-2 block text-xs font-semibold text-[#716969]">
                                                                        Start Time
                                                                    </label>

                                                                    <input type="time" name="availabilities[{{ $dayNumber }}][start_time]" value="{{ old(
                                    "availabilities.$dayNumber.start_time",
                                    $availability?->start_time
                                    ? \Carbon\Carbon::parse($availability->start_time)->format('H:i')
                                    : '09:00'
                                ) }}" class="w-full rounded-xl border border-[#BCABAE] bg-white px-4 py-2.5 text-[#2D2E2E] focus:border-[#716969] focus:ring-[#716969]">

                                                                    @error("availabilities.$dayNumber.start_time")
                                                                        <p class="mt-1 text-xs text-red-600">
                                                                            {{ $message }}
                                                                        </p>
                                                                    @enderror

                                                                </div>

                                                                {{-- End Time --}}
                                                                <div>

                                                                    <label class="mb-2 block text-xs font-semibold text-[#716969]">
                                                                        End Time
                                                                    </label>

                                                                    <input type="time" name="availabilities[{{ $dayNumber }}][end_time]" value="{{ old(
                                    "availabilities.$dayNumber.end_time",
                                    $availability?->end_time
                                    ? \Carbon\Carbon::parse($availability->end_time)->format('H:i')
                                    : '17:00'
                                ) }}" class="w-full rounded-xl border border-[#BCABAE] bg-white px-4 py-2.5
                                                                                                                                                    text-[#2D2E2E] focus:border-[#716969] focus:ring-[#716969]">

                                                                    @error("availabilities.$dayNumber.end_time")
                                                                        <p class="mt-1 text-xs text-red-600">
                                                                            {{ $message }}
                                                                        </p>
                                                                    @enderror

                                                                </div>

                                                            </div>

                                                        </div>

                            @endforeach

                        </div>
                    </div>

                    {{-- Information --}}
                    <div class="border-t border-[#BCABAE]/40 bg-[#BCABAE]/20 px-7 py-5">

                        <div class="flex items-start gap-3">

                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-[#716969]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>

                            <div>
                                <p class="text-sm font-semibold text-[#2D2E2E]">
                                    Booking availability
                                </p>

                                <p class="mt-1 text-sm text-[#716969]">
                                    Customers will only see booking times that fall within your active availability.
                                </p>
                            </div>

                        </div>

                    </div>

                    {{-- Save --}}
                    <div class="flex justify-end border-t border-[#BCABAE]/30 bg-white px-7 py-6">

                        <button type="submit"
                            class="rounded-xl bg-[#2D2E2E] px-6 py-3 font-semibold text-white shadow-sm transition hover:bg-[#0F0F0F]">

                            Save Availability

                        </button>

                    </div>

                </div>

            </form>

        </div>
    </div>

</x-app-layout>