<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-[#192A51]">
                Browse Services
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Find the right service and book your next appointment.
            </p>
        </div>
    </x-slot>

    <livewire:customer.service-browser />

</x-app-layout>