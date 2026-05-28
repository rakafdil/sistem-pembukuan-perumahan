<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('histori_huni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penghuni_id')->constrained('penghuni')->cascadeOnDelete();
            $table->foreignId('rumah_id')->constrained('rumah')->cascadeOnDelete();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable()->comment('Null = Masih menghuni');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('histori_huni');
    }
};