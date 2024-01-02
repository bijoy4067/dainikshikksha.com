<?php

namespace RalphJSmit\Filament\MediaLibrary\Forms\Components;

use Closure;
use Filament\Forms\Components\Field;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use RalphJSmit\Filament\MediaLibrary\FilamentMediaLibrary;
use RalphJSmit\Filament\MediaLibrary\Media\Models\MediaLibraryFolder;
use RalphJSmit\Filament\MediaLibrary\Media\Models\MediaLibraryItem;

class MediaPicker extends Field
{
    // Keep the ".index" in the view name for old Laravel versions.
    protected string $view = 'media-library::forms.components.media-picker.index';

    public ?Collection $mediaLibraryItems = null;

    protected bool $isMultiple = false;

    protected bool | Closure $isReorderable = false;

    public string | Htmlable | Closure | null $buttonLabel = null;

    public MediaLibraryFolder | Closure | null $defaultFolder = null;

    protected array | Arrayable | Closure | null $acceptedFileTypes = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this
            // If we return an incorrect type (e.g. an array as default on single item components)
            // then Livewire V3 will throw an error, because it will assume an array still from
            // the default which was initially set. In V2, Livewire would just override the old
            // value and not look at the old value at all. Exception: foreach not type null.
            ->default(function (self $component): ?array {
                return $component->isMultiple() ? [] : null;
            })
            ->reactive();
    }

    public function getState(): mixed
    {
        $state = parent::getState();

        if ($state === null) {
            return null;
        }

        $state = Collection::wrap($state)->filter()->map(function (string | int $mediaItemId): int {
            return is_numeric($mediaItemId) ? (int) $mediaItemId : $mediaItemId;
        });

        if ($this->isMultiple()) {
            return $state;
        }

        return $state->first();
    }

    public function getImages(): null | Collection | MediaLibraryItem
    {
        $state = $this->getState();

        if ($state === null) {
            return collect();
        }

        $state = Collection::wrap($state);

        return FilamentMediaLibrary::get()
            ->getModelItem()::find($state)
            ->load('folder')
            // Sort the items as they were present in the original state.
            ->sortBy(fn (MediaLibraryItem $model) => array_search($model->getKey(), $state->all()));
    }

    public function getMedia(): Collection
    {
        return $this->mediaLibraryItems ??= FilamentMediaLibrary::get()->getModelItem()::query()->latest()->get();
    }

    public function multiple(bool $multiple = true): static
    {
        $this->isMultiple = $multiple;

        return $this;
    }

    public function isMultiple(): bool
    {
        return $this->isMultiple;
    }

    public function reorderable(bool | Closure $reorderable = true): static
    {
        $this->isReorderable = $reorderable;

        return $this;
    }

    public function isReorderable(): bool
    {
        return $this->evaluate($this->isReorderable);
    }

    public function buttonLabel(string | Htmlable | Closure | null $buttonLabel): static
    {
        $this->buttonLabel = $buttonLabel;

        return $this;
    }

    public function getButtonLabel(): string | Htmlable | null
    {
        return $this->evaluate($this->buttonLabel);
    }

    public function defaultFolder(MediaLibraryFolder | Closure | null $folder): static
    {
        $this->defaultFolder = $folder;

        return $this;
    }

    public function getDefaultFolder(): ?MediaLibraryFolder
    {
        return $this->evaluate($this->defaultFolder);
    }

    public function acceptedFileTypes(array | Arrayable | Closure $types): static
    {
        $this->acceptedFileTypes = $types;

        $this->rules([
            function (self $component) {
                return function (string $attribute, $value, Closure $fail) use ($component) {
                    /** @var class-string<Model> $modelItemClass */
                    $modelItemClass = FilamentMediaLibrary::get()->getModelItem();

                    $mediaLibraryItems = $modelItemClass::query()->whereIn(( new $modelItemClass )->getKeyName(), Collection::wrap($value))->with('media')->get();

                    $mediaLibraryItems->each(function (MediaLibraryItem $mediaLibraryItem) use ($fail, $component) {
                        $mimeType = $mediaLibraryItem->getItem()->mime_type;

                        $acceptedFileTypes = $component->getAcceptedFileTypes();

                        foreach ($acceptedFileTypes as $acceptedFileType) {
                            if (Str::is($acceptedFileType, $mimeType)) {
                                return;
                            }
                        }

                        $fail(
                            __('validation.mimetypes', [
                                'attribute' => $component->getValidationAttribute(),
                                'values' => collect($component->getAcceptedFileTypes())
                                    ->map(function (string $mimeType) {
                                        $mimeType = str($mimeType);

                                        if ($mimeType->endsWith('/*')) {
                                            return $mimeType->beforeLast('/')->headline()->lower();
                                        }

                                        return $mimeType->after('/')->headline()->lower();
                                    })
                                    ->implode(', '),
                            ])
                        );
                    });
                };
            },
        ]);

        return $this;
    }

    /**
     * @return array<string> | null
     */
    public function getAcceptedFileTypes(): ?array
    {
        $types = $this->evaluate($this->acceptedFileTypes);

        if ($types instanceof Arrayable) {
            $types = $types->toArray();
        }

        return $types;
    }
}
