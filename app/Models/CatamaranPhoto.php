<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['catamaran_id', 'path', 'caption', 'sort_order'])]
class CatamaranPhoto extends Model
{
    public function catamaran(): BelongsTo
    {
        return $this->belongsTo(Catamaran::class);
    }
}
