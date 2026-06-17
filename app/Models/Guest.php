<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'booking_id',
    'name',
    'date',
    'phone_number',
    'email',
    'number_of_passengers',
])]
class Guest extends Model
{
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'number_of_passengers' => 'integer',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function summary(): HasOne
    {
        return $this->hasOne(Summary::class);
    }
}
