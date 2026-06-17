<?php

namespace App\Http\Resources;

use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Route */
class CatamaranRouteResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'catamaran_id' => $this->catamaran_id,
            'departure' => $this->departure,
            'destinations' => $this->destinations,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
