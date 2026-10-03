<x-form-section submit="updatePassword">

    <x-slot name="title">
        <span class="font-bold text-[#2D2E2E]">
            {{ __('Update Password') }}
        </span>
    </x-slot>

    <x-slot name="description">
        <span class="text-[#716969]">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </span>
    </x-slot>


    <x-slot name="form">

        <!-- Current Password -->
        <div class="col-span-6 sm:col-span-4">

            <label for="current_password" class="block text-sm font-semibold text-white">
                {{ __('Current Password') }}
            </label>

            <input id="current_password" type="password" wire:model="state.current_password"
                autocomplete="current-password" class="mt-2 block w-full rounded-xl
                       border border-[#BCABAE]
                       bg-white px-4 py-3
                       text-[#2D2E2E]
                       shadow-sm
                       focus:border-[#716969]
                       focus:ring-[#716969]">

            <x-input-error for="current_password" class="mt-2" />

        </div>


        <!-- New Password -->
        <div class="col-span-6 sm:col-span-4">

            <label for="password" class="block text-sm font-semibold text-white">
                {{ __('New Password') }}
            </label>

            <input id="password" type="password" wire:model="state.password" autocomplete="new-password" class="mt-2 block w-full rounded-xl
                       border border-[#BCABAE]
                       bg-white px-4 py-3
                       text-[#2D2E2E]
                       shadow-sm
                       focus:border-[#716969]
                       focus:ring-[#716969]">

            <x-input-error for="password" class="mt-2" />

        </div>


        <!-- Confirm Password -->
        <div class="col-span-6 sm:col-span-4">

            <label for="password_confirmation" class="block text-sm font-semibold text-white">
                {{ __('Confirm Password') }}
            </label>

            <input id="password_confirmation" type="password" wire:model="state.password_confirmation"
                autocomplete="new-password" class="mt-2 block w-full rounded-xl
                       border border-[#BCABAE]
                       bg-white px-4 py-3
                       text-[#2D2E2E]
                       shadow-sm
                       focus:border-[#716969]
                       focus:ring-[#716969]">

            <x-input-error for="password_confirmation" class="mt-2" />

        </div>

    </x-slot>


    <x-slot name="actions">

        <x-action-message class="me-3 text-green-600" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <button type="submit" class="rounded-xl bg-[#2D2E2E]
                   px-6 py-2.5
                   text-sm font-semibold text-white
                   shadow-sm transition
                   hover:bg-[#0F0F0F]
                   focus:outline-none">

            {{ __('Save Password') }}

        </button>

    </x-slot>

</x-form-section>