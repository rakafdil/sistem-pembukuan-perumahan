<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tagihan extends Model
{
    use HasFactory;

    protected $table = 'tagihan';

    protected $fillable = [
        'rumah_id',
        'jenis_iuran_id',
        'periode_bulan',
        'periode_tahun',
        'nominal_tagihan',
        'status_pembayaran',
    ];

    protected $casts = [
        'periode_bulan' => 'integer',
        'periode_tahun' => 'integer',
        'nominal_tagihan' => 'decimal:2',
    ];

    public function rumah(): BelongsTo
    {
        return $this->belongsTo(Rumah::class, 'rumah_id');
    }

    public function jenisIuran(): BelongsTo
    {
        return $this->belongsTo(JenisIuran::class, 'jenis_iuran_id');
    }

    public function detailPembayaran(): HasMany
    {
        return $this->hasMany(DetailPembayaran::class, 'tagihan_id');
    }

    public function scopeBelumLunas($query)
    {
        return $query->whereIn('status_pembayaran', ['belum_bayar', 'sebagian']);
    }
}