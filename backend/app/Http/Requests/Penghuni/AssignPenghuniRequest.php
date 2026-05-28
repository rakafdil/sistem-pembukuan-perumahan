<?php

namespace App\Http\Requests\Penghuni;

use Illuminate\Foundation\Http\FormRequest;

class AssignPenghuniRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'penghuni_id' => ['required', 'exists:penghuni,id'],
            'tanggal_mulai' => ['required', 'date'],
        ];
    }
}
