<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penghuni_id')->nullable()->constrained('penghuni')->nullOnDelete()->comment('Bisa null jika dibayarkan pihak lain');
            $table->foreignId('rumah_id')->constrained('rumah')->restrictOnDelete();
            $table->date('tanggal_bayar');
            $table->decimal('total_bayar', 12, 2);
            $table->string('metode_pembayaran', 50)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};