<?php

namespace RalphJSmit\Filament\MediaLibrary\FilamentMediaLibrary;

trait HasConversionsConfiguration
{
    protected bool $conversionResponsiveEnabled = true;

    protected bool $conversionMediumEnabled = true;

    protected int $conversionMediumWidth = 800;

    protected bool $conversionSmallEnabled = true;

    protected int $conversionSmallWidth = 400;

    protected bool $conversionThumbEnabled = true;

    protected int $conversionThumbWidth = 600;

    protected int $conversionThumbHeight = 600;

    public function conversionResponsive(bool $enabled): static
    {
        $this->conversionResponsiveEnabled = $enabled;

        return $this;
    }

    public function conversionMedium(bool $enabled, ?int $width = null): static
    {
        $this->conversionMediumEnabled = $enabled;

        if ($width !== null) {
            $this->conversionMediumWidth($width);
        }

        return $this;
    }

    public function conversionMediumWidth(int $width = 800): static
    {
        $this->conversionMediumWidth = $width;

        return $this;
    }

    public function conversionSmall(bool $enabled, ?int $width = null): static
    {
        $this->conversionSmallEnabled = $enabled;

        if ($width !== null) {
            $this->conversionSmallWidth($width);
        }

        return $this;
    }

    public function conversionSmallWidth(int $width = 400): static
    {
        $this->conversionSmallWidth = $width;

        return $this;
    }

    public function conversionThumb(bool $enabled, ?int $width = null, ?int $height = null): static
    {
        $this->conversionThumbEnabled = $enabled;

        if ($width !== null) {
            $this->conversionThumbWidth($width);
        }

        if ($height !== null) {
            $this->conversionThumbHeight($height);
        }

        return $this;
    }

    public function conversionThumbWidth(int $width = 600): static
    {
        $this->conversionThumbWidth = $width;

        return $this;
    }

    public function conversionThumbHeight(int $height = 600): static
    {
        $this->conversionThumbHeight = $height;

        return $this;
    }

    public function isConversionResponsiveEnabled(): bool
    {
        return $this->conversionResponsiveEnabled;
    }

    public function isConversionMediumEnabled(): bool
    {
        return $this->conversionMediumEnabled;
    }

    public function getConversionMediumWidth(): int
    {
        return $this->conversionMediumWidth;
    }

    public function isConversionSmallEnabled(): bool
    {
        return $this->conversionSmallEnabled;
    }

    public function getConversionSmallWidth(): int
    {
        return $this->conversionSmallWidth;
    }

    public function isConversionThumbEnabled(): bool
    {
        return $this->conversionThumbEnabled;
    }

    public function getConversionThumbWidth(): int
    {
        return $this->conversionThumbWidth;
    }

    public function getConversionThumbHeight(): int
    {
        return $this->conversionThumbHeight;
    }
}
