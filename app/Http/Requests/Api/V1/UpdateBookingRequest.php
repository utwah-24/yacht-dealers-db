<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\CharterType;
use App\Enums\LocationType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookingRequest extends FormRequest
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
            'catamaran_id' => ['sometimes', 'required', 'integer', 'exists:catamarans,id'],
            'catamaran_name' => ['nullable', 'string', 'max:255'],
            'location_type' => ['sometimes', 'required', Rule::enum(LocationType::class)],
            'charter_type' => ['sometimes', 'required', Rule::enum(CharterType::class)],
            'duration' => ['sometimes', 'required', 'integer', 'min:1'],
            'charter_price' => ['sometimes', 'required', 'numeric', 'min:0'],
        ];
    }
}
