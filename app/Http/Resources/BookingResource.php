<?php

namespace App\Http\Resources;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Booking */
class BookingResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'catamaran_id' => $this->catamaran_id,
            'catamaran_name' => $this->catamaran_name,
            'location_type' => $this->location_type?->value,
            'location_label' => $this->location_type?->label(),
            'charter_type' => $this->charter_type?->value,
            'charter_label' => $this->charter_type?->label(),
            'duration' => $this->duration,
            'charter_price' => $this->charter_price,
            'catamaran' => CatamaranResource::make($this->whenLoaded('catamaran')),
            'extras' => ExtraResource::make($this->whenLoaded('extras')),
            'personal_requests' => PersonalRequestResource::collection($this->whenLoaded('personalRequests')),
            'guest' => GuestResource::make($this->whenLoaded('guest')),
            'summary' => SummaryResource::make($this->whenLoaded('summary')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
