<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCatamaranRouteRequest extends FormRequest
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
            'departure' => ['sometimes', 'required', 'string', 'max:255'],
            'destinations' => ['sometimes', 'required', 'array', 'min:1'],
            'destinations.*' => ['required', 'string'],
        ];
    }
}
