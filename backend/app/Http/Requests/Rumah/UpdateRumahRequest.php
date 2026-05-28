<?php

namespace App\Http\Requests\Rumah;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRumahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rumah = $this->route('rumah');

        return [
            'blok_nomor' => [
                'sometimes',
                'string',
                'max:50',
                Rule::unique('rumah', 'blok_nomor')->ignore($rumah?->id),
            ],
        ];
    }
}