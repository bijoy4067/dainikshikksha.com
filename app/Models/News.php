<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class News extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $table = 'news';

    protected $casts = [
        'category_id' => 'array',
        'tag_id' => 'array',
        'lead_position' => 'array',
        'featured_image' => 'array',
        'social_featured_image' => 'array',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public  function tags()
    {
        return $this->belongsTo(Tag::class, 'tag_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function news_category()
    {
        // return $this->belongsToMany(
        //     related: Category::class,
        //     table: 'news_category',
        //     foreignPivotKey: 'category_id',
        //     relatedPivotKey: 'news_id'
        // );

        return $this->belongsTo(NewsCategory::class, 'news_id', 'id');
    }
}
