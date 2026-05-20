<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jurnal_entries', function (Blueprint $table) {
            $table->id();
            $table->string('kode_jurnal');
            $table->date('tanggal');
            $table->foreignId('akun_id')->constrained('akun_keuangans')->cascadeOnDelete();
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('kredit', 15, 2)->default(0);
            $table->string('keterangan')->nullable();
            $table->string('referensi_tipe')->nullable(); // barang_masuk or barang_keluar
            $table->unsignedBigInteger('referensi_id')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jurnal_entries');
    }
};
