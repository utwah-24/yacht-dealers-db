<?php

namespace App\Http\Resources;

use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Guest */
class GuestResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'booking_id' => $this->booking_id,
            'name' => $this->name,
            'date' => $this->date?->toDateString(),
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'number_of_passengers' => $this->number_of_passengers,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
