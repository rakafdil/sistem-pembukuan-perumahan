<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PembayaranResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tanggal_bayar' => $this->tanggal_bayar->format('Y-m-d'),
            'total_bayar' => $this->total_bayar,
            'metode_pembayaran' => $this->metode_pembayaran,
            'catatan' => $this->catatan,
            'waktu_transaksi' => $this->created_at->format('d M Y H:i'),

            'rumah' => $this->whenLoaded('rumah', function () {
                return ['id' => $this->rumah->id, 'blok_nomor' => $this->rumah->blok_nomor];
            }),
            'penghuni' => $this->whenLoaded('penghuni', function () {
                return ['id' => $this->penghuni->id, 'nama_lengkap' => $this->penghuni->nama_lengkap];
            }),

            'detail_alokasi' => $this->whenLoaded('detailPembayaran', function () {
                return $this->detailPembayaran->map(function ($detail) {
                    return [
                        'tagihan_id' => $detail->tagihan_id,
                        'nominal_dialokasikan' => $detail->nominal_alokasi,
                        'jenis_iuran' => $detail->tagihan->jenisIuran->nama_iuran ?? null,
                        'periode' => ($detail->tagihan->periode_bulan ?? '') . '/' . ($detail->tagihan->periode_tahun ?? '')
                    ];
                });
            })
        ];
    }
}