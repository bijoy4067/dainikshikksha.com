<?php

namespace RalphJSmit\Filament\MediaLibrary\FilamentTipTap\Actions;

use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Actions\Action;
use FilamentTiptapEditor\TiptapEditor;
use RalphJSmit\Filament\MediaLibrary\FilamentMediaLibrary;
use RalphJSmit\Filament\MediaLibrary\Forms\Components\MediaPicker;
use RalphJSmit\Filament\MediaLibrary\Media\Models\MediaLibraryItem;

class MediaLibraryAction extends Action
{
    public static function getDefaultName(): ?string
    {
        // Keep this name the same in order to be able to replace default media action.
        return 'filament_tiptap_media';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->mountUsing(function (TiptapEditor $component, ComponentContainer $form) {
            $form->fill([
                'media_library_item_id' => null,
            ]);
        });

        $this->modalHeading(function (TiptapEditor $component) {
            return __('filament-media-library::translations.filament-tip-tap.actions.media-library-action.modal-heading');
        });

        $this->modalWidth('md');

        $this->form(function (TiptapEditor $component) {
            return [
                MediaPicker::make('media_library_item_id')
                    ->required()
                    ->hiddenLabel(),
            ];
        });

        $this->action(function (TiptapEditor $component, array $data) {
            /** @var MediaLibraryItem $mediaLibraryItem */
            $mediaLibraryItem = FilamentMediaLibrary::get()->getModelItem()::find($data['media_library_item_id']);

            $mediaLibraryItemMeta = $mediaLibraryItem->getMeta(true);

            $component->getLivewire()->dispatch(
                'insert-content',
                type: 'media',
                statePath: $component->getStatePath(),
                media: [
                    'src' => $mediaLibraryItemMeta->url,
                    'alt' => $mediaLibraryItemMeta->altText,
                    'title' => $mediaLibraryItemMeta->name,
                    'width' => $mediaLibraryItemMeta->width,
                    'height' => $mediaLibraryItemMeta->height,
                    'link_text' => null,
                ]);
        });
    }
}
