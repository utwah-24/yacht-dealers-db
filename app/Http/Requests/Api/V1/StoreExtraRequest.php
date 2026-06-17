<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreExtraRequest extends FormRequest
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
            'additional_services' => ['required', 'string', 'max:255'],
            'additional_cost' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'boolean'],
            'description' => ['nullable', 'string'],
        ];
    }
}
