<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class DynamicExportsNoBillingMonth implements FromArray, ShouldAutoSize, WithColumnFormatting, WithHeadings, WithStyles, WithEvents, WithCustomStartCell {

    private $data, $town, $headers, $columnFormats, $cellStart, $styles, $title;

    public function __construct(array $data, $town, $headers, $columnFormats, $cellStart, $styles, $title) {
        $this->data = $data;
        $this->town = $town;
        $this->headers = $headers;
        $this->columnFormats = $columnFormats;
        $this->cellStart = $cellStart;
        $this->styles = $styles;
        $this->title = $title;
    }

    public function columnFormats(): array
    {
        return $this->columnFormats;
    }

    public function array(): array {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->headers;
    }

    public function styles(Worksheet $sheet)
    {
        return $this->styles;
    }

    public function startCell(): string
    {
        return $this->cellStart;
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function(AfterSheet $event) { 
                $event->sheet->mergeCells(sprintf('A1:U1'));
                $event->sheet->mergeCells(sprintf('A2:U2'));

                $event->sheet->mergeCells(sprintf('A4:U4'));
                
                $event->sheet->setCellValue('A1', env('APP_COMPANY'));
                $event->sheet->setCellValue('A2', env('APP_ADDRESS'));
                $event->sheet->setCellValue('A4', $this->title);
                $event->sheet->setCellValue('A5', 'AREA: ' . $this->town);
                
                // SET TOTAL
                // $totalRow = count($this->billOfMaterials) + 8;
                // $event->sheet->mergeCells(sprintf('A' . $totalRow . ':D' . $totalRow));
                // $event->sheet->setCellValue('A' . $totalRow, 'Total');
                // $event->sheet->setCellValue('E' . $totalRow, '=SUM(E8:E' . ($totalRow-1) . ')');
                // $event->sheet->getStyle('E' . ($totalRow-1) . ')')->getNumberFormat()
                // ->setFormatCode('#,##0.00');
            }
        ];
    }
}