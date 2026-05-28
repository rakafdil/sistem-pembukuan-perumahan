<?php

namespace App\Http\Requests\Keuangan;

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
            'rumah_id' => ['required', 'exists:rumah,id'],
            'penghuni_id' => ['nullable', 'exists:penghuni,id'],
            'tanggal_bayar' => ['required', 'date'],
            'total_bayar' => ['required', 'numeric', 'min:1'],
            'metode_pembayaran' => ['nullable', 'string', 'max:50'],
            'catatan' => ['nullable', 'string'],
            'tagihan_ids' => ['required', 'array', 'min:1'],
            'tagihan_ids.*' => ['exists:tagihan,id']
        ];
    }
}