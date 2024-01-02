<?php

namespace RalphJSmit\Filament\MediaLibrary\Support\Concerns;

use RalphJSmit\Filament\MediaLibrary\Facades\MediaLibrary;
use RalphJSmit\Filament\MediaLibrary\FilamentMediaLibrary;
use Spatie\Image\Enums\Fit;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HasContentMedia
{
    use InteractsWithMedia;

    public function getMediaLibraryCollectionName(): string
    {
        return 'library';
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection($this->getMediaLibraryCollectionName())
            ->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        foreach (MediaLibrary::getRegisterMediaConversionsUsing() as $registerMediaConversions) {
            $registerMediaConversions($this, $media);
        }

        // Responsive
        if (FilamentMediaLibrary::get()->isConversionResponsiveEnabled()) {
            $this
                ->addMediaConversion('responsive')
                ->withResponsiveImages();
        }

        // 800
        if (FilamentMediaLibrary::get()->isConversionMediumEnabled()) {
            $this
                ->addMediaConversion('800')
                ->width(FilamentMediaLibrary::get()->getConversionMediumWidth())
                ->optimize();
        }

        // 400
        if (FilamentMediaLibrary::get()->isConversionSmallEnabled()) {
            $this
                ->addMediaConversion('400')
                ->width(FilamentMediaLibrary::get()->getConversionSmallWidth())
                ->optimize();
        }

        if (FilamentMediaLibrary::get()->isConversionThumbEnabled()) {
            $usesSpatieMedialibraryV11 = class_exists('Spatie\Image\Enums\Fit');

            $this
                ->addMediaConversion('thumb')
                ->fit(
                    $usesSpatieMedialibraryV11 ? Fit::Crop : Manipulations::FIT_CROP,
                    FilamentMediaLibrary::get()->getConversionThumbWidth(),
                    FilamentMediaLibrary::get()->getConversionThumbHeight(),
                )
                ->optimize()
                ->nonQueued();
        }
    }
}
