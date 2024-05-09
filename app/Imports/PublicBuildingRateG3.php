<?php
namespace App\Imports;

use App\Models\Rates;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use App\Models\IDGenerator;

class PublicBuildingRateG3 implements WithMappedCells, WithCalculatedFormulas, ToModel 
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
            'GenerationSystemCharge' => 'K10',
            'ACRM' => 'K11',
            'TransmissionDeliveryChargeKWH' => 'K12',
            'SystemLossCharge' => 'K13',
            // 'OtherGenerationRateAdjustment' => 'K',
            // 'OtherTransmissionCostAdjustmentKW' => 'K',
            // 'OtherTransmissionCostAdjustmentKWH' => 'K',
            // 'OtherSystemLossCostAdjustment' => 'K',
            'DistributionDemandCharge' => 'K15',
            'DistributionSystemCharge' => 'K14',
            'SupplyRetailCustomerCharge' => 'K16',
            // 'SupplySystemCharge' => 'K',
            'MeteringRetailCustomerCharge' => 'K18',
            'MeteringSystemCharge' => 'K19',
            // 'RFSC' => 'K',
            'PowerActReduction' => 'K20',
            'LifelineRate' => 'K21',
            'InterClassCrossSubsidyCharge' => 'K23',
            // 'PPARefund' => 'K',
            'SeniorCitizenSubsidy' => 'K22',
            // 'OtherLifelineRateCostAdjustment' => 'K',
            // 'SeniorCitizenDiscountAndSubsidyAdjustment' => 'K',
            // 'MissionaryElectrificationCharge' => 'K',
            // 'EnvironmentalCharge' => 'K',
            // 'StrandedContractCosts' => 'K',
            'NPCStrandedDebt' => 'K35',
            'FeedInTariffAllowance' => 'K36',
            'MissionaryElectrificationREDCI' => 'K32',
            'MissionaryElectrificationSPUG' => 'K30',
            'MissionaryElectrificationSPUGTRUEUP' => 'K31',
            'GenerationVAT' => 'K25',
            'TransmissionVAT' => 'K27',
            'SystemLossVAT' => 'K28',
            'ACRMVAT' => 'K26',
            'FranchiseTax' => 'K37',
            // 'BusinessTax' => 'K',
            // 'RealPropertyTax' => 'K',
            // 'TotalRateVATExcluded' => 'K',
            // 'TotalRateVATExcludedWithAdjustments' => 'K',
            'TotalRateVATIncluded' => 'K39',
        ];
    }
    
    public function model(array $row)
    {
        return new Rates([
            'id' => IDGenerator::generateIDandRandString(),
            'RateFor' => $this->district,
            'ServicePeriod' => $this->servicePeriod,
            'UserId' => $this->userId,
            'ConsumerType' => 'G3',
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

