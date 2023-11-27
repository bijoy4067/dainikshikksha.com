<?php

namespace App\Filament\Pages;

use App\Filament\Resources\MenuResource\Widgets\MenuWidget;
use App\Settings\GeneralSettings;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;

class Settings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = GeneralSettings::class;

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
                            ])
                    ])
                    ->columnSpan(2)
            ]);
    }
    protected function getFooterWidgets(): array
    {
        return [
            MenuWidget::class
        ];
    }
}
