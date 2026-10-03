<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-[#192A51]">
                Service Details
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Review the service information before booking.
            </p>
        </div>
    </x-slot>


    <div class="min-h-screen bg-[#FAF8FB] py-8">

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Service Card --}}
            <div class="bg-white border border-[#D5C6E0]/70
                        rounded-2xl shadow-sm overflow-hidden">

                {{-- Top Accent --}}
                <div class="h-1.5 bg-[#967AA1]"></div>


                <div class="p-6 md:p-8">

                    {{-- Top Section --}}
                    <div class="flex flex-col sm:flex-row
                                sm:items-start sm:justify-between gap-4">

                        <div>

                            {{-- Category --}}
                            <span class="inline-flex items-center
                                         px-3 py-1 rounded-full
                                         bg-[#F5E6E8] text-[#967AA1]
                                         text-xs font-semibold mb-4">

                                {{ $service->category->name }}

                            </span>


                            {{-- Service Name --}}
                            <h1 class="text-3xl font-bold text-[#192A51]">
                                {{ $service->name }}
                            </h1>

                        </div>


                        {{-- Available Badge --}}
                        <span class="inline-flex items-center gap-2
                                     self-start px-3 py-1.5
                                     bg-green-50 text-green-700
                                     text-xs font-semibold rounded-full">

                            <span class="w-2 h-2 rounded-full bg-green-500"></span>

                            Available

                        </span>

                    </div>


                    {{-- Description --}}
                    <div class="mt-8">

                        <h3 class="text-sm font-semibold text-[#192A51]">
                            About this service
                        </h3>

                        <p class="mt-2 text-gray-500 leading-relaxed">
                            {{ $service->description }}
                        </p>

                    </div>


                    {{-- Service Information --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">

                        {{-- Provider --}}
                        <div class="bg-[#FAF8FB]
                                    border border-[#D5C6E0]/60
                                    rounded-2xl p-5">

                            <div class="w-10 h-10 rounded-xl
                                        bg-[#D5C6E0]/60
                                        flex items-center justify-center
                                        text-[#192A51] mb-4">

                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1.8">

                                    <circle cx="12" cy="8" r="4"></circle>

                                    <path stroke-linecap="round" d="M5 21a7 7 0 0 1 14 0">
                                    </path>

                                </svg>

                            </div>

                            <p class="text-xs text-gray-400">
                                Provider
                            </p>

                            <p class="mt-1 font-semibold text-[#192A51]">
                                {{ $service->provider->name }}
                            </p>

                        </div>


                        {{-- Price --}}
                        <div class="bg-[#FAF8FB]
                                    border border-[#D5C6E0]/60
                                    rounded-2xl p-5">

                            <div class="w-10 h-10 rounded-xl
                                        bg-[#F5E6E8]
                                        flex items-center justify-center
                                        text-[#967AA1] mb-4">

                                <span class="text-sm font-bold">
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
                                        text-[#967AA1] mb-4">

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


                    {{-- Booking Information --}}
                    <div class="mt-8 p-5 rounded-2xl
                                bg-[#F5E6E8]/60
                                border border-[#D5C6E0]/60">

                        <div class="flex items-start gap-3">

                            <div class="w-9 h-9 shrink-0 rounded-xl
                                        bg-white flex items-center justify-center
                                        text-[#192A51]">

                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1.8">

                                    <rect x="4" y="5" width="16" height="15" rx="2"></rect>

                                    <path d="M8 3v4M16 3v4M4 10h16"></path>

                                </svg>

                            </div>

                            <div>

                                <p class="font-semibold text-[#192A51]">
                                    Ready to book?
                                </p>

                                <p class="text-sm text-gray-500 mt-1">
                                    Continue to choose an available date and time
                                    for this service.
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Actions --}}
                    <div class="mt-8 flex flex-col-reverse sm:flex-row
                                sm:items-center sm:justify-between gap-3">

                        <a href="{{ route('customer.services.index') }}" class="inline-flex items-center justify-center gap-2
                                  px-5 py-3
                                  bg-white
                                  border border-[#D5C6E0]
                                  text-[#192A51]
                                  text-sm font-semibold
                                  rounded-xl
                                  hover:bg-[#F5E6E8]
                                  transition">

                            ← Back to Services

                        </a>


                        <a href="{{ route('customer.bookings.create', $service) }}" class="inline-flex items-center justify-center gap-2
                                  px-7 py-3
                                  bg-[#192A51]
                                  text-white
                                  text-sm font-semibold
                                  rounded-xl
                                  hover:bg-[#967AA1]
                                  transition">

                            Book Service

                            <span>→</span>

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>