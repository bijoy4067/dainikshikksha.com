<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    public function category(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public  function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
