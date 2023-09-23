<?php

namespace App\Exports;

use App\Models\ServiceConnections;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Illuminate\Support\Facades\DB;

class KPSTicketsExport implements WithColumnWidths, WithStyles, WithEvents {

    private $data, $office;

    public function __construct($data, $office) {
        $this->data = $data;
        $this->office = $office;
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
            'A6:K9' => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 55,
            'C' => 15,
            'D' => 15,
            'E' => 15
        ];
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function(AfterSheet $event) { 
                $event->sheet->mergeCells(sprintf('A1:E1'));
                $event->sheet->mergeCells(sprintf('A2:E2'));

                $event->sheet->mergeCells(sprintf('A4:E4'));
                
                $event->sheet->setCellValue('A1', env('APP_COMPANY'));
                $event->sheet->setCellValue('A2', env('APP_ADDRESS'));
                $event->sheet->setCellValue('A4', 'SUMMARY OF COMPLAINTS RECEIVED AND ACTED UPON');
                
                $event->sheet->setCellValue('A6', 'Area: ' . $this->office);

                // HEADER COLUMNS
                $event->sheet->mergeCells(sprintf('A8:A9'))
                    ->setCellValue('A8', 'No.');
                $event->sheet->mergeCells(sprintf('B8:B9'))
                    ->setCellValue('B8', 'Nature of Complaints');
                $event->sheet->mergeCells(sprintf('C8:D8'))
                    ->setCellValue('C8', 'No. of Complaints');
                $event->sheet->setCellValue('C9', 'Received *');
                $event->sheet->setCellValue('D9', 'Acted Upon *');
                $event->sheet->mergeCells(sprintf('E8:E9'))
                    ->setCellValue('E8', 'Remarks');

                $event->sheet->setCellValue('A10', '1');
                $event->sheet->setCellValue('B10', 'No Light/Power');
                $event->sheet->setCellValue('C10', '=SUM(C11:C13)');
                $event->sheet->setCellValue('D10', '=SUM(D11:D13)');

                $event->sheet->setCellValue('A11', '1.a');
                $event->sheet->setCellValue('B11', 'Primary Line');
                $event->sheet->setCellValue('C11', $this->data->Received1a);
                $event->sheet->setCellValue('D11', $this->data->Acted1a);
                
                $event->sheet->setCellValue('A12', '1.b');
                $event->sheet->setCellValue('B12', 'Distribution Transformer/ Secondary Line');
                $event->sheet->setCellValue('C12', $this->data->Received1b);
                $event->sheet->setCellValue('D12', $this->data->Acted1b);
                
                $event->sheet->setCellValue('A13', '1.c');
                $event->sheet->setCellValue('B13', 'Residence No Power');
                $event->sheet->setCellValue('C13', $this->data->Received1c);
                $event->sheet->setCellValue('D13', $this->data->Acted1c);
                
                $event->sheet->setCellValue('A14', '2');
                $event->sheet->setCellValue('B14', 'Power Quality Complaint');
                $event->sheet->setCellValue('C14', '=SUM(C15:C17)');
                $event->sheet->setCellValue('D14', '=SUM(D15:D17)');
                
                $event->sheet->setCellValue('A15', '2.a');
                $event->sheet->setCellValue('B15', 'Low Voltage');
                $event->sheet->setCellValue('C15', $this->data->Received2a);
                $event->sheet->setCellValue('D15', $this->data->Acted2a);
                
                $event->sheet->setCellValue('A16', '2.b');
                $event->sheet->setCellValue('B16', 'Fluctuating Voltage');
                
                $event->sheet->setCellValue('A17', '2.c');
                $event->sheet->setCellValue('B17', 'Loose Connection');
                $event->sheet->setCellValue('C17', $this->data->Received2c);
                $event->sheet->setCellValue('D17', $this->data->Acted2c);
                
                $event->sheet->setCellValue('A18', '3');
                $event->sheet->setCellValue('B18', 'Complaints/ Services on Service Drop');
                $event->sheet->setCellValue('C18', '=SUM(C19:C21)');
                $event->sheet->setCellValue('D18', '=SUM(D19:D21)');
                
                $event->sheet->setCellValue('A19', '3.a');
                $event->sheet->setCellValue('B19', 'Reroute Service Drop');
                
                $event->sheet->setCellValue('A20', '3.b');
                $event->sheet->setCellValue('B20', 'Change/ Upgrade Service Drop');
                $event->sheet->setCellValue('C20', $this->data->Received3b);
                $event->sheet->setCellValue('D20', $this->data->Acted3b);
                
                $event->sheet->setCellValue('A21', '3.c');
                $event->sheet->setCellValue('B21', 'Others (e.g. Broken, Sagging, Sparking, etc.)');
                $event->sheet->setCellValue('C21', $this->data->Received3c);
                $event->sheet->setCellValue('D21', $this->data->Acted3c);
                
                $event->sheet->setCellValue('A22', '4');
                $event->sheet->setCellValue('B22', 'Distribution Pole Complaint and Others');
                $event->sheet->setCellValue('C22', '=SUM(C23:C26)');
                $event->sheet->setCellValue('D22', '=SUM(D23:D26)');
                
                $event->sheet->setCellValue('A23', '4.a');
                $event->sheet->setCellValue('B23', 'Rotten Pole');
                $event->sheet->setCellValue('C23', $this->data->Received4a);
                $event->sheet->setCellValue('D23', $this->data->Acted4a);
                
                $event->sheet->setCellValue('A24', '4.b');
                $event->sheet->setCellValue('B24', 'Leaning Pole');
                $event->sheet->setCellValue('C24', $this->data->Received4b);
                $event->sheet->setCellValue('D24', $this->data->Acted4b);
                
                $event->sheet->setCellValue('A25', '4.c');
                $event->sheet->setCellValue('B25', 'Relocation of Pole');
                $event->sheet->setCellValue('C25', $this->data->Received4c);
                $event->sheet->setCellValue('D25', $this->data->Acted4c);
                
                $event->sheet->setCellValue('A26', '4.d');
                $event->sheet->setCellValue('B26', 'Distribution Transformer Replacement (e.g. Busted Transformer)');
                $event->sheet->setCellValue('C26', $this->data->Received4d);
                $event->sheet->setCellValue('D26', $this->data->Acted4d);
                
                $event->sheet->setCellValue('A27', '5');
                $event->sheet->setCellValue('B27', 'Complaints on kWh Meter');
                $event->sheet->setCellValue('C27', $this->data->Received5);
                $event->sheet->setCellValue('D27', $this->data->Acted5);
                
                $event->sheet->setCellValue('A28', '6');
                $event->sheet->setCellValue('B28', 'Others (Board, Management, Employees, etc.) ');
                
                $event->sheet->setCellValue('A29', '7');
                $event->sheet->setCellValue('B29', 'Other Verified Complaints');
                $event->sheet->setCellValue('C29', '=SUM(C30:C33)');
                $event->sheet->setCellValue('D29', '=SUM(D30:D33)');
                
                $event->sheet->setCellValue('A30', '7.a');
                $event->sheet->setCellValue('B30', 'Endorsed by Department of Energy (DoE)');
                
                $event->sheet->setCellValue('A31', '7.b');
                $event->sheet->setCellValue('B31', 'Endorsed by Presidential Action Center (PAC)');
                
                $event->sheet->setCellValue('A32', '7.c');
                $event->sheet->setCellValue('B32', 'Endorsed by Civil Service Commission (CSC)');
                
                $event->sheet->setCellValue('A33', '7.d');
                $event->sheet->setCellValue('B33', 'National Electrification Administration (NEA) Referral');

                // TOTAL
                $event->sheet->setCellValue('B34', 'TOTAL');
                $event->sheet->setCellValue('C34', '=SUM(C10, C14, C18, C22, C27, C28, C29)');
                $event->sheet->setCellValue('D34', '=SUM(D10, D14, D18, D22, D27, D28, D29)');

                // APPLY STYLE
                // $event->sheet->getStyle('G8:K' . $rowStartComplaints)
                // ->applyFromArray([
                //     'borders' => [
                //         'allBorders' => [
                //             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                //         ]
                //     ]
                // ]);

                // APPLY STYLE
                // $event->sheet->getStyle('A8:E' . $rowStartRequest)
                // ->applyFromArray([
                //     'borders' => [
                //         'allBorders' => [
                //             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                //         ]
                //     ]
                // ]);
                
            }
        ];
    }
}