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

    protected static ?string $navigationIcon = 'heroicon-s-user-circle';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required(),
                ColorPicker::make('color'),
                Forms\Components\MarkdownEditor::make('bio')
                    ->columnSpan('full'),

                SpatieMediaLibraryFileUpload::make('photo')
                    ->collection('authors')
                    ->responsiveImages(),
                Forms\Components\Toggle::make('status')
                    ->label('Active')
                    ->default(true)
                    ->columnSpan('full'),
                \Filament\Forms\Components\Select::make('language')
                    ->label('Language')
                    ->options([
                        'en' => 'English',
                        'bn' => 'Bangla',
                    ])
                    ->default('en')
                    ->id('language'),
                // ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('photo')
                    ->collection('authors')
                    ->conversion('thumb')
                    ->circular(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->alignLeft(),
                ColorColumn::make('color')
                    ->label('Color'),
                \Filament\Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('language')
                    ->getStateUsing(fn (Author $record): string => $record->language == 'en' ? 'en' : 'bn')
                    ->badge()
                    ->color(fn (Author $record): string => $record->language == 'en' ? 'success' : 'warning'),
                Tables\Columns\IconColumn::make('status')
                    ->label('Active')
                    ->boolean()
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('language')
                    ->options([
                        'en' => 'English',
                        'bn' => 'Bangla',
                    ]),
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
            ])
            ->paginated([25]);;
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
