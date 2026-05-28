<?php

namespace App\Http\Requests\Keuangan;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePengeluaranRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kategori_id' => 'nullable|exists:kategori_pengeluaran,id',
            'deskripsi' => 'sometimes|string',
            'nominal' => 'sometimes|numeric|min:1',
            'tanggal_pengeluaran' => 'sometimes|date',
        ];
    }
}
