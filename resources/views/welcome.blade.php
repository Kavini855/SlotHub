<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SlotHub – Online Service Booking</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <meta name="description"
        content="SlotHub connects customers with service providers. Find a service, pick a time, and you're booked.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-white text-gray-900">

    {{-- Navigation --}}
    <nav class="border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <a href="/" class="flex items-center gap-2 font-semibold text-xl tracking-tight text-gray-900">
                    <svg class="w-6 h-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z" />
                    </svg>
                    SlotHub
                </a>

                {{-- Nav links --}}
                <div class="hidden sm:flex items-center gap-6 text-sm font-medium text-gray-600">
                    @auth
                        @if (Auth::user()->role === 'customer')
                            <a href="{{ route('customer.services.index') }}" class="hover:text-gray-900 transition-colors">
                                Services
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="hover:text-gray-900 transition-colors">
                            Services
                        </a>
                    @endauth
                    <a href="#how-it-works" class="hover:text-gray-900 transition-colors">How It Works</a>
                </div>

                {{-- Auth links --}}
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">Log in</a>
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 transition-colors">
                            Get started
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-16">
        <div class="max-w-2xl">
            <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 leading-tight tracking-tight">
                Find a service.<br>Pick a time.<br><span class="text-indigo-600">You're booked.</span>
            </h1>
            <p class="mt-5 text-lg text-gray-600 leading-relaxed">
                SlotHub connects you with local service providers. Browse services, check live availability, and book
                appointments instantly — no phone calls required.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                @auth
                    @if (Auth::user()->role === 'customer')
                        <a href="{{ route('customer.services.index') }}"
                            class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 transition-colors">
                            Browse services
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 transition-colors">
                        Browse services
                    </a>
                @endauth
                @guest
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-gray-700 border border-gray-300 rounded-md hover:border-gray-400 hover:bg-gray-50 transition-colors">
                        Register as a provider
                    </a>
                @endguest
            </div>
        </div>
    </section>

    {{-- Categories placeholder – will be populated in Phase 4 --}}
    <section class="bg-gray-50 border-t border-gray-200 py-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-sm font-semibold uppercase tracking-widest text-gray-500 mb-8">Service categories</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ([
                        ['icon' => '✂️', 'label' => 'Hair & Beauty'],
                        ['icon' => '💪', 'label' => 'Fitness'],
                        ['icon' => '📋', 'label' => 'Consultation'],
                        ['icon' => '📚', 'label' => 'Tutoring'],
                        ['icon' => '📷', 'label' => 'Photography'],
                        ['icon' => '🧘', 'label' => 'Wellness'],
                        ['icon' => '🔧', 'label' => 'Repair'],
                        ['icon' => '⭐', 'label' => 'More'],
                    ] as $category)
                    <div
                        class="flex items-center gap-3 p-4 bg-white border border-gray-200 rounded-lg hover:border-indigo-300 hover:bg-indigo-50 transition-colors cursor-pointer">
                        <span class="text-2xl">{{ $category['icon'] }}</span>
                        <span class="text-sm font-medium text-gray-800">{{ $category['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- How it works --}}
    <section id="how-it-works" class="py-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-10">How SlotHub works</h2>
            <div class="grid sm:grid-cols-3 gap-8">
                <div>
                    <div class="text-3xl font-bold text-indigo-600 mb-3">1</div>
                    <h3 class="font-semibold text-gray-900 mb-2">Browse services</h3>
                    <p class="text-sm text-gray-600">Search by category, location, or service type. View pricing,
                        duration, and provider details.</p>
                </div>
                <div>
                    <div class="text-3xl font-bold text-indigo-600 mb-3">2</div>
                    <h3 class="font-semibold text-gray-900 mb-2">Pick a slot</h3>
                    <p class="text-sm text-gray-600">Choose a date and see real-time available slots based on the
                        provider's schedule — no guessing.</p>
                </div>
                <div>
                    <div class="text-3xl font-bold text-indigo-600 mb-3">3</div>
                    <h3 class="font-semibold text-gray-900 mb-2">You're confirmed</h3>
                    <p class="text-sm text-gray-600">Receive a booking confirmation. The provider confirms, and you get
                        notified instantly.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA for providers --}}
    <section class="bg-indigo-600 py-14">
        <div
            class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
            <div>
                <h2 class="text-xl font-bold text-white">Are you a service provider?</h2>
                <p class="text-indigo-200 text-sm mt-1">List your services, manage your schedule, and accept bookings
                    online.</p>
            </div>
            @guest
                <a href="{{ route('register') }}"
                    class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-indigo-600 bg-white rounded-md hover:bg-indigo-50 transition-colors whitespace-nowrap">
                    Join as a provider
                </a>
            @endguest
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-gray-200 py-8">
        <div
            class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-500">
            <span class="font-semibold text-gray-700">SlotHub</span>
            <span>&copy; {{ date('Y') }} SlotHub. Online Service Booking Platform.</span>
        </div>
    </footer>

</body>

</html>