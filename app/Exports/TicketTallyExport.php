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

class TicketTallyExport implements WithColumnWidths, WithStyles, WithEvents {

    private $tickets, $office;

    public function __construct(array $tickets, $office) {
        $this->tickets = $tickets;
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
            'A' => 12,
            'B' => 45,
            'G' => 12,
            'H' => 45,
        ];
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function(AfterSheet $event) { 
                $event->sheet->mergeCells(sprintf('A1:K1'));
                $event->sheet->mergeCells(sprintf('A2:K2'));

                $event->sheet->mergeCells(sprintf('A4:K4'));
                
                $event->sheet->setCellValue('A1', env('APP_COMPANY'));
                $event->sheet->setCellValue('A2', env('APP_ADDRESS'));
                $event->sheet->setCellValue('A4', 'Ticket Tally Report');
                
                $event->sheet->setCellValue('A6', 'Area: ' . $this->office);

                $event->sheet->setCellValue('A8', 'REQUESTS')
                    ->mergeCells('A8:E8');
                $event->sheet->mergeCells('A9:B9')
                    ->setCellValue('A9', 'Ticket');
                $event->sheet->setCellValue('C9', 'Total');
                $event->sheet->setCellValue('D9', 'Attended/Executed');
                $event->sheet->setCellValue('E9', 'Unattended/Unexecuted');

                $event->sheet->setCellValue('G8', 'COMPLAINTS')
                    ->mergeCells('G8:K8');
                $event->sheet->mergeCells('G9:H9')
                    ->setCellValue('G9', 'Ticket');
                $event->sheet->setCellValue('I9', 'Total');
                $event->sheet->setCellValue('J9', 'Attended/Executed');
                $event->sheet->setCellValue('K9', 'Unattended/Unexecuted');

                $rowStartComplaints = 10;
                $rowStartRequest = 10;
                foreach($this->tickets as $item) {
                    if ($item['ReceivedTotal'] == 'Parent') {
                        $event->sheet->setCellValue('G' . $rowStartComplaints, $item['Name'])
                            ->mergeCells('G' . $rowStartComplaints . ':K' . $rowStartComplaints);
                        $event->sheet->getStyle('G' . $rowStartComplaints)->getFont()->setBold(true);
                        $rowStartComplaints++;
                    } else {
                        if ($item['Type'] == 'Complaints') {
                            $event->sheet->setCellValue('H' . $rowStartComplaints, $item['Name']);
                            $event->sheet->setCellValue('I' . $rowStartComplaints, $item['ReceivedTotal'] == '0' ? '' : $item['ReceivedTotal']);
                            $event->sheet->setCellValue('J' . $rowStartComplaints, $item['ExecutedTotal'] == '0' ? '' : $item['ExecutedTotal']);
                            $event->sheet->setCellValue('K' . $rowStartComplaints, $item['NotExecutedTotal'] == '0' ? '' : $item['NotExecutedTotal']);
                            $rowStartComplaints++;
                        }                        
                    } 
                }

                // TOTAL
                $event->sheet->setCellValue('G' . $rowStartComplaints, 'Total');
                $event->sheet->setCellValue('I' . $rowStartComplaints, '=SUM(I10:I' . ($rowStartComplaints-1) . ')');                    
                $event->sheet->setCellValue('J' . $rowStartComplaints, '=SUM(J10:J' . ($rowStartComplaints-1) . ')');                    
                $event->sheet->setCellValue('K' . $rowStartComplaints, '=SUM(K10:K' . ($rowStartComplaints-1) . ')');
                $event->sheet->getStyle('G' . $rowStartComplaints . ':K' . $rowStartComplaints)->getFont()->setBold(true);

                // APPLY STYLE
                $event->sheet->getStyle('G8:K' . $rowStartComplaints)
                ->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ]
                ]);

                foreach($this->tickets as $item) {
                    if ($item['ReceivedTotal'] == 'Parent') {
                        $event->sheet->setCellValue('A' . $rowStartRequest, $item['Name'])
                            ->mergeCells('A' . $rowStartRequest . ':E' . $rowStartRequest);
                        $event->sheet->getStyle('A' . $rowStartRequest)->getFont()->setBold(true);
                        $rowStartRequest++;
                    } else {
                        if ($item['Type'] == 'Request') {
                            $event->sheet->setCellValue('B' . $rowStartRequest, $item['Name']);
                            $event->sheet->setCellValue('C' . $rowStartRequest, $item['ReceivedTotal'] == '0' ? '' : $item['ReceivedTotal']);
                            $event->sheet->setCellValue('D' . $rowStartRequest, $item['ExecutedTotal'] == '0' ? '' : $item['ExecutedTotal']);
                            $event->sheet->setCellValue('E' . $rowStartRequest, $item['NotExecutedTotal'] == '0' ? '' : $item['NotExecutedTotal']);
                            $rowStartRequest++;
                        }                        
                    } 
                }

                // TOTAL
                $event->sheet->setCellValue('A' . $rowStartRequest, 'Total');
                $event->sheet->setCellValue('C' . $rowStartRequest, '=SUM(C10:C' . ($rowStartRequest-1) . ')');                    
                $event->sheet->setCellValue('D' . $rowStartRequest, '=SUM(D10:D' . ($rowStartRequest-1) . ')');                    
                $event->sheet->setCellValue('E' . $rowStartRequest, '=SUM(E10:E' . ($rowStartRequest-1) . ')');
                $event->sheet->getStyle('A' . $rowStartRequest . ':E' . $rowStartRequest)->getFont()->setBold(true);

                // APPLY STYLE
                $event->sheet->getStyle('A8:E' . $rowStartRequest)
                ->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ]
                ]);
                
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