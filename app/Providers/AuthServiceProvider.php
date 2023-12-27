<?php

namespace App\Providers;

use App\Filament\App\Pages\Settings;
use App\Models\Author;
use App\Models\Category;
use App\Models\News;
use App\Models\Tag;
use App\Models\User;
use App\Policies\AuthorPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Policies\CategoryPolicy;
use App\Policies\NewsPolicy;
use App\Policies\TagPolicy;
use App\Policies\UserPolicy;
use App\Models\Settings as SettingModel;
use App\Policies\SettingsPolicy;
use App\Settings\GeneralSettings;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Category::class => CategoryPolicy::class,
        Tag::class => TagPolicy::class,
        News::class => NewsPolicy::class,
        Author::class => AuthorPolicy::class,
        GeneralSettings::class => SettingsPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //

        // Implicitly grant "Super-Admin" role all permission checks using can()
        // Gate::before(function (User $user, string $ability) {
        //     return $user->isSuperAdmin() ? true : null;
        // });
    }
}
