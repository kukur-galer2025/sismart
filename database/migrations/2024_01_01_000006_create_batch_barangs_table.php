<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batch_barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barangs')->cascadeOnDelete();
            $table->foreignId('barang_masuk_id')->constrained('barang_masuks')->cascadeOnDelete();
            $table->integer('jumlah_awal');
            $table->integer('jumlah_sisa');
            $table->decimal('harga_satuan', 15, 2);
            $table->date('tanggal_masuk');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batch_barangs');
    }
};
