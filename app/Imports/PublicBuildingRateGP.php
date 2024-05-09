<?php
namespace App\Imports;

use App\Models\Rates;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use App\Models\IDGenerator;

class PublicBuildingRateGP implements WithMappedCells, WithCalculatedFormulas, ToModel 
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
            'GenerationSystemCharge' => 'M10',
            'ACRM' => 'M11',
            'TransmissionDeliveryChargeKWH' => 'M12',
            'SystemLossCharge' => 'M13',
            // 'OtherGenerationRateAdjustment' => 'M',
            // 'OtherTransmissionCostAdjustmentKW' => 'M',
            // 'OtherTransmissionCostAdjustmentKWH' => 'M',
            // 'OtherSystemLossCostAdjustment' => 'M',
            'DistributionDemandCharge' => 'M15',
            'DistributionSystemCharge' => 'M14',
            'SupplyRetailCustomerCharge' => 'M16',
            // 'SupplySystemCharge' => 'M',
            'MeteringRetailCustomerCharge' => 'M18',
            'MeteringSystemCharge' => 'M19',
            // 'RFSC' => 'M',
            'PowerActReduction' => 'M20',
            'LifelineRate' => 'M21',
            'InterClassCrossSubsidyCharge' => 'M23',
            // 'PPARefund' => 'M',
            'SeniorCitizenSubsidy' => 'M22',
            // 'OtherLifelineRateCostAdjustment' => 'M',
            // 'SeniorCitizenDiscountAndSubsidyAdjustment' => 'M',
            // 'MissionaryElectrificationCharge' => 'M',
            // 'EnvironmentalCharge' => 'M',
            // 'StrandedContractCosts' => 'M',
            'NPCStrandedDebt' => 'M35',
            'FeedInTariffAllowance' => 'M36',
            'MissionaryElectrificationREDCI' => 'M32',
            'MissionaryElectrificationSPUG' => 'M30',
            'MissionaryElectrificationSPUGTRUEUP' => 'M31',
            'GenerationVAT' => 'M25',
            'TransmissionVAT' => 'M27',
            'SystemLossVAT' => 'M28',
            'ACRMVAT' => 'M26',
            'FranchiseTax' => 'M37',
            // 'BusinessTax' => 'M',
            // 'RealPropertyTax' => 'M',
            // 'TotalRateVATExcluded' => 'M',
            // 'TotalRateVATExcludedWithAdjustments' => 'M',
            'TotalRateVATIncluded' => 'M39',
        ];
    }
    
    public function model(array $row)
    {
        return new Rates([
            'id' => IDGenerator::generateIDandRandString(),
            'RateFor' => $this->district,
            'ServicePeriod' => $this->servicePeriod,
            'UserId' => $this->userId,
            'ConsumerType' => 'GP',
            'ConsumerTypeDescription' => 'PUBLIC BLDG/ST LIGHT w/o DAA',
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

