<?php

namespace App\Exports;

use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LaporanTransaksiExport implements WithMultipleSheets
{
    public function __construct(
        private string $dari,
        private string $sampai
    ) {}

    public function sheets(): array
    {
        return [
            new BarangMasukSheet($this->dari, $this->sampai),
            new BarangKeluarSheet($this->dari, $this->sampai),
        ];
    }
}
