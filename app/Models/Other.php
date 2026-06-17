<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'personal_request_id',
    'type',
    'description',
])]
class Other extends Model
{
    protected $table = 'others';

    public function personalRequest(): BelongsTo
    {
        return $this->belongsTo(PersonalRequest::class);
    }
}
