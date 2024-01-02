<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RalphJSmit\Filament\MediaLibrary\Media\Models\MediaLibraryItem;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Author extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'authors';

    public function photo(): BelongsTo
    {
        return $this->belongsTo(MediaLibraryItem::class, 'photo_id', 'id');
    }
}
