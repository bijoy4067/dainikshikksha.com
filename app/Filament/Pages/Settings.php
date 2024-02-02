<?php

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;

class Settings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $slug = 'admin/settings';
    protected static string $settings = GeneralSettings::class;
    protected static bool $shouldRegisterNavigation = false;

    // protected static bool $canAccessPanel = false;
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Settings')
                    ->tabs([
                        Tabs\Tab::make('English')
                            ->schema([
                                TextInput::make('site_title_en')
                                    ->label('Site Title English')
                                    ->string()
                                    ->maxWidth(50),
                                Textarea::make('site_tagline_en')
                                    ->label('Site Tagline English')
                                    ->maxWidth(50),
                                Forms\Components\MarkdownEditor::make('contacts_en')
                                    ->label('Contacts English')
                                    ->default(null),
                                Forms\Components\MarkdownEditor::make('email_en')
                                    ->label('Emails English')
                                    ->default(null),
                                Forms\Components\MarkdownEditor::make('ads_en')
                                    ->label('Ads English')
                                    ->default(null),
                                Repeater::make('menu_links_en')
                                    ->label('Menu Links English')
                                    ->schema([
                                        TextInput::make('label')->required(),
                                        Select::make('target')
                                            ->options([
                                                '_self' => 'Current Page (_self)',
                                                '_blank' => 'New Page (_blank)',
                                            ])
                                            ->default('_self'),
                                        TextInput::make('link')->required()
                                    ])
                                    ->collapsible()
                                    ->columns(3)
                                    ->collapseAllAction(
                                        fn (\Filament\Forms\Components\Actions\Action $action) => $action->label('Collapse all menu'),
                                    )
                                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),
                                Repeater::make('social_links_en')
                                    ->schema([
                                        TextInput::make('label')->required(),
                                        Select::make('target')
                                            ->options([
                                                '_self' => 'Current Page (_self)',
                                                '_blank' => 'New Page (_blank)',
                                            ])
                                            ->default('_self'),
                                        TextInput::make('link')->required()
                                    ])
                                    ->collapsible()
                                    ->columns(3)
                                    ->collapseAllAction(
                                        fn (\Filament\Forms\Components\Actions\Action $action) => $action->label('Collapse all menu'),
                                    )
                                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),
                                Repeater::make('footer_links_en')
                                    ->schema([
                                        TextInput::make('label')->required(),
                                        Select::make('target')
                                            ->options([
                                                '_self' => 'Current Page (_self)',
                                                '_blank' => 'New Page (_blank)',
                                            ])
                                            ->default('_self'),
                                        TextInput::make('link')->required()
                                    ])
                                    ->collapsible()
                                    ->columns(3)
                                    ->collapseAllAction(
                                        fn (\Filament\Forms\Components\Actions\Action $action) => $action->label('Collapse all menu'),
                                    )
                                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),

                            ]),
                        Tabs\Tab::make('Bangla')
                            ->schema([
                                TextInput::make('site_title_bn')
                                    ->label('Site Title Bangla')
                                    ->default(''),
                                Textarea::make('site_tagline_bn')
                                    ->label('Site Tagline Bangla'),
                                Forms\Components\MarkdownEditor::make('contacts_bn')
                                    ->label('Contacts Bangla')
                                    ->default(null),
                                Forms\Components\MarkdownEditor::make('email_bn')
                                    ->label('Bangla Bangla')
                                    ->default(null),
                                Forms\Components\MarkdownEditor::make('ads_bn')
                                    ->label('Ads Bangla')
                                    ->default(null),
                                Repeater::make('menu_links_bn')
                                    ->schema([
                                        TextInput::make('label')->required(),
                                        Select::make('target')
                                            ->options([
                                                '_self' => 'Current Page (_self)',
                                                '_blank' => 'New Page (_blank)',
                                            ])
                                            ->default('_self'),
                                        TextInput::make('link')->required()
                                    ])
                                    ->collapsible()
                                    ->columns(3)
                                    ->collapseAllAction(
                                        fn (\Filament\Forms\Components\Actions\Action $action) => $action->label('Collapse all menu'),
                                    )
                                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),
                                Repeater::make('social_links_bn')
                                    ->schema([
                                        TextInput::make('label')->required(),
                                        Select::make('target')
                                            ->options([
                                                '_self' => 'Current Page (_self)',
                                                '_blank' => 'New Page (_blank)',
                                            ])
                                            ->default('_self'),
                                        TextInput::make('link')->required()
                                    ])
                                    ->collapsible()
                                    ->columns(3)
                                    ->collapseAllAction(
                                        fn (\Filament\Forms\Components\Actions\Action $action) => $action->label('Collapse all menu'),
                                    )
                                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),
                                Repeater::make('footer_links_bn')
                                    ->schema([
                                        TextInput::make('label')->required(),
                                        Select::make('target')
                                            ->options([
                                                '_self' => 'Current Page (_self)',
                                                '_blank' => 'New Page (_blank)',
                                            ])
                                            ->default('_self'),
                                        TextInput::make('link')->required()
                                    ])
                                    ->collapsible()
                                    ->columns(3)
                                    ->collapseAllAction(
                                        fn (\Filament\Forms\Components\Actions\Action $action) => $action->label('Collapse all menu'),
                                    )
                                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),
                            ])
                    ])
                    ->columnSpan(2)
            ]);
    }

    // public function mount(): void
    // {
    //     $user = auth()->user();
    //     if (!$user->hasRole(['Super Admin'])) {
    //         abort(403);
    //     }
    // }
    // public static function isDiscovered(): bool
    // {
    //     // dd(self::$canAccessPanel, 'asdfsd', $user = auth()->user());
    //     return self::$canAccessPanel;
    // }

    // public function mount(): void
    // {
    //     static::authorizeResourceAccess();
    // }
}
