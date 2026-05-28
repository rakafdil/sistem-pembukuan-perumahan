<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pembayaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pembayaran';

    protected $fillable = [
        'penghuni_id',
        'rumah_id',
        'tanggal_bayar',
        'total_bayar',
        'metode_pembayaran',
        'catatan',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'total_bayar' => 'decimal:2',
    ];

    public function penghuni(): BelongsTo
    {
        return $this->belongsTo(Penghuni::class, 'penghuni_id');
    }

    public function rumah(): BelongsTo
    {
        return $this->belongsTo(Rumah::class, 'rumah_id');
    }


    public function detailPembayaran(): HasMany
    {
        return $this->hasMany(DetailPembayaran::class, 'pembayaran_id');
    }
}