<?php

namespace App\Http\Resources;

use App\Models\Summary;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Summary */
class SummaryResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'booking_id' => $this->booking_id,
            'guest_id' => $this->guest_id,
            'catamaran_photo_id' => $this->catamaran_photo_id,
            'guest' => GuestResource::make($this->whenLoaded('guest')),
            'photo' => CatamaranPhotoResource::make($this->whenLoaded('photo')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
