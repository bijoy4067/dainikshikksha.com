<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
//filament sociolite
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use DutchCodingCompany\FilamentSocialite\Facades\FilamentSocialite as FilamentSocialiteFacade;
use DutchCodingCompany\FilamentSocialite\FilamentSocialite;
use Filament\Support\Facades\FilamentIcon;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Model::unguard();

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
        FilamentSocialiteFacade::setCreateUserCallback(fn (SocialiteUserContract $oauthUser, FilamentSocialite $socialite) => $socialite->getUserModelClass()::create([
            'name' => $oauthUser->getName(),
            'email' => $oauthUser->getEmail(),
        ]));

        FilamentIcon::register([
            'panels::topbar.global-search.field' => 'fas-magnifying-glass',
            'panels::sidebar.group.collapse-button' => 'fas-chevron-up',
        ]);
        $this->app->bind(LoginResponseContract::class, \App\Http\Responses\LoginResponse::class);
    }
}
