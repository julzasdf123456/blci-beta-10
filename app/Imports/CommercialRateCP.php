<?php
namespace App\Imports;

use App\Models\Rates;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use App\Models\IDGenerator;

class CommercialRateCP implements WithMappedCells, WithCalculatedFormulas, ToModel 
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
            'GenerationSystemCharge' => 'I10',
            'ACRM' => 'I11',
            'TransmissionDeliveryChargeKWH' => 'I12',
            'SystemLossCharge' => 'I13',
            // 'OtherGenerationRateAdjustment' => 'I',
            // 'OtherTransmissionCostAdjustmentKW' => 'I',
            // 'OtherTransmissionCostAdjustmentKWH' => 'I',
            // 'OtherSystemLossCostAdjustment' => 'I',
            'DistributionDemandCharge' => 'I15',
            'DistributionSystemCharge' => 'I14',
            'SupplyRetailCustomerCharge' => 'I16',
            // 'SupplySystemCharge' => 'I',
            'MeteringRetailCustomerCharge' => 'I18',
            'MeteringSystemCharge' => 'I19',
            // 'RFSC' => 'I',
            'PowerActReduction' => 'I20',
            'LifelineRate' => 'I21',
            'InterClassCrossSubsidyCharge' => 'I23',
            // 'PPARefund' => 'I',
            'SeniorCitizenSubsidy' => 'I22',
            // 'OtherLifelineRateCostAdjustment' => 'I',
            // 'SeniorCitizenDiscountAndSubsidyAdjustment' => 'I',
            // 'MissionaryElectrificationCharge' => 'I',
            // 'EnvironmentalCharge' => 'I',
            // 'StrandedContractCosts' => 'I',
            'NPCStrandedDebt' => 'I35',
            // 'FeedInTariffAllowance' => 'I',
            'MissionaryElectrificationREDCI' => 'I32',
            'MissionaryElectrificationSPUG' => 'I30',
            'MissionaryElectrificationSPUGTRUEUP' => 'I31',
            'GenerationVAT' => 'I25',
            'TransmissionVAT' => 'I27',
            'SystemLossVAT' => 'I28',
            'ACRMVAT' => 'I26',
            'FranchiseTax' => 'I37',
            // 'BusinessTax' => 'I',
            // 'RealPropertyTax' => 'I',
            // 'TotalRateVATExcluded' => 'I',
            // 'TotalRateVATExcludedWithAdjustments' => 'I',
            'TotalRateVATIncluded' => 'I39',
        ];
    }
    
    public function model(array $row)
    {
        return new Rates([
            'id' => IDGenerator::generateIDandRandString(),
            'RateFor' => $this->district,
            'ServicePeriod' => $this->servicePeriod,
            'UserId' => $this->userId,
            'ConsumerType' => 'CP',
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

