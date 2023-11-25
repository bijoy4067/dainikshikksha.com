<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TagResource\Pages;
use App\Filament\Resources\TagResource\RelationManagers;
use App\Models\Tag;
use Faker\Core\Color;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TagResource extends Resource
{
    // protected static ?string $model = Tag::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    // protected static ?string $navigationGroup = 'User';
    // protected static ?int $navigationSort = 2;

    protected static ?string $model =   Tag::class;

    protected static ?string $slug = 'admin/tag';

    // protected static ?string $recordTitleAttribute = 'name';

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
                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', \Str::slug($state)) : null),

                Forms\Components\TextInput::make('slug')
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->unique(Tag::class, 'slug', ignoreRecord: true),
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
                    ->getStateUsing(fn (Tag $record): string => $record->language == 0 ? 'en' : 'bn')
                    ->badge()
                    ->color(fn (Tag $record): string => $record->language == 0 ? 'success' : 'warning'),
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTags::route('/'),
        ];
    }
}
