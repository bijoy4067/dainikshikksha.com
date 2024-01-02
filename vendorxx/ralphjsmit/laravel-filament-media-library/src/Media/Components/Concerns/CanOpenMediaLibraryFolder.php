<?php

namespace RalphJSmit\Filament\MediaLibrary\Media\Components\Concerns;

use RalphJSmit\Filament\MediaLibrary\FilamentMediaLibrary;
use RalphJSmit\Filament\MediaLibrary\Media\Models\MediaLibraryFolder;

/**
 * @property-read MediaLibraryFolder $mediaLibraryFolder
 */
trait CanOpenMediaLibraryFolder
{
    public null | int | string $mediaLibraryFolderId = null;

    public function bootCanOpenMediaLibraryFolder(): void
    {
        $this->listeners['openMediaLibraryFolder'] = 'openMediaLibraryFolder';
    }

    public function openMediaLibraryFolder(null | int | string $mediaLibraryFolderId): void
    {
        $this->mediaLibraryFolderId = $mediaLibraryFolderId;

        unset($this->mediaLibraryFolder);
    }

    public function getMediaLibraryFolderProperty(): ?MediaLibraryFolder
    {
        if (! $this->mediaLibraryFolderId) {
            return null;
        }

        return FilamentMediaLibrary::get()->getModelFolder()::find($this->mediaLibraryFolderId);
    }
}
