<?php

namespace App\Exports;

use App\Models\ServiceAccounts;
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
use Illuminate\Support\Facades\DB;

class KwhSalesTsdRoutesExport implements FromArray, ShouldAutoSize, WithColumnFormatting, WithHeadings, WithStyles, WithEvents, WithCustomStartCell {

    private $data, $period, $town;

    public function __construct(array $data, $period, $town) {
        $this->data = $data;
        $this->period = $period;
        $this->town = $town;
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function array(): array {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Route',
            'No. of Consumers',
            'Residential',
            'Low Voltage',
            'High Voltage',
            'Total Kwh',
            'Total Amount',
            'Missionary',
            'Environmental',
            'NPC',
            'StrandedCC',
            'REDCI',
            'Fit-All',
            'RPT',
            'RFSC',
            'Gen VAT',
            'Trans VAT',
            'Sys Loss VAT',
            'Dist/Others VAT',
            'SC Subsidy',
            'SC Discount'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
            2 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
            4 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
            8 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
            'A' => [
                'font' => ['bold' => true],
            ],
        ];
    }

    public function startCell(): string
    {
        return 'A8';
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function(AfterSheet $event) { 
                $event->sheet->mergeCells(sprintf('A1:U1'));
                $event->sheet->mergeCells(sprintf('A2:U2'));

                $event->sheet->mergeCells(sprintf('A4:U4'));
                
                $event->sheet->setCellValue('A1', env('APP_COMPANY'));
                $event->sheet->setCellValue('A2', env('APP_ADDRESS'));
                $event->sheet->setCellValue('A4', 'KWH SALES SUMMARY REPORT');
                $event->sheet->setCellValue('A5', 'AREA: ' . $this->town);
                $event->sheet->setCellValue('A6', 'BILLING MONTH: ' . date('F Y', strtotime($this->period)));
                
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