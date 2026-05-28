<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PengeluaranResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'deskripsi' => $this->deskripsi,
            'nominal' => $this->nominal,
            'tanggal_pengeluaran' => $this->tanggal_pengeluaran->format('Y-m-d'),
            'kategori' => $this->whenLoaded('kategori', function () {
                return [
                    'id' => $this->kategori->id,
                    'nama_kategori' => $this->kategori->nama_kategori,
                ];
            }),
        ];
    }
}