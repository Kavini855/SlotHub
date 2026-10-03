<x-form-section submit="updateProfileInformation">

    <x-slot name="title">
        <span class="text-[#2D2E2E] font-bold">
            {{ __('Profile Information') }}
        </span>
    </x-slot>

    <x-slot name="description">
        <span class="text-[#716969]">
            {{ __('Update your account\'s profile information and email address.') }}
        </span>
    </x-slot>

    <x-slot name="form">

        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">

                <input type="file" id="photo" class="hidden" wire:model.live="photo" x-ref="photo" x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                                " />

                <label for="photo" class="block text-sm font-semibold text-[#2D2E2E]">
                    {{ __('Photo') }}
                </label>

                <!-- Current Profile Photo -->
                <div class="mt-3" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}"
                        class="size-20 rounded-full object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-3" x-show="photoPreview" style="display: none;">

                    <span class="block size-20 rounded-full bg-cover bg-center bg-no-repeat"
                        x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>

                </div>

                <div class="mt-3 flex flex-wrap gap-2">

                    <x-secondary-button type="button" x-on:click.prevent="$refs.photo.click()">

                        {{ __('Select A New Photo') }}

                    </x-secondary-button>

                    @if ($this->user->profile_photo_path)

                        <x-secondary-button type="button" wire:click="deleteProfilePhoto">

                            {{ __('Remove Photo') }}

                        </x-secondary-button>

                    @endif

                </div>

                <x-input-error for="photo" class="mt-2" />

            </div>
        @endif


        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">

            <label for="name" class="block text-sm font-semibold text-white">
                {{ __('Name') }}
            </label>

            <input id="name" type="text" wire:model="state.name" required autocomplete="name" class="mt-2 block w-full rounded-xl border border-[#BCABAE]
                       bg-white px-4 py-3 text-[#2D2E2E]
                       shadow-sm
                       focus:border-[#716969]
                       focus:ring-[#716969]">

            <x-input-error for="name" class="mt-2" />

        </div>


        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">

            <label for="email" class="block text-sm font-semibold text-white">
                {{ __('Email') }}
            </label>

            <input id="email" type="email" wire:model="state.email" required autocomplete="username" class="mt-2 block w-full rounded-xl border border-[#BCABAE]
                       bg-white px-4 py-3 text-[#2D2E2E]
                       shadow-sm
                       focus:border-[#716969]
                       focus:ring-[#716969]">

            <x-input-error for="email" class="mt-2" />


            @if (
                    Laravel\Fortify\Features::enabled(
                        Laravel\Fortify\Features::emailVerification()
                    ) &&
                    !$this->user->hasVerifiedEmail()
                )

                <div class="mt-3 rounded-xl bg-[#BCABAE]/20 p-4">

                    <p class="text-sm text-[#716969]">

                        {{ __('Your email address is unverified.') }}

                        <button type="button" class="font-semibold underline text-white
                                               hover:text-[#0F0F0F]
                                               focus:outline-none" wire:click.prevent="sendEmailVerification">

                            {{ __('Click here to re-send the verification email.') }}

                        </button>
                    </p>

                    @if ($this->verificationLinkSent)

                        <p class="mt-2 text-sm font-medium text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>

                    @endif

                </div>

            @endif

        </div>

    </x-slot>


    <x-slot name="actions">

        <x-action-message class="me-3 text-green-600" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <button type="submit" wire:loading.attr="disabled" wire:target="photo" class="rounded-xl bg-[#2D2E2E] px-6 py-2.5
                   text-sm font-semibold text-white
                   shadow-sm transition
                   hover:bg-[#0F0F0F]
                   focus:outline-none">

            {{ __('Save Changes') }}
        </button>

    </x-slot>

</x-form-section>