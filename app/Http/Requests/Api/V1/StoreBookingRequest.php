<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\CharterType;
use App\Enums\LocationType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'catamaran_id' => ['required', 'integer', 'exists:catamarans,id'],
            'catamaran_name' => ['nullable', 'string', 'max:255'],
            'location_type' => ['required', Rule::enum(LocationType::class)],
            'charter_type' => ['required', Rule::enum(CharterType::class)],
            'duration' => ['required', 'integer', 'min:1'],
            'charter_price' => ['required', 'numeric', 'min:0'],
        ];
    }
}
