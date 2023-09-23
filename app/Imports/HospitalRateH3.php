<?php
namespace App\Imports;

use App\Models\Rates;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use App\Models\IDGenerator;

class HospitalRateH3 implements WithMappedCells, WithCalculatedFormulas, ToModel 
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
            'GenerationSystemCharge' => 'O10',
            'ACRM' => 'O11',
            'TransmissionDeliveryChargeKWH' => 'O12',
            'SystemLossCharge' => 'O13',
            // 'OtherGenerationRateAdjustment' => 'O',
            // 'OtherTransmissionCostAdjustmentKW' => 'O',
            // 'OtherTransmissionCostAdjustmentKWH' => 'O',
            // 'OtherSystemLossCostAdjustment' => 'O',
            'DistributionDemandCharge' => 'O15',
            'DistributionSystemCharge' => 'O14',
            'SupplyRetailCustomerCharge' => 'O16',
            // 'SupplySystemCharge' => 'O',
            'MeteringRetailCustomerCharge' => 'O18',
            'MeteringSystemCharge' => 'O19',
            // 'RFSC' => 'O',
            'PowerActReduction' => 'O20',
            'LifelineRate' => 'O21',
            'InterClassCrossSubsidyCharge' => 'O23',
            // 'PPARefund' => 'O',
            'SeniorCitizenSubsidy' => 'O22',
            // 'OtherLifelineRateCostAdjustment' => 'O',
            // 'SeniorCitizenDiscountAndSubsidyAdjustment' => 'O',
            // 'MissionaryElectrificationCharge' => 'O',
            // 'EnvironmentalCharge' => 'O',
            // 'StrandedContractCosts' => 'O',
            'NPCStrandedDebt' => 'O35',
            // 'FeedInTariffAllowance' => 'O',
            'MissionaryElectrificationREDCI' => 'O32',
            'MissionaryElectrificationSPUG' => 'O30',
            'MissionaryElectrificationSPUGTRUEUP' => 'O31',
            'GenerationVAT' => 'O25',
            'TransmissionVAT' => 'O27',
            'SystemLossVAT' => 'O28',
            'ACRMVAT' => 'O26',
            'FranchiseTax' => 'O37',
            // 'BusinessTax' => 'O',
            // 'RealPropertyTax' => 'O',
            // 'TotalRateVATExcluded' => 'O',
            // 'TotalRateVATExcludedWithAdjustments' => 'O',
            'TotalRateVATIncluded' => 'O39',
        ];
    }
    
    public function model(array $row)
    {
        return new Rates([
            'id' => IDGenerator::generateIDandRandString(),
            'RateFor' => $this->district,
            'ServicePeriod' => $this->servicePeriod,
            'UserId' => $this->userId,
            'ConsumerType' => 'H3',
            'ConsumerTypeDescription' => 'HOSPITALS/ RADIO STA. w/o DAA',
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

