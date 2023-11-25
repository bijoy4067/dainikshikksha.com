<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Guava\FilamentDrafts\Concerns\HasDrafts;
use Spatie\MediaLibrary\HasMedia;

class News extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasDrafts;
    protected $table = 'news';

    protected $casts = [
        'category_id' => 'array',
        'tag_id' => 'array',
        'lead_position' => 'array',
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
}
