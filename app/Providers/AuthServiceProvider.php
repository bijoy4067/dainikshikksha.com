<?php

namespace App\Providers;

use App\Models\Author;
use App\Models\Category;
use App\Models\News;
use App\Models\Settings;
use App\Models\Tag;
use App\Models\User;
use App\Policies\AuthorPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Policies\CategoryPolicy;
use App\Policies\NewsPolicy;
use App\Policies\SettingsPolicy;
use App\Policies\TagPolicy;
use App\Policies\UserPolicy;

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
        // Settings::class => SettingsPolicy::class,
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
