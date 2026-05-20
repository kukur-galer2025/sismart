<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CollectionExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    protected Collection $data;
    protected string $title;

    public function __construct(Collection $data, string $title = 'Data')
    {
        $this->data = $data;
        $this->title = $title;
    }

    public function collection(): Collection
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->data->isNotEmpty() ? array_keys($this->data->first()) : [];
    }

    public function title(): string
    {
        return $this->title;
    }

    public function styles(Worksheet $sheet): array
    {
        $lastCol = $sheet->getHighestColumn();
        $lastRow = $sheet->getHighestRow();

        // Header row styling
        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4F46E5']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            'borders' => ['allBorders' => ['borderStyle' => 'thin', 'color' => ['rgb' => '3730A3']]],
        ]);

        // Data rows styling
        if ($lastRow > 1) {
            $sheet->getStyle("A2:{$lastCol}{$lastRow}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => 'thin', 'color' => ['rgb' => 'D1D5DB']]],
                'alignment' => ['vertical' => 'center'],
            ]);

            // Zebra striping
            for ($row = 2; $row <= $lastRow; $row++) {
                if ($row % 2 === 0) {
                    $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
                        'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'F3F4F6']],
                    ]);
                }
            }
        }

        return [];
    }
}
