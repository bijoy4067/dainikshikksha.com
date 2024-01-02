<?php

namespace RalphJSmit\Filament\MediaLibrary\Media\Components;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use RalphJSmit\Filament\MediaLibrary\FilamentMediaLibrary;
use RalphJSmit\Filament\MediaLibrary\Media\Components\BrowseLibrary as Concerns;
use RalphJSmit\Filament\MediaLibrary\Media\Models\MediaLibraryFolder;
use RalphJSmit\Filament\MediaLibrary\Media\Pages\MediaLibrary;

/**
 * @property-read Collection $breadcrumbs
 */
class BrowseLibrary extends Component implements HasForms
{
    use Concerns\CanCreateMediaFolder;
    use Concerns\CanDeleteMediaFolder;
    use Concerns\CanMoveMediaFolder;
    use Concerns\CanRenameMediaFolder;
    use Concerns\HasBrowseLibraryItems;
    use Concerns\HasPagination;
    use InteractsWithForms;
    use WithPagination;

    public ?array $data = [];

    public ?MediaLibraryFolder $mediaLibraryFolder = null;

    /**
     * Used in actions for modals, such as delete or rename.
     * This is NOT the folder that the user is currently
     * browsing in, only the subject of modal actions.
     */
    public ?MediaLibraryFolder $activeMediaLibraryFolder = null;

    /**
     * Allow opening a folder by URL when linking to MediaLibrary page using ?folder={ID},
     * plus keep the folder in the URL using a query string when reloading a page.
     */
    #[Url(as: 'folder')]
    public mixed $mediaLibraryFolderKey = null;

    protected $listeners = [
        '$refresh' => '$refresh',
        'loadMedia' => 'loadMedia',
        'openMediaLibraryFolder' => 'openMediaLibraryFolder',
    ];

    public function mount(): void
    {
        if ($this->mediaLibraryFolderKey) {
            $this->openMediaLibraryFolder($this->mediaLibraryFolderKey, false);
        }

        // Folder opening should not work on other pages than the BrowseLibrary page.
        $mediaLibraryPage = FilamentMediaLibrary::get()->getMediaLibraryPage();

        if ($mediaLibraryPage && ! $this->mediaLibraryFolderKey && ! Str::of(request()->url())->startsWith($mediaLibraryPage::getUrl())) {
            $this->openMediaLibraryFolder(null);
            $this->resetPage();
        }
    }

    public function render(): View
    {
        return view('media-library::media.livewire.browse-library', [
            'browseLibraryItems' => $this->getBrowseLibraryItems(),
        ]);
    }

    public function getForms(): array
    {
        return [
            'searchForm' => $this
                ->getSearchForm()
                ->statePath('data.searchForm'),
            'sortOrderForm' => $this
                ->getSortOrderForm()
                ->statePath('data.sortOrderForm'),
            'createMediaFolderForm' => $this
                ->getCreateMediaFolderForm()
                ->statePath('data.createMediaFolderForm'),
            'deleteMediaFolderForm' => $this
                ->getDeleteMediaFolderForm()
                ->statePath('data.deleteMediaFolderForm'),
            'renameMediaFolderForm' => $this
                ->getRenameMediaFolderForm()
                ->statePath('data.renameMediaFolderForm'),
            'moveMediaFolderForm' => $this
                ->getMoveMediaFolderForm()
                ->statePath('data.moveMediaFolderForm'),
        ];
    }

    public function canCreate(): bool
    {
        if (! Gate::getPolicyFor(FilamentMediaLibrary::get()->getModelItem())) {
            return true;
        }

        if (Auth::guest()) {
            return true;
        }

        return Auth::user()->can('create', FilamentMediaLibrary::get()->getModelItem());
    }

    public function getBreadcrumbsProperty(): Collection
    {
        $breadcrumbs = collect([
            'openMediaLibraryFolder()' => __('filament-media-library::translations.components.browse-library.breadcrumbs.root'),
        ]);

        $ancestorMediaLibraryFolders = $this->mediaLibraryFolder?->getAncestors(level: 3);

        foreach ($ancestorMediaLibraryFolders ?? [] as $ancestorMediaLibraryFolder) {
            $breadcrumbs["openMediaLibraryFolder('{$ancestorMediaLibraryFolder->getKey()}')"] = $ancestorMediaLibraryFolder->name;
        }

        if ($this->mediaLibraryFolder) {
            $breadcrumbs["openMediaLibraryFolder('{$this->mediaLibraryFolder->getKey()}')"] = $this->mediaLibraryFolder->name;
        }

        return $breadcrumbs->splice(-3)->map(fn (?string $value) => $value ? Str::limit($value, 18) : $value)->filter();
    }

    public function openMediaLibraryFolder(mixed $mediaLibraryFolderId = null, bool $resetPage = true): void
    {
        if ($mediaLibraryFolderId !== null) {
            $mediaLibraryFolder = FilamentMediaLibrary::get()->getModelFolder()::find($mediaLibraryFolderId);
        } else {
            $mediaLibraryFolder = null;
        }

        $this->mediaLibraryFolder = $mediaLibraryFolder;
        // There is an issue with #[Url] not working on Eloquent models. Therefore we will temporarily
        // mirror the effect using a separate property that contains the key of the folder.
        $this->mediaLibraryFolderKey = $mediaLibraryFolder?->getKey();

        $this->searchForm->fill();

        if ($mediaLibraryFolder) {
            $this->dispatch('openMediaLibraryFolder', mediaLibraryFolderId: $mediaLibraryFolder->getKey())->to('media-library::media.upload-media');
        }

        $this->dispatch('$refresh')->self();

        if ($resetPage) {
            $this->resetPage();
        }
    }
}
