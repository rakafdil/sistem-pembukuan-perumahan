<?php

namespace App\Http\Requests\Rumah;

use Illuminate\Foundation\Http\FormRequest;

class StoreRumahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'blok_nomor' => ['required', 'string', 'max:50', 'unique:rumah,blok_nomor'],
            'penghuni_id' => ['sometimes', 'string'],
            'tanggal_mulai' => ['sometimes', 'date'],
        ];
    }
}