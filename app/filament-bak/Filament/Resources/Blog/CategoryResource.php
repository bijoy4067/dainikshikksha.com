<?php

namespace App\Filament\Resources\Blog;

use App\Filament\Resources\Blog\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\ColorPicker;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;

class CategoryResource extends Resource
{
    protected static ?string $model =   Category::class;

    protected static ?string $slug = 'admin/categories';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = '';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxValue(50)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                Forms\Components\TextInput::make('slug')
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->unique(Category::class, 'slug', ignoreRecord: true),
                ColorPicker::make('color'),
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
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),
                ColorColumn::make('color')
                    ->label('color'),
                Tables\Columns\TextColumn::make('language'),
                Tables\Columns\TextColumn::make('language')
                    ->getStateUsing(fn (Category $record): string => $record->language == 0 ? 'en' : 'bn')
                    ->badge()
                    ->color(fn (Category $record): string => $record->language == 0 ? 'success' : 'warning'),
                Tables\Columns\IconColumn::make('status')
                    ->label('status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->date(),
            ])
            ->filters([

                SelectFilter::make('language')
                    ->options([
                        1 => 'Bangla',
                        0 => 'English',
                    ])
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name'),
                TextEntry::make('slug'),
                IconEntry::make('status')
                    ->label('Status')
                    ->boolean(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ])
            ->columns(1)
            ->inlineLabel();
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
            'index' => Pages\ManageCategories::route('/'),
        ];
    }
}
