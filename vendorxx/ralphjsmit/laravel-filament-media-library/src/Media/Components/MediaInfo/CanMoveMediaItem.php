<?php

namespace RalphJSmit\Filament\MediaLibrary\Media\Components\MediaInfo;

use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use RalphJSmit\Filament\MediaLibrary\FilamentMediaLibrary;
use RalphJSmit\Filament\MediaLibrary\Media\Components\MediaInfo;
use RalphJSmit\Filament\MediaLibrary\Media\Models\MediaLibraryFolder;

/**
 * @mixin MediaInfo
 */
trait CanMoveMediaItem
{
    public bool $openMoveItemForm = false;

    public function canMove(): bool
    {
        if (! FilamentMediaLibrary::get()->getModelFolder()::exists()) {
            return false;
        }

        if (! Gate::getPolicyFor(FilamentMediaLibrary::get()->getModelItem())) {
            return true;
        }

        return Auth::user()?->can('update', $this->getMediaProperty());
    }

    protected function getMoveMediaItemForm(): ComponentContainer
    {
        return $this
            ->makeForm()
            ->schema([
                Select::make('media_library_folder_id')
                    ->disableLabel()
                    ->placeholder(__('filament-media-library::translations.components.media-info.move-media-item-form.fields.media_library_folder_id.placeholder'))
                    ->autofocus()
                    ->required()
                    ->options(function () {
                        return FilamentMediaLibrary::get()
                            ->getModelFolder()::query()
                            // First, we reject the immediate children of the current active media folder.
                            // These are easy, because they have a parent ID present. This reduces the
                            // nr of "->getAncestors()" queries in the next `mapWithKeys()` below.
                            ->get()
                            ->mapWithKeys(function (MediaLibraryFolder $mediaLibraryFolder): array {
                                $ancestorsIncludingCurrent = $mediaLibraryFolder->parent_id
                                    ? $mediaLibraryFolder->getAncestors()
                                    : new Collection([$mediaLibraryFolder]);

                                $pathNameIncludingCurrent = $ancestorsIncludingCurrent->implode(function (MediaLibraryFolder $mediaLibraryFolder) {
                                    return Str::limit($mediaLibraryFolder->name, 20);
                                }, ' / ');

                                return [$mediaLibraryFolder->getKey() => $pathNameIncludingCurrent];
                            })
                            ->filter()
                            ->sort()
                            ->prepend('/', 'root');
                    }),
            ]);
    }

    public function moveMediaItem(): void
    {
        $state = $this->moveMediaItemForm->getState();

        $this->media->update([
            'folder_id' => $state['media_library_folder_id'] === 'root' ? null : $state['media_library_folder_id'],
        ]);

        unset($this->media);

        Notification::make()
            ->body(__('filament-media-library::translations.components.media-info.move-media-item-form.messages.moved.body'))
            ->success()
            ->send();

        $this->openMoveItemForm = false;

        $this->dispatch(
            'openMediaLibraryFolder',
            // Do not send a parameter with "null", otherwise the browse library component will try to retrieve the MediaLibraryFolder and throw a 404 on failure.
            ...array_filter([$this->media->folder_id]),
        )->to('media-library::media.browse-library');
    }
}
