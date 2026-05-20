<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatchBarang extends Model
{
    protected $fillable = [
        'barang_id', 'barang_masuk_id', 'jumlah_awal',
        'jumlah_sisa', 'harga_satuan', 'tanggal_masuk',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_masuk' => 'date',
            'harga_satuan' => 'decimal:2',
        ];
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    public function barangMasuk(): BelongsTo
    {
        return $this->belongsTo(BarangMasuk::class);
    }
}
