<?php

use App\Livewire\Form;
use App\Models\User;
use Spatie\Permission\Models\Role;

\Illuminate\Support\Facades\Route::get('form', Form::class);
\Illuminate\Support\Facades\Route::get('test', function () {
    // Role::create(['name' => 'editor']);
    $user = User::find(3);
    $user->assignRole('editor');
    dd($user, User::all());
});
