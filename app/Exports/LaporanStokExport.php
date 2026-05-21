<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class LaporanStokExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle, ShouldAutoSize
{
    private int $row = 0;

    public function collection()
    {
        return Barang::with('kategori')->where('is_active', true)->orderBy('nama')->get();
    }

    public function headings(): array
    {
        return [
            __('export.no'), 
            __('export.excel.kode_barang'), 
            __('export.excel.nama_barang'), 
            __('export.kategori'), 
            __('export.satuan'), 
            __('export.excel.metode_stok'), 
            __('export.stok'), 
            __('export.excel.harga_rata'), 
            __('export.excel.total_nilai'), 
            __('export.excel.safety_stock'), 
            __('export.excel.reorder_point'), 
            __('export.status')
        ];
    }

    public function map($barang): array
    {
        $this->row++;
        return [
            $this->row,
            $barang->kode,
            $barang->nama,
            $barang->kategori->nama ?? '-',
            $barang->satuan,
            strtoupper($barang->metode_stok),
            $barang->stok,
            $barang->harga_rata_rata,
            $barang->total_nilai,
            $barang->safety_stock,
            $barang->hitungReorderPoint(),
            $barang->status_stok,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5, 'B' => 14, 'C' => 25, 'D' => 15, 'E' => 10,
            'F' => 12, 'G' => 8, 'H' => 20, 'I' => 20, 'J' => 14, 'K' => 16, 'L' => 10,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $lastRow = $this->row + 1;

        // Title row above the table
        $sheet->insertNewRowBefore(1, 3);
        $sheet->mergeCells('A1:L1');
        $sheet->setCellValue('A1', __('export.excel.laporan_stok_title'));
        $sheet->mergeCells('A2:L2');
        $sheet->setCellValue('A2', __('export.tanggal') . ': ' . now()->format('d/m/Y'));
        $sheet->mergeCells('A3:L3');

        // Format currency columns
        $lastDataRow = $lastRow + 3;
        $sheet->getStyle("H5:I{$lastDataRow}")->getNumberFormat()->setFormatCode('#,##0');

        // Summary row
        $sumRow = $lastDataRow + 1;
        $sheet->setCellValue("G{$sumRow}", '=SUM(G5:G' . $lastDataRow . ')');
        $sheet->setCellValue("I{$sumRow}", '=SUM(I5:I' . $lastDataRow . ')');
        $sheet->setCellValue("F{$sumRow}", 'TOTAL:');
        $sheet->getStyle("F{$sumRow}:I{$sumRow}")->getFont()->setBold(true);
        $sheet->getStyle("I{$sumRow}")->getNumberFormat()->setFormatCode('#,##0');

        return [
            'A1' => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '4338CA']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            'A2' => [
                'font' => ['size' => 10, 'color' => ['rgb' => '6B7280']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            4 => [ // Header row
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '4338CA']]],
            ],
            "A5:L{$lastDataRow}" => [
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }

    public function title(): string
    {
        return __('export.excel.laporan_stok_sheet');
    }
}
