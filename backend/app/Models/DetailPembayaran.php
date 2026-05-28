<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPembayaran extends Model
{
    protected $table = 'detail_pembayaran';

 
    public const UPDATED_AT = null;

    protected $fillable = [
        'pembayaran_id',
        'tagihan_id',
        'nominal_alokasi',
    ];

    protected $casts = [
        'nominal_alokasi' => 'decimal:2',
    ];


    public function pembayaran(): BelongsTo
    {
        return $this->belongsTo(Pembayaran::class, 'pembayaran_id');
    }


    public function tagihan(): BelongsTo
    {
        return $this->belongsTo(Tagihan::class, 'tagihan_id');
    }
}