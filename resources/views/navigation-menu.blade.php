<nav x-data="{ open: false }"
    class="{{ Auth::user()->role === 'customer' ? 'bg-[#192A51]' : 'bg-[#2D2E2E]' }} shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <img src="{{ asset('images/slothub-logo-dark.png') }}" alt="SlotHub"
                            class="h-14 w-auto object-contain">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">

                    @if (Auth::user()->role === 'customer')

                        <x-nav-link href="{{ route('customer.dashboard') }}"
                            :active="request()->routeIs('customer.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>

                        <x-nav-link href="{{ route('customer.services.index') }}"
                            :active="request()->routeIs('customer.services.*')">
                            {{ __('Browse Services') }}
                        </x-nav-link>

                        <x-nav-link href="{{ route('customer.bookings.index') }}"
                            :active="request()->routeIs('customer.bookings.*')">
                            {{ __('My Bookings') }}
                        </x-nav-link>

                    @elseif (Auth::user()->role === 'provider')

                        <x-nav-link href="{{ route('provider.dashboard') }}"
                            :active="request()->routeIs('provider.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>

                        <x-nav-link href="{{ route('provider.services.index') }}"
                            :active="request()->routeIs('provider.services.*')">
                            {{ __('My Services') }}
                        </x-nav-link>

                        <x-nav-link href="{{ route('provider.bookings.index') }}"
                            :active="request()->routeIs('provider.bookings.*')">
                            {{ __('Bookings') }}
                        </x-nav-link>

                        <x-nav-link href="{{ route('provider.availabilities.index') }}"
                            :active="request()->routeIs('provider.availabilities.*')">
                            {{ __('Availability') }}
                        </x-nav-link>

                    @endif

                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @if (in_array(Auth::user()->role, ['customer', 'provider']))
                    <!-- Notifications -->
                    <div class="relative me-3">
                        <x-dropdown align="right" width="80">
                            <x-slot name="trigger">
                                <button type="button"
                                    class="relative inline-flex items-center justify-center p-2 text-gray-300 hover:text-white transition focus:outline-none"
                                    aria-label="Notifications">

                                    <!-- Bell Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                                    </svg>

                                    @if (Auth::user()->unreadNotifications->count() > 0)
                                        <span
                                            class="absolute -top-1 -right-1 min-w-5 h-5 px-1 flex items-center justify-center rounded-full bg-red-500 text-white text-xs font-bold">
                                            {{ Auth::user()->unreadNotifications->count() }}
                                        </span>
                                    @endif
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-80 max-h-96 overflow-y-auto">

                                    <div class="px-4 py-3 border-b border-gray-200">
                                        <p class="font-semibold text-[#192A51]">
                                            Notifications
                                        </p>

                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ Auth::user()->unreadNotifications->count() }}
                                            unread
                                        </p>
                                    </div>

                                    @forelse (Auth::user()->notifications()->latest()->take(5)->get() as $notification)
                                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="block w-full text-left px-4 py-3 border-b border-gray-100 hover:bg-gray-50 transition
                                                                {{ is_null($notification->read_at) ? 'bg-gray-50' : '' }}">
                                                <p class="text-sm font-medium text-gray-800">
                                                    {{ $notification->data['message'] ?? 'New notification' }}
                                                </p>

                                                @if (isset($notification->data['service_name']))
                                                    <p class="text-xs text-gray-600 mt-1">
                                                        {{ $notification->data['service_name'] }}
                                                    </p>
                                                @endif

                                                @if (isset($notification->data['customer_name']))
                                                    <p class="text-xs text-gray-500">
                                                        Customer:
                                                        {{ $notification->data['customer_name'] }}
                                                    </p>
                                                @endif

                                                @if (
                                                        isset($notification->data['booking_date']) &&
                                                        isset($notification->data['booking_time'])
                                                    )
                                                    <p class="text-xs text-gray-500">
                                                        {{ $notification->data['booking_date'] }}
                                                        at
                                                        {{ substr($notification->data['booking_time'], 0, 5) }}
                                                    </p>
                                                @endif

                                                <p class="text-xs text-gray-400 mt-1">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </p>

                                            </button>
                                        </form>

                                    @empty

                                        <div class="px-4 py-6 text-center text-sm text-gray-500">
                                            No notifications yet.
                                        </div>

                                    @endforelse

                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif
                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                                        {{ Auth::user()->currentTeam->name }}

                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Manage Team') }}
                                    </div>

                                    <!-- Team Settings -->
                                    <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                        {{ __('Team Settings') }}
                                    </x-dropdown-link>

                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-dropdown-link href="{{ route('teams.create') }}">
                                            {{ __('Create New Team') }}
                                        </x-dropdown-link>
                                    @endcan

                                    <!-- Team Switcher -->
                                    @if (Auth::user()->allTeams()->count() > 1)
                                        <div class="border-t border-gray-200 dark:border-gray-600"></div>

                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Switch Teams') }}
                                        </div>

                                        @foreach (Auth::user()->allTeams() as $team)
                                            <x-switchable-team :team="$team" />
                                        @endforeach
                                    @endif
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif

                <!-- Settings Dropdown -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button
                                    class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="size-8 rounded-full object-cover"
                                        src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                                        {{ Auth::user()->name }}

                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <div class="border-t border-gray-200 dark:border-gray-600"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">

            @if (Auth::user()->role === 'customer')

                <x-responsive-nav-link href="{{ route('customer.dashboard') }}"
                    :active="request()->routeIs('customer.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link href="{{ route('customer.services.index') }}"
                    :active="request()->routeIs('customer.services.*')">
                    {{ __('Browse Services') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link href="{{ route('customer.bookings.index') }}"
                    :active="request()->routeIs('customer.bookings.*')">
                    {{ __('My Bookings') }}
                </x-responsive-nav-link>

            @elseif (Auth::user()->role === 'provider')

                <x-responsive-nav-link href="{{ route('provider.dashboard') }}"
                    :active="request()->routeIs('provider.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link href="{{ route('provider.services.index') }}"
                    :active="request()->routeIs('provider.services.*')">
                    {{ __('My Services') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link href="{{ route('provider.bookings.index') }}"
                    :active="request()->routeIs('provider.bookings.*')">
                    {{ __('Bookings') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link href="{{ route('provider.availabilities.index') }}"
                    :active="request()->routeIs('provider.availabilities.*')">
                    {{ __('Availability') }}
                </x-responsive-nav-link>

            @endif

        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="size-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                            alt="{{ Auth::user()->name }}" />
                    </div>
                @endif

                <div>
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>

                <!-- Team Management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-gray-200 dark:border-gray-600"></div>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Manage Team') }}
                    </div>

                    <!-- Team Settings -->
                    <x-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}"
                        :active="request()->routeIs('teams.show')">
                        {{ __('Team Settings') }}
                    </x-responsive-nav-link>

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                            {{ __('Create New Team') }}
                        </x-responsive-nav-link>
                    @endcan

                    <!-- Team Switcher -->
                    @if (Auth::user()->allTeams()->count() > 1)
                        <div class="border-t border-gray-200 dark:border-gray-600"></div>

                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Switch Teams') }}
                        </div>

                        @foreach (Auth::user()->allTeams() as $team)
                            <x-switchable-team :team="$team" component="responsive-nav-link" />
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </div>
</nav>