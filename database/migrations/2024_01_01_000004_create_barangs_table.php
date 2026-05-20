<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->foreignId('kategori_id')->nullable()->constrained('kategoris')->nullOnDelete();
            $table->string('satuan')->default('pcs');
            $table->integer('stok')->default(0);
            $table->decimal('harga_rata_rata', 15, 2)->default(0);
            $table->decimal('total_nilai', 15, 2)->default(0);
            $table->integer('safety_stock')->default(10);
            $table->integer('lead_time')->default(3); // in days
            $table->integer('pemakaian_rata_rata')->default(0); // avg daily usage
            $table->integer('pemakaian_maksimum')->default(0); // max daily usage
            $table->enum('metode_stok', ['fifo', 'average'])->default('average');
            $table->string('lokasi')->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
