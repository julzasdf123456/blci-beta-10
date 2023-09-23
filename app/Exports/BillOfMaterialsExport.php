<?php

namespace App\Exports;

use App\Models\BillOfMaterialsMatrix;
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

class BillOfMaterialsExport implements FromArray, ShouldAutoSize, WithColumnFormatting, WithHeadings, WithStyles, WithEvents, WithCustomStartCell
{
    private $billOfMaterials;

    public function __construct(array $billOfMaterials)
    {
        $this->billOfMaterials = $billOfMaterials;
    }

    public function array(): array
    {
        return $this->billOfMaterials;
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function headings(): array
    {
        return [
            'NEA Code',
            'Description',
            'Unit Cost',
            'Project Requirements',
            'Extended Cost'
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
                $event->sheet->mergeCells(sprintf('A1:E1'));
                $event->sheet->mergeCells(sprintf('A2:E2'));

                $event->sheet->mergeCells(sprintf('A4:E4'));
                
                $event->sheet->setCellValue('A1', env('APP_COMPANY'));
                $event->sheet->setCellValue('A2', env('APP_ADDRESS'));
                $event->sheet->setCellValue('A4', 'Bill of Materials');
                
                // SET TOTAL
                $totalRow = count($this->billOfMaterials) + 8;
                $event->sheet->mergeCells(sprintf('A' . $totalRow . ':D' . $totalRow));
                $event->sheet->setCellValue('A' . $totalRow, 'Total');
                $event->sheet->setCellValue('E' . $totalRow, '=SUM(E8:E' . ($totalRow-1) . ')');
                // $event->sheet->getStyle('E' . ($totalRow-1) . ')')->getNumberFormat()
                // ->setFormatCode('#,##0.00');
            }
        ];
    }
}
