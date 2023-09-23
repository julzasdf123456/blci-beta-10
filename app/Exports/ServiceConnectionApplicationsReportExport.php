<?php

namespace App\Exports;

use App\Models\ServiceConnections;
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

class ServiceConnectionApplicationsReportExport implements FromArray, ShouldAutoSize, WithColumnFormatting, WithHeadings, WithStyles, WithEvents, WithCustomStartCell {

    private $serviceConnections;

    public function __construct(array $serviceConnections) {
        $this->serviceConnections = $serviceConnections;
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function array(): array {
        return $this->serviceConnections;
    }

    public function headings(): array
    {
        return [
            'Service Account No.',
            'Applicant Name',
            'Date of Application',
            'Office',
            'Purok',
            'Barangay',
            'Town',
            'Request',
            'Status'
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
            7 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }

    public function startCell(): string
    {
        return 'A7';
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function(AfterSheet $event) { 
                $event->sheet->mergeCells(sprintf('A1:I1'));
                $event->sheet->mergeCells(sprintf('A2:I2'));

                $event->sheet->mergeCells(sprintf('A4:I4'));
                
                $event->sheet->setCellValue('A1', env('APP_COMPANY'));
                $event->sheet->setCellValue('A2', env('APP_ADDRESS'));
                $event->sheet->setCellValue('A4', 'Service Connection Application Report');
                
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