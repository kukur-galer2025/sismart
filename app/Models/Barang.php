<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    protected $fillable = [
        'kode', 'nama', 'kategori_id', 'satuan', 'stok',
        'harga_rata_rata', 'total_nilai', 'safety_stock',
        'lead_time', 'pemakaian_rata_rata', 'pemakaian_maksimum',
        'metode_stok', 'lokasi', 'keterangan', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'harga_rata_rata' => 'decimal:2',
            'total_nilai' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function barangMasuks(): HasMany
    {
        return $this->hasMany(BarangMasuk::class);
    }

    public function barangKeluars(): HasMany
    {
        return $this->hasMany(BarangKeluar::class);
    }

    public function batches(): HasMany
    {
        return $this->hasMany(BatchBarang::class)->where('jumlah_sisa', '>', 0)->orderBy('tanggal_masuk');
    }

    public function allBatches(): HasMany
    {
        return $this->hasMany(BatchBarang::class)->orderBy('tanggal_masuk');
    }

    /**
     * Calculate Safety Stock
     * Safety Stock = (Pemakaian Maksimum - Pemakaian Rata-rata) × Lead Time
     */
    public function hitungSafetyStock(): int
    {
        return ($this->pemakaian_maksimum - $this->pemakaian_rata_rata) * $this->lead_time;
    }

    /**
     * Calculate Reorder Point
     * ROP = (Rata-rata Pemakaian × Lead Time) + Safety Stock
     */
    public function hitungReorderPoint(): int
    {
        return ($this->pemakaian_rata_rata * $this->lead_time) + $this->safety_stock;
    }

    /**
     * Check if stock is at or below safety stock level
     */
    public function isStokKritis(): bool
    {
        return $this->stok <= $this->safety_stock;
    }

    /**
     * Check if stock has reached reorder point
     */
    public function perluReorder(): bool
    {
        return $this->stok <= $this->hitungReorderPoint();
    }

    /**
     * Get status label for stock level
     */
    public function getStatusStokAttribute(): string
    {
        if ($this->stok <= 0) return 'Habis';
        if ($this->isStokKritis()) return 'Kritis';
        if ($this->perluReorder()) return 'Reorder';
        return 'Aman';
    }

    /**
     * Calculate inventory turnover
     */
    public function hitungPerputaran(string $periode = 'bulan'): float
    {
        $query = $this->barangKeluars();

        if ($periode === 'bulan') {
            $query->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year);
        } else {
            $query->whereYear('tanggal', now()->year);
        }

        $totalKeluar = $query->sum('total_harga');
        $rataStok = $this->total_nilai > 0 ? $this->total_nilai : 1;

        return round($totalKeluar / $rataStok, 2);
    }
}
