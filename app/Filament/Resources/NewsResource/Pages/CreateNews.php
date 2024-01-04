<?php

namespace App\Filament\Resources\NewsResource\Pages;

use App\Filament\Resources\NewsResource;
use App\Models\NewsCategory;
use Filament\Resources\Pages\CreateRecord;

class CreateNews extends CreateRecord
{
    protected static string $resource = NewsResource::class;

    protected function afterCreate(): void
    {
        // $category = Category::whereIn('id', $this->record->category_id)->get();
        // foreach ($category as $value) {
        //     dd($this->record, $value);
        //     $this->record->news_category->sync($value);
        // }
        // $this->record->save();
        // if (is_array($this->record->category_id)) {
        //     foreach ($this->record->category_id as $category_id) {
        //         $news_comments = NewsCategory::where([
        //             'news_id' => $this->record->id,
        //             'category_id' => $category_id
        //         ])->first();

        //         if (is_null($news_comments)) {
        //             $news_comments = new NewsCategory();
        //             $news_comments->news_id = $this->record->id;
        //             $news_comments->category_id = $category_id;
        //             $news_comments->save();
        //         }
        //     }
        // }
    }
}
