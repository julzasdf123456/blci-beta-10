<?php
namespace App\Imports;

use App\Models\Rates;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use App\Models\IDGenerator;

class HospitalRateHR implements WithMappedCells, WithCalculatedFormulas, ToModel 
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
            'GenerationSystemCharge' => 'P10',
            'ACRM' => 'P11',
            'TransmissionDeliveryChargeKWH' => 'P12',
            'SystemLossCharge' => 'P13',
            // 'OtherGenerationRateAdjustment' => 'P',
            // 'OtherTransmissionCostAdjustmentKW' => 'P',
            // 'OtherTransmissionCostAdjustmentKWH' => 'P',
            // 'OtherSystemLossCostAdjustment' => 'P',
            'DistributionDemandCharge' => 'P15',
            'DistributionSystemCharge' => 'P14',
            'SupplyRetailCustomerCharge' => 'P16',
            // 'SupplySystemCharge' => 'P',
            'MeteringRetailCustomerCharge' => 'P18',
            'MeteringSystemCharge' => 'P19',
            // 'RFSC' => 'P',
            'PowerActReduction' => 'P20',
            'LifelineRate' => 'P21',
            'InterClassCrossSubsidyCharge' => 'P23',
            // 'PPARefund' => 'P',
            'SeniorCitizenSubsidy' => 'P22',
            // 'OtherLifelineRateCostAdjustment' => 'P',
            // 'SeniorCitizenDiscountAndSubsidyAdjustment' => 'P',
            // 'MissionaryElectrificationCharge' => 'P',
            // 'EnvironmentalCharge' => 'P',
            // 'StrandedContractCosts' => 'P',
            'NPCStrandedDebt' => 'P35',
            // 'FeedInTariffAllowance' => 'P',
            'MissionaryElectrificationREDCI' => 'P32',
            'MissionaryElectrificationSPUG' => 'P30',
            'MissionaryElectrificationSPUGTRUEUP' => 'P31',
            'GenerationVAT' => 'P25',
            'TransmissionVAT' => 'P27',
            'SystemLossVAT' => 'P28',
            'ACRMVAT' => 'P26',
            'FranchiseTax' => 'P37',
            // 'BusinessTax' => 'P',
            // 'RealPropertyTax' => 'P',
            // 'TotalRateVATExcluded' => 'P',
            // 'TotalRateVATExcludedWithAdjustments' => 'P',
            'TotalRateVATIncluded' => 'P39',
        ];
    }
    
    public function model(array $row)
    {
        return new Rates([
            'id' => IDGenerator::generateIDandRandString(),
            'RateFor' => $this->district,
            'ServicePeriod' => $this->servicePeriod,
            'UserId' => $this->userId,
            'ConsumerType' => 'HR',
            'ConsumerTypeDescription' => 'HOSPITALS/ RADIO STA. w/ DAA',
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

