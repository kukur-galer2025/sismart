<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AkunKeuangan extends Model
{
    protected $fillable = [
        'kode', 'nama', 'tipe', 'saldo_normal', 'saldo', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'saldo' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function jurnalEntries()
    {
        return $this->hasMany(JurnalEntry::class, 'akun_id');
    }

    /**
     * Get accounts by type
     */
    public function scopeByTipe($query, string $tipe)
    {
        return $query->where('tipe', $tipe);
    }
}
