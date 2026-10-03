<div class="md:col-span-1 flex justify-between">
    <div class="px-4 sm:px-0">
        <h3 class="text-xl font-bold text-[#2D2E2E]">
            {{ $title }}
        </h3>

        <p class="mt-2 text-base leading-6 text-[#716969]">
            {{ $description }}
        </p>
    </div>

    <div class="px-4 sm:px-0">
        {{ $aside ?? '' }}
    </div>
</div>