<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRouteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (! is_string($this->input('destinations'))) {
            return;
        }

        $destinations = collect(preg_split('/[\r\n,]+/', $this->input('destinations')))
            ->map(fn (string $destination) => trim($destination))
            ->filter()
            ->values()
            ->all();

        $this->merge([
            'destinations' => $destinations,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'catamaran_id' => ['required', 'integer', 'exists:catamarans,id'],
            'departure' => ['required', 'string', 'max:255'],
            'destinations' => ['required', 'array', 'min:1'],
            'destinations.*' => ['required', 'string', 'max:255'],
        ];
    }
}
