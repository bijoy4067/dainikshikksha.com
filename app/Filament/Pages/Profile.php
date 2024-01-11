<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.pages.profile';

    protected static ?string $slug = 'admin/profile';


    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'User';

    protected static ?int $navigationSort = 2;



    public ?array $data = [];

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->rules('min:3', 'max:255'),
                TextInput::make('email')
                    ->disabled(),
                TextInput::make('password')
                    ->label('Change Password')
                    ->password()
                    ->maxLength(255)
            ])
            ->columns(2)
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Update Profile')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
            if ($data['password'] == null) {
                $data['password'] = auth()->user()->password;
            } else {
                $data['password'] = \Hash::make($data['password']);
            }

            if (auth()->user()->update($data)) {
                Notification::make()
                    ->title('Profile Update Successfully')
                    ->success()
                    ->send();
            }
        } catch (Halt $exception) {
            dd($exception);
            return;
        }
    }

    public function mount(): void
    {
        $user = auth()->user();
        $this->form->fill(
            [
                'name' => $user->name,
                'email' => $user->email,
                'avater' => $user->avater,
                'created_at' => $user->created_at,
            ]
        );
    }
}
