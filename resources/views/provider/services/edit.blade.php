<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="font-bold text-xl text-[#2D2E2E] leading-tight">
                Edit Service
            </h2>
            <p class="text-sm text-[#716969] mt-1">
                Update your service information.
            </p>
        </div>
    </x-slot>

    <div class="py-10 bg-[#FBFBFB] min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- Page Heading --}}
            <div class="mb-7">
                <h1 class="text-2xl font-bold text-[#2D2E2E]">
                    Edit Service
                </h1>

                <p class="text-[#716969] mt-1">
                    Update the information customers see when viewing this service.
                </p>
            </div>

            {{-- Validation Summary --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200
                                        text-red-700 rounded-xl">
                    <p class="font-semibold mb-2">
                        Please check the following:
                    </p>

                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form Card --}}
            <div class="bg-white border border-[#BCABAE]/40
                        rounded-2xl shadow-sm overflow-hidden">

                {{-- Card Header --}}
                <div class="px-7 py-6 border-b border-[#BCABAE]/30">

                    <div class="flex items-center gap-4">

                        <div class="w-11 h-11 rounded-xl bg-[#BCABAE]/25
                                    flex items-center justify-center">

                            <svg class="w-5 h-5 text-[#2D2E2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                                       m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828
                                       l8.586-8.586z" />
                            </svg>

                        </div>

                        <div>
                            <h2 class="text-lg font-bold text-[#2D2E2E]">
                                Service Information
                            </h2>

                            <p class="text-sm text-[#716969]">
                                Modify the details of your existing service.
                            </p>
                        </div>

                    </div>
                </div>

                <form method="POST" action="{{ route('provider.services.update', $service) }}" class="p-7">

                    @csrf
                    @method('PUT')

                    {{-- Service Name --}}
                    <div class="mb-6">

                        <label for="name" class="block text-sm font-semibold
                                   text-[#2D2E2E] mb-2">
                            Service Name
                        </label>

                        <input type="text" id="name" name="name" value="{{ old('name', $service->name) }}" required
                            class="block w-full px-4 py-3 rounded-xl
                                   border border-[#BCABAE]
                                   bg-[#FBFBFB] text-[#2D2E2E]
                                   focus:border-[#716969]
                                   focus:ring-[#716969]">

                        @error('name')
                            <p class="text-red-600 text-sm mt-2">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Category --}}
                    <div class="mb-6">

                        <label for="category_id" class="block text-sm font-semibold
                                   text-[#2D2E2E] mb-2">
                            Category
                        </label>

                        <select id="category_id" name="category_id" required class="block w-full px-4 py-3 rounded-xl
                                   border border-[#BCABAE]
                                   bg-[#FBFBFB] text-[#2D2E2E]
                                   focus:border-[#716969]
                                   focus:ring-[#716969]">

                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(
                                    old(
                                        'category_id',
                                        $service->category_id
                                    ) == $category->id
                                )>
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

                        <label for="description" class="block text-sm font-semibold
                                   text-[#2D2E2E] mb-2">
                            Description
                        </label>

                        <textarea id="description" name="description" rows="5" required class="block w-full px-4 py-3 rounded-xl
                                   border border-[#BCABAE]
                                   bg-[#FBFBFB] text-[#2D2E2E]
                                   focus:border-[#716969]
                                   focus:ring-[#716969]">{{ old('description', $service->description) }}</textarea>

                        @error('description')
                            <p class="text-red-600 text-sm mt-2">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Price + Duration --}}
                    <div class="grid grid-cols-1 md:grid-cols-2
                                gap-6 mb-7">

                        {{-- Price --}}
                        <div>

                            <label for="price" class="block text-sm font-semibold
                                       text-[#2D2E2E] mb-2">
                                Price (LKR)
                            </label>

                            <input type="number" id="price" name="price" step="0.01" min="0"
                                value="{{ old('price', $service->price) }}" required class="block w-full px-4 py-3 rounded-xl
                                       border border-[#BCABAE]
                                       bg-[#FBFBFB] text-[#2D2E2E]
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

                            <label for="duration_minutes" class="block text-sm font-semibold
                                       text-[#2D2E2E] mb-2">
                                Duration (Minutes)
                            </label>

                            <input type="number" id="duration_minutes" name="duration_minutes" min="1"
                                value="{{ old('duration_minutes', $service->duration_minutes) }}" required class="block w-full px-4 py-3 rounded-xl
                                       border border-[#BCABAE]
                                       bg-[#FBFBFB] text-[#2D2E2E]
                                       focus:border-[#716969]
                                       focus:ring-[#716969]">

                            @error('duration_minutes')
                                <p class="text-red-600 text-sm mt-2">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>
                    </div>

                    {{-- Service Status --}}
                    <div class="mb-7 p-5 bg-[#BCABAE]/15
                                border border-[#BCABAE]/30 rounded-xl">

                        <div class="flex items-center justify-between gap-5">

                            <div>
                                <p class="font-semibold text-[#2D2E2E]">
                                    Service Status
                                </p>

                                <p class="text-sm text-[#716969] mt-1">
                                    Active services are available for customers
                                    to browse and book.
                                </p>
                            </div>

                            <label class="flex items-center gap-3 cursor-pointer">

                                {{-- ensures unchecked checkbox sends 0 --}}
                                <input type="hidden" name="is_active" value="0">

                                <input type="checkbox" id="is_active" name="is_active" value="1" @checked(
                                    old(
                                        'is_active',
                                        $service->is_active
                                    )
                                ) class="w-5 h-5 rounded
                                           border-[#BCABAE]
                                           text-[#2D2E2E]
                                           focus:ring-[#716969]">

                                <span class="font-semibold text-sm
                                             text-[#2D2E2E]">
                                    Active
                                </span>

                            </label>
                        </div>
                    </div>

                    {{-- Information Box --}}
                    <div class="bg-[#BCABAE]/15
                                border border-[#BCABAE]/30
                                rounded-xl p-4 mb-7 flex gap-3">

                        <svg class="w-5 h-5 text-[#716969]
                                    mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01
                                   M21 12a9 9 0 11-18 0
                                   9 9 0 0118 0z" />
                        </svg>

                        <div>
                            <p class="font-semibold text-sm text-[#2D2E2E]">
                                Updating this service
                            </p>

                            <p class="text-sm text-[#716969] mt-1">
                                Your changes will be reflected when customers
                                browse this service.
                            </p>
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-center justify-end gap-3
                                pt-6 border-t border-[#BCABAE]/30">

                        <a href="{{ route('provider.services.index') }}" class="px-6 py-3 rounded-xl
                                   border border-[#BCABAE]
                                   text-[#2D2E2E] font-semibold
                                   hover:bg-[#BCABAE]/15 transition">
                            Cancel
                        </a>

                        <button type="submit" class="px-7 py-3 rounded-xl
                                   bg-[#2D2E2E] text-white font-semibold
                                   hover:bg-[#0F0F0F] transition">
                            Update Service
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>