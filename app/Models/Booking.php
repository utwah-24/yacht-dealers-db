<?php

namespace App\Models;

use App\Enums\CharterType;
use App\Enums\LocationType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'catamaran_id',
    'catamaran_name',
    'location_type',
    'charter_type',
    'duration',
    'charter_price',
])]
class Booking extends Model
{
    protected static function booted(): void
    {
        static::saving(function (Booking $booking): void {
            if ($booking->catamaran_id && ($booking->isDirty('catamaran_id') || blank($booking->catamaran_name))) {
                $booking->catamaran_name = Catamaran::query()
                    ->whereKey($booking->catamaran_id)
                    ->value('name');
            }
        });
    }

    protected function casts(): array
    {
        return [
            'location_type' => LocationType::class,
            'charter_type' => CharterType::class,
            'duration' => 'integer',
            'charter_price' => 'decimal:2',
        ];
    }

    public function catamaran(): BelongsTo
    {
        return $this->belongsTo(Catamaran::class);
    }

    public function extras(): HasOne
    {
        return $this->hasOne(Extra::class);
    }

    public function personalRequests(): HasMany
    {
        return $this->hasMany(PersonalRequest::class);
    }

    public function guest(): HasOne
    {
        return $this->hasOne(Guest::class);
    }

    public function summary(): HasOne
    {
        return $this->hasOne(Summary::class);
    }
}
