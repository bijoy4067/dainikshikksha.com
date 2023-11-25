<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsResource\Pages;
use App\Filament\Resources\NewsResource\RelationManagers;
use App\Models\News;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Guava\FilamentDrafts\Admin\Resources\Concerns\Draftable;
use Filament\Tables\Columns\TextColumn;

class NewsResource extends Resource
{
    use Draftable;
    protected static ?string $model = News::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxValue(50)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', \Str::slug($state)) : null),
                Forms\Components\TextInput::make('slug')
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->unique(News::class, 'slug', ignoreRecord: true, ignorable: fn ($record) => $record),
                Forms\Components\TextInput::make('social_title')
                    ->label('Social Title')
                    ->maxValue(50)
                    ->live(onBlur: true),
                Forms\Components\TextInput::make('sub_title')
                    ->label('Sub Title')
                    ->maxValue(50)
                    ->live(onBlur: true),
                Forms\Components\TextInput::make('upper_title')
                    ->label('Upper Title')
                    ->maxValue(50)
                    ->live(onBlur: true),
                Forms\Components\MarkdownEditor::make('description')
                    ->label('News')
                    ->default(null)
                    ->columnSpan('full'),
                Forms\Components\MarkdownEditor::make('summery')
                    ->label('Summery')
                    ->default(null)
                    ->columnSpan('full'),
                Forms\Components\MarkdownEditor::make('social_summery')
                    ->label('Social Summery')
                    ->default(null)
                    ->columnSpan('full'),
                Select::make('author_id')
                    ->relationship(name: 'author', titleAttribute: 'name')
                    ->preload(),
                Select::make('category_id')
                    ->multiple()
                    ->relationship(name: 'category', titleAttribute: 'name')
                    ->preload(),
                Select::make('tags_id')
                    ->multiple()
                    ->relationship(name: 'tags', titleAttribute: 'name')
                    ->preload(),
                Select::make('lead_position')
                    ->multiple()
                    ->options([
                        'draft' => 'Draft',
                        'reviewing' => 'Reviewing',
                        'published' => 'Published',
                    ])
                    ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                        if ($operation === 'create' || $operation === 'update') {
                            $leadPosition = implode(',', $state);
                            $set('lead_position', $leadPosition);
                        }
                    }),
                SpatieMediaLibraryFileUpload::make('featured_image')
                    // ->multiple()
                    ->collection('featured_image')
                    ->responsiveImages(),
                SpatieMediaLibraryFileUpload::make('social_featured_image')
                    // ->multiple()
                    ->collection('social_featured_image')
                    ->responsiveImages(),
                Checkbox::make('show_created_at')
                    ->id('show_created_at')
                    ->label('Show Created At'),
                // ->inline(),
                Checkbox::make('show_updated_at')
                    ->id('show_updated_at')
                    ->label('Show Update At')
                    ->inline(),
                Checkbox::make('show_featured_image')
                    ->id('show_featured_image')
                    ->label('Show Featured Image')
                    ->inline(),
                Forms\Components\Toggle::make('language')
                    ->inline()
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
                Hidden::make('created_by')
                    ->default(auth()->id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->description(fn (News $record): string => \Str::limit($record->description, 20, '...') ?? '...'),
                // Column::make('user.name')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }
}
