<?php

namespace RalphJSmit\Filament\MediaLibrary\Media\Components;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use RalphJSmit\Filament\MediaLibrary\Facades\MediaLibrary;
use RalphJSmit\Filament\MediaLibrary\FilamentMediaLibrary;
use RalphJSmit\Filament\MediaLibrary\Media\Components\MediaInfo as Concerns;
use RalphJSmit\Filament\MediaLibrary\Media\DataTransferObjects\MediaItemMeta;
use RalphJSmit\Filament\MediaLibrary\Media\Models\MediaLibraryItem;

/**
 * @property-read MediaLibraryItem $media
 */
class MediaInfo extends Component implements HasForms
{
    use Concerns\CanMoveMediaItem;
    use Concerns\CanRegenerateMediaItem;
    use InteractsWithForms;

    public array $data = [];

    public null | int | string $mediaItemId = null;

    public ?MediaItemMeta $mediaItemMeta = null;

    protected ?MediaLibraryItem $cachedMediaItem = null;

    public bool $openEditForm = false;

    public $replaceMediaUpload = null;

    protected $listeners = [
        'mediaItemSelected' => 'setMedia',
        '$refresh',
    ];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function render(): View
    {
        return view('media-library::media.livewire.media-info');
    }

    public function deleteImage(): void
    {
        if ($this->mediaItemId) {
            $this->getMediaProperty()->delete();
        }

        $this->mediaItemId = null;
        $this->mediaItemMeta = null;
        $this->cachedMediaItem = null;

        $this->dispatch('reset-selected-media-item');

        $this->dispatch('$refresh');
    }

    public function getForms(): array
    {
        return [
            'form' => $this
                ->makeForm()
                ->statePath('data.form')
                ->model($this->getMediaProperty())
                ->schema($this->getFormSchema()),
            'moveMediaItemForm' => $this
                ->getMoveMediaItemForm()
                ->statePath('data.moveMediaItemForm'),
        ];
    }

    protected function getFormSchema(): array
    {
        $schema = [
            TextInput::make('caption')
                ->label(Str::ucfirst(__('filament-media-library::translations.caption')))
                ->rules(['string', 'max:255'])
                ->placeholder(Str::ucfirst(__('filament-media-library::translations.sentences.add-a-caption-to-this-image'))),
            TextInput::make('alt_text')
                ->label(Str::ucfirst(__('filament-media-library::translations.alt-text')))
                ->rules(['string', 'max:255'])
                ->placeholder(Str::ucfirst(__('filament-media-library::translations.sentences.add-an-alt-text-to-this-image'))),
        ];

        if (! $this->getMediaProperty()) {
            return $schema;
        }

        foreach (MediaLibrary::getRegisterMediaInfoFormFieldsUsing() as $registerMediaInfoFormFields) {
            $schema = $registerMediaInfoFormFields($schema, $this->getMediaProperty());
        }

        return $schema;
    }

    public function getMediaProperty(): ?MediaLibraryItem
    {
        return $this->cachedMediaItem ??= FilamentMediaLibrary::get()->getModelItem()::find($this->mediaItemId);
    }

    public function submit(): void
    {
        $state = $this->form->getState();

        $media = $this->getMediaProperty();

        $media->update($state);

        $this->form->model($media)->saveRelationships();

        $this->openEditForm = false;
    }

    public function setMedia(null | int | string | array $ids): void
    {
        if ($ids === null) {
            return;
        }

        if (is_array($ids) && count($ids) !== 1) {
            return;
        }

        $this->reset();

        $this->mediaItemId = is_array($ids) ? Arr::first($ids) : $ids;
        $this->mediaItemMeta = $this->getMediaProperty()?->getMeta(parseItem: true);

        $this->form->fill($this->getMediaProperty()?->toArray() ?? []);
        $this->moveMediaItemForm->fill([
            'media_library_folder_id' => $this->media->folder_id ?? 'root',
        ]);

        $this->dispatch('close-delete-panel');
    }

    public function canDelete(): bool
    {
        if (! Gate::getPolicyFor(FilamentMediaLibrary::get()->getModelItem())) {
            return true;
        }

        return Auth::user()?->can('delete', $this->getMediaProperty());
    }

    public function canEdit(): bool
    {
        if ($this->form->getComponents() === []) {
            return false;
        }

        if (! Gate::getPolicyFor(FilamentMediaLibrary::get()->getModelItem())) {
            return true;
        }

        return Auth::user()?->can('update', $this->getMediaProperty());
    }

    public function getInformation(MediaLibraryItem $mediaLibraryItem, MediaItemMeta $mediaItemMeta): array
    {
        $information = [
            __('filament-media-library::translations.time.uploaded_by') => $mediaItemMeta->uploaded_by_name,
            __('filament-media-library::translations.time.uploaded_at') => $mediaItemMeta->uploaded_at,
        ];

        if ($mediaItemMeta->width && $mediaItemMeta->height) {
            $information[__('filament-media-library::translations.dimensions')] = "{$mediaItemMeta->width} x {$mediaItemMeta->height}";
        } elseif ($mediaItemMeta->pdf_nr_of_pages) {
            $information[__('filament-media-library::translations.size')] = $mediaItemMeta->pdf_nr_of_pages . ' ' . trans_choice('filament-media-library::translations.page', $mediaItemMeta->pdf_nr_of_pages);
        } elseif ($mediaItemMeta->video_duration) {
            $information[__('filament-media-library::translations.duration')] = $mediaItemMeta->video_duration;
        }

        foreach (MediaLibrary::getRegisterMediaInfoInformationUsing() as $registerMediaInfoInformation) {
            $information = $registerMediaInfoInformation($information, $mediaLibraryItem, $mediaItemMeta);
        }

        return $information;
    }

    public function updatedReplaceMediaUpload(): void
    {
        $this->submitReplaceMediaForm();
    }

    public function submitReplaceMediaForm(): void
    {
        if (! $this->replaceMediaUpload) {
            return;
        }

        try {
            $this->validate([
                'replaceMediaUpload' => [
                    'max:' . config('media-library.max_file_size'),
                    'mimetypes:' . FilamentMediaLibrary::get()->getAcceptedFileTypes()->implode(','),
                ],
            ]);
        } catch (ValidationException $e) {
            Notification::make()
                ->title(Arr::first($e->errors()['replaceMediaUpload']))
                ->danger()
                ->send();

            return;
        }

        $this->getMediaProperty()->addOrReplaceUpload($this->replaceMediaUpload);

        $this->setMedia($this->mediaItemId);

        $this->dispatch('$refresh')->to('media-library::media.browse-library');
        $this->dispatch('$refresh')->self();
    }
}
