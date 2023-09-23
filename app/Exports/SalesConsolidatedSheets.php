<?php

namespace App\Exports;

ini_set('max_execution_time', '600');

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithPreCalculateFormulas;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Facades\DB;

class SalesConsolidatedSheets implements ShouldAutoSize, WithColumnFormatting, WithStyles, WithEvents, WithPreCalculateFormulas, WithTitle {

    private $period, $townCode, $townName, $sales;

    public function __construct($period, $townCode, $townName, $sales) {
        $this->period = $period;
        $this->townCode = $townCode;
        $this->townName = $townName;
        $this->sales = $sales;
    }

    public function columnFormats(): array {
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
        ];
    }

    public function styles(Worksheet $sheet) {
        return [
            // Style the first row as bold text.
            'A1:L8' => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
            'A7:L59' => [
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
        return $this->townName . ' (' . $this->townCode . ')';
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function(AfterSheet $event) { 
                $event->sheet->mergeCells(sprintf('A1:L1'));
                $event->sheet->mergeCells(sprintf('A2:L2'));
                $event->sheet->mergeCells(sprintf('A4:L4'));
                $event->sheet->mergeCells(sprintf('A5:L5'));
                
                $event->sheet->setCellValue('A1', env('APP_COMPANY'));
                $event->sheet->setCellValue('A2', env('APP_ADDRESS'));
                $event->sheet->setCellValue('A4', 'SALES CONSOLIDATED - ' . date('F Y', strtotime($this->period)));
                $event->sheet->setCellValue('A5', 'DISTRICT: ' . $this->townName);

                // HEADERS
                $event->sheet->mergeCells(sprintf('A7:A8'))
                    ->setCellValue('A7', 'REFERENCE');   
                $event->sheet->mergeCells(sprintf('B7:C7'))
                    ->setCellValue('B7', 'RESIDENTIAL');   
                $event->sheet->setCellValue('B8', 'POBLACION');    
                $event->sheet->setCellValue('C8', 'RURAL'); 
                $event->sheet->mergeCells(sprintf('D7:E7'))
                    ->setCellValue('D7', 'COMMERCIAL');   
                $event->sheet->setCellValue('D8', 'LOW VOLTAGE');   
                $event->sheet->setCellValue('E8', 'HIGH VOLTAGE'); 
                $event->sheet->mergeCells(sprintf('F7:F8'))
                    ->setCellValue('F7', 'IRRIGATION/WATER SYSTEM');   
                $event->sheet->mergeCells(sprintf('G7:H7'))
                    ->setCellValue('G7', 'INDUSTRIAL');   
                $event->sheet->setCellValue('G8', 'LOW VOLTAGE');   
                $event->sheet->setCellValue('H8', 'HIGH VOLTAGE');  
                $event->sheet->mergeCells(sprintf('I7:I8'))
                    ->setCellValue('I7', 'STREET LIGHT');  
                $event->sheet->mergeCells(sprintf('J7:K7'))
                    ->setCellValue('J7', 'PUBLIC BUILDINGS');   
                $event->sheet->setCellValue('J8', 'LOW VOLTAGE');   
                $event->sheet->setCellValue('K8', 'HIGH VOLTAGE');  
                $event->sheet->mergeCells(sprintf('L7:L8'))
                    ->setCellValue('L7', 'TOTAL AMOUNT');  
                    
                // CHARGES
                $event->sheet->setCellValue('A9', 'GENERATION AND TRANSMISSION CHARGES:');    
                $event->sheet->setCellValue('A10', 'Generation System Charge');
                $event->sheet->setCellValue('A11', 'Transmission Delivery Charge (kW)');
                $event->sheet->setCellValue('A12', 'Transmission Delivery Charge (kWH)');
                $event->sheet->setCellValue('A13', 'System Loss Charge');
                $event->sheet->setCellValue('A14', 'Other Generation Rate Adjustment (OGA) (KWH)');
                $event->sheet->setCellValue('A15', 'Other Transmission Cost Adjustment (OTCA) (KW)');
                $event->sheet->setCellValue('A16', 'Other Transmission Cost Adjustment (OTCA) (KWH)');
                $event->sheet->setCellValue('A17', 'Other System Loss Cost Adjustment (OSLA) (KWH)');
                $event->sheet->setCellValue('A18', 'SUB-TOTAL:');
                $event->sheet->setCellValue('A19', 'DISTRIBUTION/SUPPLY/METERING CHARGES:');
                $event->sheet->setCellValue('A20', 'Distribution Demand Charge');
                $event->sheet->setCellValue('A21', 'Distribution System Charge');
                $event->sheet->setCellValue('A22', 'Supply Retail Customer Charge');
                $event->sheet->setCellValue('A23', 'Supply System Charge');
                $event->sheet->setCellValue('A24', 'Metering Retail Customer Charge');
                $event->sheet->setCellValue('A25', 'Metering System Charge');
                $event->sheet->setCellValue('A26', 'Reinvestment Fund For Sust. CAPEX (RFSC)');
                $event->sheet->setCellValue('A27', 'SUB-TOTAL:');
                $event->sheet->setCellValue('A28', 'OTHERS:');
                $event->sheet->setCellValue('A29', 'Lifeline Rate (Discount/Subsidy)');
                $event->sheet->setCellValue('A30', 'Inter-Class Cross Subsidy Charge');
                $event->sheet->setCellValue('A31', 'Senior Citizen Discount');
                $event->sheet->setCellValue('A32', 'Senior Citizen Subsidy');
                $event->sheet->setCellValue('A33', 'Senior Citizen Discount & Subsidy Adjustment (KWH)');
                $event->sheet->setCellValue('A34', 'Other Lifeline Rate Cost Adjustment (OLRA) (KWH)');
                $event->sheet->setCellValue('A35', 'SUB-TOTAL:');
                $event->sheet->setCellValue('A36', 'UNIVERSAL CHARGE:');
                $event->sheet->setCellValue('A37', 'Missionary Electrification Charge');
                $event->sheet->setCellValue('A38', 'Environmental Charge');
                $event->sheet->setCellValue('A39', 'Stranded Contract Costs');
                $event->sheet->setCellValue('A40', 'NPC Stranded Debt');
                $event->sheet->setCellValue('A41', 'Feed-inTariff Allowance');
                $event->sheet->setCellValue('A42', 'Renewable Energy Development Cash Incentive (REDCI)');
                $event->sheet->setCellValue('A43', 'SUB-TOTAL:');
                $event->sheet->setCellValue('A44', 'GOVERNMENT REVENUES:');
                $event->sheet->setCellValue('A45', 'VAT - Generation');
                $event->sheet->setCellValue('A46', 'VAT - Transmission');
                $event->sheet->setCellValue('A47', 'VAT - System Loss');
                $event->sheet->setCellValue('A48', 'VAT - Distribution & Others');
                $event->sheet->setCellValue('A49', 'SUB-TOTAL:');
                $event->sheet->setCellValue('A50', 'Franchise Tax');
                $event->sheet->setCellValue('A51', 'Business Tax');
                $event->sheet->setCellValue('A52', 'Real Property Tax (RPT)');
                $event->sheet->setCellValue('A53', 'SUB-TOTAL:');

                $event->sheet->setCellValue('A55', 'GRAND TOTAL:');
                $event->sheet->setCellValue('A56', 'TOTAL DSM:');
                $event->sheet->setCellValue('A57', 'TOTAL KWH USED:');
                $event->sheet->setCellValue('A58', 'TOTAL DEMAND KW:');
                $event->sheet->setCellValue('A59', 'NUMBER OF CONSUMERS:');

                /**
                 * CONTENT
                 */
                if ($this->sales != null && $this->sales->Status=='CLOSED') {
                        if ($this->townCode == '00') {
                                $data = DB::table('Billing_Rates')
                                ->select(
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS GenerationPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS GenerationRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS GenerationComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS GenerationComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS GenerationWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS GenerationIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS GenerationIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS GenerationStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS GenerationPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS GenerationPbHighVoltage"),  

                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS TransmissionKwPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS TransmissionKwRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS TransmissionKwComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS TransmissionKwComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS TransmissionKwWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS TransmissionKwIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS TransmissionKwIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS TransmissionKwStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS TransmissionKwPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS TransmissionKwPbHighVoltage"),   
                                
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS TransmissionKwhPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS TransmissionKwhRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS TransmissionKwhComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS TransmissionKwhComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS TransmissionKwhWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS TransmissionKwhIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS TransmissionKwhIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS TransmissionKwhStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS TransmissionKwhPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS TransmissionKwhPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS SystemLossPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS SystemLossRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS SystemLossComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS SystemLossComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS SystemLossWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS SystemLossIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS SystemLossIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS SystemLossStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS SystemLossPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS SystemLossPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS OgaPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS OgaRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS OgaComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS OgaComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS OgaWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS OgaIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS OgaIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS OgaStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS OgaPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS OgaPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS OtcaKwPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS OtcaKwRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS OtcaKwComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS OtcaKwComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS OtcaKwWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS OtcaKwIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS OtcaKwIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS OtcaKwStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS OtcaKwPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS OtcaKwPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS OtcaKwhPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS OtcaKwhRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS OtcaKwhComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS OtcaKwhComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS OtcaKwhWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS OtcaKwhIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS OtcaKwhIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS OtcaKwhStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS OtcaKwhPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS OtcaKwhPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS OslaPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS OslaRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS OslaComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS OslaComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS OslaWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS OslaIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS OslaIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS OslaStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS OslaPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS OslaPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS DistributionDemandPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS DistributionDemandRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS DistributionDemandComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS DistributionDemandComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS DistributionDemandWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS DistributionDemandIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS DistributionDemandIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS DistributionDemandStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS DistributionDemandPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS DistributionDemandPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS DistributionSystemPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS DistributionSystemRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS DistributionSystemComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS DistributionSystemComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS DistributionSystemWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS DistributionSystemIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS DistributionSystemIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS DistributionSystemStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS DistributionSystemPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS DistributionSystemPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS SupplyRetailPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS SupplyRetailRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS SupplyRetailComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS SupplyRetailComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS SupplyRetailWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS SupplyRetailIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS SupplyRetailIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS SupplyRetailStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS SupplyRetailPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS SupplyRetailPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS SupplySystemPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS SupplySystemRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS SupplySystemComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS SupplySystemComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS SupplySystemWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS SupplySystemIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS SupplySystemIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS SupplySystemStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS SupplySystemPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS SupplySystemPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS MeteringRetailPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS MeteringRetailRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS MeteringRetailComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS MeteringRetailComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS MeteringRetailWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS MeteringRetailIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS MeteringRetailIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS MeteringRetailStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS MeteringRetailPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS MeteringRetailPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS MeteringSystemPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS MeteringSystemRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS MeteringSystemComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS MeteringSystemComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS MeteringSystemWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS MeteringSystemIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS MeteringSystemIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS MeteringSystemStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS MeteringSystemPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS MeteringSystemPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS RfscPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS RfscRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS RfscComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS RfscComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS RfscWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS RfscIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS RfscIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS RfscStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS RfscPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS RfscPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS LifelinePoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS LifelineRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS LifelineComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS LifelineComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS LifelineWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS LifelineIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS LifelineIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS LifelineStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS LifelinePbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS LifelinePbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS IccsPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS IccsRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS IccsComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS IccsComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS IccsWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IccsIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IccsIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS IccsStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS IccsPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS IccsPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(AdditionalKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('RESIDENTIAL')) AS SCDiscountPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(AdditionalKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('RURAL RESIDENTIAL')) AS SCDiscountRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(AdditionalKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('COMMERCIAL')) AS SCDiscountComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(AdditionalKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('COMMERCIAL HIGH VOLTAGE')) AS SCDiscountComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(AdditionalKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('IRRIGATION/WATER SYSTEMS')) AS SCDiscountWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(AdditionalKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('INDUSTRIAL')) AS SCDiscountIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(AdditionalKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('INDUSTRIAL HIGH VOLTAGE')) AS SCDiscountIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(AdditionalKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('STREET LIGHTS')) AS SCDiscountStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(AdditionalKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('PUBLIC BUILDING')) AS SCDiscountPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(AdditionalKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS SCDiscountPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('RESIDENTIAL')) AS SCSubsidyPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('RURAL RESIDENTIAL')) AS SCSubsidyRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('COMMERCIAL')) AS SCSubsidyComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('COMMERCIAL HIGH VOLTAGE')) AS SCSubsidyComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('IRRIGATION/WATER SYSTEMS')) AS SCSubsidyWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('INDUSTRIAL')) AS SCSubsidyIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('INDUSTRIAL HIGH VOLTAGE')) AS SCSubsidyIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('STREET LIGHTS')) AS SCSubsidyStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('PUBLIC BUILDING')) AS SCSubsidyPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS SCSubsidyPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS SCAdjPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS SCAdjRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS SCAdjComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS SCAdjComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS SCAdjWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS SCAdjIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS SCAdjIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS SCAdjStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS SCAdjPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS SCAdjPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS OlraPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS OlraRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS OlraComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS OlraComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS OlraWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS OlraIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS OlraIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS OlraStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS OlraPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS OlraPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS MissionaryPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS MissionaryRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS MissionaryComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS MissionaryComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS MissionaryWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS MissionaryIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS MissionaryIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS MissionaryStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS MissionaryPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS MissionaryPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS EnvironmentalPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS EnvironmentalRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS EnvironmentalComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS EnvironmentalComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS EnvironmentalWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS EnvironmentalIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS EnvironmentalIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS EnvironmentalStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS EnvironmentalPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS EnvironmentalPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS StrandedContractPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS StrandedContractRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS StrandedContractComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS StrandedContractComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS StrandedContractWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS StrandedContractIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS StrandedContractIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StrandedContractStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS StrandedContractPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS StrandedContractPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS StrandedDebtPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS StrandedDebtRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS StrandedDebtComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS StrandedDebtComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS StrandedDebtWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS StrandedDebtIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS StrandedDebtIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StrandedDebtStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS StrandedDebtPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS StrandedDebtPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS FitAllPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS FitAllRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS FitAllComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS FitAllComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS FitAllWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS FitAllIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS FitAllIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS FitAllStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS FitAllPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS FitAllPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS RedciPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS RedciRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS RedciComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS RedciComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS RedciWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS RedciIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS RedciIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS RedciStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS RedciPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS RedciPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS GenVatPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS GenVatRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS GenVatComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS GenVatComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS GenVatWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS GenVatIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS GenVatIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS GenVatStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS GenVatPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS GenVatPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS TransVatPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS TransVatRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS TransVatComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS TransVatComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS TransVatWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS TransVatIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS TransVatIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS TransVatStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS TransVatPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS TransVatPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS SysLossVatPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS SysLossVatRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS SysLossVatComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS SysLossVatComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS SysLossVatWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS SysLossVatIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS SysLossVatIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS SysLossVatStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS SysLossVatPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS SysLossVatPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS DistributionVatPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS DistributionVatRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS DistributionVatComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS DistributionVatComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS DistributionVatWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS DistributionVatIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS DistributionVatIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS DistributionVatStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS DistributionVatPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS DistributionVatPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS FranchisePoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS FranchiseRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS FranchiseComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS FranchiseComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS FranchiseWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS FranchiseIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS FranchiseIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS FranchiseStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS FranchisePbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS FranchisePbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS BusinessPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS BusinessRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS BusinessComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS BusinessComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS BusinessWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS BusinessIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS BusinessIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS BusinessStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS BusinessPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS BusinessPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS RPTPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS RPTRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS RPTComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS RPTComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS RPTWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS RPTIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS RPTIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS RPTStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS RPTPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS RPTPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS KwhUsedPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS KwhUsedRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS KwhUsedComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS KwhUsedComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS KwhUsedWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS KwhUsedIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS KwhUsedIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS KwhUsedStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS KwhUsedPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS KwhUsedPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS DemandPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS DemandRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS DemandComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS DemandComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS DemandWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS DemandIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS DemandIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS DemandStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS DemandPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS DemandPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(BillNumber AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS CountPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(BillNumber AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS CountRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(BillNumber AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS CountComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(BillNumber AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS CountComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(BillNumber AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS CountWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(BillNumber AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS CountIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(BillNumber AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS CountIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(BillNumber AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS CountStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(BillNumber AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS CountPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(BillNumber AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS CountPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS GrandTotalPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS GrandTotalRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS GrandTotalComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS GrandTotalComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS GrandTotalWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS GrandTotalIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS GrandTotalIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS GrandTotalStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS GrandTotalPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS GrandTotalPbHighVoltage"), 
                                )
                                ->limit(1)
                                ->first();
                        } else {
                                $data = DB::table('Billing_Rates')
                                ->select(
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS GenerationPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS GenerationRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS GenerationComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS GenerationComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS GenerationWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS GenerationIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS GenerationIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS GenerationStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS GenerationPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS GenerationPbHighVoltage"),    
                                
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS TransmissionKwPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS TransmissionKwRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS TransmissionKwComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS TransmissionKwComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS TransmissionKwWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS TransmissionKwIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS TransmissionKwIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS TransmissionKwStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS TransmissionKwPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS TransmissionKwPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS TransmissionKwhPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS TransmissionKwhRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS TransmissionKwhComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS TransmissionKwhComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS TransmissionKwhWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS TransmissionKwhIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS TransmissionKwhIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS TransmissionKwhStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS TransmissionKwhPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS TransmissionKwhPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS SystemLossPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS SystemLossRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS SystemLossComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS SystemLossComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS SystemLossWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS SystemLossIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS SystemLossIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS SystemLossStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS SystemLossPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS SystemLossPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS OgaPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS OgaRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS OgaComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS OgaComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS OgaWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS OgaIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS OgaIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS OgaStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS OgaPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS OgaPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS OtcaKwPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS OtcaKwRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS OtcaKwComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS OtcaKwComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS OtcaKwWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS OtcaKwIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS OtcaKwIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS OtcaKwStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS OtcaKwPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS OtcaKwPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS OtcaKwhPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS OtcaKwhRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS OtcaKwhComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS OtcaKwhComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS OtcaKwhWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS OtcaKwhIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS OtcaKwhIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS OtcaKwhStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS OtcaKwhPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS OtcaKwhPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS OslaPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS OslaRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS OslaComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS OslaComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS OslaWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS OslaIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS OslaIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS OslaStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS OslaPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS OslaPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS DistributionDemandPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS DistributionDemandRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS DistributionDemandComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS DistributionDemandComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS DistributionDemandWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS DistributionDemandIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS DistributionDemandIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS DistributionDemandStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS DistributionDemandPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS DistributionDemandPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS DistributionSystemPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS DistributionSystemRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS DistributionSystemComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS DistributionSystemComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS DistributionSystemWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS DistributionSystemIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS DistributionSystemIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS DistributionSystemStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS DistributionSystemPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS DistributionSystemPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS SupplyRetailPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS SupplyRetailRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS SupplyRetailComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS SupplyRetailComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS SupplyRetailWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS SupplyRetailIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS SupplyRetailIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS SupplyRetailStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS SupplyRetailPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS SupplyRetailPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS SupplySystemPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS SupplySystemRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS SupplySystemComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS SupplySystemComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS SupplySystemWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS SupplySystemIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS SupplySystemIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS SupplySystemStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS SupplySystemPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS SupplySystemPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS MeteringRetailPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS MeteringRetailRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS MeteringRetailComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS MeteringRetailComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS MeteringRetailWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS MeteringRetailIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS MeteringRetailIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS MeteringRetailStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS MeteringRetailPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS MeteringRetailPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS MeteringSystemPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS MeteringSystemRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS MeteringSystemComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS MeteringSystemComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS MeteringSystemWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS MeteringSystemIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS MeteringSystemIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS MeteringSystemStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS MeteringSystemPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS MeteringSystemPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS RfscPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS RfscRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS RfscComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS RfscComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS RfscWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS RfscIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS RfscIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS RfscStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS RfscPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS RfscPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS LifelinePoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS LifelineRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS LifelineComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS LifelineComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS LifelineWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS LifelineIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS LifelineIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS LifelineStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS LifelinePbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS LifelinePbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS IccsPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS IccsRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS IccsComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS IccsComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS IccsWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IccsIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IccsIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS IccsStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS IccsPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS IccsPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(AdditionalKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('RESIDENTIAL')) AS SCDiscountPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(AdditionalKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('RURAL RESIDENTIAL')) AS SCDiscountRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(AdditionalKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('COMMERCIAL')) AS SCDiscountComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(AdditionalKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('COMMERCIAL HIGH VOLTAGE')) AS SCDiscountComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(AdditionalKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('IRRIGATION/WATER SYSTEMS')) AS SCDiscountWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(AdditionalKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('INDUSTRIAL')) AS SCDiscountIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(AdditionalKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('INDUSTRIAL HIGH VOLTAGE')) AS SCDiscountIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(AdditionalKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('STREET LIGHTS')) AS SCDiscountStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(AdditionalKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('PUBLIC BUILDING')) AS SCDiscountPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(AdditionalKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS SCDiscountPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('RESIDENTIAL')) AS SCSubsidyPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('RURAL RESIDENTIAL')) AS SCSubsidyRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('COMMERCIAL')) AS SCSubsidyComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('COMMERCIAL HIGH VOLTAGE')) AS SCSubsidyComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('IRRIGATION/WATER SYSTEMS')) AS SCSubsidyWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('INDUSTRIAL')) AS SCSubsidyIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('INDUSTRIAL HIGH VOLTAGE')) AS SCSubsidyIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('STREET LIGHTS')) AS SCSubsidyStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('PUBLIC BUILDING')) AS SCSubsidyPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType  IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS SCSubsidyPbHighVoltage"), 
                                
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS SCAdjPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS SCAdjRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS SCAdjComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS SCAdjComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS SCAdjWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS SCAdjIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS SCAdjIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS SCAdjStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS SCAdjPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS SCAdjPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS OlraPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS OlraRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS OlraComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS OlraComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS OlraWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS OlraIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS OlraIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS OlraStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS OlraPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS OlraPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS MissionaryPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS MissionaryRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS MissionaryComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS MissionaryComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS MissionaryWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS MissionaryIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS MissionaryIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS MissionaryStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS MissionaryPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS MissionaryPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS EnvironmentalPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS EnvironmentalRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS EnvironmentalComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS EnvironmentalComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS EnvironmentalWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS EnvironmentalIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS EnvironmentalIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS EnvironmentalStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS EnvironmentalPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS EnvironmentalPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS StrandedContractPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS StrandedContractRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS StrandedContractComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS StrandedContractComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS StrandedContractWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS StrandedContractIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS StrandedContractIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StrandedContractStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS StrandedContractPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS StrandedContractPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS StrandedDebtPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS StrandedDebtRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS StrandedDebtComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS StrandedDebtComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS StrandedDebtWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS StrandedDebtIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS StrandedDebtIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StrandedDebtStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS StrandedDebtPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS StrandedDebtPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS FitAllPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS FitAllRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS FitAllComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS FitAllComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS FitAllWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS FitAllIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS FitAllIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS FitAllStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS FitAllPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS FitAllPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS RedciPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS RedciRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS RedciComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS RedciComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS RedciWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS RedciIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS RedciIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS RedciStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS RedciPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS RedciPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS GenVatPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS GenVatRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS GenVatComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS GenVatComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS GenVatWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS GenVatIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS GenVatIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS GenVatStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS GenVatPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS GenVatPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS TransVatPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS TransVatRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS TransVatComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS TransVatComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS TransVatWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS TransVatIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS TransVatIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS TransVatStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS TransVatPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS TransVatPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS SysLossVatPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS SysLossVatRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS SysLossVatComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS SysLossVatComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS SysLossVatWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS SysLossVatIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS SysLossVatIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS SysLossVatStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS SysLossVatPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS SysLossVatPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS DistributionVatPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS DistributionVatRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS DistributionVatComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS DistributionVatComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS DistributionVatWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS DistributionVatIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS DistributionVatIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS DistributionVatStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS DistributionVatPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS DistributionVatPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS FranchisePoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS FranchiseRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS FranchiseComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS FranchiseComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS FranchiseWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS FranchiseIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS FranchiseIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS FranchiseStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS FranchisePbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS FranchisePbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS BusinessPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS BusinessRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS BusinessComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS BusinessComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS BusinessWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS BusinessIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS BusinessIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS BusinessStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS BusinessPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS BusinessPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS RPTPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS RPTRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS RPTComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS RPTComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS RPTWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS RPTIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS RPTIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS RPTStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS RPTPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS RPTPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS KwhUsedPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS KwhUsedRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS KwhUsedComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS KwhUsedComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS KwhUsedWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS KwhUsedIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS KwhUsedIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS KwhUsedStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS KwhUsedPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS KwhUsedPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS DemandPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS DemandRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS DemandComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS DemandComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS DemandWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS DemandIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS DemandIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS DemandStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS DemandPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS DemandPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(BillNumber AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS CountPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(BillNumber AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS CountRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(BillNumber AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS CountComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(BillNumber AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS CountComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(BillNumber AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS CountWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(BillNumber AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS CountIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(BillNumber AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS CountIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(BillNumber AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS CountStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(BillNumber AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS CountPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(BillNumber AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS CountPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS GrandTotalPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS GrandTotalRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS GrandTotalComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS GrandTotalComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS GrandTotalWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS GrandTotalIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS GrandTotalIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS GrandTotalStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS GrandTotalPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS GrandTotalPbHighVoltage"),
                                )
                                ->limit(1)
                                ->first();
                        }
                } else {
                        if ($this->townCode == '00') {
                                $data = DB::table('Billing_Rates')
                                ->select(
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS GenerationPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS GenerationRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS GenerationComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS GenerationComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS GenerationWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS GenerationIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS GenerationIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS GenerationStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS GenerationPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS GenerationPbHighVoltage"),  

                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS TransmissionKwPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS TransmissionKwRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS TransmissionKwComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS TransmissionKwComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS TransmissionKwWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS TransmissionKwIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS TransmissionKwIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS TransmissionKwStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS TransmissionKwPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS TransmissionKwPbHighVoltage"),   
                                
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS TransmissionKwhPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS TransmissionKwhRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS TransmissionKwhComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS TransmissionKwhComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS TransmissionKwhWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS TransmissionKwhIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS TransmissionKwhIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS TransmissionKwhStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS TransmissionKwhPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS TransmissionKwhPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS SystemLossPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS SystemLossRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS SystemLossComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS SystemLossComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS SystemLossWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS SystemLossIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS SystemLossIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS SystemLossStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS SystemLossPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS SystemLossPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS OgaPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS OgaRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS OgaComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS OgaComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS OgaWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS OgaIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS OgaIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS OgaStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS OgaPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS OgaPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS OtcaKwPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS OtcaKwRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS OtcaKwComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS OtcaKwComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS OtcaKwWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS OtcaKwIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS OtcaKwIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS OtcaKwStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS OtcaKwPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS OtcaKwPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS OtcaKwhPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS OtcaKwhRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS OtcaKwhComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS OtcaKwhComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS OtcaKwhWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS OtcaKwhIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS OtcaKwhIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS OtcaKwhStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS OtcaKwhPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS OtcaKwhPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS OslaPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS OslaRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS OslaComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS OslaComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS OslaWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS OslaIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS OslaIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS OslaStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS OslaPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS OslaPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS DistributionDemandPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS DistributionDemandRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS DistributionDemandComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS DistributionDemandComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS DistributionDemandWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS DistributionDemandIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS DistributionDemandIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS DistributionDemandStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS DistributionDemandPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS DistributionDemandPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS DistributionSystemPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS DistributionSystemRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS DistributionSystemComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS DistributionSystemComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS DistributionSystemWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS DistributionSystemIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS DistributionSystemIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS DistributionSystemStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS DistributionSystemPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS DistributionSystemPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS SupplyRetailPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS SupplyRetailRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS SupplyRetailComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS SupplyRetailComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS SupplyRetailWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS SupplyRetailIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS SupplyRetailIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS SupplyRetailStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS SupplyRetailPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS SupplyRetailPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS SupplySystemPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS SupplySystemRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS SupplySystemComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS SupplySystemComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS SupplySystemWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS SupplySystemIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS SupplySystemIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS SupplySystemStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS SupplySystemPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS SupplySystemPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS MeteringRetailPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS MeteringRetailRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS MeteringRetailComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS MeteringRetailComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS MeteringRetailWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS MeteringRetailIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS MeteringRetailIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS MeteringRetailStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS MeteringRetailPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS MeteringRetailPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS MeteringSystemPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS MeteringSystemRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS MeteringSystemComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS MeteringSystemComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS MeteringSystemWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS MeteringSystemIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS MeteringSystemIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS MeteringSystemStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS MeteringSystemPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS MeteringSystemPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS RfscPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS RfscRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS RfscComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS RfscComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS RfscWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS RfscIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS RfscIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS RfscStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS RfscPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS RfscPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS LifelinePoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS LifelineRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS LifelineComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS LifelineComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS LifelineWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS LifelineIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS LifelineIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS LifelineStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS LifelinePbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS LifelinePbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS IccsPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS IccsRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS IccsComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS IccsComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS IccsWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IccsIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IccsIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS IccsStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS IccsPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS IccsPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType  IN ('RESIDENTIAL')) AS SCDiscountPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType  IN ('RURAL RESIDENTIAL')) AS SCDiscountRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType  IN ('COMMERCIAL')) AS SCDiscountComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType  IN ('COMMERCIAL HIGH VOLTAGE')) AS SCDiscountComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType  IN ('IRRIGATION/WATER SYSTEMS')) AS SCDiscountWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType  IN ('INDUSTRIAL')) AS SCDiscountIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType  IN ('INDUSTRIAL HIGH VOLTAGE')) AS SCDiscountIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType  IN ('STREET LIGHTS')) AS SCDiscountStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType  IN ('PUBLIC BUILDING')) AS SCDiscountPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType  IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS SCDiscountPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType  IN ('RESIDENTIAL')) AS SCSubsidyPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType  IN ('RURAL RESIDENTIAL')) AS SCSubsidyRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType  IN ('COMMERCIAL')) AS SCSubsidyComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType  IN ('COMMERCIAL HIGH VOLTAGE')) AS SCSubsidyComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType  IN ('IRRIGATION/WATER SYSTEMS')) AS SCSubsidyWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType  IN ('INDUSTRIAL')) AS SCSubsidyIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType  IN ('INDUSTRIAL HIGH VOLTAGE')) AS SCSubsidyIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType  IN ('STREET LIGHTS')) AS SCSubsidyStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType  IN ('PUBLIC BUILDING')) AS SCSubsidyPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType  IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS SCSubsidyPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS SCAdjPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS SCAdjRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS SCAdjComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS SCAdjComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS SCAdjWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS SCAdjIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS SCAdjIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS SCAdjStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS SCAdjPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS SCAdjPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS OlraPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS OlraRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS OlraComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS OlraComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS OlraWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS OlraIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS OlraIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS OlraStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS OlraPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS OlraPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS MissionaryPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS MissionaryRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS MissionaryComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS MissionaryComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS MissionaryWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS MissionaryIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS MissionaryIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS MissionaryStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS MissionaryPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS MissionaryPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS EnvironmentalPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS EnvironmentalRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS EnvironmentalComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS EnvironmentalComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS EnvironmentalWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS EnvironmentalIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS EnvironmentalIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS EnvironmentalStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS EnvironmentalPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS EnvironmentalPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS StrandedContractPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS StrandedContractRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS StrandedContractComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS StrandedContractComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS StrandedContractWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS StrandedContractIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS StrandedContractIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StrandedContractStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS StrandedContractPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS StrandedContractPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS StrandedDebtPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS StrandedDebtRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS StrandedDebtComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS StrandedDebtComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS StrandedDebtWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS StrandedDebtIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS StrandedDebtIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StrandedDebtStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS StrandedDebtPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS StrandedDebtPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS FitAllPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS FitAllRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS FitAllComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS FitAllComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS FitAllWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS FitAllIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS FitAllIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS FitAllStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS FitAllPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS FitAllPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS RedciPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS RedciRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS RedciComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS RedciComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS RedciWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS RedciIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS RedciIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS RedciStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS RedciPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS RedciPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS GenVatPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS GenVatRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS GenVatComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS GenVatComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS GenVatWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS GenVatIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS GenVatIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS GenVatStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS GenVatPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS GenVatPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS TransVatPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS TransVatRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS TransVatComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS TransVatComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS TransVatWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS TransVatIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS TransVatIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS TransVatStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS TransVatPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS TransVatPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS SysLossVatPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS SysLossVatRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS SysLossVatComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS SysLossVatComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS SysLossVatWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS SysLossVatIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS SysLossVatIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS SysLossVatStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS SysLossVatPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS SysLossVatPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS DistributionVatPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS DistributionVatRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS DistributionVatComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS DistributionVatComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS DistributionVatWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS DistributionVatIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS DistributionVatIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS DistributionVatStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS DistributionVatPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS DistributionVatPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS FranchisePoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS FranchiseRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS FranchiseComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS FranchiseComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS FranchiseWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS FranchiseIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS FranchiseIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS FranchiseStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS FranchisePbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS FranchisePbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS BusinessPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS BusinessRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS BusinessComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS BusinessComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS BusinessWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS BusinessIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS BusinessIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS BusinessStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS BusinessPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS BusinessPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS RPTPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS RPTRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS RPTComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS RPTComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS RPTWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS RPTIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS RPTIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS RPTStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS RPTPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS RPTPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS KwhUsedPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS KwhUsedRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS KwhUsedComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS KwhUsedComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS KwhUsedWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS KwhUsedIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS KwhUsedIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS KwhUsedStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS KwhUsedPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS KwhUsedPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS DemandPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS DemandRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS DemandComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS DemandComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS DemandWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS DemandIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS DemandIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS DemandStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS DemandPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS DemandPbHighVoltage"), 

                                DB::raw("(SELECT COUNT(id) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS CountPoblacion"),
                                DB::raw("(SELECT COUNT(id) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS CountRural"),
                                DB::raw("(SELECT COUNT(id) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS CountComLowVoltage"),
                                DB::raw("(SELECT COUNT(id) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS CountComHighVoltage"),
                                DB::raw("(SELECT COUNT(id) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS CountWaterSystems"),
                                DB::raw("(SELECT COUNT(id) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS CountIndLowVoltage"),
                                DB::raw("(SELECT COUNT(id) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS CountIndHighVoltage"),
                                DB::raw("(SELECT COUNT(id) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS CountStreetLights"), 
                                DB::raw("(SELECT COUNT(id) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS CountPbLowVoltage"),  
                                DB::raw("(SELECT COUNT(id) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS CountPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS GrandTotalPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS GrandTotalRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS GrandTotalComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS GrandTotalComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS GrandTotalWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS GrandTotalIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS GrandTotalIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS GrandTotalStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS GrandTotalPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS GrandTotalPbHighVoltage"), 
                                )
                                ->limit(1)
                                ->first();
                        } else {
                                $data = DB::table('Billing_Rates')
                                ->select(
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS GenerationPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS GenerationRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS GenerationComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS GenerationComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS GenerationWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS GenerationIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS GenerationIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS GenerationStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS GenerationPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS GenerationPbHighVoltage"),    
                                
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS TransmissionKwPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS TransmissionKwRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS TransmissionKwComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS TransmissionKwComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS TransmissionKwWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS TransmissionKwIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS TransmissionKwIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS TransmissionKwStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS TransmissionKwPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS TransmissionKwPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS TransmissionKwhPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS TransmissionKwhRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS TransmissionKwhComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS TransmissionKwhComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS TransmissionKwhWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS TransmissionKwhIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS TransmissionKwhIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS TransmissionKwhStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS TransmissionKwhPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS TransmissionKwhPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS SystemLossPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS SystemLossRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS SystemLossComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS SystemLossComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS SystemLossWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS SystemLossIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS SystemLossIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS SystemLossStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS SystemLossPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS SystemLossPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS OgaPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS OgaRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS OgaComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS OgaComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS OgaWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS OgaIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS OgaIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS OgaStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS OgaPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS OgaPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS OtcaKwPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS OtcaKwRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS OtcaKwComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS OtcaKwComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS OtcaKwWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS OtcaKwIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS OtcaKwIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS OtcaKwStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS OtcaKwPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS OtcaKwPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS OtcaKwhPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS OtcaKwhRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS OtcaKwhComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS OtcaKwhComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS OtcaKwhWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS OtcaKwhIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS OtcaKwhIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS OtcaKwhStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS OtcaKwhPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS OtcaKwhPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS OslaPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS OslaRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS OslaComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS OslaComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS OslaWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS OslaIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS OslaIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS OslaStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS OslaPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS OslaPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS DistributionDemandPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS DistributionDemandRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS DistributionDemandComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS DistributionDemandComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS DistributionDemandWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS DistributionDemandIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS DistributionDemandIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS DistributionDemandStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS DistributionDemandPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS DistributionDemandPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS DistributionSystemPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS DistributionSystemRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS DistributionSystemComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS DistributionSystemComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS DistributionSystemWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS DistributionSystemIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS DistributionSystemIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS DistributionSystemStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS DistributionSystemPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS DistributionSystemPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS SupplyRetailPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS SupplyRetailRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS SupplyRetailComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS SupplyRetailComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS SupplyRetailWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS SupplyRetailIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS SupplyRetailIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS SupplyRetailStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS SupplyRetailPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS SupplyRetailPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS SupplySystemPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS SupplySystemRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS SupplySystemComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS SupplySystemComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS SupplySystemWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS SupplySystemIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS SupplySystemIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS SupplySystemStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS SupplySystemPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS SupplySystemPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS MeteringRetailPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS MeteringRetailRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS MeteringRetailComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS MeteringRetailComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS MeteringRetailWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS MeteringRetailIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS MeteringRetailIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS MeteringRetailStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS MeteringRetailPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS MeteringRetailPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS MeteringSystemPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS MeteringSystemRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS MeteringSystemComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS MeteringSystemComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS MeteringSystemWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS MeteringSystemIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS MeteringSystemIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS MeteringSystemStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS MeteringSystemPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS MeteringSystemPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS RfscPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS RfscRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS RfscComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS RfscComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS RfscWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS RfscIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS RfscIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS RfscStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS RfscPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS RfscPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS LifelinePoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS LifelineRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS LifelineComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS LifelineComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS LifelineWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS LifelineIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS LifelineIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS LifelineStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS LifelinePbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS LifelinePbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS IccsPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS IccsRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS IccsComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS IccsComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS IccsWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IccsIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IccsIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS IccsStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS IccsPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS IccsPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType  IN ('RESIDENTIAL')) AS SCDiscountPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType  IN ('RURAL RESIDENTIAL')) AS SCDiscountRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType  IN ('COMMERCIAL')) AS SCDiscountComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType  IN ('COMMERCIAL HIGH VOLTAGE')) AS SCDiscountComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType  IN ('IRRIGATION/WATER SYSTEMS')) AS SCDiscountWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType  IN ('INDUSTRIAL')) AS SCDiscountIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType  IN ('INDUSTRIAL HIGH VOLTAGE')) AS SCDiscountIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType  IN ('STREET LIGHTS')) AS SCDiscountStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType  IN ('PUBLIC BUILDING')) AS SCDiscountPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType  IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS SCDiscountPbHighVoltage"), 

                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType  IN ('RESIDENTIAL')) AS SCSubsidyPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType  IN ('RURAL RESIDENTIAL')) AS SCSubsidyRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType  IN ('COMMERCIAL')) AS SCSubsidyComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType  IN ('COMMERCIAL HIGH VOLTAGE')) AS SCSubsidyComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType  IN ('IRRIGATION/WATER SYSTEMS')) AS SCSubsidyWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType  IN ('INDUSTRIAL')) AS SCSubsidyIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType  IN ('INDUSTRIAL HIGH VOLTAGE')) AS SCSubsidyIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType  IN ('STREET LIGHTS')) AS SCSubsidyStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType  IN ('PUBLIC BUILDING')) AS SCSubsidyPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType  IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS SCSubsidyPbHighVoltage"), 
                                
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS SCAdjPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS SCAdjRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS SCAdjComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS SCAdjComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS SCAdjWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS SCAdjIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS SCAdjIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS SCAdjStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS SCAdjPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS SCAdjPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS OlraPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS OlraRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS OlraComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS OlraComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS OlraWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS OlraIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS OlraIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS OlraStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS OlraPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS OlraPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS MissionaryPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS MissionaryRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS MissionaryComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS MissionaryComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS MissionaryWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS MissionaryIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS MissionaryIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS MissionaryStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS MissionaryPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS MissionaryPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS EnvironmentalPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS EnvironmentalRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS EnvironmentalComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS EnvironmentalComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS EnvironmentalWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS EnvironmentalIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS EnvironmentalIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS EnvironmentalStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS EnvironmentalPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS EnvironmentalPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS StrandedContractPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS StrandedContractRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS StrandedContractComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS StrandedContractComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS StrandedContractWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS StrandedContractIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS StrandedContractIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StrandedContractStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS StrandedContractPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS StrandedContractPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS StrandedDebtPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS StrandedDebtRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS StrandedDebtComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS StrandedDebtComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS StrandedDebtWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS StrandedDebtIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS StrandedDebtIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StrandedDebtStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS StrandedDebtPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS StrandedDebtPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS FitAllPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS FitAllRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS FitAllComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS FitAllComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS FitAllWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS FitAllIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS FitAllIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS FitAllStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS FitAllPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS FitAllPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS RedciPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS RedciRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS RedciComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS RedciComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS RedciWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS RedciIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS RedciIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS RedciStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS RedciPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS RedciPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS GenVatPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS GenVatRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS GenVatComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS GenVatComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS GenVatWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS GenVatIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS GenVatIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS GenVatStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS GenVatPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS GenVatPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS TransVatPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS TransVatRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS TransVatComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS TransVatComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS TransVatWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS TransVatIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS TransVatIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS TransVatStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS TransVatPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS TransVatPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS SysLossVatPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS SysLossVatRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS SysLossVatComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS SysLossVatComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS SysLossVatWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS SysLossVatIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS SysLossVatIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS SysLossVatStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS SysLossVatPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS SysLossVatPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS DistributionVatPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS DistributionVatRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS DistributionVatComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS DistributionVatComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS DistributionVatWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS DistributionVatIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS DistributionVatIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS DistributionVatStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS DistributionVatPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS DistributionVatPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS FranchisePoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS FranchiseRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS FranchiseComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS FranchiseComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS FranchiseWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS FranchiseIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS FranchiseIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS FranchiseStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS FranchisePbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(FranchiseTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS FranchisePbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS BusinessPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS BusinessRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS BusinessComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS BusinessComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS BusinessWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS BusinessIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS BusinessIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS BusinessStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS BusinessPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(BusinessTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS BusinessPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS RPTPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS RPTRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS RPTComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS RPTComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS RPTWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS RPTIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS RPTIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS RPTStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS RPTPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS RPTPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS KwhUsedPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS KwhUsedRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS KwhUsedComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS KwhUsedComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS KwhUsedWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS KwhUsedIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS KwhUsedIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS KwhUsedStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS KwhUsedPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS KwhUsedPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS DemandPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS DemandRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS DemandComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS DemandComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS DemandWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS DemandIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS DemandIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS DemandStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS DemandPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS DemandPbHighVoltage"),

                                DB::raw("(SELECT COUNT(id) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS CountPoblacion"),
                                DB::raw("(SELECT COUNT(id) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS CountRural"),
                                DB::raw("(SELECT COUNT(id) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS CountComLowVoltage"),
                                DB::raw("(SELECT COUNT(id) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS CountComHighVoltage"),
                                DB::raw("(SELECT COUNT(id) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS CountWaterSystems"),
                                DB::raw("(SELECT COUNT(id) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS CountIndLowVoltage"),
                                DB::raw("(SELECT COUNT(id) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS CountIndHighVoltage"),
                                DB::raw("(SELECT COUNT(id) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS CountStreetLights"), 
                                DB::raw("(SELECT COUNT(id) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS CountPbLowVoltage"),  
                                DB::raw("(SELECT COUNT(id) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS CountPbHighVoltage"),

                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RESIDENTIAL')) AS GrandTotalPoblacion"),
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS GrandTotalRural"),
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL')) AS GrandTotalComLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS GrandTotalComHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS GrandTotalWaterSystems"),
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL')) AS GrandTotalIndLowVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS GrandTotalIndHighVoltage"),
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('STREET LIGHTS')) AS GrandTotalStreetLights"), 
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS GrandTotalPbLowVoltage"),  
                                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND AccountNumber LIKE '" . $this->townCode . "%' AND ServicePeriod='" . $this->period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS GrandTotalPbHighVoltage"),
                                )
                                ->limit(1)
                                ->first();
                        }
                }
                
                // GENERATION
                $event->sheet->setCellValue('B10', $data != null ? $data->GenerationPoblacion : '-')
                        ->getStyle('B10')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C10', $data != null ? $data->GenerationRural : '-')
                        ->getStyle('C10')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D10', $data != null ? $data->GenerationComLowVoltage : '-')
                        ->getStyle('D10')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E10', $data != null ? $data->GenerationComHighVoltage : '-')
                        ->getStyle('E10')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F10', $data != null ? $data->GenerationWaterSystems : '-')
                        ->getStyle('F10')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G10', $data != null ? $data->GenerationIndLowVoltage : '-')
                        ->getStyle('G10')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H10', $data != null ? $data->GenerationIndHighVoltage : '-')
                        ->getStyle('H10')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I10', $data != null ? $data->GenerationStreetLights : '-')
                        ->getStyle('I10')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J10', $data != null ? $data->GenerationPbLowVoltage : '-')
                        ->getStyle('J10')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K10', $data != null ? $data->GenerationPbHighVoltage : '-')
                        ->getStyle('K10')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L10', '=SUM(B10:K10)')
                        ->getStyle('L10')->getNumberFormat()->setFormatCode('#,##0.00');

                // TRANSMISSION KW
                $event->sheet->setCellValue('B11', $data != null ? $data->TransmissionKwPoblacion : '-')
                ->getStyle('B11')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C11', $data != null ? $data->TransmissionKwRural : '-')
                        ->getStyle('C11')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D11', $data != null ? $data->TransmissionKwComLowVoltage : '-')
                        ->getStyle('D11')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E11', $data != null ? $data->TransmissionKwComHighVoltage : '-')
                        ->getStyle('E11')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F11', $data != null ? $data->TransmissionKwWaterSystems : '-')
                        ->getStyle('F11')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G11', $data != null ? $data->TransmissionKwIndLowVoltage : '-')
                        ->getStyle('G11')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H11', $data != null ? $data->TransmissionKwIndHighVoltage : '-')
                        ->getStyle('H11')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I11', $data != null ? $data->TransmissionKwStreetLights : '-')
                        ->getStyle('I11')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J11', $data != null ? $data->TransmissionKwPbLowVoltage : '-')
                        ->getStyle('J11')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K11', $data != null ? $data->TransmissionKwPbHighVoltage : '-')
                        ->getStyle('K11')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L11', '=SUM(B11:K11)')
                        ->getStyle('L11')->getNumberFormat()->setFormatCode('#,##0.00');

                // TRANSMISSION KWH
                $event->sheet->setCellValue('B12', $data != null ? $data->TransmissionKwhPoblacion : '-')
                ->getStyle('B12')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C12', $data != null ? $data->TransmissionKwhRural : '-')
                        ->getStyle('C12')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D12', $data != null ? $data->TransmissionKwhComLowVoltage : '-')
                        ->getStyle('D12')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E12', $data != null ? $data->TransmissionKwhComHighVoltage : '-')
                        ->getStyle('E12')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F12', $data != null ? $data->TransmissionKwhWaterSystems : '-')
                        ->getStyle('F12')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G12', $data != null ? $data->TransmissionKwhIndLowVoltage : '-')
                        ->getStyle('G12')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H12', $data != null ? $data->TransmissionKwhIndHighVoltage : '-')
                        ->getStyle('H12')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I12', $data != null ? $data->TransmissionKwhStreetLights : '-')
                        ->getStyle('I12')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J12', $data != null ? $data->TransmissionKwhPbLowVoltage : '-')
                        ->getStyle('J12')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K12', $data != null ? $data->TransmissionKwhPbHighVoltage : '-')
                        ->getStyle('K12')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L12', '=SUM(B12:K12)')
                        ->getStyle('L12')->getNumberFormat()->setFormatCode('#,##0.00');

                // SYSTEM LOSS
                $event->sheet->setCellValue('B13', $data != null ? $data->SystemLossPoblacion : '-')
                ->getStyle('B13')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C13', $data != null ? $data->SystemLossRural : '-')
                        ->getStyle('C13')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D13', $data != null ? $data->SystemLossComLowVoltage : '-')
                        ->getStyle('D13')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E13', $data != null ? $data->SystemLossComHighVoltage : '-')
                        ->getStyle('E13')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F13', $data != null ? $data->SystemLossWaterSystems : '-')
                        ->getStyle('F13')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G13', $data != null ? $data->SystemLossIndLowVoltage : '-')
                        ->getStyle('G13')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H13', $data != null ? $data->SystemLossIndHighVoltage : '-')
                        ->getStyle('H13')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I13', $data != null ? $data->SystemLossStreetLights : '-')
                        ->getStyle('I13')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J13', $data != null ? $data->SystemLossPbLowVoltage : '-')
                        ->getStyle('J13')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K13', $data != null ? $data->SystemLossPbHighVoltage : '-')
                        ->getStyle('K13')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L13', '=SUM(B13:K13)')
                        ->getStyle('L13')->getNumberFormat()->setFormatCode('#,##0.00');

                // OGA
                $event->sheet->setCellValue('B14', $data != null ? $data->OgaPoblacion : '-')
                ->getStyle('B14')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C14', $data != null ? $data->OgaRural : '-')
                        ->getStyle('C14')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D14', $data != null ? $data->OgaComLowVoltage : '-')
                        ->getStyle('D14')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E14', $data != null ? $data->OgaComHighVoltage : '-')
                        ->getStyle('E14')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F14', $data != null ? $data->OgaWaterSystems : '-')
                        ->getStyle('F14')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G14', $data != null ? $data->OgaIndLowVoltage : '-')
                        ->getStyle('G14')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H14', $data != null ? $data->OgaIndHighVoltage : '-')
                        ->getStyle('H14')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I14', $data != null ? $data->OgaStreetLights : '-')
                        ->getStyle('I14')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J14', $data != null ? $data->OgaPbLowVoltage : '-')
                        ->getStyle('J14')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K14', $data != null ? $data->OgaPbHighVoltage : '-')
                        ->getStyle('K14')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L14', '=SUM(B14:K14)')
                        ->getStyle('L14')->getNumberFormat()->setFormatCode('#,##0.00');

                // OTCA KW
                $event->sheet->setCellValue('B15', $data != null ? $data->OtcaKwPoblacion : '-')
                ->getStyle('B15')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C15', $data != null ? $data->OtcaKwRural : '-')
                        ->getStyle('C15')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D15', $data != null ? $data->OtcaKwComLowVoltage : '-')
                        ->getStyle('D15')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E15', $data != null ? $data->OtcaKwComHighVoltage : '-')
                        ->getStyle('E15')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F15', $data != null ? $data->OtcaKwWaterSystems : '-')
                        ->getStyle('F15')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G15', $data != null ? $data->OtcaKwIndLowVoltage : '-')
                        ->getStyle('G15')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H15', $data != null ? $data->OtcaKwIndHighVoltage : '-')
                        ->getStyle('H15')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I15', $data != null ? $data->OtcaKwStreetLights : '-')
                        ->getStyle('I15')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J15', $data != null ? $data->OtcaKwPbLowVoltage : '-')
                        ->getStyle('J15')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K15', $data != null ? $data->OtcaKwPbHighVoltage : '-')
                        ->getStyle('K15')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L15', '=SUM(B15:K15)')
                        ->getStyle('L15')->getNumberFormat()->setFormatCode('#,##0.00');

                // OTCA KWH
                $event->sheet->setCellValue('B16', $data != null ? $data->OtcaKwhPoblacion : '-')
                ->getStyle('B16')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C16', $data != null ? $data->OtcaKwhRural : '-')
                        ->getStyle('C16')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D16', $data != null ? $data->OtcaKwhComLowVoltage : '-')
                        ->getStyle('D16')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E16', $data != null ? $data->OtcaKwhComHighVoltage : '-')
                        ->getStyle('E16')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F16', $data != null ? $data->OtcaKwhWaterSystems : '-')
                        ->getStyle('F16')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G16', $data != null ? $data->OtcaKwhIndLowVoltage : '-')
                        ->getStyle('G16')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H16', $data != null ? $data->OtcaKwhIndHighVoltage : '-')
                        ->getStyle('H16')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I16', $data != null ? $data->OtcaKwhStreetLights : '-')
                        ->getStyle('I16')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J16', $data != null ? $data->OtcaKwhPbLowVoltage : '-')
                        ->getStyle('J16')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K16', $data != null ? $data->OtcaKwhPbHighVoltage : '-')
                        ->getStyle('K16')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L16', '=SUM(B16:K16)')
                        ->getStyle('L16')->getNumberFormat()->setFormatCode('#,##0.00');

                // OSLA
                $event->sheet->setCellValue('B17', $data != null ? $data->OslaPoblacion : '-')
                ->getStyle('B17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C17', $data != null ? $data->OslaRural : '-')
                        ->getStyle('C17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D17', $data != null ? $data->OslaComLowVoltage : '-')
                        ->getStyle('D17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E17', $data != null ? $data->OslaComHighVoltage : '-')
                        ->getStyle('E17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F17', $data != null ? $data->OslaWaterSystems : '-')
                        ->getStyle('F17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G17', $data != null ? $data->OslaIndLowVoltage : '-')
                        ->getStyle('G17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H17', $data != null ? $data->OslaIndHighVoltage : '-')
                        ->getStyle('H17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I17', $data != null ? $data->OslaStreetLights : '-')
                        ->getStyle('I17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J17', $data != null ? $data->OslaPbLowVoltage : '-')
                        ->getStyle('J17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K17', $data != null ? $data->OslaPbHighVoltage : '-')
                        ->getStyle('K17')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L17', '=SUM(B17:K17)')
                        ->getStyle('L17')->getNumberFormat()->setFormatCode('#,##0.00');

                // GENERATION SUB TOTAL
                $event->sheet->setCellValue('B18', '=SUM(B10:B17)')
                ->getStyle('B18')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C18', '=SUM(C10:C17)')
                        ->getStyle('C18')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D18', '=SUM(D10:D17)')
                        ->getStyle('D18')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E18', '=SUM(E10:E17)')
                        ->getStyle('E18')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F18', '=SUM(F10:F17)')
                        ->getStyle('F18')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G18', '=SUM(G10:G17)')
                        ->getStyle('G18')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H18', '=SUM(H10:H17)')
                        ->getStyle('H18')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I18', '=SUM(I10:I17)')
                        ->getStyle('I18')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J18', '=SUM(J10:J17)')
                        ->getStyle('J18')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K18', '=SUM(K10:K17)')
                        ->getStyle('K18')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L18', '=SUM(B18:K18)')
                        ->getStyle('L18')->getNumberFormat()->setFormatCode('#,##0.00');

                // DISTRIBUTION DEMAND
                $event->sheet->setCellValue('B20', $data != null ? $data->DistributionDemandPoblacion : '-')
                ->getStyle('B20')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C20', $data != null ? $data->DistributionDemandRural : '-')
                        ->getStyle('C20')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D20', $data != null ? $data->DistributionDemandComLowVoltage : '-')
                        ->getStyle('D20')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E20', $data != null ? $data->DistributionDemandComHighVoltage : '-')
                        ->getStyle('E20')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F20', $data != null ? $data->DistributionDemandWaterSystems : '-')
                        ->getStyle('F20')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G20', $data != null ? $data->DistributionDemandIndLowVoltage : '-')
                        ->getStyle('G20')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H20', $data != null ? $data->DistributionDemandIndHighVoltage : '-')
                        ->getStyle('H20')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I20', $data != null ? $data->DistributionDemandStreetLights : '-')
                        ->getStyle('I20')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J20', $data != null ? $data->DistributionDemandPbLowVoltage : '-')
                        ->getStyle('J20')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K20', $data != null ? $data->DistributionDemandPbHighVoltage : '-')
                        ->getStyle('K20')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L20', '=SUM(B20:K20)')
                        ->getStyle('L20')->getNumberFormat()->setFormatCode('#,##0.00');

                // DISTRIBUTION SYSTEM
                $event->sheet->setCellValue('B21', $data != null ? $data->DistributionSystemPoblacion : '-')
                ->getStyle('B21')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C21', $data != null ? $data->DistributionSystemRural : '-')
                        ->getStyle('C21')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D21', $data != null ? $data->DistributionSystemComLowVoltage : '-')
                        ->getStyle('D21')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E21', $data != null ? $data->DistributionSystemComHighVoltage : '-')
                        ->getStyle('E21')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F21', $data != null ? $data->DistributionSystemWaterSystems : '-')
                        ->getStyle('F21')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G21', $data != null ? $data->DistributionSystemIndLowVoltage : '-')
                        ->getStyle('G21')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H21', $data != null ? $data->DistributionSystemIndHighVoltage : '-')
                        ->getStyle('H21')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I21', $data != null ? $data->DistributionSystemStreetLights : '-')
                        ->getStyle('I21')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J21', $data != null ? $data->DistributionSystemPbLowVoltage : '-')
                        ->getStyle('J21')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K21', $data != null ? $data->DistributionSystemPbHighVoltage : '-')
                        ->getStyle('K21')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L21', '=SUM(B21:K21)')
                        ->getStyle('L21')->getNumberFormat()->setFormatCode('#,##0.00');

                // SUPPLY RETIAL
                $event->sheet->setCellValue('B22', $data != null ? $data->SupplyRetailPoblacion : '-')
                ->getStyle('B22')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C22', $data != null ? $data->SupplyRetailRural : '-')
                        ->getStyle('C22')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D22', $data != null ? $data->SupplyRetailComLowVoltage : '-')
                        ->getStyle('D22')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E22', $data != null ? $data->SupplyRetailComHighVoltage : '-')
                        ->getStyle('E22')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F22', $data != null ? $data->SupplyRetailWaterSystems : '-')
                        ->getStyle('F22')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G22', $data != null ? $data->SupplyRetailIndLowVoltage : '-')
                        ->getStyle('G22')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H22', $data != null ? $data->SupplyRetailIndHighVoltage : '-')
                        ->getStyle('H22')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I22', $data != null ? $data->SupplyRetailStreetLights : '-')
                        ->getStyle('I22')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J22', $data != null ? $data->SupplyRetailPbLowVoltage : '-')
                        ->getStyle('J22')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K22', $data != null ? $data->SupplyRetailPbHighVoltage : '-')
                        ->getStyle('K22')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L22', '=SUM(B22:K22)')
                        ->getStyle('L22')->getNumberFormat()->setFormatCode('#,##0.00');

                // SUPPLY SYSTEM
                $event->sheet->setCellValue('B23', $data != null ? $data->SupplySystemPoblacion : '-')
                ->getStyle('B23')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C23', $data != null ? $data->SupplySystemRural : '-')
                        ->getStyle('C23')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D23', $data != null ? $data->SupplySystemComLowVoltage : '-')
                        ->getStyle('D23')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E23', $data != null ? $data->SupplySystemComHighVoltage : '-')
                        ->getStyle('E23')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F23', $data != null ? $data->SupplySystemWaterSystems : '-')
                        ->getStyle('F23')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G23', $data != null ? $data->SupplySystemIndLowVoltage : '-')
                        ->getStyle('G23')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H23', $data != null ? $data->SupplySystemIndHighVoltage : '-')
                        ->getStyle('H23')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I23', $data != null ? $data->SupplySystemStreetLights : '-')
                        ->getStyle('I23')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J23', $data != null ? $data->SupplySystemPbLowVoltage : '-')
                        ->getStyle('J23')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K23', $data != null ? $data->SupplySystemPbHighVoltage : '-')
                        ->getStyle('K23')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L23', '=SUM(B23:K23)')
                        ->getStyle('L23')->getNumberFormat()->setFormatCode('#,##0.00');

                // METERING RETAIL
                $event->sheet->setCellValue('B24', $data != null ? $data->MeteringRetailPoblacion : '-')
                ->getStyle('B24')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C24', $data != null ? $data->MeteringRetailRural : '-')
                        ->getStyle('C24')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D24', $data != null ? $data->MeteringRetailComLowVoltage : '-')
                        ->getStyle('D24')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E24', $data != null ? $data->MeteringRetailComHighVoltage : '-')
                        ->getStyle('E24')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F24', $data != null ? $data->MeteringRetailWaterSystems : '-')
                        ->getStyle('F24')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G24', $data != null ? $data->MeteringRetailIndLowVoltage : '-')
                        ->getStyle('G24')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H24', $data != null ? $data->MeteringRetailIndHighVoltage : '-')
                        ->getStyle('H24')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I24', $data != null ? $data->MeteringRetailStreetLights : '-')
                        ->getStyle('I24')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J24', $data != null ? $data->MeteringRetailPbLowVoltage : '-')
                        ->getStyle('J24')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K24', $data != null ? $data->MeteringRetailPbHighVoltage : '-')
                        ->getStyle('K24')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L24', '=SUM(B24:K24)')
                        ->getStyle('L24')->getNumberFormat()->setFormatCode('#,##0.00');

                // METERING SYSTEM
                $event->sheet->setCellValue('B25', $data != null ? $data->MeteringSystemPoblacion : '-')
                ->getStyle('B25')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C25', $data != null ? $data->MeteringSystemRural : '-')
                        ->getStyle('C25')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D25', $data != null ? $data->MeteringSystemComLowVoltage : '-')
                        ->getStyle('D25')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E25', $data != null ? $data->MeteringSystemComHighVoltage : '-')
                        ->getStyle('E25')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F25', $data != null ? $data->MeteringSystemWaterSystems : '-')
                        ->getStyle('F25')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G25', $data != null ? $data->MeteringSystemIndLowVoltage : '-')
                        ->getStyle('G25')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H25', $data != null ? $data->MeteringSystemIndHighVoltage : '-')
                        ->getStyle('H25')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I25', $data != null ? $data->MeteringSystemStreetLights : '-')
                        ->getStyle('I25')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J25', $data != null ? $data->MeteringSystemPbLowVoltage : '-')
                        ->getStyle('J25')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K25', $data != null ? $data->MeteringSystemPbHighVoltage : '-')
                        ->getStyle('K25')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L25', '=SUM(B25:K25)')
                        ->getStyle('L25')->getNumberFormat()->setFormatCode('#,##0.00');

                // RFSC
                $event->sheet->setCellValue('B26', $data != null ? $data->RfscPoblacion : '-')
                ->getStyle('B26')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C26', $data != null ? $data->RfscRural : '-')
                        ->getStyle('C26')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D26', $data != null ? $data->RfscComLowVoltage : '-')
                        ->getStyle('D26')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E26', $data != null ? $data->RfscComHighVoltage : '-')
                        ->getStyle('E26')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F26', $data != null ? $data->RfscWaterSystems : '-')
                        ->getStyle('F26')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G26', $data != null ? $data->RfscIndLowVoltage : '-')
                        ->getStyle('G26')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H26', $data != null ? $data->RfscIndHighVoltage : '-')
                        ->getStyle('H26')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I26', $data != null ? $data->RfscStreetLights : '-')
                        ->getStyle('I26')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J26', $data != null ? $data->RfscPbLowVoltage : '-')
                        ->getStyle('J26')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K26', $data != null ? $data->RfscPbHighVoltage : '-')
                        ->getStyle('K26')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L26', '=SUM(B26:K26)')
                        ->getStyle('L26')->getNumberFormat()->setFormatCode('#,##0.00');

                // DISTRIBUTION SUB TOTAL
                $event->sheet->setCellValue('B27', '=SUM(B20:B26)')
                ->getStyle('B27')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C27', '=SUM(C20:C26)')
                        ->getStyle('C27')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D27', '=SUM(D20:D26)')
                        ->getStyle('D27')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E27', '=SUM(E20:E26)')
                        ->getStyle('E27')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F27', '=SUM(F20:F26)')
                        ->getStyle('F27')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G27', '=SUM(G20:G26)')
                        ->getStyle('G27')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H27', '=SUM(H20:H26)')
                        ->getStyle('H27')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I27', '=SUM(I20:I26)')
                        ->getStyle('I27')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J27', '=SUM(J20:J26)')
                        ->getStyle('J27')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K27', '=SUM(K20:K26)')
                        ->getStyle('K27')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L27', '=SUM(B27:K27)')
                        ->getStyle('L27')->getNumberFormat()->setFormatCode('#,##0.00');

                // LIFELINE
                $event->sheet->setCellValue('B29', $data != null ? $data->LifelinePoblacion : '-')
                ->getStyle('B29')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C29', $data != null ? $data->LifelineRural : '-')
                        ->getStyle('C29')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D29', $data != null ? $data->LifelineComLowVoltage : '-')
                        ->getStyle('D29')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E29', $data != null ? $data->LifelineComHighVoltage : '-')
                        ->getStyle('E29')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F29', $data != null ? $data->LifelineWaterSystems : '-')
                        ->getStyle('F29')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G29', $data != null ? $data->LifelineIndLowVoltage : '-')
                        ->getStyle('G29')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H29', $data != null ? $data->LifelineIndHighVoltage : '-')
                        ->getStyle('H29')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I29', $data != null ? $data->LifelineStreetLights : '-')
                        ->getStyle('I29')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J29', $data != null ? $data->LifelinePbLowVoltage : '-')
                        ->getStyle('J29')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K29', $data != null ? $data->LifelinePbHighVoltage : '-')
                        ->getStyle('K29')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L29', '=SUM(B29:K29)')
                        ->getStyle('L29')->getNumberFormat()->setFormatCode('#,##0.00');

                // ICCS
                $event->sheet->setCellValue('B30', $data != null ? $data->IccsPoblacion : '-')
                ->getStyle('B30')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C30', $data != null ? $data->IccsRural : '-')
                        ->getStyle('C30')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D30', $data != null ? $data->IccsComLowVoltage : '-')
                        ->getStyle('D30')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E30', $data != null ? $data->IccsComHighVoltage : '-')
                        ->getStyle('E30')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F30', $data != null ? $data->IccsWaterSystems : '-')
                        ->getStyle('F30')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G30', $data != null ? $data->IccsIndLowVoltage : '-')
                        ->getStyle('G30')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H30', $data != null ? $data->IccsIndHighVoltage : '-')
                        ->getStyle('H30')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I30', $data != null ? $data->IccsStreetLights : '-')
                        ->getStyle('I30')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J30', $data != null ? $data->IccsPbLowVoltage : '-')
                        ->getStyle('J30')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K30', $data != null ? $data->IccsPbHighVoltage : '-')
                        ->getStyle('K30')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L30', '=SUM(B30:K30)')
                        ->getStyle('L30')->getNumberFormat()->setFormatCode('#,##0.00');

                if ($this->sales != null && $this->sales->Status=='CLOSED') {
                        // SENIOR CITIZEN DISCOUNT
                        $event->sheet->setCellValue('B31', $data != null ? $data->SCDiscountPoblacion : '-')
                        ->getStyle('B31')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('C31', $data != null ? $data->SCDiscountRural : '-')
                                ->getStyle('C31')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('D31', $data != null ? $data->SCDiscountComLowVoltage : '-')
                                ->getStyle('D31')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('E31', $data != null ? $data->SCDiscountComHighVoltage : '-')
                                ->getStyle('E31')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('F31', $data != null ? $data->SCDiscountWaterSystems : '-')
                                ->getStyle('F31')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('G31', $data != null ? $data->SCDiscountIndLowVoltage : '-')
                                ->getStyle('G31')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('H31', $data != null ? $data->SCDiscountIndHighVoltage : '-')
                                ->getStyle('H31')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('I31', $data != null ? $data->SCDiscountStreetLights : '-')
                                ->getStyle('I31')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('J31', $data != null ? $data->SCDiscountPbLowVoltage : '-')
                                ->getStyle('J31')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('K31', $data != null ? $data->SCDiscountPbHighVoltage : '-')
                                ->getStyle('K31')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('L31', '=SUM(B31:K31)')
                                ->getStyle('L31')->getNumberFormat()->setFormatCode('#,##0.00');

                        // // SENIOR CITIZEN SUBSIDY
                        // $event->sheet->setCellValue('B32', $data != null ?  ('=' . $data->SCSubsidyPoblacion . '+B31') : '-')
                        // ->getStyle('B32')->getNumberFormat()->setFormatCode('#,##0.00');
                        // $event->sheet->setCellValue('C32', $data != null ?  ('=' . $data->SCSubsidyRural . '+C31') : '-')
                        //         ->getStyle('C32')->getNumberFormat()->setFormatCode('#,##0.00');
                        // $event->sheet->setCellValue('D32', $data != null ?  ('=' . $data->SCSubsidyComLowVoltage . '+D31') : '-')
                        //         ->getStyle('D32')->getNumberFormat()->setFormatCode('#,##0.00');
                        // $event->sheet->setCellValue('E32', $data != null ?  ('=' . $data->SCSubsidyComHighVoltage . '+E31') : '-')
                        //         ->getStyle('E32')->getNumberFormat()->setFormatCode('#,##0.00');
                        // $event->sheet->setCellValue('F32', $data != null ?  ('=' . $data->SCSubsidyWaterSystems . '+F31') : '-')
                        //         ->getStyle('F32')->getNumberFormat()->setFormatCode('#,##0.00');
                        // $event->sheet->setCellValue('G32', $data != null ?  ('=' . $data->SCSubsidyIndLowVoltage . '+G31') : '-')
                        //         ->getStyle('G32')->getNumberFormat()->setFormatCode('#,##0.00');
                        // $event->sheet->setCellValue('H32', $data != null ?  ('=' . $data->SCSubsidyIndHighVoltage . '+H31') : '-')
                        //         ->getStyle('H32')->getNumberFormat()->setFormatCode('#,##0.00');
                        // $event->sheet->setCellValue('I32', $data != null ?  ('=' . $data->SCSubsidyStreetLights . '+I31') : '-')
                        //         ->getStyle('I32')->getNumberFormat()->setFormatCode('#,##0.00');
                        // $event->sheet->setCellValue('J32', $data != null ?  ('=' . $data->SCSubsidyPbLowVoltage . '+J31') : '-')
                        //         ->getStyle('J32')->getNumberFormat()->setFormatCode('#,##0.00');
                        // $event->sheet->setCellValue('K32', $data != null ?  ('=' . $data->SCSubsidyPbHighVoltage . '+K31') : '-')
                        //         ->getStyle('K32')->getNumberFormat()->setFormatCode('#,##0.00');
                        // $event->sheet->setCellValue('L32', '=SUM(B32:K32)')
                        //         ->getStyle('L32')->getNumberFormat()->setFormatCode('#,##0.00');
                        // SENIOR CITIZEN SUBSIDY
                        $event->sheet->setCellValue('B32', $data != null ?  $data->SCSubsidyPoblacion : '-')
                        ->getStyle('B32')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('C32', $data != null ?  $data->SCSubsidyRural : '-')
                                ->getStyle('C32')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('D32', $data != null ?  $data->SCSubsidyComLowVoltage : '-')
                                ->getStyle('D32')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('E32', $data != null ?  $data->SCSubsidyComHighVoltage : '-')
                                ->getStyle('E32')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('F32', $data != null ?  $data->SCSubsidyWaterSystems : '-')
                                ->getStyle('F32')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('G32', $data != null ?  $data->SCSubsidyIndLowVoltage : '-')
                                ->getStyle('G32')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('H32', $data != null ?  $data->SCSubsidyIndHighVoltage : '-')
                                ->getStyle('H32')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('I32', $data != null ?  $data->SCSubsidyStreetLights : '-')
                                ->getStyle('I32')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('J32', $data != null ?  $data->SCSubsidyPbLowVoltage : '-')
                                ->getStyle('J32')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('K32', $data != null ?  $data->SCSubsidyPbHighVoltage : '-')
                                ->getStyle('K32')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('L32', '=SUM(B32:K32)')
                                ->getStyle('L32')->getNumberFormat()->setFormatCode('#,##0.00');

                } else {
                        // SENIOR CITIZEN DISCOUNT
                        $event->sheet->setCellValue('B31', $data != null ? $data->SCDiscountPoblacion : '-')
                        ->getStyle('B31')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('C31', $data != null ? $data->SCDiscountRural : '-')
                                ->getStyle('C31')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('D31', $data != null ? $data->SCDiscountComLowVoltage : '-')
                                ->getStyle('D31')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('E31', $data != null ? $data->SCDiscountComHighVoltage : '-')
                                ->getStyle('E31')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('F31', $data != null ? $data->SCDiscountWaterSystems : '-')
                                ->getStyle('F31')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('G31', $data != null ? $data->SCDiscountIndLowVoltage : '-')
                                ->getStyle('G31')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('H31', $data != null ? $data->SCDiscountIndHighVoltage : '-')
                                ->getStyle('H31')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('I31', $data != null ? $data->SCDiscountStreetLights : '-')
                                ->getStyle('I31')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('J31', $data != null ? $data->SCDiscountPbLowVoltage : '-')
                                ->getStyle('J31')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('K31', $data != null ? $data->SCDiscountPbHighVoltage : '-')
                                ->getStyle('K31')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('L31', '=SUM(B31:K31)')
                                ->getStyle('L31')->getNumberFormat()->setFormatCode('#,##0.00');

                        // SENIOR CITIZEN SUBSIDY
                        $event->sheet->setCellValue('B32', $data != null ? $data->SCSubsidyPoblacion : '-')
                        ->getStyle('B32')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('C32', $data != null ? $data->SCSubsidyRural : '-')
                                ->getStyle('C32')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('D32', $data != null ? $data->SCSubsidyComLowVoltage : '-')
                                ->getStyle('D32')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('E32', $data != null ? $data->SCSubsidyComHighVoltage : '-')
                                ->getStyle('E32')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('F32', $data != null ? $data->SCSubsidyWaterSystems : '-')
                                ->getStyle('F32')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('G32', $data != null ? $data->SCSubsidyIndLowVoltage : '-')
                                ->getStyle('G32')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('H32', $data != null ? $data->SCSubsidyIndHighVoltage : '-')
                                ->getStyle('H32')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('I32', $data != null ? $data->SCSubsidyStreetLights : '-')
                                ->getStyle('I32')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('J32', $data != null ? $data->SCSubsidyPbLowVoltage : '-')
                                ->getStyle('J32')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('K32', $data != null ? $data->SCSubsidyPbHighVoltage : '-')
                                ->getStyle('K32')->getNumberFormat()->setFormatCode('#,##0.00');
                        $event->sheet->setCellValue('L32', '=SUM(B32:K32)')
                                ->getStyle('L32')->getNumberFormat()->setFormatCode('#,##0.00');

                }

                // SENIOR CITIZEN ADJUSTMENTS
                $event->sheet->setCellValue('B33', $data != null ? $data->SCAdjPoblacion : '-')
                ->getStyle('B33')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C33', $data != null ? $data->SCAdjRural : '-')
                        ->getStyle('C33')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D33', $data != null ? $data->SCAdjComLowVoltage : '-')
                        ->getStyle('D33')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E33', $data != null ? $data->SCAdjComHighVoltage : '-')
                        ->getStyle('E33')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F33', $data != null ? $data->SCAdjWaterSystems : '-')
                        ->getStyle('F33')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G33', $data != null ? $data->SCAdjIndLowVoltage : '-')
                        ->getStyle('G33')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H33', $data != null ? $data->SCAdjIndHighVoltage : '-')
                        ->getStyle('H33')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I33', $data != null ? $data->SCAdjStreetLights : '-')
                        ->getStyle('I33')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J33', $data != null ? $data->SCAdjPbLowVoltage : '-')
                        ->getStyle('J33')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K33', $data != null ? $data->SCAdjPbHighVoltage : '-')
                        ->getStyle('K33')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L33', '=SUM(B33:K33)')
                        ->getStyle('L33')->getNumberFormat()->setFormatCode('#,##0.00');

                // OLRA
                $event->sheet->setCellValue('B34', $data != null ? $data->OlraPoblacion : '-')
                ->getStyle('B34')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C34', $data != null ? $data->OlraRural : '-')
                        ->getStyle('C34')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D34', $data != null ? $data->OlraComLowVoltage : '-')
                        ->getStyle('D34')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E34', $data != null ? $data->OlraComHighVoltage : '-')
                        ->getStyle('E34')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F34', $data != null ? $data->OlraWaterSystems : '-')
                        ->getStyle('F34')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G34', $data != null ? $data->OlraIndLowVoltage : '-')
                        ->getStyle('G34')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H34', $data != null ? $data->OlraIndHighVoltage : '-')
                        ->getStyle('H34')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I34', $data != null ? $data->OlraStreetLights : '-')
                        ->getStyle('I34')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J34', $data != null ? $data->OlraPbLowVoltage : '-')
                        ->getStyle('J34')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K34', $data != null ? $data->OlraPbHighVoltage : '-')
                        ->getStyle('K34')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L34', '=SUM(B34:K34)')
                        ->getStyle('L34')->getNumberFormat()->setFormatCode('#,##0.00');

                // OTHERS SUB TOTAL
                $event->sheet->setCellValue('B35', '=SUM(B29:B34)')
                ->getStyle('B35')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C35', '=SUM(C29:C34)')
                        ->getStyle('C35')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D35', '=SUM(D29:D34)')
                        ->getStyle('D35')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E35', '=SUM(E29:E34)')
                        ->getStyle('E35')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F35', '=SUM(F29:F34)')
                        ->getStyle('F35')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G35', '=SUM(G29:G34)')
                        ->getStyle('G35')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H35', '=SUM(H29:H34)')
                        ->getStyle('H35')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I35', '=SUM(I29:I34)')
                        ->getStyle('I35')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J35', '=SUM(J29:J34)')
                        ->getStyle('J35')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K35', '=SUM(K29:K34)')
                        ->getStyle('K35')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L35', '=SUM(B35:K35)')
                        ->getStyle('L35')->getNumberFormat()->setFormatCode('#,##0.00');

                // MSSIONARY
                $event->sheet->setCellValue('B37', $data != null ? $data->MissionaryPoblacion : '-')
                ->getStyle('B37')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C37', $data != null ? $data->MissionaryRural : '-')
                        ->getStyle('C37')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D37', $data != null ? $data->MissionaryComLowVoltage : '-')
                        ->getStyle('D37')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E37', $data != null ? $data->MissionaryComHighVoltage : '-')
                        ->getStyle('E37')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F37', $data != null ? $data->MissionaryWaterSystems : '-')
                        ->getStyle('F37')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G37', $data != null ? $data->MissionaryIndLowVoltage : '-')
                        ->getStyle('G37')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H37', $data != null ? $data->MissionaryIndHighVoltage : '-')
                        ->getStyle('H37')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I37', $data != null ? $data->MissionaryStreetLights : '-')
                        ->getStyle('I37')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J37', $data != null ? $data->MissionaryPbLowVoltage : '-')
                        ->getStyle('J37')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K37', $data != null ? $data->MissionaryPbHighVoltage : '-')
                        ->getStyle('K37')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L37', '=SUM(B37:K37)')
                        ->getStyle('L37')->getNumberFormat()->setFormatCode('#,##0.00');

                // ENVIRONMENTAL
                $event->sheet->setCellValue('B38', $data != null ? $data->EnvironmentalPoblacion : '-')
                ->getStyle('B38')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C38', $data != null ? $data->EnvironmentalRural : '-')
                        ->getStyle('C38')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D38', $data != null ? $data->EnvironmentalComLowVoltage : '-')
                        ->getStyle('D38')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E38', $data != null ? $data->EnvironmentalComHighVoltage : '-')
                        ->getStyle('E38')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F38', $data != null ? $data->EnvironmentalWaterSystems : '-')
                        ->getStyle('F38')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G38', $data != null ? $data->EnvironmentalIndLowVoltage : '-')
                        ->getStyle('G38')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H38', $data != null ? $data->EnvironmentalIndHighVoltage : '-')
                        ->getStyle('H38')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I38', $data != null ? $data->EnvironmentalStreetLights : '-')
                        ->getStyle('I38')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J38', $data != null ? $data->EnvironmentalPbLowVoltage : '-')
                        ->getStyle('J38')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K38', $data != null ? $data->EnvironmentalPbHighVoltage : '-')
                        ->getStyle('K38')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L38', '=SUM(B38:K38)')
                        ->getStyle('L38')->getNumberFormat()->setFormatCode('#,##0.00');

                // STRANDED CONTRACT
                $event->sheet->setCellValue('B39', $data != null ? $data->StrandedContractPoblacion : '-')
                ->getStyle('B39')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C39', $data != null ? $data->StrandedContractRural : '-')
                        ->getStyle('C39')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D39', $data != null ? $data->StrandedContractComLowVoltage : '-')
                        ->getStyle('D39')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E39', $data != null ? $data->StrandedContractComHighVoltage : '-')
                        ->getStyle('E39')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F39', $data != null ? $data->StrandedContractWaterSystems : '-')
                        ->getStyle('F39')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G39', $data != null ? $data->StrandedContractIndLowVoltage : '-')
                        ->getStyle('G39')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H39', $data != null ? $data->StrandedContractIndHighVoltage : '-')
                        ->getStyle('H39')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I39', $data != null ? $data->StrandedContractStreetLights : '-')
                        ->getStyle('I39')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J39', $data != null ? $data->StrandedContractPbLowVoltage : '-')
                        ->getStyle('J39')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K39', $data != null ? $data->StrandedContractPbHighVoltage : '-')
                        ->getStyle('K39')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L39', '=SUM(B39:K39)')
                        ->getStyle('L39')->getNumberFormat()->setFormatCode('#,##0.00');

                // STRANDED DEBT
                $event->sheet->setCellValue('B40', $data != null ? $data->StrandedDebtPoblacion : '-')
                ->getStyle('B40')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C40', $data != null ? $data->StrandedDebtRural : '-')
                        ->getStyle('C40')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D40', $data != null ? $data->StrandedDebtComLowVoltage : '-')
                        ->getStyle('D40')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E40', $data != null ? $data->StrandedDebtComHighVoltage : '-')
                        ->getStyle('E40')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F40', $data != null ? $data->StrandedDebtWaterSystems : '-')
                        ->getStyle('F40')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G40', $data != null ? $data->StrandedDebtIndLowVoltage : '-')
                        ->getStyle('G40')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H40', $data != null ? $data->StrandedDebtIndHighVoltage : '-')
                        ->getStyle('H40')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I40', $data != null ? $data->StrandedDebtStreetLights : '-')
                        ->getStyle('I40')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J40', $data != null ? $data->StrandedDebtPbLowVoltage : '-')
                        ->getStyle('J40')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K40', $data != null ? $data->StrandedDebtPbHighVoltage : '-')
                        ->getStyle('K40')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L40', '=SUM(B40:K40)')
                        ->getStyle('L40')->getNumberFormat()->setFormatCode('#,##0.00');

                // FIT ALL
                $event->sheet->setCellValue('B41', $data != null ? $data->FitAllPoblacion : '-')
                ->getStyle('B41')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C41', $data != null ? $data->FitAllRural : '-')
                        ->getStyle('C41')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D41', $data != null ? $data->FitAllComLowVoltage : '-')
                        ->getStyle('D41')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E41', $data != null ? $data->FitAllComHighVoltage : '-')
                        ->getStyle('E41')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F41', $data != null ? $data->FitAllWaterSystems : '-')
                        ->getStyle('F41')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G41', $data != null ? $data->FitAllIndLowVoltage : '-')
                        ->getStyle('G41')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H41', $data != null ? $data->FitAllIndHighVoltage : '-')
                        ->getStyle('H41')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I41', $data != null ? $data->FitAllStreetLights : '-')
                        ->getStyle('I41')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J41', $data != null ? $data->FitAllPbLowVoltage : '-')
                        ->getStyle('J41')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K41', $data != null ? $data->FitAllPbHighVoltage : '-')
                        ->getStyle('K41')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L41', '=SUM(B41:K41)')
                        ->getStyle('L41')->getNumberFormat()->setFormatCode('#,##0.00');

                // REDCI
                $event->sheet->setCellValue('B42', $data != null ? $data->RedciPoblacion : '-')
                ->getStyle('B42')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C42', $data != null ? $data->RedciRural : '-')
                        ->getStyle('C42')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D42', $data != null ? $data->RedciComLowVoltage : '-')
                        ->getStyle('D42')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E42', $data != null ? $data->RedciComHighVoltage : '-')
                        ->getStyle('E42')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F42', $data != null ? $data->RedciWaterSystems : '-')
                        ->getStyle('F42')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G42', $data != null ? $data->RedciIndLowVoltage : '-')
                        ->getStyle('G42')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H42', $data != null ? $data->RedciIndHighVoltage : '-')
                        ->getStyle('H42')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I42', $data != null ? $data->RedciStreetLights : '-')
                        ->getStyle('I42')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J42', $data != null ? $data->RedciPbLowVoltage : '-')
                        ->getStyle('J42')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K42', $data != null ? $data->RedciPbHighVoltage : '-')
                        ->getStyle('K42')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L42', '=SUM(B42:K42)')
                        ->getStyle('L42')->getNumberFormat()->setFormatCode('#,##0.00');

                // UNIVERSAL SUB TOTAL
                $event->sheet->setCellValue('B43', '=SUM(B37:B42)')
                ->getStyle('B43')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C43', '=SUM(C37:C42)')
                        ->getStyle('C43')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D43', '=SUM(D37:D42)')
                        ->getStyle('D43')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E43', '=SUM(E37:E42)')
                        ->getStyle('E43')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F43', '=SUM(F37:F42)')
                        ->getStyle('F43')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G43', '=SUM(G37:G42)')
                        ->getStyle('G43')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H43', '=SUM(H37:H42)')
                        ->getStyle('H43')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I43', '=SUM(I37:I42)')
                        ->getStyle('I43')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J43', '=SUM(J37:J42)')
                        ->getStyle('J43')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K43', '=SUM(K37:K42)')
                        ->getStyle('K43')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L43', '=SUM(B43:K43)')
                        ->getStyle('L43')->getNumberFormat()->setFormatCode('#,##0.00');

                // GENVAT
                $event->sheet->setCellValue('B45', $data != null ? $data->GenVatPoblacion : '-')
                ->getStyle('B45')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C45', $data != null ? $data->GenVatRural : '-')
                        ->getStyle('C45')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D45', $data != null ? $data->GenVatComLowVoltage : '-')
                        ->getStyle('D45')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E45', $data != null ? $data->GenVatComHighVoltage : '-')
                        ->getStyle('E45')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F45', $data != null ? $data->GenVatWaterSystems : '-')
                        ->getStyle('F45')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G45', $data != null ? $data->GenVatIndLowVoltage : '-')
                        ->getStyle('G45')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H45', $data != null ? $data->GenVatIndHighVoltage : '-')
                        ->getStyle('H45')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I45', $data != null ? $data->GenVatStreetLights : '-')
                        ->getStyle('I45')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J45', $data != null ? $data->GenVatPbLowVoltage : '-')
                        ->getStyle('J45')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K45', $data != null ? $data->GenVatPbHighVoltage : '-')
                        ->getStyle('K45')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L45', '=SUM(B45:K45)')
                        ->getStyle('L45')->getNumberFormat()->setFormatCode('#,##0.00');

                // TRANSVAT
                $event->sheet->setCellValue('B46', $data != null ? $data->TransVatPoblacion : '-')
                ->getStyle('B46')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C46', $data != null ? $data->TransVatRural : '-')
                        ->getStyle('C46')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D46', $data != null ? $data->TransVatComLowVoltage : '-')
                        ->getStyle('D46')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E46', $data != null ? $data->TransVatComHighVoltage : '-')
                        ->getStyle('E46')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F46', $data != null ? $data->TransVatWaterSystems : '-')
                        ->getStyle('F46')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G46', $data != null ? $data->TransVatIndLowVoltage : '-')
                        ->getStyle('G46')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H46', $data != null ? $data->TransVatIndHighVoltage : '-')
                        ->getStyle('H46')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I46', $data != null ? $data->TransVatStreetLights : '-')
                        ->getStyle('I46')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J46', $data != null ? $data->TransVatPbLowVoltage : '-')
                        ->getStyle('J46')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K46', $data != null ? $data->TransVatPbHighVoltage : '-')
                        ->getStyle('K46')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L46', '=SUM(B46:K46)')
                        ->getStyle('L46')->getNumberFormat()->setFormatCode('#,##0.00');

                // SYSLOSS VAT
                $event->sheet->setCellValue('B47', $data != null ? $data->SysLossVatPoblacion : '-')
                ->getStyle('B47')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C47', $data != null ? $data->SysLossVatRural : '-')
                        ->getStyle('C47')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D47', $data != null ? $data->SysLossVatComLowVoltage : '-')
                        ->getStyle('D47')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E47', $data != null ? $data->SysLossVatComHighVoltage : '-')
                        ->getStyle('E47')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F47', $data != null ? $data->SysLossVatWaterSystems : '-')
                        ->getStyle('F47')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G47', $data != null ? $data->SysLossVatIndLowVoltage : '-')
                        ->getStyle('G47')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H47', $data != null ? $data->SysLossVatIndHighVoltage : '-')
                        ->getStyle('H47')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I47', $data != null ? $data->SysLossVatStreetLights : '-')
                        ->getStyle('I47')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J47', $data != null ? $data->SysLossVatPbLowVoltage : '-')
                        ->getStyle('J47')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K47', $data != null ? $data->SysLossVatPbHighVoltage : '-')
                        ->getStyle('K47')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L47', '=SUM(B47:K47)')
                        ->getStyle('L47')->getNumberFormat()->setFormatCode('#,##0.00');

                // DIST VAT
                $event->sheet->setCellValue('B48', $data != null ? $data->DistributionVatPoblacion : '-')
                ->getStyle('B48')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C48', $data != null ? $data->DistributionVatRural : '-')
                        ->getStyle('C48')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D48', $data != null ? $data->DistributionVatComLowVoltage : '-')
                        ->getStyle('D48')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E48', $data != null ? $data->DistributionVatComHighVoltage : '-')
                        ->getStyle('E48')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F48', $data != null ? $data->DistributionVatWaterSystems : '-')
                        ->getStyle('F48')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G48', $data != null ? $data->DistributionVatIndLowVoltage : '-')
                        ->getStyle('G48')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H48', $data != null ? $data->DistributionVatIndHighVoltage : '-')
                        ->getStyle('H48')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I48', $data != null ? $data->DistributionVatStreetLights : '-')
                        ->getStyle('I48')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J48', $data != null ? $data->DistributionVatPbLowVoltage : '-')
                        ->getStyle('J48')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K48', $data != null ? $data->DistributionVatPbHighVoltage : '-')
                        ->getStyle('K48')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L48', '=SUM(B48:K48)')
                        ->getStyle('L48')->getNumberFormat()->setFormatCode('#,##0.00');

                // GOVT SUB TOTAL
                $event->sheet->setCellValue('B49', '=SUM(B45:B48)')
                ->getStyle('B49')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C49', '=SUM(C45:C48)')
                        ->getStyle('C49')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D49', '=SUM(D45:D48)')
                        ->getStyle('D49')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E49', '=SUM(E45:E48)')
                        ->getStyle('E49')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F49', '=SUM(F45:F48)')
                        ->getStyle('F49')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G49', '=SUM(G45:G48)')
                        ->getStyle('G49')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H49', '=SUM(H45:H48)')
                        ->getStyle('H49')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I49', '=SUM(I45:I48)')
                        ->getStyle('I49')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J49', '=SUM(J45:J48)')
                        ->getStyle('J49')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K49', '=SUM(K45:K48)')
                        ->getStyle('K49')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L49', '=SUM(B49:K49)')
                        ->getStyle('L49')->getNumberFormat()->setFormatCode('#,##0.00');

                // FRANCHISE TAX
                $event->sheet->setCellValue('B50', $data != null ? $data->FranchisePoblacion : '-')
                ->getStyle('B50')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C50', $data != null ? $data->FranchiseRural : '-')
                        ->getStyle('C50')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D50', $data != null ? $data->FranchiseComLowVoltage : '-')
                        ->getStyle('D50')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E50', $data != null ? $data->FranchiseComHighVoltage : '-')
                        ->getStyle('E50')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F50', $data != null ? $data->FranchiseWaterSystems : '-')
                        ->getStyle('F50')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G50', $data != null ? $data->FranchiseIndLowVoltage : '-')
                        ->getStyle('G50')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H50', $data != null ? $data->FranchiseIndHighVoltage : '-')
                        ->getStyle('H50')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I50', $data != null ? $data->FranchiseStreetLights : '-')
                        ->getStyle('I50')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J50', $data != null ? $data->FranchisePbLowVoltage : '-')
                        ->getStyle('J50')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K50', $data != null ? $data->FranchisePbHighVoltage : '-')
                        ->getStyle('K50')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L50', '=SUM(B50:K50)')
                        ->getStyle('L50')->getNumberFormat()->setFormatCode('#,##0.00');

                // BUSINESS TAX
                $event->sheet->setCellValue('B51', $data != null ? $data->BusinessPoblacion : '-')
                ->getStyle('B51')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C51', $data != null ? $data->BusinessRural : '-')
                        ->getStyle('C51')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D51', $data != null ? $data->BusinessComLowVoltage : '-')
                        ->getStyle('D51')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E51', $data != null ? $data->BusinessComHighVoltage : '-')
                        ->getStyle('E51')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F51', $data != null ? $data->BusinessWaterSystems : '-')
                        ->getStyle('F51')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G51', $data != null ? $data->BusinessIndLowVoltage : '-')
                        ->getStyle('G51')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H51', $data != null ? $data->BusinessIndHighVoltage : '-')
                        ->getStyle('H51')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I51', $data != null ? $data->BusinessStreetLights : '-')
                        ->getStyle('I51')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J51', $data != null ? $data->BusinessPbLowVoltage : '-')
                        ->getStyle('J51')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K51', $data != null ? $data->BusinessPbHighVoltage : '-')
                        ->getStyle('K51')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L51', '=SUM(B51:K51)')
                        ->getStyle('L51')->getNumberFormat()->setFormatCode('#,##0.00');

                // RPT TAX
                $event->sheet->setCellValue('B52', $data != null ? $data->RPTPoblacion : '-')
                ->getStyle('B52')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C52', $data != null ? $data->RPTRural : '-')
                        ->getStyle('C52')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D52', $data != null ? $data->RPTComLowVoltage : '-')
                        ->getStyle('D52')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E52', $data != null ? $data->RPTComHighVoltage : '-')
                        ->getStyle('E52')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F52', $data != null ? $data->RPTWaterSystems : '-')
                        ->getStyle('F52')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G52', $data != null ? $data->RPTIndLowVoltage : '-')
                        ->getStyle('G52')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H52', $data != null ? $data->RPTIndHighVoltage : '-')
                        ->getStyle('H52')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I52', $data != null ? $data->RPTStreetLights : '-')
                        ->getStyle('I52')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J52', $data != null ? $data->RPTPbLowVoltage : '-')
                        ->getStyle('J52')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K52', $data != null ? $data->RPTPbHighVoltage : '-')
                        ->getStyle('K52')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L52', '=SUM(B52:K52)')
                        ->getStyle('L52')->getNumberFormat()->setFormatCode('#,##0.00');

                // OTHER TAXES SUB TOTAL
                $event->sheet->setCellValue('B53', '=SUM(B50:B52)')
                ->getStyle('B53')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C53', '=SUM(C50:C52)')
                        ->getStyle('C53')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D53', '=SUM(D50:D52)')
                        ->getStyle('D53')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E53', '=SUM(E50:E52)')
                        ->getStyle('E53')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F53', '=SUM(F50:F52)')
                        ->getStyle('F53')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G53', '=SUM(G50:G52)')
                        ->getStyle('G53')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H53', '=SUM(H50:H52)')
                        ->getStyle('H53')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I53', '=SUM(I50:I52)')
                        ->getStyle('I53')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J53', '=SUM(J50:J52)')
                        ->getStyle('J53')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K53', '=SUM(K50:K52)')
                        ->getStyle('K53')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L53', '=SUM(B53:K53)')
                        ->getStyle('L53')->getNumberFormat()->setFormatCode('#,##0.00');

                // GRAND TOTAL
                $event->sheet->setCellValue('B55', '=SUM(B53,B49,B43,B35,B27,B18)')
                        ->getStyle('B55')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C55', '=SUM(C53,C49,C43,C35,C27,C18)')
                        ->getStyle('C55')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D55', '=SUM(D53,D49,D43,D35,D27,D18)')
                        ->getStyle('D55')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E55', '=SUM(E53,E49,E43,E35,E27,E18)')
                        ->getStyle('E55')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F55', '=SUM(F53,F49,F43,F35,F27,F18)')
                        ->getStyle('F55')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G55', '=SUM(G53,G49,G43,G35,G27,G18)')
                        ->getStyle('G55')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H55', '=SUM(H53,H49,H43,H35,H27,H18)')
                        ->getStyle('H55')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I55', '=SUM(I53,I49,I43,I35,I27,I18)')
                        ->getStyle('I55')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J55', '=SUM(J53,J49,J43,J35,J27,J18)')
                        ->getStyle('J55')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K55', '=SUM(K53,K49,K43,K35,K27,K18)')
                        ->getStyle('K55')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L55', '=SUM(B55:K55)')
                        ->getStyle('L55')->getNumberFormat()->setFormatCode('#,##0.00');
                
                // $event->sheet->setCellValue('B55', $data != null ? $data->GrandTotalPoblacion : '-')
                //         ->getStyle('B55')->getNumberFormat()->setFormatCode('#,##0.00');
                // $event->sheet->setCellValue('C55', $data != null ? $data->GrandTotalRural : '-')
                //         ->getStyle('C55')->getNumberFormat()->setFormatCode('#,##0.00');
                // $event->sheet->setCellValue('D55', $data != null ? $data->GrandTotalComLowVoltage : '-')
                //         ->getStyle('D55')->getNumberFormat()->setFormatCode('#,##0.00');
                // $event->sheet->setCellValue('E55', $data != null ? $data->GrandTotalComHighVoltage : '-')
                //         ->getStyle('E55')->getNumberFormat()->setFormatCode('#,##0.00');
                // $event->sheet->setCellValue('F55', $data != null ? $data->GrandTotalWaterSystems : '-')
                //         ->getStyle('F55')->getNumberFormat()->setFormatCode('#,##0.00');
                // $event->sheet->setCellValue('G55', $data != null ? $data->GrandTotalIndLowVoltage : '-')
                //         ->getStyle('G55')->getNumberFormat()->setFormatCode('#,##0.00');
                // $event->sheet->setCellValue('H55', $data != null ? $data->GrandTotalIndHighVoltage : '-')
                //         ->getStyle('H55')->getNumberFormat()->setFormatCode('#,##0.00');
                // $event->sheet->setCellValue('I55', $data != null ? $data->GrandTotalStreetLights : '-')
                //         ->getStyle('I55')->getNumberFormat()->setFormatCode('#,##0.00');
                // $event->sheet->setCellValue('J55', $data != null ? $data->GrandTotalPbLowVoltage : '-')
                //         ->getStyle('J55')->getNumberFormat()->setFormatCode('#,##0.00');
                // $event->sheet->setCellValue('K55', $data != null ? $data->GrandTotalPbHighVoltage : '-')
                //         ->getStyle('K55')->getNumberFormat()->setFormatCode('#,##0.00');
                // $event->sheet->setCellValue('L55', '=SUM(B55:K55)')
                //         ->getStyle('L55')->getNumberFormat()->setFormatCode('#,##0.00');

                // DSM TOTAL
                $event->sheet->setCellValue('B56', '=SUM(B18,B27,B35)-B26')
                ->getStyle('B56')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C56', '=SUM(C18,C27,C35)-C26')
                        ->getStyle('C56')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D56', '=SUM(D18,D27,D35)-D26')
                        ->getStyle('D56')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E56', '=SUM(E18,E27,E35)-E26')
                        ->getStyle('E56')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F56', '=SUM(F18,F27,F35)-F26')
                        ->getStyle('F56')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G56', '=SUM(G18,G27,G35)-G26')
                        ->getStyle('G56')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H56', '=SUM(H18,H27,H35)-H26')
                        ->getStyle('H56')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I56', '=SUM(I18,I27,I35)-I26')
                        ->getStyle('I56')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J56', '=SUM(J18,J27,J35)-J26')
                        ->getStyle('J56')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K56', '=SUM(K18,K27,K35)-K26')
                        ->getStyle('K56')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L56', '=SUM(B56:K56)')
                        ->getStyle('L56')->getNumberFormat()->setFormatCode('#,##0.00');

                // KWH USED
                $event->sheet->setCellValue('B57', $data != null ? $data->KwhUsedPoblacion : '-')
                ->getStyle('B57')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C57', $data != null ? $data->KwhUsedRural : '-')
                        ->getStyle('C57')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D57', $data != null ? $data->KwhUsedComLowVoltage : '-')
                        ->getStyle('D57')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E57', $data != null ? $data->KwhUsedComHighVoltage : '-')
                        ->getStyle('E57')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F57', $data != null ? $data->KwhUsedWaterSystems : '-')
                        ->getStyle('F57')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G57', $data != null ? $data->KwhUsedIndLowVoltage : '-')
                        ->getStyle('G57')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H57', $data != null ? $data->KwhUsedIndHighVoltage : '-')
                        ->getStyle('H57')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I57', $data != null ? $data->KwhUsedStreetLights : '-')
                        ->getStyle('I57')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J57', $data != null ? $data->KwhUsedPbLowVoltage : '-')
                        ->getStyle('J57')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K57', $data != null ? $data->KwhUsedPbHighVoltage : '-')
                        ->getStyle('K57')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L57', '=SUM(B57:K57)')
                        ->getStyle('L57')->getNumberFormat()->setFormatCode('#,##0.00');

                // DEMAND
                $event->sheet->setCellValue('B58', $data != null ? $data->DemandPoblacion : '-')
                ->getStyle('B58')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('C58', $data != null ? $data->DemandRural : '-')
                        ->getStyle('C58')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('D58', $data != null ? $data->DemandComLowVoltage : '-')
                        ->getStyle('D58')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('E58', $data != null ? $data->DemandComHighVoltage : '-')
                        ->getStyle('E58')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('F58', $data != null ? $data->DemandWaterSystems : '-')
                        ->getStyle('F58')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('G58', $data != null ? $data->DemandIndLowVoltage : '-')
                        ->getStyle('G58')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('H58', $data != null ? $data->DemandIndHighVoltage : '-')
                        ->getStyle('H58')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('I58', $data != null ? $data->DemandStreetLights : '-')
                        ->getStyle('I58')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('J58', $data != null ? $data->DemandPbLowVoltage : '-')
                        ->getStyle('J58')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('K58', $data != null ? $data->DemandPbHighVoltage : '-')
                        ->getStyle('K58')->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->setCellValue('L58', '=SUM(B58:K58)')
                        ->getStyle('L58')->getNumberFormat()->setFormatCode('#,##0.00');

                // COUNT
                $event->sheet->setCellValue('B59', $data != null ? $data->CountPoblacion : '-')
                ->getStyle('B59')->getNumberFormat()->setFormatCode('#,##0');
                $event->sheet->setCellValue('C59', $data != null ? $data->CountRural : '-')
                        ->getStyle('C59')->getNumberFormat()->setFormatCode('#,##0');
                $event->sheet->setCellValue('D59', $data != null ? $data->CountComLowVoltage : '-')
                        ->getStyle('D59')->getNumberFormat()->setFormatCode('#,##0');
                $event->sheet->setCellValue('E59', $data != null ? $data->CountComHighVoltage : '-')
                        ->getStyle('E59')->getNumberFormat()->setFormatCode('#,##0');
                $event->sheet->setCellValue('F59', $data != null ? $data->CountWaterSystems : '-')
                        ->getStyle('F59')->getNumberFormat()->setFormatCode('#,##0');
                $event->sheet->setCellValue('G59', $data != null ? $data->CountIndLowVoltage : '-')
                        ->getStyle('G59')->getNumberFormat()->setFormatCode('#,##0');
                $event->sheet->setCellValue('H59', $data != null ? $data->CountIndHighVoltage : '-')
                        ->getStyle('H59')->getNumberFormat()->setFormatCode('#,##0');
                $event->sheet->setCellValue('I59', $data != null ? $data->CountStreetLights : '-')
                        ->getStyle('I59')->getNumberFormat()->setFormatCode('#,##0');
                $event->sheet->setCellValue('J59', $data != null ? $data->CountPbLowVoltage : '-')
                        ->getStyle('J59')->getNumberFormat()->setFormatCode('#,##0');
                $event->sheet->setCellValue('K59', $data != null ? $data->CountPbHighVoltage : '-')
                        ->getStyle('K59')->getNumberFormat()->setFormatCode('#,##0');
                $event->sheet->setCellValue('L59', '=SUM(B59:K59)')
                        ->getStyle('L59')->getNumberFormat()->setFormatCode('#,##0');

                // SIGNATORIES
                $event->sheet->setCellValue('B63', 'Prepared By:');
                $event->sheet->mergeCells(sprintf('B65:C65'));
                $event->sheet->setCellValue('B65', 'ANTHONY VAN C. DE LA NOCHE');
                $event->sheet->mergeCells(sprintf('B66:C66'));
                $event->sheet->setCellValue('B66', 'Meter Reading and Billing Analyst');

                $event->sheet->setCellValue('E63', 'Recommending Approval:');
                $event->sheet->mergeCells(sprintf('E65:F65'));
                $event->sheet->setCellValue('E65', 'ANTHONY B. LAGRADA');
                $event->sheet->mergeCells(sprintf('E66:F66'));
                $event->sheet->setCellValue('E66', 'OIC - CITET Dept. Manager');

                $event->sheet->mergeCells(sprintf('G65:H65'));
                $event->sheet->setCellValue('G65', 'ELREEN JANE Z. BANOT');
                $event->sheet->mergeCells(sprintf('G66:H66'));
                $event->sheet->setCellValue('G66', 'FSD Manager');

                $event->sheet->setCellValue('J63', 'Approved by:');
                $event->sheet->mergeCells(sprintf('J65:K65'));
                $event->sheet->setCellValue('J65', 'ATTY. DANNY L. PONDEVILLA');
                $event->sheet->mergeCells(sprintf('J66:K66'));
                $event->sheet->setCellValue('J66', 'General Manager');
            }
        ];
    }
}