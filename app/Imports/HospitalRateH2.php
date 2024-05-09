<?php
namespace App\Imports;

use App\Models\Rates;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use App\Models\IDGenerator;

class HospitalRateH2 implements WithMappedCells, WithCalculatedFormulas, ToModel 
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
            'GenerationSystemCharge' => 'N10',
            'ACRM' => 'N11',
            'TransmissionDeliveryChargeKWH' => 'N12',
            'SystemLossCharge' => 'N13',
            // 'OtherGenerationRateAdjustment' => 'N',
            // 'OtherTransmissionCostAdjustmentKW' => 'N',
            // 'OtherTransmissionCostAdjustmentKWH' => 'N',
            // 'OtherSystemLossCostAdjustment' => 'N',
            'DistributionDemandCharge' => 'N15',
            'DistributionSystemCharge' => 'N14',
            'SupplyRetailCustomerCharge' => 'N16',
            // 'SupplySystemCharge' => 'N',
            'MeteringRetailCustomerCharge' => 'N18',
            'MeteringSystemCharge' => 'N19',
            // 'RFSC' => 'N',
            'PowerActReduction' => 'N20',
            'LifelineRate' => 'N21',
            'InterClassCrossSubsidyCharge' => 'N23',
            // 'PPARefund' => 'N',
            'SeniorCitizenSubsidy' => 'N22',
            // 'OtherLifelineRateCostAdjustment' => 'N',
            // 'SeniorCitizenDiscountAndSubsidyAdjustment' => 'N',
            // 'MissionaryElectrificationCharge' => 'N',
            // 'EnvironmentalCharge' => 'N',
            // 'StrandedContractCosts' => 'N',
            'NPCStrandedDebt' => 'N35',
            'FeedInTariffAllowance' => 'N36',
            'MissionaryElectrificationREDCI' => 'N32',
            'MissionaryElectrificationSPUG' => 'N30',
            'MissionaryElectrificationSPUGTRUEUP' => 'N31',
            'GenerationVAT' => 'N25',
            'TransmissionVAT' => 'N27',
            'SystemLossVAT' => 'N28',
            'ACRMVAT' => 'N26',
            'FranchiseTax' => 'N37',
            // 'BusinessTax' => 'N',
            // 'RealPropertyTax' => 'N',
            // 'TotalRateVATExcluded' => 'N',
            // 'TotalRateVATExcludedWithAdjustments' => 'N',
            'TotalRateVATIncluded' => 'N39',
        ];
    }
    
    public function model(array $row)
    {
        return new Rates([
            'id' => IDGenerator::generateIDandRandString(),
            'RateFor' => $this->district,
            'ServicePeriod' => $this->servicePeriod,
            'UserId' => $this->userId,
            'ConsumerType' => 'H2',
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

