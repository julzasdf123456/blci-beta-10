<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithPreCalculateFormulas;
use Maatwebsite\Excel\Concerns\WithTitle;

class SummaryOfSalesPerConsumerTypeExport implements ShouldAutoSize, WithStyles, WithEvents, WithPreCalculateFormulas, WithTitle {

    private $period, $residential, $commercial, $irrigation, $industrial, $streetLights, $publicBuilding, $totalLv, $commercialHv, $industrialHv, $publicBuildingHv, $totalHv, $grandTotal;

    public function __construct($period, 
                        $residential, 
                        $commercial, 
                        $irrigation, 
                        $industrial, 
                        $streetLights, 
                        $publicBuilding, 
                        $totalLv, 
                        $commercialHv, 
                        $industrialHv, 
                        $publicBuildingHv, 
                        $totalHv, 
                        $grandTotal) {
        $this->period = $period;
        $this->residential = $residential; 
        $this->commercial = $commercial; 
        $this->irrigation = $irrigation; 
        $this->industrial = $industrial; 
        $this->streetLights = $streetLights; 
        $this->publicBuilding = $publicBuilding; 
        $this->totalLv = $totalLv; 
        $this->commercialHv = $commercialHv; 
        $this->industrialHv = $industrialHv; 
        $this->publicBuildingHv = $publicBuildingHv; 
        $this->totalHv = $totalHv; 
        $this->grandTotal= $grandTotal;
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
            29 => [
                'font' => ['bold' => true],
            ],
            33 => [
                'font' => ['bold' => true],
            ],
            'A' => [
                'font' => ['bold' => true],
            ],
            'A6:L21' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ]
        ];
    }

    public function title(): string {
        return 'Summary Per Consumer Type';
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function(AfterSheet $event) { 
                $event->sheet->mergeCells(sprintf('A1:L1'));
                $event->sheet->mergeCells(sprintf('A2:L2'));

                $event->sheet->mergeCells(sprintf('A4:L4'));
                
                $event->sheet->setCellValue('A1', env('APP_COMPANY'));
                $event->sheet->setCellValue('A2', env('APP_ADDRESS'));
                $event->sheet->setCellValue('A4', 'SUMMARY OF SALES PER CONSUMER TYPE - ' . date('F Y', strtotime($this->period)));
            
                // HEADER
                $event->sheet->mergeCells(sprintf('A6:A7'))
                    ->setCellValue('A6', 'Classification');
                $event->sheet->mergeCells(sprintf('B6:B7'))
                    ->setCellValue('B6', 'Number of Consumers');
                $event->sheet->mergeCells(sprintf('C6:D6'))
                    ->setCellValue('C6', 'TOTAL SOLD');
                $event->sheet->setCellValue('C7', 'KWH');
                $event->sheet->setCellValue('D7', 'KW');
                $event->sheet->mergeCells(sprintf('E6:E7'))
                    ->setCellValue('E6', 'AMOUNT');
                $event->sheet->mergeCells(sprintf('F6:F7'))
                    ->setCellValue('F6', 'REAL PROPERTY TAX');
                $event->sheet->mergeCells(sprintf('G6:K6'))
                    ->setCellValue('G6', 'VALUE ADDED TAX');
                $event->sheet->setCellValue('G7', 'GENERATION');
                $event->sheet->setCellValue('H7', 'TRANSMISSION');
                $event->sheet->setCellValue('I7', 'SYSTEM LOSS');
                $event->sheet->setCellValue('J7', 'DIST/OTHERS');
                $event->sheet->setCellValue('K7', 'TOTAL');
                $event->sheet->mergeCells(sprintf('L6:L7'))
                    ->setCellValue('L6', 'TOTAL AMOUNT');

                // RESIDENTIAL
                $event->sheet->setCellValue('A8', 'RESIDENTIAL');
                $event->sheet->setCellValue('B8', $this->residential->NoOfConsumers)
                        ->getStyle('B8')->getNumberFormat()->setFormatCode('#,##0');
                $event->sheet->setCellValue('C8', $this->residential->KwhUsed)
                        ->getStyle('C8')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D8', $this->residential->DemandKwh)
                        ->getStyle('D8')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F8', $this->residential->RealPropertyTax)
                        ->getStyle('F8')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G8', $this->residential->GenerationVAT)
                        ->getStyle('G8')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H8', $this->residential->TransmissionVAT)
                        ->getStyle('H8')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I8', $this->residential->SystemLossVAT)
                        ->getStyle('I8')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J8', $this->residential->DistributionVAT)
                        ->getStyle('J8')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K8', '=SUM(F8:J8)')
                        ->getStyle('K8')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L8', $this->residential->NetAmount)
                        ->getStyle('L8')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E8', '=L8-SUM(F8:J8)')
                        ->getStyle('E8')->getNumberFormat()->setFormatCode('#,##0.00');

                 
                $event->sheet->mergeCells(sprintf('A9:L9'))
                    ->setCellValue('A9', 'LOWER VOLTAGE');
                
                // COMMERCIAL
                $event->sheet->setCellValue('A10', 'COMMERCIAL');
                $event->sheet->setCellValue('B10', $this->commercial->NoOfConsumers)
                        ->getStyle('B10')->getNumberFormat()->setFormatCode('#,##0');
                $event->sheet->setCellValue('C10', $this->commercial->KwhUsed)
                        ->getStyle('C10')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D10', $this->commercial->DemandKwh)
                        ->getStyle('D10')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F10', $this->commercial->RealPropertyTax)
                        ->getStyle('F10')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G10', $this->commercial->GenerationVAT)
                        ->getStyle('G10')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H10', $this->commercial->TransmissionVAT)
                        ->getStyle('H10')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I10', $this->commercial->SystemLossVAT)
                        ->getStyle('I10')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J10', $this->commercial->DistributionVAT)
                        ->getStyle('J10')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K10', '=SUM(F10:J10)')
                        ->getStyle('K10')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L10', $this->commercial->NetAmount)
                        ->getStyle('L10')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E10', '=L10-SUM(F10:J10)')
                        ->getStyle('E10')->getNumberFormat()->setFormatCode('#,##0.00');

                // IRRIGATION
                $event->sheet->setCellValue('A11', 'IRRIGATION/WATER SYSTEMS');
                $event->sheet->setCellValue('B11', $this->irrigation->NoOfConsumers)
                        ->getStyle('B11')->getNumberFormat()->setFormatCode('#,##0');
                $event->sheet->setCellValue('C11', $this->irrigation->KwhUsed)
                        ->getStyle('C11')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D11', $this->irrigation->DemandKwh)
                        ->getStyle('D11')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F11', $this->irrigation->RealPropertyTax)
                        ->getStyle('F11')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G11', $this->irrigation->GenerationVAT)
                        ->getStyle('G11')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H11', $this->irrigation->TransmissionVAT)
                        ->getStyle('H11')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I11', $this->irrigation->SystemLossVAT)
                        ->getStyle('I11')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J11', $this->irrigation->DistributionVAT)
                        ->getStyle('J11')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K11', '=SUM(F11:J11)')
                        ->getStyle('K11')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L11', $this->irrigation->NetAmount)
                        ->getStyle('L11')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E11', '=L11-SUM(F11:J11)')
                        ->getStyle('E11')->getNumberFormat()->setFormatCode('#,##0.00');

                // IRRIGATION
                $event->sheet->setCellValue('A12', 'INDUSTRIAL');
                $event->sheet->setCellValue('B12', $this->industrial->NoOfConsumers)
                        ->getStyle('B12')->getNumberFormat()->setFormatCode('#,##0');
                $event->sheet->setCellValue('C12', $this->industrial->KwhUsed)
                        ->getStyle('C12')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D12', $this->industrial->DemandKwh)
                        ->getStyle('D12')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F12', $this->industrial->RealPropertyTax)
                        ->getStyle('F12')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G12', $this->industrial->GenerationVAT)
                        ->getStyle('G12')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H12', $this->industrial->TransmissionVAT)
                        ->getStyle('H12')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I12', $this->industrial->SystemLossVAT)
                        ->getStyle('I12')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J12', $this->industrial->DistributionVAT)
                        ->getStyle('J12')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K12', '=SUM(F12:J12)')
                        ->getStyle('K12')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L12', $this->industrial->NetAmount)
                        ->getStyle('L12')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E12', '=L12-SUM(F12:J12)')
                        ->getStyle('E12')->getNumberFormat()->setFormatCode('#,##0.00');

                // STREET LIGHTS
                $event->sheet->setCellValue('A13', 'STREET LIGHTS');
                $event->sheet->setCellValue('B13', $this->streetLights->NoOfConsumers)
                        ->getStyle('B13')->getNumberFormat()->setFormatCode('#,##0');
                $event->sheet->setCellValue('C13', $this->streetLights->KwhUsed)
                        ->getStyle('C13')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D13', $this->streetLights->DemandKwh)
                        ->getStyle('D13')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F13', $this->streetLights->RealPropertyTax)
                        ->getStyle('F13')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G13', $this->streetLights->GenerationVAT)
                        ->getStyle('G13')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H13', $this->streetLights->TransmissionVAT)
                        ->getStyle('H13')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I13', $this->streetLights->SystemLossVAT)
                        ->getStyle('I13')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J13', $this->streetLights->DistributionVAT)
                        ->getStyle('J13')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K13', '=SUM(F13:J13)')
                        ->getStyle('K13')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L13', $this->streetLights->NetAmount)
                        ->getStyle('L13')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E13', '=L13-SUM(F13:J13)')
                        ->getStyle('E13')->getNumberFormat()->setFormatCode('#,##0.00');

                // PUBLIC BUILDING
                $event->sheet->setCellValue('A14', 'PUBLIC BUILDING');
                $event->sheet->setCellValue('B14', $this->publicBuilding->NoOfConsumers)
                        ->getStyle('B14')->getNumberFormat()->setFormatCode('#,##0');
                $event->sheet->setCellValue('C14', $this->publicBuilding->KwhUsed)
                        ->getStyle('C14')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D14', $this->publicBuilding->DemandKwh)
                        ->getStyle('D14')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F14', $this->publicBuilding->RealPropertyTax)
                        ->getStyle('F14')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G14', $this->publicBuilding->GenerationVAT)
                        ->getStyle('G14')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H14', $this->publicBuilding->TransmissionVAT)
                        ->getStyle('H14')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I14', $this->publicBuilding->SystemLossVAT)
                        ->getStyle('I14')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J14', $this->publicBuilding->DistributionVAT)
                        ->getStyle('J14')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K14', '=SUM(F14:J14)')
                        ->getStyle('K14')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L14', $this->publicBuilding->NetAmount)
                        ->getStyle('L14')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E14', '=L14-SUM(F14:J14)')
                        ->getStyle('E14')->getNumberFormat()->setFormatCode('#,##0.00');

                // TOTAL
                $event->sheet->setCellValue('A15', 'TOTAL');
                $event->sheet->setCellValue('B15', $this->totalLv->NoOfConsumers)
                        ->getStyle('B15')->getNumberFormat()->setFormatCode('#,##0');
                $event->sheet->setCellValue('C15', $this->totalLv->KwhUsed)
                        ->getStyle('C15')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D15', $this->totalLv->DemandKwh)
                        ->getStyle('D15')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F15', $this->totalLv->RealPropertyTax)
                        ->getStyle('F15')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G15', $this->totalLv->GenerationVAT)
                        ->getStyle('G15')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H15', $this->totalLv->TransmissionVAT)
                        ->getStyle('H15')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I15', $this->totalLv->SystemLossVAT)
                        ->getStyle('I15')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J15', $this->totalLv->DistributionVAT)
                        ->getStyle('J15')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K15', '=SUM(F15:J15)')
                        ->getStyle('K15')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L15', $this->totalLv->NetAmount)
                        ->getStyle('L15')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E15', '=L15-SUM(F15:J15)')
                        ->getStyle('E15')->getNumberFormat()->setFormatCode('#,##0.00');

                $event->sheet->mergeCells(sprintf('A16:L16'))
                    ->setCellValue('A16', 'HIGHER VOLTAGE');

                // COMMERCIAL
                $event->sheet->setCellValue('A17', 'COMMERCIAL');
                $event->sheet->setCellValue('B17', $this->commercialHv->NoOfConsumers)
                        ->getStyle('B17')->getNumberFormat()->setFormatCode('#,##0');
                $event->sheet->setCellValue('C17', $this->commercialHv->KwhUsed)
                        ->getStyle('C17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D17', $this->commercialHv->DemandKwh)
                        ->getStyle('D17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F17', $this->commercialHv->RealPropertyTax)
                        ->getStyle('F17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G17', $this->commercialHv->GenerationVAT)
                        ->getStyle('G17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H17', $this->commercialHv->TransmissionVAT)
                        ->getStyle('H17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I17', $this->commercialHv->SystemLossVAT)
                        ->getStyle('I17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J17', $this->commercialHv->DistributionVAT)
                        ->getStyle('J17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K17', '=SUM(F17:J17)')
                        ->getStyle('K17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L17', $this->commercialHv->NetAmount)
                        ->getStyle('L17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E17', '=L17-SUM(F17:J17)')
                        ->getStyle('E17')->getNumberFormat()->setFormatCode('#,##0.00');

                // INDUSTRIAL
                $event->sheet->setCellValue('A18', 'INDUSTRIAL');
                $event->sheet->setCellValue('B18', $this->industrialHv->NoOfConsumers)
                        ->getStyle('B18')->getNumberFormat()->setFormatCode('#,##0');
                $event->sheet->setCellValue('C18', $this->industrialHv->KwhUsed)
                        ->getStyle('C18')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D18', $this->industrialHv->DemandKwh)
                        ->getStyle('D18')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F18', $this->industrialHv->RealPropertyTax)
                        ->getStyle('F18')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G18', $this->industrialHv->GenerationVAT)
                        ->getStyle('G18')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H18', $this->industrialHv->TransmissionVAT)
                        ->getStyle('H18')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I18', $this->industrialHv->SystemLossVAT)
                        ->getStyle('I18')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J18', $this->industrialHv->DistributionVAT)
                        ->getStyle('J18')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K18', '=SUM(F18:J18)')
                        ->getStyle('K18')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L18', $this->industrialHv->NetAmount)
                        ->getStyle('L18')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E18', '=L18-SUM(F18:J18)')
                        ->getStyle('E18')->getNumberFormat()->setFormatCode('#,##0.00');

                // PUBLIC BUILDING
                $event->sheet->setCellValue('A19', 'PUBLIC BUILDING');
                $event->sheet->setCellValue('B19', $this->publicBuildingHv->NoOfConsumers)
                        ->getStyle('B19')->getNumberFormat()->setFormatCode('#,##0');
                $event->sheet->setCellValue('C19', $this->publicBuildingHv->KwhUsed)
                        ->getStyle('C19')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D19', $this->publicBuildingHv->DemandKwh)
                        ->getStyle('D19')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F19', $this->publicBuildingHv->RealPropertyTax)
                        ->getStyle('F19')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G19', $this->publicBuildingHv->GenerationVAT)
                        ->getStyle('G19')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H19', $this->publicBuildingHv->TransmissionVAT)
                        ->getStyle('H19')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I19', $this->publicBuildingHv->SystemLossVAT)
                        ->getStyle('I19')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J19', $this->publicBuildingHv->DistributionVAT)
                        ->getStyle('J19')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K19', '=SUM(F19:J19)')
                        ->getStyle('K19')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L19', $this->publicBuildingHv->NetAmount)
                        ->getStyle('L19')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E19', '=L19-SUM(F19:J19)')
                        ->getStyle('E19')->getNumberFormat()->setFormatCode('#,##0.00');

                // TOTAL
                $event->sheet->setCellValue('A20', 'TOTAL');
                $event->sheet->setCellValue('B20', $this->totalHv->NoOfConsumers)
                        ->getStyle('B20')->getNumberFormat()->setFormatCode('#,##0');
                $event->sheet->setCellValue('C20', $this->totalHv->KwhUsed)
                        ->getStyle('C20')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D20', $this->totalHv->DemandKwh)
                        ->getStyle('D20')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F20', $this->totalHv->RealPropertyTax)
                        ->getStyle('F20')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G20', $this->totalHv->GenerationVAT)
                        ->getStyle('G20')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H20', $this->totalHv->TransmissionVAT)
                        ->getStyle('H20')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I20', $this->totalHv->SystemLossVAT)
                        ->getStyle('I20')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J20', $this->totalHv->DistributionVAT)
                        ->getStyle('J20')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K20', '=SUM(F20:J20)')
                        ->getStyle('K20')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L20', $this->totalHv->NetAmount)
                        ->getStyle('L20')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E20', '=L20-SUM(F20:J20)')
                        ->getStyle('E20')->getNumberFormat()->setFormatCode('#,##0.00');

                // GRAND TOTAL
                $event->sheet->setCellValue('A21', 'GRAND TOTAL');
                $event->sheet->setCellValue('B21', $this->grandTotal->NoOfConsumers)
                        ->getStyle('B21')->getNumberFormat()->setFormatCode('#,##0');
                $event->sheet->setCellValue('C21', $this->grandTotal->KwhUsed)
                        ->getStyle('C21')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D21', $this->grandTotal->DemandKwh)
                        ->getStyle('D21')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F21', $this->grandTotal->RealPropertyTax)
                        ->getStyle('F21')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G21', $this->grandTotal->GenerationVAT)
                        ->getStyle('G21')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H21', $this->grandTotal->TransmissionVAT)
                        ->getStyle('H21')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I21', $this->grandTotal->SystemLossVAT)
                        ->getStyle('I21')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J21', $this->grandTotal->DistributionVAT)
                        ->getStyle('J21')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K21', '=SUM(F21:J21)')
                        ->getStyle('K21')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L21', $this->grandTotal->NetAmount)
                        ->getStyle('L21')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E21', '=L21-SUM(F21:J21)')
                        ->getStyle('E21')->getNumberFormat()->setFormatCode('#,##0.00');

                // SIGNATORIES
                $event->sheet->setCellValue('B25', 'Prepared By:');
                $event->sheet->mergeCells(sprintf('B27:C27'));
                $event->sheet->setCellValue('B27', 'ANTHONY VAN C. DE LA NOCHE');
                $event->sheet->mergeCells(sprintf('B28:C28'));
                $event->sheet->setCellValue('B28', 'Meter Reading and Billing Analyst');

                $event->sheet->setCellValue('E25', 'Recommending Approval:');
                $event->sheet->mergeCells(sprintf('E27:F27'));
                $event->sheet->setCellValue('E27', 'ANTHONY B. LAGRADA');
                $event->sheet->mergeCells(sprintf('E28:F28'));
                $event->sheet->setCellValue('E28', 'OIC - CITET Dept. Manager');

                $event->sheet->mergeCells(sprintf('G27:H27'));
                $event->sheet->setCellValue('G27', 'ELREEN JANE Z. BANOT');
                $event->sheet->mergeCells(sprintf('G28:H28'));
                $event->sheet->setCellValue('G28', 'FSD Manager');

                $event->sheet->setCellValue('J25', 'Approved by:');
                $event->sheet->mergeCells(sprintf('J27:K27'));
                $event->sheet->setCellValue('J27', 'ATTY. DANNY L. PONDEVILLA');
                $event->sheet->mergeCells(sprintf('J28:K28'));
                $event->sheet->setCellValue('J28', 'General Manager');
            }
        ];
    }
}