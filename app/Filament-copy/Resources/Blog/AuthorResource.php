<?php

namespace App\Filament\Resources\Blog;

use App\Filament\Resources\Blog\AuthorResource\Pages;
use App\Models\Author;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource as FResource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class AuthorResource extends FResource
{
    protected static ?string $model = Author::class;

    protected static ?string $slug = 'admin/authors';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = '';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                ColorPicker::make('color'),
                Forms\Components\MarkdownEditor::make('bio')
                    ->columnSpan('full'),

                SpatieMediaLibraryFileUpload::make('photo')
                    ->collection('authors')
                    ->responsiveImages(),
                Forms\Components\Toggle::make('status')
                    ->label('Status')
                    ->default(true)
                    ->columnSpan('full'),
                Forms\Components\Toggle::make('language')
                    ->label(function (callable $get) {
                        if ($get('language')) {
                            return 'English';
                        } else {
                            return 'Bangla';
                        }
                    })
                    ->default(false)
                    ->id('language')
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Split::make([
                    SpatieMediaLibraryImageColumn::make('photo')
                        ->collection('authors')
                        ->conversion('thumb')
                        ->circular(),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('name')
                            ->searchable()
                            ->sortable()
                            ->weight('medium')
                            ->alignLeft()
                    ])->space(),
                    ColorColumn::make('color')
                        ->label('color'),
                    Tables\Columns\TextColumn::make('language')
                        ->getStateUsing(fn (Author $record): string => $record->language == 0 ? 'en' : 'bn')
                        ->badge()
                        ->color(fn (Author $record): string => $record->language == 0 ? 'success' : 'warning'),
                    Tables\Columns\IconColumn::make('status')
                        ->label('status')
                        ->boolean(),
                    // Tables\Columns\Layout\Stack::make([
                    //     Tables\Columns\TextColumn::make('github_handle')
                    //         ->icon('icon-github')
                    //         ->label('GitHub')
                    //         ->alignLeft(),

                    //     Tables\Columns\TextColumn::make('twitter_handle')
                    //         ->icon('icon-twitter')
                    //         ->label('Twitter')
                    //         ->alignLeft(),
                    // ])->space(2),
                ])->from('md'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->groupedBulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->action(function () {
                        Notification::make()
                            ->title('Now, now, don\'t be cheeky, leave some records for others to play with!')
                            ->warning()
                            ->send();
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAuthors::route('/'),
        ];
    }
}
