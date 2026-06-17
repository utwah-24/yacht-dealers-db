<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['catamaran_id', 'departure', 'destinations'])]
class Route extends Model
{
    protected function casts(): array
    {
        return [
            'destinations' => 'array',
        ];
    }

    public function catamaran(): BelongsTo
    {
        return $this->belongsTo(Catamaran::class);
    }
}
