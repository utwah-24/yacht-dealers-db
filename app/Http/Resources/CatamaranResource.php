<?php

namespace App\Http\Resources;

use App\Models\Catamaran;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Catamaran */
class CatamaranResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'services_provided' => $this->services_provided,
            'description' => $this->description,
            'photos' => CatamaranPhotoResource::collection($this->whenLoaded('photos')),
            'routes' => CatamaranRouteResource::collection($this->whenLoaded('routes')),
            'bookings' => BookingResource::collection($this->whenLoaded('bookings')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
