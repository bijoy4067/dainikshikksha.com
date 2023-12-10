<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BasePage;

class Login extends BasePage
{
    public function authenticated(): void
    {
        // Perform any additional logic you need here

        // Redirect to another page after successful login
        $this->redirect('/sdfasd'); // Replace '/dashboard' with the desired URL
    }
    public function mount(): void
    {
        parent::mount();
        $this->form->fill([
            'email' => 'admin@filamentphp.com',
            'password' => 'password',
            'remember' => true,
        ]);
    }
}
