<?php

namespace App\Http\Requests\Pembayaran;

use Illuminate\Foundation\Http\FormRequest;

class StorePembayaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'penghuni_id' => ['required', 'exists:penghuni,id'],
            'rumah_id' => ['required', 'exists:rumah,id'],
            'total_bayar' => ['required', 'numeric', 'min:1'],
            'metode_pembayaran' => ['required', 'string'],
            'detail' => ['nullable', 'array'],
        ];
    }
}
