<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tag extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'tags';

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
        'language' => 'boolean',
    ];
}
