<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-[#192A51]">
                Book Service
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Choose your preferred date and available time.
            </p>
        </div>
    </x-slot>


    <div class="min-h-screen bg-[#FAF8FB] py-8">

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">


            <div class="bg-white border border-[#D5C6E0]/70
                        rounded-2xl shadow-sm overflow-hidden">

                {{-- Top Accent --}}
                <div class="h-1.5 bg-[#967AA1]"></div>


                <div class="p-6 md:p-8">


                    {{-- Service Heading --}}
                    <div class="mb-7">

                        <span class="inline-flex items-center
                                     px-3 py-1 rounded-full
                                     bg-[#F5E6E8]
                                     text-[#967AA1]
                                     text-xs font-semibold mb-3">

                            {{ $service->category->name }}

                        </span>

                        <h1 class="text-3xl font-bold text-[#192A51]">
                            Book {{ $service->name }}
                        </h1>

                        <p class="mt-2 text-gray-500">
                            with
                            <span class="font-semibold text-[#192A51]">
                                {{ $service->provider->name }}
                            </span>
                        </p>

                    </div>


                    {{-- Service Summary --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">

                        {{-- Category --}}
                        <div class="bg-[#FAF8FB]
                                    border border-[#D5C6E0]/60
                                    rounded-2xl p-5">

                            <div class="w-10 h-10 rounded-xl
                                        bg-[#F5E6E8]
                                        flex items-center justify-center
                                        text-[#967AA1] mb-3">

                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1.8">

                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h10M4 17h7">
                                    </path>

                                </svg>

                            </div>

                            <p class="text-xs text-gray-400">
                                Category
                            </p>

                            <p class="mt-1 font-semibold text-[#192A51]">
                                {{ $service->category->name }}
                            </p>

                        </div>


                        {{-- Price --}}
                        <div class="bg-[#FAF8FB]
                                    border border-[#D5C6E0]/60
                                    rounded-2xl p-5">

                            <div class="w-10 h-10 rounded-xl
                                        bg-[#D5C6E0]/60
                                        flex items-center justify-center
                                        text-[#967AA1] mb-3">

                                <span class="text-xs font-bold">
                                    LKR
                                </span>

                            </div>

                            <p class="text-xs text-gray-400">
                                Price
                            </p>

                            <p class="mt-1 text-lg font-bold text-[#192A51]">
                                LKR {{ number_format($service->price, 2) }}
                            </p>

                        </div>


                        {{-- Duration --}}
                        <div class="bg-[#FAF8FB]
                                    border border-[#D5C6E0]/60
                                    rounded-2xl p-5">

                            <div class="w-10 h-10 rounded-xl
                                        bg-[#D5C6E0]/60
                                        flex items-center justify-center
                                        text-[#967AA1] mb-3">

                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1.8">

                                    <circle cx="12" cy="12" r="9"></circle>

                                    <path stroke-linecap="round" d="M12 7v5l3 2">
                                    </path>

                                </svg>

                            </div>

                            <p class="text-xs text-gray-400">
                                Duration
                            </p>

                            <p class="mt-1 font-semibold text-[#192A51]">
                                {{ $service->duration_minutes }} minutes
                            </p>

                        </div>

                    </div>


                    {{-- Booking Form --}}
                    <form method="POST" action="{{ route('customer.bookings.store', $service) }}">

                        @csrf


                        <div class="border-t border-gray-100 pt-7">

                            <h2 class="text-lg font-bold text-[#192A51]">
                                Select Date & Time
                            </h2>

                            <p class="text-sm text-gray-500 mt-1 mb-6">
                                Available times will appear after you select a date.
                            </p>


                            {{-- Booking Date --}}
                            <div class="mb-6">

                                <label for="booking_date" class="block text-sm font-semibold text-[#192A51] mb-2">

                                    Booking Date

                                </label>

                                <input type="date" id="booking_date" name="booking_date"
                                    value="{{ old('booking_date') }}" min="{{ now()->format('Y-m-d') }}" required class="block w-full h-12 px-4
                                           rounded-xl
                                           border border-[#D5C6E0]
                                           bg-white
                                           text-[#192A51]
                                           focus:border-[#967AA1]
                                           focus:ring-[#967AA1]">


                                {{-- Holiday API message --}}
                                <div id="holiday-message" class="hidden mt-3 p-3 rounded-xl text-sm">
                                </div>


                                @error('booking_date')

                                    <p class="mt-2 text-sm text-red-600">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>


                            {{-- Available Time --}}
                            <div class="mb-6">

                                <label for="booking_time" class="block text-sm font-semibold text-[#192A51] mb-2">

                                    Available Time

                                </label>


                                <select id="booking_time" name="booking_time" required disabled class="block w-full h-12 px-4
                                           rounded-xl
                                           border border-[#D5C6E0]
                                           bg-white
                                           text-[#192A51]
                                           disabled:bg-gray-50
                                           disabled:text-gray-400
                                           focus:border-[#967AA1]
                                           focus:ring-[#967AA1]">

                                    <option value="">
                                        Select a booking date first
                                    </option>
                                </select>

                                <div class="flex items-start gap-2 mt-2">

                                    <svg class="w-4 h-4 mt-0.5 shrink-0
                                                text-[#967AA1]" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.8">

                                        <circle cx="12" cy="12" r="9"></circle>
                                        <path stroke-linecap="round" d="M12 8v4m0 4h.01">
                                        </path>
                                    </svg>

                                    <p id="slot_message" class="text-sm text-gray-500">

                                        Choose a date to view available time slots.
                                    </p>

                                </div>

                                @error('booking_time')

                                    <p class="mt-2 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Notes --}}
                            <div class="mb-7">
                                <div class="flex items-center justify-between mb-2">

                                    <label for="notes" class="text-sm font-semibold text-[#192A51]">
                                        Notes
                                    </label>

                                    <span class="text-xs text-gray-400">
                                        Optional
                                    </span>
                                </div>

                                <textarea id="notes" name="notes" rows="4" maxlength="1000"
                                    placeholder="Add any information for the service provider..." class="block w-full px-4 py-3
                                           rounded-xl
                                           border border-[#D5C6E0]
                                           bg-white
                                           text-[#192A51]
                                           placeholder:text-gray-400
                                           focus:border-[#967AA1]
                                           focus:ring-[#967AA1]">{{ old('notes') }}</textarea>


                                @error('notes')

                                    <p class="mt-2 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Booking Notice --}}
                            <div class="p-4 rounded-xl
                                        bg-[#F5E6E8]/60
                                        border border-[#D5C6E0]/60
                                        mb-7">

                                <div class="flex items-start gap-3">

                                    <div class="w-8 h-8 shrink-0
                                                rounded-lg bg-white
                                                flex items-center justify-center
                                                text-[#192A51]">

                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="1.8">

                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 8v4m0 4h.01M10.3 4.3 3.8 16a2 2 0 0 0 1.7 3h13a2 2 0 0 0 1.7-3L13.7 4.3a2 2 0 0 0-3.4 0Z">
                                            </path>
                                        </svg>
                                    </div>

                                    <p class="text-sm text-gray-600 leading-relaxed">
                                        Your booking will be submitted as
                                        <span class="font-semibold text-[#192A51]">
                                            pending
                                        </span>
                                        until the service provider accepts it.
                                    </p>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex flex-col-reverse sm:flex-row
                                        sm:justify-between gap-3">

                                <a href="{{ route('customer.services.show', $service) }}" class="inline-flex items-center justify-center
                                          px-5 py-3
                                          border border-[#D5C6E0]
                                          text-[#192A51]
                                          text-sm font-semibold
                                          rounded-xl
                                          hover:bg-[#F5E6E8]
                                          transition">

                                    ← Cancel

                                </a>

                                <button type="submit" class="inline-flex items-center justify-center gap-2
                                           px-7 py-3
                                           bg-[#192A51]
                                           text-white
                                           text-sm font-semibold
                                           rounded-xl
                                           hover:bg-[#967AA1]
                                           transition">

                                    Confirm Booking

                                    <span>→</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const dateInput = document.getElementById('booking_date');
        const holidayMessage = document.getElementById('holiday-message');
        const holidayCheckUrl = "{{ route('customer.holidays.check') }}";

        const timeSelect = document.getElementById('booking_time');
        const slotMessage = document.getElementById('slot_message');

        const slotsUrl = @json(
            route('customer.services.available-slots', $service)
        );

        const oldBookingTime = @json(old('booking_time'));

        async function loadAvailableSlots() {

            const selectedDate = dateInput.value;

            // No date selected yet
            if (!selectedDate) {
                timeSelect.innerHTML =
                    '<option value="">Select a booking date first</option>';

                timeSelect.disabled = true;

                slotMessage.textContent =
                    'Choose a date to view available time slots.';

                return;
            }

            timeSelect.disabled = true;

            timeSelect.innerHTML =
                '<option value="">Loading available times...</option>';

            slotMessage.textContent =
                'Checking provider availability...';

            try {

                const response = await fetch(
                    `${slotsUrl}?date=${encodeURIComponent(selectedDate)}`,
                    {
                        headers: {
                            'Accept': 'application/json'
                        }
                    }
                );

                if (!response.ok) {
                    throw new Error('Unable to load available slots.');
                }

                const data = await response.json();

                timeSelect.innerHTML = '';

                // Provider unavailable / no valid slots
                if (!data.slots || data.slots.length === 0) {

                    timeSelect.innerHTML =
                        '<option value="">No available times</option>';

                    timeSelect.disabled = true;

                    slotMessage.textContent =
                        data.message ??
                        'No available time slots for this date.';

                    return;
                }

                // Default option
                const defaultOption = document.createElement('option');

                defaultOption.value = '';
                defaultOption.textContent = 'Select an available time';

                timeSelect.appendChild(defaultOption);

                // Add available slots
                data.slots.forEach(function (slot) {

                    const option = document.createElement('option');

                    option.value = slot.value;
                    option.textContent = slot.label;

                    if (slot.value === oldBookingTime) {
                        option.selected = true;
                    }

                    timeSelect.appendChild(option);
                });

                timeSelect.disabled = false;

                slotMessage.textContent =
                    `${data.slots.length} available time slot(s).`;

            } catch (error) {

                timeSelect.innerHTML =
                    '<option value="">Unable to load times</option>';

                timeSelect.disabled = true;

                slotMessage.textContent =
                    'Could not load available times. Please try again.';

                console.error(error);
            }
        }

        // Reload slots whenever the customer changes the date
        dateInput.addEventListener('change', function () {
            loadAvailableSlots();
            checkHoliday(dateInput.value);
        });

        // Useful when Laravel redirects back with old input
        if (dateInput.value) {
            loadAvailableSlots();
            checkHoliday(dateInput.value);
        }


        async function checkHoliday(date) {
            holidayMessage.classList.add('hidden');
            holidayMessage.textContent = '';

            if (!date) {
                return;
            }

            try {
                const response = await fetch(
                    `${holidayCheckUrl}?date=${encodeURIComponent(date)}`,
                    {
                        headers: {
                            'Accept': 'application/json'
                        }
                    }
                );

                const data = await response.json();

                if (!response.ok || !data.available) {
                    holidayMessage.className =
                        'mt-3 p-3 rounded-md text-sm bg-gray-100 text-gray-700';

                    holidayMessage.textContent =
                        'Holiday information is currently unavailable.';

                    return;
                }

                if (data.holiday.is_holiday) {
                    holidayMessage.className =
                        'mt-3 p-3 rounded-md text-sm bg-yellow-100 text-yellow-800';

                    holidayMessage.textContent =
                        `Public Holiday: ${data.holiday.name}. You can still continue with your booking.`;
                } else {
                    holidayMessage.classList.add('hidden');
                }

            } catch (error) {
                holidayMessage.className =
                    'mt-3 p-3 rounded-md text-sm bg-gray-100 text-gray-700';

                holidayMessage.textContent =
                    'Holiday information is currently unavailable.';
            }
        }

    });
</script>