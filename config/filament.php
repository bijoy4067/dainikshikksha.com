<?php

use DutchCodingCompany\FilamentSocialite\FilamentSocialitePlugin;
use Filament\AdminPanel;

return [

    /*
    |--------------------------------------------------------------------------
    | Broadcasting
    |--------------------------------------------------------------------------
    |
    | By uncommenting the Laravel Echo configuration, you may connect your
    | admin panel to any Pusher-compatible websockets server.
    |
    | This will allow your admin panel to receive real-time notifications.
    |
    */

    'broadcasting' => [

        // 'echo' => [
        //     'broadcaster' => 'pusher',
        //     'key' => env('VITE_PUSHER_APP_KEY'),
        //     'cluster' => env('VITE_PUSHER_APP_CLUSTER'),
        //     'forceTLS' => true,
        // ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | This is the storage disk Filament will use to put media. You may use any
    | of the disks defined in the `config/filesystems.php`.
    |
    */

    'default_filesystem_disk' => env('FILAMENT_FILESYSTEM_DISK', 'public'),
    'plugins' => [
        // FilamentSocialitePlugin::class,
    ],
    'layout' => [
        'sidebar' => [
            'is_collapsible_on_desktop' => true,
        ],
    ],
    // ...

    'admin_panel' => [
        'providers' => [
            AdminPanel\AdminPanelServiceProvider::class,
        ],
    ],
];
