<?php
namespace App\Imports;

use App\Models\Rates;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use App\Models\IDGenerator;

class ResidentialRateRS implements WithMappedCells, WithCalculatedFormulas, ToModel 
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
            'GenerationSystemCharge' => 'C10',
            'ACRM' => 'C11',
            'TransmissionDeliveryChargeKWH' => 'C12',
            'SystemLossCharge' => 'C13',
            // 'OtherGenerationRateAdjustment' => 'C',
            // 'OtherTransmissionCostAdjustmentKW' => 'C',
            // 'OtherTransmissionCostAdjustmentKWH' => 'C',
            // 'OtherSystemLossCostAdjustment' => 'C',
            'DistributionDemandCharge' => 'C15',
            'DistributionSystemCharge' => 'C14',
            'SupplyRetailCustomerCharge' => 'C16',
            // 'SupplySystemCharge' => 'C',
            'MeteringRetailCustomerCharge' => 'C18',
            'MeteringSystemCharge' => 'C19',
            // 'RFSC' => 'C',
            'PowerActReduction' => 'C20',
            'LifelineRate' => 'C21',
            'InterClassCrossSubsidyCharge' => 'C23',
            // 'PPARefund' => 'C',
            'SeniorCitizenSubsidy' => 'C22',
            // 'OtherLifelineRateCostAdjustment' => 'C',
            // 'SeniorCitizenDiscountAndSubsidyAdjustment' => 'C',
            // 'MissionaryElectrificationCharge' => 'C',
            // 'EnvironmentalCharge' => 'C',
            // 'StrandedContractCosts' => 'C',
            'NPCStrandedDebt' => 'C35',
            // 'FeedInTariffAllowance' => 'C',
            'MissionaryElectrificationREDCI' => 'C32',
            'MissionaryElectrificationSPUG' => 'C30',
            'MissionaryElectrificationSPUGTRUEUP' => 'C31',
            'GenerationVAT' => 'C25',
            'TransmissionVAT' => 'C27',
            'SystemLossVAT' => 'C28',
            'ACRMVAT' => 'C26',
            'FranchiseTax' => 'C37',
            // 'BusinessTax' => 'C',
            // 'RealPropertyTax' => 'C',
            // 'TotalRateVATExcluded' => 'C',
            // 'TotalRateVATExcludedWithAdjustments' => 'C',
            'TotalRateVATIncluded' => 'C39',
        ];
    }
    
    public function model(array $row)
    {
        return new Rates([
            'id' => IDGenerator::generateIDandRandString(),
            'RateFor' => $this->district,
            'ServicePeriod' => $this->servicePeriod,
            'UserId' => $this->userId,
            'ConsumerType' => 'RS',
            'ConsumerTypeDescription' => 'RESIDENTIAL w/DAA',
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

