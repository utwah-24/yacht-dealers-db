<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreCatamaranRouteRequest extends FormRequest
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
            'departure' => ['required', 'string', 'max:255'],
            'destinations' => ['required', 'array', 'min:1'],
            'destinations.*' => ['required', 'string'],
        ];
    }
}
