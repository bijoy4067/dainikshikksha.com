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
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;

class NewsResource extends Resource
{
    use Draftable;
    protected static ?string $model = News::class;
    protected static ?string $slug = 'admin/news';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = '';

    protected static ?string $navigationIcon = 'heroicon-s-pencil-square';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxValue(50)
                    ->live(onBlur: true),
                Forms\Components\TextInput::make('social_title')
                    ->label('Seo Title')
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
                    ->label('Description')
                    ->default(null)
                    ->columnSpan('full'),
                Forms\Components\MarkdownEditor::make('social_summery')
                    ->label('Seo Description')
                    ->default(null)
                    ->columnSpan('full'),
                Select::make('author_id')
                    ->relationship(name: 'author', titleAttribute: 'title')
                    ->preload(),
                Select::make('category_id')
                    ->multiple()
                    ->relationship(name: 'category', titleAttribute: 'title')
                    ->preload(),
                Select::make('tags_id')
                    ->multiple()
                    ->relationship(name: 'tags', titleAttribute: 'title')
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
                \Filament\Forms\Components\Select::make('language')
                    ->label('Language')
                    ->options([
                        'en' => 'English',
                        'bn' => 'Bangla',
                    ])
                    ->default('en')
                    ->id('language'),
                Hidden::make('updated_by')
                    ->default(auth()->id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->description(fn (News $record): string => \Str::limit($record->description, 20, '...') ?? '...')
                    ->searchable(),
                Tables\Columns\TextColumn::make('language')
                    ->getStateUsing(fn (News $record): string => $record->language == 'en' ? 'en' : 'bn')
                    ->badge()
                    ->color(fn (News $record): string => $record->language == 'en' ? 'success' : 'warning'),
                TextColumn::make('created_at')
                    ->dateTime(),
                TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                Filter::make('title')
                    ->label('Title'),
                SelectFilter::make('language')
                    ->options([
                        'en' => 'English',
                        'bn' => 'bangla',
                    ]),
                SelectFilter::make('category_id')
                    ->label('category')
                    ->multiple()
                    ->relationship('category', 'title'),
                SelectFilter::make('tag_id')
                    ->label('Tag')
                    ->multiple()
                    ->relationship('tag', 'title'),
                SelectFilter::make('author_id')
                    ->label('Author')
                    ->relationship('author', 'title')
                // ->attribute('category_id'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->paginated([25]);
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
