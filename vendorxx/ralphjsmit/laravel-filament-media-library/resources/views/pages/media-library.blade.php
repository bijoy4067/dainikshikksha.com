<x-filament::page>
    <div class="flex flex-row">
        <div
            class="mr-2 flex-grow"
            x-data
            x-init="
                $watch('$store.browseLibrary.selectedMediaItemId', (value) => {
                    $dispatch('media-item-selected', value)
                })
            "
        >
            @livewire('media-library::media.browse-library')
        </div>

        <aside
            @class([
                'sticky top-24 -mr-8 ml-8 min-h-screen w-full min-w-[320px] max-w-[360px] flex-grow-0 self-start rounded-l-xl bg-white p-8 dark:bg-gray-900',
                // Only round the right side if the content width is not full width.
                'rounded-r-xl' => ($this->getMaxContentWidth() ?? config('filament.layout.max_content_width') ?? '7xl') !== 'full',
            ])
        >
            @livewire('media-library::media.media-info')
        </aside>
    </div>
</x-filament::page>
