<x-filament-actions::actions
    :attributes="\Filament\Support\prepare_inherited_attributes($attributes)"
    :alignment="config('filament.layout.actions.modal.actions.alignment')"
    :dark-mode="config('filament.dark_mode')"
>
    {{ $slot }}
</x-filament-actions::actions>
