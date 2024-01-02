<?php

namespace RalphJSmit\Filament\MediaLibrary\Media\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;
use RalphJSmit\Filament\MediaLibrary\FilamentMediaLibrary;

class MediaLibraryFolder extends Model
{
    protected $table = 'filament_media_library_folders';

    protected $guarded = [];

    public static function booted(): void
    {
        static::deleting(function (self $mediaLibraryFolder): void {
            $mediaLibraryFolder->children()->lazy()->each(function (self $mediaLibraryFolder) {
                $mediaLibraryFolder->delete();
            });

            $mediaLibraryFolder->mediaLibraryItems()->update([
                'folder_id' => $mediaLibraryFolder->parent_id,
            ]);
        });
    }

    public function deleteRecursive(): void
    {
        $this->children()->lazy()->each(function (self $mediaLibraryFolder) {
            $mediaLibraryFolder->deleteRecursive();
        });

        $this->mediaLibraryItems()->lazy()->each(function (MediaLibraryItem $mediaLibraryItem) {
            $mediaLibraryItem->delete();
        });

        $this->delete();
    }

    public function mediaLibraryItems(): HasMany
    {
        return $this->hasMany(FilamentMediaLibrary::get()->getModelItem(), 'folder_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    public function getAncestors(?int $level = null): Collection
    {
        return match ($this->getConnection()->getDriverName()) {
            // This Pqsql query might work for MySQL as well, but I haven't tested it.
            // I will implement it once the other MySQL query becomes really outdated.
            'pgsql' => $this->getAncestorsPgsql($level),
            default => $this->getAncestorsMysql($level),
        };
    }

    protected function getAncestorsMysql(?int $level = null): Collection
    {
        $mediaLibraryFoldersTable = ( new static() )->getTable();

        return static::query()
            ->selectRaw('T2.*')
            ->fromSub(function (Builder $query) use ($mediaLibraryFoldersTable) {
                return $query
                    ->selectRaw(
                        <<<SQL
                        @r AS _id,
                        (SELECT @r := parent_id FROM {$mediaLibraryFoldersTable} WHERE id = _id) AS parent_id,
                        @l := @l + 1 AS level
                    SQL
                    )
                    ->fromRaw(
                        "(SELECT @r := ?, @l := 0) vars,
                        {$mediaLibraryFoldersTable} alias_one",
                        [$this->getKey()]
                    )
                    ->where(new Expression('@r'), '!=', 0);
            }, 'T1')
            ->join(new Expression($mediaLibraryFoldersTable . ' as T2'), 'T1._id', '=', 'T2.id')
            ->when($level, fn (EloquentBuilder $query) => $query->where('T1.level', '<=', $level))
            ->orderByDesc('T1.level')
            ->get();
    }

    protected function getAncestorsPgsql(?int $level = null): Collection
    {
        $mediaLibraryFoldersTable = ( new static() )->getTable();

        $folders = DB::select(
            <<<PGSQL
            WITH RECURSIVE T1 AS (
            SELECT
            alias_one.id AS _id,
            alias_one.parent_id,
            0 AS level
            FROM {$mediaLibraryFoldersTable} alias_one
            WHERE alias_one.id = ?
            UNION ALL
            SELECT
            fm.id AS _id,
            fm.parent_id,
            T1.level + 1
            FROM {$mediaLibraryFoldersTable} fm
            INNER JOIN T1 ON fm.id = T1.parent_id
            WHERE T1.level <= ?
            )
            
            SELECT T2.*
            FROM T1
            INNER JOIN {$mediaLibraryFoldersTable} T2 ON T1._id = T2.id
            ORDER BY T1.level DESC
            PGSQL,
            [$this->getKey(), $level]
        );

        return static::hydrate($folders);
    }
}
