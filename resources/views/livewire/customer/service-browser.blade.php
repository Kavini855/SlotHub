<div class="min-h-screen bg-[#FAF8FB] py-8">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Page Introduction --}}
        <div class="mb-7">
            <h1 class="text-2xl font-bold text-[#192A51]">
                Find a Service
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Explore available services from SlotHub providers.
            </p>
        </div>

        {{-- Search & Filter --}}
        <div class="bg-white border border-[#D5C6E0]/60 rounded-2xl shadow-sm p-5 mb-7">

            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">

                {{-- Search --}}
                <div class="relative md:col-span-5">

                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center
                                pointer-events-none text-[#967AA1]">

                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <circle cx="11" cy="11" r="7"></circle>
                            <path stroke-linecap="round" d="m20 20-4-4"></path>
                        </svg>

                    </div>

                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search services..." class="block w-full h-12 pl-11 pr-4 rounded-xl
                               border border-[#D5C6E0]
                               bg-white text-[#192A51]
                               placeholder:text-gray-400
                               focus:border-[#967AA1]
                               focus:ring-[#967AA1]">
                </div>

                {{-- Category --}}
                <div class="md:col-span-4">

                    <select wire:model.live="category" class="block w-full h-12 px-4 rounded-xl
                               border border-[#D5C6E0]
                               bg-white text-[#192A51]
                               focus:border-[#967AA1]
                               focus:ring-[#967AA1]">

                        <option value="">All Categories</option>

                        @foreach ($categories as $categoryItem)
                            <option value="{{ $categoryItem->id }}">
                                {{ $categoryItem->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- Clear --}}
                <div class="md:col-span-3">
                    <button type="button" wire:click="clearFilters" class="flex w-full h-12 items-center justify-center
                               bg-[#F5E6E8] text-[#967AA1]
                               text-sm font-semibold rounded-xl
                               hover:bg-[#D5C6E0] transition">
                        Clear
                    </button>
                </div>

            </div>

        </div>

        {{-- Loading indicator --}}
        <div wire:loading class="mb-4 text-sm font-medium text-[#967AA1]">
            Updating services...
        </div>

        {{-- Service Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @forelse ($services as $service)

                <div wire:key="service-{{ $service->id }}" class="group bg-white
                                   border border-[#D5C6E0]/70
                                   rounded-2xl
                                   shadow-sm
                                   hover:shadow-md
                                   hover:-translate-y-1
                                   transition duration-200
                                   overflow-hidden">

                    {{-- Card Top Accent --}}
                    <div class="h-1.5 bg-[#967AA1]"></div>

                    <div class="p-6 flex flex-col h-full">

                        {{-- Category + Availability --}}
                        <div class="flex items-center justify-between gap-3 mb-4">

                            <span class="inline-flex items-center
                                                 px-3 py-1 rounded-full
                                                 bg-[#F5E6E8] text-[#967AA1]
                                                 text-xs font-semibold">
                                {{ $service->category->name }}
                            </span>

                            <span class="inline-flex items-center gap-1.5
                                                 px-3 py-1 rounded-full
                                                 bg-green-50 text-green-700
                                                 text-xs font-semibold">

                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>

                                Available
                            </span>

                        </div>

                        {{-- Service Name --}}
                        <h3 class="text-xl font-bold text-[#192A51]
                                           group-hover:text-[#967AA1] transition">
                            {{ $service->name }}
                        </h3>

                        {{-- Description --}}
                        <p class="mt-3 text-sm text-gray-500 leading-relaxed min-h-[42px]">
                            {{ \Illuminate\Support\Str::limit($service->description, 100) }}
                        </p>

                        {{-- Provider --}}
                        <div class="flex items-center gap-3 mt-5">

                            <div class="w-9 h-9 rounded-xl
                                                bg-[#D5C6E0]/60
                                                flex items-center justify-center
                                                text-[#192A51]">

                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1.8">
                                    <circle cx="12" cy="8" r="4"></circle>
                                    <path stroke-linecap="round" d="M5 21a7 7 0 0 1 14 0"></path>
                                </svg>

                            </div>

                            <div>
                                <p class="text-xs text-gray-400">
                                    Provider
                                </p>

                                <p class="text-sm font-semibold text-[#192A51]">
                                    {{ $service->provider->name }}
                                </p>
                            </div>

                        </div>

                        <div class="border-t border-gray-100 my-5"></div>

                        {{-- Price + Duration --}}
                        <div class="flex items-center justify-between gap-4">

                            <div>
                                <p class="text-xs text-gray-400">Price</p>

                                <p class="text-lg font-bold text-[#192A51]">
                                    LKR {{ number_format($service->price, 2) }}
                                </p>
                            </div>

                            <div class="text-right">
                                <p class="text-xs text-gray-400">Duration</p>

                                <div class="flex items-center gap-1.5
                                                    text-sm font-semibold text-[#967AA1]">

                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.8">
                                        <circle cx="12" cy="12" r="9"></circle>
                                        <path stroke-linecap="round" d="M12 7v5l3 2"></path>
                                    </svg>

                                    {{ $service->duration_minutes }} min
                                </div>
                            </div>

                        </div>

                        {{-- View Button --}}
                        <div class="mt-6">

                            <a href="{{ route('customer.services.show', $service) }}" class="flex items-center justify-center gap-2
                                               w-full px-4 py-3
                                               bg-[#192A51] text-white
                                               text-sm font-semibold rounded-xl
                                               hover:bg-[#967AA1] transition">

                                View Service

                                <span class="group-hover:translate-x-1 transition">
                                    →
                                </span>

                            </a>

                        </div>

                    </div>

                </div>

            @empty

                <div class="md:col-span-2 lg:col-span-3">

                    <div class="bg-white border border-[#D5C6E0]/60
                                        rounded-2xl py-16 px-6 text-center">

                        <div class="w-14 h-14 mx-auto rounded-2xl
                                            bg-[#D5C6E0]/50
                                            flex items-center justify-center
                                            text-[#192A51] mb-4">

                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                <circle cx="11" cy="11" r="7"></circle>
                                <path stroke-linecap="round" d="m20 20-4-4"></path>
                            </svg>

                        </div>

                        <h3 class="font-semibold text-[#192A51]">
                            No services found
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Try changing your search or category filter.
                        </p>

                    </div>

                </div>

            @endforelse

        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $services->links() }}
        </div>

    </div>

</div>