<?php

namespace App\Http\Controllers;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect(string $provider)
    {
        // dd($provider, $this->validateProvider($provider));
        $this->validateProvider($provider);
        return Socialite::driver($provider)
            ->scopes(['email', 'profile'])
            ->redirectUrl(env(strtoupper($provider) . '_REDIRECT_URL'))
            ->redirect();
    }

    public function callback(string $provider)
    {
        $this->validateProvider($provider);
        $response = Socialite::driver($provider)->stateless()->user();

        $user = User::firstWhere(['email' => $response->getEmail()]);

        if (!$user) {
            $user = User::create([
                // $provider . '_id' => $response->getId(),
                'name'            => $response->getName(),
                'email'           => $response->getEmail(),
                'password'        => '',
            ]);
            // $user->update([$provider . '_id' => $response->getId()]);
        } else {
        }

        auth()->login($user);

        return redirect()->intended('/admin/news');
    }

    protected function validateProvider(string $provider): array
    {
        return $this->getValidationFactory()->make(
            ['provider' => $provider],
            ['provider' => 'in:google,facebook'],
            // ['provider' => 'in:facebook'],
        )->validate();
    }
}
