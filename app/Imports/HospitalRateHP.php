<?php
namespace App\Imports;

use App\Models\Rates;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use App\Models\IDGenerator;

class HospitalRateHP implements WithMappedCells, WithCalculatedFormulas, ToModel 
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
            'GenerationSystemCharge' => 'Q10',
            'ACRM' => 'Q11',
            'TransmissionDeliveryChargeKWH' => 'Q12',
            'SystemLossCharge' => 'Q13',
            // 'OtherGenerationRateAdjustment' => 'Q',
            // 'OtherTransmissionCostAdjustmentKW' => 'Q',
            // 'OtherTransmissionCostAdjustmentKWH' => 'Q',
            // 'OtherSystemLossCostAdjustment' => 'Q',
            'DistributionDemandCharge' => 'Q15',
            'DistributionSystemCharge' => 'Q14',
            'SupplyRetailCustomerCharge' => 'Q16',
            // 'SupplySystemCharge' => 'Q',
            'MeteringRetailCustomerCharge' => 'Q18',
            'MeteringSystemCharge' => 'Q19',
            // 'RFSC' => 'Q',
            'PowerActReduction' => 'Q20',
            'LifelineRate' => 'Q21',
            'InterClassCrossSubsidyCharge' => 'Q23',
            // 'PPARefund' => 'Q',
            'SeniorCitizenSubsidy' => 'Q22',
            // 'OtherLifelineRateCostAdjustment' => 'Q',
            // 'SeniorCitizenDiscountAndSubsidyAdjustment' => 'Q',
            // 'MissionaryElectrificationCharge' => 'Q',
            // 'EnvironmentalCharge' => 'Q',
            // 'StrandedContractCosts' => 'Q',
            'NPCStrandedDebt' => 'Q35',
            // 'FeedInTariffAllowance' => 'Q',
            'MissionaryElectrificationREDCI' => 'Q32',
            'MissionaryElectrificationSPUG' => 'Q30',
            'MissionaryElectrificationSPUGTRUEUP' => 'Q31',
            'GenerationVAT' => 'Q25',
            'TransmissionVAT' => 'Q27',
            'SystemLossVAT' => 'Q28',
            'ACRMVAT' => 'Q26',
            'FranchiseTax' => 'Q37',
            // 'BusinessTax' => 'Q',
            // 'RealPropertyTax' => 'Q',
            // 'TotalRateVATExcluded' => 'Q',
            // 'TotalRateVATExcludedWithAdjustments' => 'Q',
            'TotalRateVATIncluded' => 'Q39',
        ];
    }
    
    public function model(array $row)
    {
        return new Rates([
            'id' => IDGenerator::generateIDandRandString(),
            'RateFor' => $this->district,
            'ServicePeriod' => $this->servicePeriod,
            'UserId' => $this->userId,
            'ConsumerType' => 'HP',
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

