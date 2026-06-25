<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable(['catamaran_id', 'path', 'caption', 'sort_order'])]
class CatamaranPhoto extends Model
{
    protected static function booted(): void
    {
        static::deleting(function (CatamaranPhoto $photo): void {
            if (! str_starts_with($photo->path, 'http://') && ! str_starts_with($photo->path, 'https://')) {
                Storage::disk('public')->delete($photo->path);
            }
        });
    }

    public function catamaran(): BelongsTo
    {
        return $this->belongsTo(Catamaran::class);
    }

    protected function url(): Attribute
    {
        return Attribute::get(function (): string {
            if (str_starts_with($this->path, 'http://') || str_starts_with($this->path, 'https://')) {
                return $this->path;
            }

            if (str_starts_with($this->path, '/storage/')) {
                return $this->path;
            }

            return Storage::disk('public')->url($this->path);
        });
    }
}
