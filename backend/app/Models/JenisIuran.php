<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisIuran extends Model
{
    use HasFactory;

    protected $table = 'jenis_iuran';

    protected $fillable = [
        'nama_iuran',
        'nominal_default',
    ];

    protected $casts = [
        'nominal_default' => 'decimal:2',
    ];

    public function tagihan(): HasMany
    {
        return $this->hasMany(Tagihan::class, 'jenis_iuran_id');
    }
}