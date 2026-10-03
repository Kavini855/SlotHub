<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-[#2D2E2E]">
                My Services
            </h2>

            <p class="mt-1 text-sm text-[#716969]">
                Manage the services you offer to customers.
            </p>
        </div>
    </x-slot>

    <div class="bg-[#FBFBFB] min-h-screen py-10">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Page Heading --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">

                <div>
                    <h1 class="text-2xl font-bold text-[#2D2E2E]">
                        Your Services
                    </h1>

                    <p class="text-sm text-[#716969] mt-1">
                        View, edit and manage your available services.
                    </p>
                </div>

                <a href="{{ route('provider.services.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3
                           bg-[#2D2E2E] text-white font-semibold rounded-xl
                           hover:bg-[#0F0F0F] transition shadow-sm">

                    <span class="text-lg">+</span>
                    Add Service
                </a>
            </div>

            {{-- Success Message --}}
            @if (session('success'))

                <div class="mb-7 flex items-center gap-3 p-4
                                    bg-green-50 border border-green-200
                                    text-green-700 rounded-xl">

                    <div class="w-8 h-8 flex items-center justify-center
                                        rounded-lg bg-green-100 flex-shrink-0">
                        ✓
                    </div>

                    <p class="font-medium">
                        {{ session('success') }}
                    </p>
                </div>

            @endif

            {{-- Search / Filter --}}
            <div class="bg-white border border-[#BCABAE]/40
                        rounded-2xl shadow-sm p-5 mb-8">

                <form method="GET" action="{{ route('provider.services.index') }}"
                    class="grid grid-cols-1 md:grid-cols-12 gap-4">

                    {{-- Search --}}
                    <div class="md:col-span-5">

                        <label class="block text-sm font-semibold text-[#2D2E2E] mb-2">
                            Search
                        </label>

                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search your services..." class="w-full rounded-xl border-[#BCABAE]
                                   text-[#2D2E2E]
                                   focus:border-[#716969]
                                   focus:ring-[#716969]">

                    </div>

                    {{-- Category --}}
                    <div class="md:col-span-4">

                        <label class="block text-sm font-semibold text-[#2D2E2E] mb-2">
                            Category
                        </label>

                        <select name="category" class="w-full rounded-xl border-[#BCABAE]
                                   text-[#2D2E2E]
                                   focus:border-[#716969]
                                   focus:ring-[#716969]">

                            <option value="">
                                All Categories
                            </option>

                            @foreach ($categories as $category)

                                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>

                                    {{ $category->name }}

                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Button --}}
                    <div class="md:col-span-3 flex items-end gap-2">

                        <button type="submit" class="flex-1 px-5 py-2.5
                                   bg-[#2D2E2E] text-white
                                   font-semibold rounded-xl
                                   hover:bg-[#0F0F0F] transition">

                            Filter
                        </button>

                        @if (request('search') || request('category'))

                            <a href="{{ route('provider.services.index') }}" class="px-4 py-2.5 border border-[#BCABAE]
                                               text-[#716969] font-semibold
                                               rounded-xl hover:bg-[#BCABAE]/20 transition">

                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Services Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @forelse ($services as $service)

                    <div class="relative bg-white border border-[#BCABAE]/40
                                        rounded-2xl shadow-sm overflow-hidden
                                        flex flex-col">

                        {{-- Top Accent --}}
                        <div class="h-1.5 bg-[#716969]"></div>

                        <div class="p-6 flex flex-col flex-1">

                            {{-- Category + Status --}}
                            <div class="flex items-center justify-between gap-3 mb-5">

                                <span class="inline-flex px-3 py-1
                                                     bg-[#BCABAE]/25 text-[#716969]
                                                     text-xs font-semibold rounded-full">

                                    {{ $service->category->name }}

                                </span>

                                @if ($service->is_active)

                                    <span class="inline-flex items-center gap-2
                                                                 px-3 py-1
                                                                 bg-green-50 text-green-700
                                                                 text-xs font-semibold rounded-full">

                                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>

                                        Active
                                    </span>
                                @else

                                    <span class="inline-flex items-center gap-2
                                                                 px-3 py-1
                                                                 bg-red-50 text-red-600
                                                                 text-xs font-semibold rounded-full">

                                        <span class="w-2 h-2 bg-red-400 rounded-full"></span>
                                        Inactive
                                    </span>
                                @endif
                            </div>

                            {{-- Service Name --}}
                            <h3 class="text-xl font-bold text-[#2D2E2E]">
                                {{ $service->name }}
                            </h3>

                            {{-- Description --}}
                            <p class="mt-3 text-sm text-[#716969] leading-6 flex-1">
                                {{ \Illuminate\Support\Str::limit($service->description, 110) }}
                            </p>

                            {{-- Price / Duration --}}
                            <div class="grid grid-cols-2 gap-4 mt-6
                                                pt-5 border-t border-[#BCABAE]/25">

                                {{-- Price --}}
                                <div>
                                    <p class="text-xs text-[#716969]">
                                        Price
                                    </p>

                                    <p class="mt-1 text-lg font-bold text-[#2D2E2E]">
                                        LKR {{ number_format($service->price, 2) }}
                                    </p>
                                </div>

                                {{-- Duration --}}
                                <div class="text-right">

                                    <p class="text-xs text-[#716969]">
                                        Duration
                                    </p>

                                    <div class="mt-1 flex items-center justify-end gap-2">

                                        <svg class="w-5 h-5 text-[#716969]" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 2m6-2a9 9 0 11-18 0
                                                           9 9 0 0118 0z" />

                                        </svg>

                                        <span class="font-semibold text-[#2D2E2E]">
                                            {{ $service->duration_minutes }} min
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="grid grid-cols-2 gap-3 mt-6">

                                {{-- Edit --}}
                                <a href="{{ route('provider.services.edit', $service) }}" class="flex items-center justify-center gap-2
                                                   px-4 py-2.5
                                                   bg-[#2D2E2E] text-white
                                                   font-semibold rounded-xl
                                                   hover:bg-[#0F0F0F] transition">

                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0
                                                       002 2h11a2 2 0 002-2v-5
                                                       m-1.414-9.414a2 2 0 112.828
                                                       2.828L11.828 15H9v-2.828
                                                       l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>

                                {{-- Delete --}}
                                <form method="POST" action="{{ route('provider.services.destroy', $service) }}"
                                    onsubmit="return confirm('Are you sure you want to delete this service?');">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="w-full flex items-center justify-center gap-2
                                                       px-4 py-2.5
                                                       border border-red-200
                                                       bg-red-50 text-red-600
                                                       font-semibold rounded-xl
                                                       hover:bg-red-100 transition">

                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0
                                                           0116.138 21H7.862a2 2 0
                                                           01-1.995-1.858L5 7m5 4v6
                                                           m4-6v6m1-10V4a1 1 0
                                                           00-1-1h-4a1 1 0 00-1 1v3
                                                           M4 7h16" />

                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                @empty

                    <div class="md:col-span-2 lg:col-span-3
                                        bg-white border border-[#BCABAE]/40
                                        rounded-2xl p-12 text-center shadow-sm">

                        <div class="w-14 h-14 mx-auto
                                            rounded-xl bg-[#BCABAE]/25
                                            flex items-center justify-center mb-4">

                            <svg class="w-7 h-7 text-[#716969]" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0
                                               002 2h10a2 2 0 002-2V7a2 2 0
                                               00-2-2h-2M9 5a2 2 0 002 2h2
                                               a2 2 0 002-2M9 5a2 2 0
                                               012-2h2a2 2 0 012 2" />

                            </svg>
                        </div>

                        <h3 class="font-bold text-[#2D2E2E]">
                            No services found
                        </h3>

                        <p class="text-sm text-[#716969] mt-2">
                            Try changing your search or create a new service.
                        </p>
                    </div>

                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $services->links() }}
            </div>
        </div>
    </div>

</x-app-layout>