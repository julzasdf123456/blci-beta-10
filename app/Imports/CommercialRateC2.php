<?php
namespace App\Imports;

use App\Models\Rates;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use App\Models\IDGenerator;

class CommercialRateC2 implements WithMappedCells, WithCalculatedFormulas, ToModel 
{

    private $servicePeriod, $userId, $district, $areaCode;

    public function __construct($servicePeriod, $userId, $district, $areaCode)
    {
        $this->servicePeriod = $servicePeriod;
        $this->userId = $userId;
        $this->district = $district;
        $this->areaCode = $areaCode;
    }

    public function mapping(): array
    {
        return [
            'GenerationSystemCharge' => 'E10',
            'ACRM' => 'E11',
            'TransmissionDeliveryChargeKWH' => 'E12',
            'SystemLossCharge' => 'E13',
            // 'OtherGenerationRateAdjustment' => 'E',
            // 'OtherTransmissionCostAdjustmentKW' => 'E',
            // 'OtherTransmissionCostAdjustmentKWH' => 'E',
            // 'OtherSystemLossCostAdjustment' => 'E',
            'DistributionDemandCharge' => 'E15',
            'DistributionSystemCharge' => 'E14',
            'SupplyRetailCustomerCharge' => 'E16',
            // 'SupplySystemCharge' => 'E',
            'MeteringRetailCustomerCharge' => 'E18',
            'MeteringSystemCharge' => 'E19',
            // 'RFSC' => 'E',
            'PowerActReduction' => 'E20',
            'LifelineRate' => 'E21',
            'InterClassCrossSubsidyCharge' => 'E23',
            // 'PPARefund' => 'E',
            'SeniorCitizenSubsidy' => 'E22',
            // 'OtherLifelineRateCostAdjustment' => 'E',
            // 'SeniorCitizenDiscountAndSubsidyAdjustment' => 'E',
            // 'MissionaryElectrificationCharge' => 'E',
            // 'EnvironmentalCharge' => 'E',
            // 'StrandedContractCosts' => 'E',
            'NPCStrandedDebt' => 'E35',
            'FeedInTariffAllowance' => 'E36',
            'MissionaryElectrificationREDCI' => 'E32',
            'MissionaryElectrificationSPUG' => 'E30',
            'MissionaryElectrificationSPUGTRUEUP' => 'E31',
            'GenerationVAT' => 'E25',
            'TransmissionVAT' => 'E27',
            'SystemLossVAT' => 'E28',
            'ACRMVAT' => 'E26',
            'FranchiseTax' => 'E37',
            // 'BusinessTax' => 'E',
            // 'RealPropertyTax' => 'E',
            // 'TotalRateVATExcluded' => 'E',
            // 'TotalRateVATExcludedWithAdjustments' => 'E',
            'TotalRateVATIncluded' => 'E39',
        ];
    }
    
    public function model(array $row)
    {
        return new Rates([
            'id' => IDGenerator::generateIDandRandString(),
            'RateFor' => $this->district,
            'ServicePeriod' => $this->servicePeriod,
            'UserId' => $this->userId,
            'ConsumerType' => 'C2',
            'ConsumerTypeDescription' => 'COMMERCIAL w/DAA',
            'GenerationSystemCharge' => floatval($row['GenerationSystemCharge']),
            'ACRM' => floatval($row['ACRM']),
            'TransmissionDeliveryChargeKWH' => floatval($row['TransmissionDeliveryChargeKWH']),
            'SystemLossCharge' => floatval($row['SystemLossCharge']),
            // 'OtherGenerationRateAdjustment' => floatval($row['OtherGenerationRateAdjustment']),
            // 'OtherTransmissionCostAdjustmentKW' => floatval($row['OtherTransmissionCostAdjustmentKW']),
            // 'OtherTransmissionCostAdjustmentKWH' => floatval($row['OtherTransmissionCostAdjustmentKWH']),
            // 'OtherSystemLossCostAdjustment' => floatval($row['OtherSystemLossCostAdjustment']),
            'DistributionDemandCharge' => floatval($row['DistributionDemandCharge']),
            'DistributionSystemCharge' => floatval($row['DistributionSystemCharge']),
            'SupplyRetailCustomerCharge' => floatval($row['SupplyRetailCustomerCharge']),
            // 'SupplySystemCharge' => floatval($row['SupplySystemCharge']),
            'MeteringRetailCustomerCharge' => floatval($row['MeteringRetailCustomerCharge']),
            'MeteringSystemCharge' => floatval($row['MeteringSystemCharge']),
            // 'RFSC' => floatval($row['RFSC']),
            'PowerActReduction' => floatval($row['PowerActReduction']),
            'LifelineRate' => floatval($row['LifelineRate']),
            'InterClassCrossSubsidyCharge' => floatval($row['InterClassCrossSubsidyCharge']),
            // 'PPARefund' => floatval($row['PPARefund']),
            'SeniorCitizenSubsidy' => floatval($row['SeniorCitizenSubsidy']),
            // 'OtherLifelineRateCostAdjustment' => floatval($row['OtherLifelineRateCostAdjustment']),
            // 'SeniorCitizenDiscountAndSubsidyAdjustment' => floatval($row['SeniorCitizenDiscountAndSubsidyAdjustment']),
            // 'MissionaryElectrificationCharge' => floatval($row['MissionaryElectrificationCharge']),
            // 'EnvironmentalCharge' => floatval($row['EnvironmentalCharge']),
            // 'StrandedContractCosts' => floatval($row['StrandedContractCosts']),
            'NPCStrandedDebt' => floatval($row['NPCStrandedDebt']),
            'FeedInTariffAllowance' => isset($row['FeedInTariffAllowance']) && is_numeric($row['FeedInTariffAllowance']) ? floatval($row['FeedInTariffAllowance']) : null,
            'MissionaryElectrificationREDCI' => floatval($row['MissionaryElectrificationREDCI']),
            'MissionaryElectrificationSPUG' => floatval($row['MissionaryElectrificationSPUG']),
            'MissionaryElectrificationSPUGTRUEUP' => floatval($row['MissionaryElectrificationSPUGTRUEUP']),
            'GenerationVAT' => floatval($row['GenerationVAT']),
            'TransmissionVAT' => floatval($row['TransmissionVAT']),
            'SystemLossVAT' => floatval($row['SystemLossVAT']),
            'ACRMVAT' => floatval($row['ACRMVAT']),
            // 'RealPropertyTax' => floatval($row['RealPropertyTax']),
            'FranchiseTax' => floatval($row['FranchiseTax']),
            // 'BusinessTax' => floatval($row['BusinessTax']),
            // 'TotalRateVATExcluded' => floatval($row['TotalRateVATExcluded']),
            'TotalRateVATIncluded' => floatval($row['TotalRateVATIncluded']),
            // 'TotalRateVATExcludedWithAdjustments' => $row['TotalRateVATExcludedWithAdjustments'],
            'AreaCode' => $this->areaCode,
        ]);
    }
}

