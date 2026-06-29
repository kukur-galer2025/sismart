<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangKeluar extends Model
{
    protected $fillable = [
        'kode_transaksi', 'barang_id', 'user_id', 'tanggal',
        'jumlah', 'harga_satuan', 'total_harga', 'harga_jual_satuan', 'total_jual', 'tujuan', 'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'harga_satuan' => 'decimal:2',
            'total_harga' => 'decimal:2',
            'harga_jual_satuan' => 'decimal:2',
            'total_jual' => 'decimal:2',
        ];
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate unique transaction code
     */
    public static function generateKode(): string
    {
        $today = now()->format('Ymd');
        $prefix = 'BK-' . $today . '-';

        $lastKode = static::where('kode_transaksi', 'like', $prefix . '%')
            ->orderByRaw('CAST(SUBSTRING(kode_transaksi, ?) AS UNSIGNED) DESC', [strlen($prefix) + 1])
            ->value('kode_transaksi');

        $nextNumber = 1;
        if ($lastKode) {
            $lastNumber = (int) substr($lastKode, strlen($prefix));
            $nextNumber = $lastNumber + 1;
        }

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
