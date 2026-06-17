<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'booking_id',
    'additional_services',
    'additional_cost',
    'status',
    'description',
])]
class Extra extends Model
{
    protected function casts(): array
    {
        return [
            'additional_cost' => 'decimal:2',
            'status' => 'boolean',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
