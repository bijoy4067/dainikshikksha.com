<?php

use App\Http\Controllers\SocialiteController;
use App\Livewire\Form;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('form', Form::class);

Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])
    ->name('socialite.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])
    ->name('socialite.callback');
