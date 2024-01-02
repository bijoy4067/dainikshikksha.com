<?php

namespace RalphJSmit\Filament\MediaLibrary;

use Illuminate\Database\Eloquent\Relations\Relation;
use RalphJSmit\Filament\MediaLibrary\Media\Models\MediaLibraryItem;
use RalphJSmit\Filament\MediaLibrary\Support\MediaLibraryManager;
use RalphJSmit\Helpers\Laravel\Support\NamespaceManager;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentMediaLibraryServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('ralphjsmit/laravel-filament-media-library')
            ->hasConfigFile()
            ->hasViews('media-library')
            ->hasMigrations([
                'create_filament_media_library_table',
                'create_filament_media_library_folders_table',
            ])
            ->hasTranslations();

        $this->app->singleton(MediaLibraryManager::class);
    }

    public function packageBooted(): void
    {
        parent::packageBooted();

        $this->mergeConfigFrom(__DIR__ . '/../config/filament-media-library.php', 'filament-media-library');

        NamespaceManager::registerNamespace('RalphJSmit\\Filament\\MediaLibrary\\', __DIR__);

        // Register as default for people who use the MediaLibraryItem model outside of a panel.
        // If people are using a custom MediaLibraryItem model, the morph map is registered
        // by the plugin class in each panel. So this only acts as a default model.
        Relation::morphMap([
            'filament_media_library_item' => MediaLibraryItem::class,
        ], true);
    }
}
