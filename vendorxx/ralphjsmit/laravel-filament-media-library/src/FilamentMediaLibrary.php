<?php

namespace RalphJSmit\Filament\MediaLibrary;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;

class FilamentMediaLibrary implements Plugin
{
    use FilamentMediaLibrary\HasAcceptedFileTypesConfiguration;
    use FilamentMediaLibrary\HasConversionsConfiguration;
    use FilamentMediaLibrary\HasDiskConfiguration;
    use FilamentMediaLibrary\HasModalsConfiguration;
    use FilamentMediaLibrary\HasModelConfiguration;
    use FilamentMediaLibrary\HasNavigationConfiguration;
    use FilamentMediaLibrary\HasPreviewConversionsConfiguration;
    use FilamentMediaLibrary\HasRegisterConfiguration;
    use FilamentMediaLibrary\HasSettingsConfiguration;
    use FilamentMediaLibrary\InitializesPluginFromConfiguration;

    public static function make(): static
    {
        $plugin = app(static::class);

        $plugin->setUp();

        return $plugin;
    }

    public static function get(): static
    {
        return filament(app(static::class)->getId());
    }

    public function getId(): string
    {
        return 'ralphjsmit/laravel-filament-media-library';
    }

    public function register(Panel $panel): void
    {
        Livewire::component('media-library::media.upload-media', $this->getUploadMediaComponent());
        Livewire::component('media-library::media.media-info', $this->getMediaInfoComponent());
        Livewire::component('media-library::media.browse-library', $this->getBrowseLibraryComponent());

        Blade::directive(
            'mediaPickerModal',
            fn (): View => view('media-library::forms.components.media-picker.modal')
        );

        $panel->pages($this->getRegistrablePages());
    }

    public function boot(Panel $panel): void
    {
        FilamentView::registerRenderHook('panels::page.start', function (): string {
            return view('media-library::forms.components.media-picker.modal')->render();
        });

        Relation::morphMap([
            'filament_media_library_item' => FilamentMediaLibrary::get()->getModelItem(),
        ], true);
    }

    protected function setUp(): void
    {
        $this->initializePluginFromConfigurationIfPresent();
    }
}
