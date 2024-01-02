<?php

namespace RalphJSmit\Filament\MediaLibrary\FilamentMediaLibrary;

trait HasSettingsConfiguration
{
    protected bool $showUploadBoxByDefault = false;

    protected bool $showUnstoredUploadsWarning = false;

    public function showUploadBoxByDefault(bool $show = true): static
    {
        $this->showUploadBoxByDefault = $show;

        return $this;
    }

    public function unstoredUploadsWarning(bool $warning = true): static
    {
        $this->showUnstoredUploadsWarning = $warning;

        return $this;
    }

    public function shouldShowUploadBoxByDefault(): bool
    {
        return $this->showUploadBoxByDefault;
    }

    public function shouldShowUnstoredUploadsWarning(): bool
    {
        return $this->showUnstoredUploadsWarning;
    }
}
