<?php
namespace App\Imports;

use App\Models\Rates;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use App\Models\IDGenerator;

class PublicBuildingRateGV implements WithMappedCells, WithCalculatedFormulas, ToModel 
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
            'GenerationSystemCharge' => 'L10',
            'ACRM' => 'L11',
            'TransmissionDeliveryChargeKWH' => 'L12',
            'SystemLossCharge' => 'L13',
            // 'OtherGenerationRateAdjustment' => 'L',
            // 'OtherTransmissionCostAdjustmentKW' => 'L',
            // 'OtherTransmissionCostAdjustmentKWH' => 'L',
            // 'OtherSystemLossCostAdjustment' => 'L',
            'DistributionDemandCharge' => 'L15',
            'DistributionSystemCharge' => 'L14',
            'SupplyRetailCustomerCharge' => 'L16',
            // 'SupplySystemCharge' => 'L',
            'MeteringRetailCustomerCharge' => 'L18',
            'MeteringSystemCharge' => 'L19',
            // 'RFSC' => 'L',
            'PowerActReduction' => 'L20',
            'LifelineRate' => 'L21',
            'InterClassCrossSubsidyCharge' => 'L23',
            // 'PPARefund' => 'L',
            'SeniorCitizenSubsidy' => 'L22',
            // 'OtherLifelineRateCostAdjustment' => 'L',
            // 'SeniorCitizenDiscountAndSubsidyAdjustment' => 'L',
            // 'MissionaryElectrificationCharge' => 'L',
            // 'EnvironmentalCharge' => 'L',
            // 'StrandedContractCosts' => 'L',
            'NPCStrandedDebt' => 'L35',
            // 'FeedInTariffAllowance' => 'L',
            'MissionaryElectrificationREDCI' => 'L32',
            'MissionaryElectrificationSPUG' => 'L30',
            'MissionaryElectrificationSPUGTRUEUP' => 'L31',
            'GenerationVAT' => 'L25',
            'TransmissionVAT' => 'L27',
            'SystemLossVAT' => 'L28',
            'ACRMVAT' => 'L26',
            'FranchiseTax' => 'L37',
            // 'BusinessTax' => 'L',
            // 'RealPropertyTax' => 'L',
            // 'TotalRateVATExcluded' => 'L',
            // 'TotalRateVATExcludedWithAdjustments' => 'L',
            'TotalRateVATIncluded' => 'L39',
        ];
    }
    
    public function model(array $row)
    {
        return new Rates([
            'id' => IDGenerator::generateIDandRandString(),
            'RateFor' => $this->district,
            'ServicePeriod' => $this->servicePeriod,
            'UserId' => $this->userId,
            'ConsumerType' => 'GV',
            'ConsumerTypeDescription' => 'PUBLIC BLDG/ST LIGHT w/ DAA',
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

