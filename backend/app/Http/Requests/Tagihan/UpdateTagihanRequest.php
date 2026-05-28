<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTagihanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nominal_tagihan' => ['sometimes', 'numeric', 'min:1'],
            'status_pembayaran' => ['sometimes', 'in:belum_bayar,sebagian,lunas'],
        ];
    }
}