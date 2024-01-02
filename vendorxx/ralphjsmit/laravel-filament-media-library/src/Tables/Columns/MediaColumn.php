<?php

namespace RalphJSmit\Filament\MediaLibrary\Tables\Columns;

use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use RalphJSmit\Filament\MediaLibrary\FilamentMediaLibrary;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Throwable;

class MediaColumn extends SpatieMediaLibraryImageColumn
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->conversion('thumb');
    }

    public function getName(): string
    {
        $name = parent::getName();

        if (! Str::of($name)->contains('.media')) {
            $name .= '.media';
        }

        return $name;
    }

    public function getCollection(): ?string
    {
        $record = $this->getRecord();

        if ($record && $this->queriesRelationships($record)) {
            $record = $record->getRelationValue($this->getRelationshipName());
        }

        if ($record instanceof Collection) {
            $record = $record->first();
        }

        return $record?->getMediaLibraryCollectionName()
            ?? (new (FilamentMediaLibrary::get()->getModelItem())())->getMediaLibraryCollectionName();
    }

    public function getImageUrl(?string $state = null): ?string
    {
        $record = $this->getRecord();

        if ($this->queriesRelationships($record)) {
            $record = $record->getRelationValue($this->getRelationshipName());
        }

        if ($record instanceof Collection) {
            $media = $record->pluck('media')->flatten(1);
        } else {
            $media = $record->media;
        }

        /** @var ?Media $media */
        $media = $media->first(fn (Media $media): bool => $media->uuid === $state);

        if (! $media) {
            return null;
        }

        if (FilamentMediaLibrary::get()->getDiskVisibility() === 'private') {
            try {
                return $media->getTemporaryUrl(
                    now()->addMinutes(5),
                    $this->getConversion(),
                );
            } catch (Throwable $exception) {
                // This driver does not support creating temporary URLs.
            }
        }

        return $media->getUrl($this->getConversion());
    }

    /**
     * @return array<string>
     */
    public function getState(): array
    {
        $collection = $this->getCollection();

        $record = $this->getRecord();

        if ($this->queriesRelationships($record)) {
            $record = $record->getRelationValue($this->getRelationshipName());
        }

        if (! $record) {
            return [];
        }

        if (! $record instanceof Collection) {
            $record = new Collection([$record]);
        }

        return $record
            ->map(fn (Model $record) => $record->getRelationValue('media'))
            ->flatten(1)
            ->filter(fn (Media $media): bool => blank($collection) || ($media->getAttributeValue('collection_name') === $collection))
            ->map(fn (Media $media): string => $media->uuid)
            ->all();
    }
}
