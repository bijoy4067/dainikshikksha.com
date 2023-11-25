<?php

namespace App\Filament\Resources\NewsResource\Pages;

use App\Filament\Resources\NewsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Guava\FilamentDrafts\Admin\Resources\Pages\Create\Draftable;

class CreateNews extends CreateRecord
{
    use Draftable;
    protected static string $resource = NewsResource::class;
}
