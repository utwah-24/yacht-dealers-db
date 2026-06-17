<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'booking_id',
    'reg',
    'status',
])]
class PersonalRequest extends Model
{
    protected $table = 'personal_requests';

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function others(): HasMany
    {
        return $this->hasMany(Other::class);
    }
}
