<div class="text-center">
    <svg
        class="mx-auto h-12 w-12 text-gray-400"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
        aria-hidden="true"
    >
        <path
            vector-effect="non-scaling-stroke"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"
        />
    </svg>
    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-300">
        {{ Str::ucfirst(__('filament-media-library::translations.media.no-images-found.title')) }}
    </h3>

    <p class="mt-1 text-sm text-gray-500">
        {{ Str::ucfirst(__('filament-media-library::translations.media.no-images-found.description')) }}
    </p>

    <div class="mt-6">
        <x-filament::button tag="button" @click="$dispatch('toggle-upload-box')">
            {{ Str::ucfirst(__('filament-media-library::translations.phrases.upload-image')) }}
        </x-filament::button>
    </div>
</div>