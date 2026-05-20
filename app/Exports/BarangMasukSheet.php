<?php

namespace App\Exports;

use App\Models\BarangMasuk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BarangMasukSheet implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    private int $row = 0;

    public function __construct(
        private string $dari,
        private string $sampai
    ) {}

    public function collection()
    {
        return BarangMasuk::with('barang', 'user')
            ->whereBetween('tanggal', [$this->dari, $this->sampai])
            ->orderBy('tanggal', 'desc')->get();
    }

    public function headings(): array
    {
        return ['No', 'Tanggal', 'Kode Transaksi', 'Nama Barang', 'Kode Barang', 'Supplier', 'Jumlah', 'Satuan', 'Harga Satuan (Rp)', 'Total Harga (Rp)', 'Petugas'];
    }

    public function map($m): array
    {
        $this->row++;
        return [
            $this->row, $m->tanggal->format('d/m/Y'), $m->kode_transaksi, $m->barang->nama,
            $m->barang->kode, $m->supplier ?? '-', $m->jumlah, $m->barang->satuan,
            $m->harga_satuan, $m->total_harga, $m->user->name,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $lastRow = $this->row + 1;
        $sheet->insertNewRowBefore(1, 3);
        $sheet->mergeCells('A1:K1');
        $sheet->setCellValue('A1', 'LAPORAN BARANG MASUK - SISmart');
        $sheet->mergeCells('A2:K2');
        $sheet->setCellValue('A2', "Periode: {$this->dari} s/d {$this->sampai}");
        $sheet->mergeCells('A3:K3');

        $lastDataRow = $lastRow + 3;
        $sheet->getStyle("I5:J{$lastDataRow}")->getNumberFormat()->setFormatCode('#,##0');

        $sumRow = $lastDataRow + 1;
        $sheet->setCellValue("H{$sumRow}", 'TOTAL:');
        $sheet->setCellValue("J{$sumRow}", "=SUM(J5:J{$lastDataRow})");
        $sheet->getStyle("H{$sumRow}:J{$sumRow}")->getFont()->setBold(true);
        $sheet->getStyle("J{$sumRow}")->getNumberFormat()->setFormatCode('#,##0');

        return [
            'A1' => ['font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '059669']], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            'A2' => ['font' => ['size' => 10, 'color' => ['rgb' => '6B7280']], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            4 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '059669']], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'wrapText' => true], 'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]],
            "A5:K{$lastDataRow}" => ['borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']]]],
        ];
    }

    public function title(): string { return 'Barang Masuk'; }
}
