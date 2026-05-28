<?php

namespace App\Http\Requests\Penghuni;

use Illuminate\Foundation\Http\FormRequest;

class UpsertPenghuniRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'foto_ktp' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'nomor_telepon' => ['nullable', 'string', 'max:20'],
        ];

        if ($this->isMethod('post')) {
            $rules['nama_lengkap'] = ['required', 'string', 'max:150'];
            $rules['status_penghuni'] = ['required', 'in:tetap,kontrak'];
            $rules['status_menikah'] = ['required', 'boolean'];
        } else {
            $rules['nama_lengkap'] = ['sometimes', 'string', 'max:150'];
            $rules['status_penghuni'] = ['sometimes', 'in:tetap,kontrak'];
            $rules['status_menikah'] = ['sometimes', 'boolean'];
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        if ($this->has('status_menikah')) {
            $statusMenikah = $this->status_menikah;

            if (is_string($statusMenikah)) {
                $statusMenikah = strtolower($statusMenikah);
                if ($statusMenikah === 'true' || $statusMenikah === '1') {
                    $this->merge(['status_menikah' => true]);
                } elseif ($statusMenikah === 'false' || $statusMenikah === '0') {
                    $this->merge(['status_menikah' => false]);
                }
            }
        }
    }

    public function messages(): array
    {
        return [
            'foto_ktp.image' => 'File harus berupa gambar.',
            'foto_ktp.max' => 'Ukuran foto maksimal adalah 2MB.',
            'status_penghuni.in' => 'Status penghuni harus tetap atau kontrak.',
        ];
    }
}