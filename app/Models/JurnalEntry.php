<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JurnalEntry extends Model
{
    protected $fillable = [
        'kode_jurnal', 'tanggal', 'akun_id', 'debit', 'kredit',
        'keterangan', 'referensi_tipe', 'referensi_id', 'user_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'debit' => 'decimal:2',
            'kredit' => 'decimal:2',
        ];
    }

    public function akun(): BelongsTo
    {
        return $this->belongsTo(AkunKeuangan::class, 'akun_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function generateKode(): string
    {
        $today = now()->format('Ymd');
        $prefix = 'JR-' . $today . '-';

        $lastKode = static::where('kode_jurnal', 'like', $prefix . '%')
            ->orderByRaw('CAST(SUBSTRING(kode_jurnal, ?) AS UNSIGNED) DESC', [strlen($prefix) + 1])
            ->value('kode_jurnal');

        $nextNumber = 1;
        if ($lastKode) {
            $lastNumber = (int) substr($lastKode, strlen($prefix));
            $nextNumber = $lastNumber + 1;
        }

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
