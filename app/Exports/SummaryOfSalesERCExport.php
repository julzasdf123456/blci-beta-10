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
use Maatwebsite\Excel\Concerns\WithPreCalculateFormulas;
use Maatwebsite\Excel\Concerns\WithTitle;

class SummaryOfSalesERCExport implements FromArray, ShouldAutoSize, WithColumnFormatting, WithHeadings, WithStyles, WithEvents, WithCustomStartCell, WithPreCalculateFormulas, WithTitle {

    private $data, $period, $sales, $demandTotal;

    public function __construct(array $data, $period, $sales, $demandTotal) {
        $this->data = $data;
        $this->period = $period;
        $this->sales = $sales;
        $this->demandTotal = $demandTotal;
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER,
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'N' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'O' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'P' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'Q' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'R' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'S' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'T' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'U' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        ];
    }

    public function array(): array {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Town',
            'No. of Consumers',
            'Residential',
            'Low Voltage',
            'High Voltage',
            'Total Kwh Sold',
            'Total Amount',
            'Missionary Electrification',
            'Environmental Charge',
            'Stranded Contract Cost',
            'NPC Stranded Debt',
            'REDCI',
            'RFSC',
            'FIT-ALL',
            'Franchise Tax',
            'Business Tax',
            'RPT',
            'VAT',
            'Senior Citizen Subsidy',
            'Senior Citizen Discount',
            'Others'
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
            17 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
            29 => [
                'font' => ['bold' => true],
            ],
            33 => [
                'font' => ['bold' => true],
            ],
            'A' => [
                'font' => ['bold' => true],
            ],
            'A7:U17' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ]
        ];
    }

    public function startCell(): string
    {
        return 'A7';
    }

    public function title(): string {
        return 'Summary of Sales Per Area ERC';
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function(AfterSheet $event) { 
                $event->sheet->mergeCells(sprintf('A1:U1'));
                $event->sheet->mergeCells(sprintf('A2:U2'));

                $event->sheet->mergeCells(sprintf('A4:U4'));
                
                $event->sheet->setCellValue('A1', env('APP_COMPANY'));
                $event->sheet->setCellValue('A2', env('APP_ADDRESS'));
                $event->sheet->setCellValue('A4', 'SUMMARY OF SALES PER AREA - ' . date('F Y', strtotime($this->period)) . ' - ERC');
                
                // GRAND TOTAL
                $event->sheet->setCellValue('A17', 'GRAND TOTAL');
                $event->sheet->setCellValue('B17', '=SUM(B8:B16)')
                        ->getStyle('B17')->getNumberFormat()->setFormatCode('#,##0');
                $event->sheet->setCellValue('C17', '=SUM(C8:C16)')
                        ->getStyle('C17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D17', '=SUM(D8:D16)')
                        ->getStyle('D17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E17', '=SUM(E8:E16)')
                        ->getStyle('E17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F17', '=SUM(F8:F16)')
                        ->getStyle('F17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G17', '=SUM(G8:G16)')
                        ->getStyle('G17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H17', '=SUM(H8:H16)')
                        ->getStyle('H17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I17', '=SUM(I8:I16)')
                        ->getStyle('I17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J17', '=SUM(J8:J16)')
                        ->getStyle('J17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K17', '=SUM(K8:K16)')
                        ->getStyle('K17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L17', '=SUM(L8:L16)')
                        ->getStyle('L17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('M17', '=SUM(M8:M16)')
                        ->getStyle('M17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('N17', '=SUM(N8:N16)')
                        ->getStyle('N17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('O17', '=SUM(O8:O16)')
                        ->getStyle('O17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('P17', '=SUM(P8:P16)')
                        ->getStyle('P17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('Q17', '=SUM(Q8:Q16)')
                        ->getStyle('Q17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('R17', '=SUM(R8:R16)')
                        ->getStyle('R17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('S17', '=SUM(S8:S16)')
                        ->getStyle('S17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('T17', '=SUM(T8:T16)')
                        ->getStyle('T17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('U17', '=SUM(U8:U16)')
                        ->getStyle('U17')->getNumberFormat()->setFormatCode('#,##0.00');

                if ($this->sales != null) {
                        // SALES - SUMMARY
                        $event->sheet->mergeCells(sprintf('B19:C19'))
                                ->setCellValue('B19', 'Total KWH Purchased');
                        $event->sheet->setCellValue('D19', $this->sales->TotalEnergyInput)
                                ->getStyle('D19')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->mergeCells(sprintf('B20:C20'))
                                ->setCellValue('B20', 'Total KWH Sold');
                        $event->sheet->setCellValue('D20', $this->sales->TotalEnergyOutput)
                                ->getStyle('D20')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->mergeCells(sprintf('B21:C21'))
                                ->setCellValue('B21', 'Total Demand Sold');
                        $event->sheet->setCellValue('D21', $this->demandTotal->Demand)
                                ->getStyle('D21')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->mergeCells(sprintf('B22:C22'))
                                ->setCellValue('B22', 'System Loss in KWH');
                        $event->sheet->setCellValue('D22', $this->sales->TotalSystemLoss)
                                ->getStyle('D22')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->mergeCells(sprintf('B23:C23'))
                                ->setCellValue('B23', 'System Loss in %');
                        $event->sheet->setCellValue('D23', $this->sales->TotalSystemLossPercentage . '%')
                                ->getStyle('D23')->getNumberFormat()->setFormatCode('#,##0.00');

                        // SALES - PER SUBSTATION
                        $event->sheet->mergeCells(sprintf('G19:H19'))
                                ->setCellValue('G19', 'Victorias Susbtation');
                        $event->sheet->setCellValue('I19', $this->sales->VictoriasSubstation)
                                ->getStyle('I19')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->mergeCells(sprintf('G20:H20'))
                                ->setCellValue('G20', 'Sagay Susbtation');
                        $event->sheet->setCellValue('I20', $this->sales->SagaySubstation)
                                ->getStyle('I20')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->mergeCells(sprintf('G21:H21'))
                                ->setCellValue('G21', 'San Carlos Susbtation');
                        $event->sheet->setCellValue('I21', $this->sales->SanCarlosSubstation)
                                ->getStyle('I21')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->mergeCells(sprintf('G22:H22'))
                                ->setCellValue('G22', 'Escalante Susbtation');
                        $event->sheet->setCellValue('I22', $this->sales->EscalanteSubstation)
                                ->getStyle('I22')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->mergeCells(sprintf('G23:H23'))
                                ->setCellValue('G23', 'Lopez Susbtation');
                        $event->sheet->setCellValue('I23', $this->sales->LopezSubstation)
                                ->getStyle('I23')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->mergeCells(sprintf('G24:H24'))
                                ->setCellValue('G24', 'Cadiz Susbtation');
                        $event->sheet->setCellValue('I24', $this->sales->CadizSubstation)
                                ->getStyle('I24')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->mergeCells(sprintf('G25:H25'))
                                ->setCellValue('G25', 'IPI Susbtation');
                        $event->sheet->setCellValue('I25', $this->sales->IpiSubstation)
                                ->getStyle('I25')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->mergeCells(sprintf('G26:H26'))
                                ->setCellValue('G26', 'Toboso-Calatrava Susbtation');
                        $event->sheet->setCellValue('I26', $this->sales->TobosoCalatravaSubstation)
                                ->getStyle('I26')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->mergeCells(sprintf('G27:H27'))
                                ->setCellValue('G27', 'Victorias Milling Company');
                        $event->sheet->setCellValue('I27', $this->sales->VictoriasMillingCompany)
                                ->getStyle('I27')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->mergeCells(sprintf('G28:H28'))
                                ->setCellValue('G28', 'San Carlos Bionergy');
                        $event->sheet->setCellValue('I28', $this->sales->SanCarlosBionergy)
                                ->getStyle('I28')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->mergeCells(sprintf('G29:H29'))
                                ->setCellValue('G29', 'Total KWH');
                        $event->sheet->setCellValue('I29', $this->sales->TotalEnergyInput)
                                ->getStyle('I29')->getNumberFormat()->setFormatCode('#,##0.00');
                }                

                // SIGNATORIES
                $event->sheet->setCellValue('B31', 'Prepared By:');
                $event->sheet->mergeCells(sprintf('B33:C33'));
                $event->sheet->setCellValue('B33', 'ANTHONY VAN C. DE LA NOCHE');
                $event->sheet->mergeCells(sprintf('B34:C34'));
                $event->sheet->setCellValue('B34', 'Meter Reading and Billing Analyst');

                $event->sheet->setCellValue('E31', 'Recommending Approval:');
                $event->sheet->mergeCells(sprintf('E33:F33'));
                $event->sheet->setCellValue('E33', 'ANTHONY B. LAGRADA');
                $event->sheet->mergeCells(sprintf('E34:F34'));
                $event->sheet->setCellValue('E34', 'OIC - CITET Dept. Manager');

                $event->sheet->mergeCells(sprintf('G33:H33'));
                $event->sheet->setCellValue('G33', 'ELREEN JANE Z. BANOT');
                $event->sheet->mergeCells(sprintf('G34:H34'));
                $event->sheet->setCellValue('G34', 'FSD Manager');

                $event->sheet->setCellValue('J31', 'Approved by:');
                $event->sheet->mergeCells(sprintf('J33:K33'));
                $event->sheet->setCellValue('J33', 'ATTY. DANNY L. PONDEVILLA');
                $event->sheet->mergeCells(sprintf('J34:K34'));
                $event->sheet->setCellValue('J34', 'General Manager');
            }
        ];
    }
}