<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'services_provided', 'description'])]
class Catamaran extends Model
{
    public function photos(): HasMany
    {
        return $this->hasMany(CatamaranPhoto::class)->orderBy('sort_order');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function routes(): HasMany
    {
        return $this->hasMany(Route::class);
    }
}
