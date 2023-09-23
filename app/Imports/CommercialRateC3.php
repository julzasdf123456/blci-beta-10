<?php
namespace App\Imports;

use App\Models\Rates;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use App\Models\IDGenerator;

class CommercialRateC3 implements WithMappedCells, WithCalculatedFormulas, ToModel 
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
            'GenerationSystemCharge' => 'F10',
            'ACRM' => 'F11',
            'TransmissionDeliveryChargeKWH' => 'F12',
            'SystemLossCharge' => 'F13',
            // 'OtherGenerationRateAdjustment' => 'F',
            // 'OtherTransmissionCostAdjustmentKW' => 'F',
            // 'OtherTransmissionCostAdjustmentKWH' => 'F',
            // 'OtherSystemLossCostAdjustment' => 'F',
            'DistributionDemandCharge' => 'F15',
            'DistributionSystemCharge' => 'F14',
            'SupplyRetailCustomerCharge' => 'F16',
            // 'SupplySystemCharge' => 'F',
            'MeteringRetailCustomerCharge' => 'F18',
            'MeteringSystemCharge' => 'F19',
            // 'RFSC' => 'F',
            'PowerActReduction' => 'F20',
            'LifelineRate' => 'F21',
            'InterClassCrossSubsidyCharge' => 'F23',
            // 'PPARefund' => 'F',
            'SeniorCitizenSubsidy' => 'F22',
            // 'OtherLifelineRateCostAdjustment' => 'F',
            // 'SeniorCitizenDiscountAndSubsidyAdjustment' => 'F',
            // 'MissionaryElectrificationCharge' => 'F',
            // 'EnvironmentalCharge' => 'F',
            // 'StrandedContractCosts' => 'F',
            'NPCStrandedDebt' => 'F35',
            // 'FeedInTariffAllowance' => 'F',
            'MissionaryElectrificationREDCI' => 'F32',
            'MissionaryElectrificationSPUG' => 'F30',
            'MissionaryElectrificationSPUGTRUEUP' => 'F31',
            'GenerationVAT' => 'F25',
            'TransmissionVAT' => 'F27',
            'SystemLossVAT' => 'F28',
            'ACRMVAT' => 'F26',
            'FranchiseTax' => 'F37',
            // 'BusinessTax' => 'F',
            // 'RealPropertyTax' => 'F',
            // 'TotalRateVATExcluded' => 'F',
            // 'TotalRateVATExcludedWithAdjustments' => 'F',
            'TotalRateVATIncluded' => 'F39',
        ];
    }
    
    public function model(array $row)
    {
        return new Rates([
            'id' => IDGenerator::generateIDandRandString(),
            'RateFor' => $this->district,
            'ServicePeriod' => $this->servicePeriod,
            'UserId' => $this->userId,
            'ConsumerType' => 'C3',
            'ConsumerTypeDescription' => 'COMMERCIAL w/o DAA',
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
            // 'FeedInTariffAllowance' => floatval($row['FeedInTariffAllowance']),
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

