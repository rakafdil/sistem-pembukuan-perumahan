<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rumah_id')->constrained('rumah')->restrictOnDelete();
            $table->foreignId('jenis_iuran_id')->constrained('jenis_iuran')->restrictOnDelete();
            $table->integer('periode_bulan')->comment('1 - 12');
            $table->integer('periode_tahun')->comment('YYYY');
            $table->decimal('nominal_tagihan', 12, 2);
            $table->enum('status_pembayaran', ['belum_bayar', 'sebagian', 'lunas'])->default('belum_bayar');
            $table->timestamps();
            
            $table->unique(['rumah_id', 'jenis_iuran_id', 'periode_bulan', 'periode_tahun'], 'tagihan_unique_idx');
            $table->index('status_pembayaran');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihan');
    }
};