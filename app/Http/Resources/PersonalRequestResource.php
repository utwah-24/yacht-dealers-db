<?php

namespace App\Http\Resources;

use App\Models\PersonalRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin PersonalRequest */
class PersonalRequestResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'booking_id' => $this->booking_id,
            'reg' => $this->reg,
            'status' => $this->status,
            'others' => OtherResource::collection($this->whenLoaded('others')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
