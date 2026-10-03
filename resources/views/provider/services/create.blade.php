<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-bold text-xl text-[#2D2E2E] leading-tight">
                Add Service
            </h2>
            <p class="text-sm text-[#716969] mt-1">
                Create a new service for your customers.
            </p>
        </div>
    </x-slot>

    <div class="py-10 bg-[#FBFBFB] min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- Page Heading --}}
            <div class="mb-7">
                <h1 class="text-2xl font-bold text-[#2D2E2E]">
                    Create a New Service
                </h1>

                <p class="text-[#716969] mt-1">
                    Enter the service information below to add it to SlotHub.
                </p>
            </div>

            {{-- Form Card --}}
            <div class="bg-white border border-[#BCABAE]/40 rounded-2xl shadow-sm overflow-hidden">

                {{-- Card Header --}}
                <div class="px-7 py-6 border-b border-[#BCABAE]/30">
                    <div class="flex items-center gap-4">

                        <div class="w-11 h-11 rounded-xl bg-[#BCABAE]/25 flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#2D2E2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                        </div>

                        <div>
                            <h2 class="text-lg font-bold text-[#2D2E2E]">
                                Service Information
                            </h2>

                            <p class="text-sm text-[#716969]">
                                Provide the basic details of the service you offer.
                            </p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('provider.services.store') }}" class="p-7">

                    @csrf

                    {{-- Service Name --}}
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-semibold text-[#2D2E2E] mb-2">
                            Service Name
                        </label>

                        <input id="name" type="text" name="name" value="{{ old('name') }}"
                            placeholder="e.g. Web Development Consultation" required class="w-full rounded-xl border border-[#BCABAE] bg-[#FBFBFB]
       px-4 py-3 text-[#2D2E2E] focus:border-[#716969] focus:ring-[#716969]">

                        @error('name')
                            <p class="text-red-600 text-sm mt-2">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Category --}}
                    <div class="mb-6">
                        <label for="category_id" class="block text-sm font-semibold text-[#2D2E2E] mb-2">
                            Category
                        </label>

                        <select id="category_id" name="category_id" required class="block w-full px-4 py-3 rounded-xl border border-[#BCABAE]
               bg-[#FBFBFB] text-[#2D2E2E]
               focus:border-[#716969] focus:ring-[#716969]">

                            <option value="">Select a category</option>

                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('category_id')
                            <p class="text-red-600 text-sm mt-2">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-semibold text-[#2D2E2E] mb-2">
                            Description
                        </label>

                        <textarea id="description" name="description" rows="5" required
                            placeholder="Describe what customers can expect from this service..."
                            class="w-full rounded-xl border border-[#BCABAE] bg-[#FBFBFB] px-4 py-3 text-[#2D2E2E] focus:border-[#716969] focus:ring-[#716969]">{{ old('description') }}</textarea>

                        @error('description')
                            <p class="text-red-600 text-sm mt-2">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Price + Duration --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-7">

                        {{-- Price --}}
                        <div>
                            <label for="price" class="block text-sm font-semibold text-[#2D2E2E] mb-2">
                                Price (LKR)
                            </label>

                            <input id="price" type="number" name="price" value="{{ old('price') }}" min="0" step="0.01"
                                placeholder="e.g. 2500.00" required class="block w-full px-4 py-3 rounded-xl
                   border border-[#BCABAE] bg-[#FBFBFB]
                   text-[#2D2E2E]
                   focus:border-[#716969]
                   focus:ring-[#716969]">

                            @error('price')
                                <p class="text-red-600 text-sm mt-2">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Duration --}}
                        <div>
                            <label for="duration_minutes" class="block text-sm font-semibold text-[#2D2E2E] mb-2">
                                Duration (Minutes)
                            </label>

                            <input id="duration_minutes" type="number" name="duration_minutes"
                                value="{{ old('duration_minutes') }}" min="1" placeholder="e.g. 60" required class="block w-full px-4 py-3 rounded-xl
                   border border-[#BCABAE] bg-[#FBFBFB]
                   text-[#2D2E2E]
                   focus:border-[#716969]
                   focus:ring-[#716969]">

                            @error('duration_minutes')
                                <p class="text-red-600 text-sm mt-2">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>

                    {{-- Information Box --}}
                    <div class="bg-[#BCABAE]/15 border border-[#BCABAE]/30
                                rounded-xl p-4 mb-7 flex gap-3">

                        <svg class="w-5 h-5 text-[#716969] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0
                                   9 9 0 0118 0z" />
                        </svg>

                        <div>
                            <p class="font-semibold text-sm text-[#2D2E2E]">
                                Service details
                            </p>

                            <p class="text-sm text-[#716969] mt-1">
                                Customers will see this information when browsing
                                and booking your service.
                            </p>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-end gap-3
                                pt-6 border-t border-[#BCABAE]/30">

                        <a href="{{ route('provider.services.index') }}" class="px-6 py-3 rounded-xl border border-[#BCABAE]
                                   text-[#2D2E2E] font-semibold
                                   hover:bg-[#BCABAE]/15 transition">
                            Cancel
                        </a>

                        <button type="submit" class="px-7 py-3 rounded-xl bg-[#2D2E2E]
                                   text-white font-semibold
                                   hover:bg-[#0F0F0F] transition">
                            + Create Service
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>