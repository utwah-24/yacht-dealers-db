<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreSummaryRequest extends FormRequest
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
            'guest_id' => ['nullable', 'integer', 'exists:guests,id'],
            'catamaran_photo_id' => ['required', 'integer', 'exists:catamaran_photos,id'],
        ];
    }
}
