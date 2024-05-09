<?php
namespace App\Imports;

use App\Models\Rates;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use App\Models\IDGenerator;

class PublicBuildingRateG2 implements WithMappedCells, WithCalculatedFormulas, ToModel 
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
            'GenerationSystemCharge' => 'J10',
            'ACRM' => 'J11',
            'TransmissionDeliveryChargeKWH' => 'J12',
            'SystemLossCharge' => 'J13',
            // 'OtherGenerationRateAdjustment' => 'J',
            // 'OtherTransmissionCostAdjustmentKW' => 'J',
            // 'OtherTransmissionCostAdjustmentKWH' => 'J',
            // 'OtherSystemLossCostAdjustment' => 'J',
            'DistributionDemandCharge' => 'J15',
            'DistributionSystemCharge' => 'J14',
            'SupplyRetailCustomerCharge' => 'J16',
            // 'SupplySystemCharge' => 'J',
            'MeteringRetailCustomerCharge' => 'J18',
            'MeteringSystemCharge' => 'J19',
            // 'RFSC' => 'J',
            'PowerActReduction' => 'J20',
            'LifelineRate' => 'J21',
            'InterClassCrossSubsidyCharge' => 'J23',
            // 'PPARefund' => 'J',
            'SeniorCitizenSubsidy' => 'J22',
            // 'OtherLifelineRateCostAdjustment' => 'J',
            // 'SeniorCitizenDiscountAndSubsidyAdjustment' => 'J',
            // 'MissionaryElectrificationCharge' => 'J',
            // 'EnvironmentalCharge' => 'J',
            // 'StrandedContractCosts' => 'J',
            'NPCStrandedDebt' => 'J35',
            'FeedInTariffAllowance' => 'J36',
            'MissionaryElectrificationREDCI' => 'J32',
            'MissionaryElectrificationSPUG' => 'J30',
            'MissionaryElectrificationSPUGTRUEUP' => 'J31',
            'GenerationVAT' => 'J25',
            'TransmissionVAT' => 'J27',
            'SystemLossVAT' => 'J28',
            'ACRMVAT' => 'J26',
            'FranchiseTax' => 'J37',
            // 'BusinessTax' => 'J',
            // 'RealPropertyTax' => 'J',
            // 'TotalRateVATExcluded' => 'J',
            // 'TotalRateVATExcludedWithAdjustments' => 'J',
            'TotalRateVATIncluded' => 'J39',
        ];
    }
    
    public function model(array $row)
    {
        return new Rates([
            'id' => IDGenerator::generateIDandRandString(),
            'RateFor' => $this->district,
            'ServicePeriod' => $this->servicePeriod,
            'UserId' => $this->userId,
            'ConsumerType' => 'G2',
            'ConsumerTypeDescription' => 'PUBLIC BLDG/ST LIGHT w/DAA',
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

