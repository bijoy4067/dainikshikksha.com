<?php

namespace RalphJSmit\Filament\MediaLibrary\FilamentMediaLibrary;

use RalphJSmit\Filament\MediaLibrary\Media\Components\BrowseLibrary;
use RalphJSmit\Filament\MediaLibrary\Media\Components\MediaInfo;
use RalphJSmit\Filament\MediaLibrary\Media\Components\UploadMedia;
use RalphJSmit\Filament\MediaLibrary\Media\Pages\MediaLibrary;

trait HasRegisterConfiguration
{
    protected array $registrablePages = [
        MediaLibrary::class,
    ];

    protected string $livewireUploadMediaComponent = UploadMedia::class;

    protected string $livewireMediaInfoComponent = MediaInfo::class;

    protected string $livewireBrowseLibraryComponent = BrowseLibrary::class;

    public function registerPages(array $pages): static
    {
        $this->registrablePages = $pages;

        return $this;
    }

    public function uploadMediaComponent(string $component): static
    {
        $this->livewireUploadMediaComponent = $component;

        return $this;
    }

    public function mediaInfoComponent(string $component): static
    {
        $this->livewireMediaInfoComponent = $component;

        return $this;
    }

    public function browseLibraryComponent(string $component): static
    {
        $this->livewireBrowseLibraryComponent = $component;

        return $this;
    }

    public function getRegistrablePages(): array
    {
        return $this->registrablePages;
    }

    public function getUploadMediaComponent(): string
    {
        return $this->livewireUploadMediaComponent;
    }

    public function getMediaInfoComponent(): string
    {
        return $this->livewireMediaInfoComponent;
    }

    public function getBrowseLibraryComponent(): string
    {
        return $this->livewireBrowseLibraryComponent;
    }

    public function getMediaLibraryPage(): ?string
    {
        foreach ($this->registrablePages as $registrablePage) {
            if (is_subclass_of($registrablePage, MediaLibrary::class)) {
                return $registrablePage;
            }
        }

        return null;
    }
}
