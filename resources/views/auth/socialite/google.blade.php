<div class="text-center">
    <x-filament::button
        :href="route('socialite.redirect', 'google')"
        tag="a"
        color="secondary"
        style="background: #fff"
    >
        <img src="{{ asset('images/google.png') }}" alt="google login" style="width: 40px">
    </x-filament::button>
    <x-filament::button
        :href="route('socialite.redirect', 'facebook')"
        tag="a"
        color="secondary"
        style="background: #fff"
    >
        <img src="{{ asset('images/facebook.png') }}" alt="facebook login" style="width: 40px">
    </x-filament::button>
</div>
