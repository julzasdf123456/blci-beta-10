<?php
namespace App\Imports;

use App\Models\Rates;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use App\Models\IDGenerator;

class ResidentialRateRP implements WithMappedCells, WithCalculatedFormulas, ToModel 
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
            'GenerationSystemCharge' => 'D10',
            'ACRM' => 'D11',
            'TransmissionDeliveryChargeKWH' => 'D12',
            'SystemLossCharge' => 'D13',
            // 'OtherGenerationRateAdjustment' => 'D',
            // 'OtherTransmissionCostAdjustmentKW' => 'D',
            // 'OtherTransmissionCostAdjustmentKWH' => 'D',
            // 'OtherSystemLossCostAdjustment' => 'D',
            'DistributionDemandCharge' => 'D15',
            'DistributionSystemCharge' => 'D14',
            'SupplyRetailCustomerCharge' => 'D16',
            // 'SupplySystemCharge' => 'D',
            'MeteringRetailCustomerCharge' => 'D18',
            'MeteringSystemCharge' => 'D19',
            // 'RFSC' => 'D',
            'PowerActReduction' => 'D20',
            'LifelineRate' => 'D21',
            'InterClassCrossSubsidyCharge' => 'D23',
            // 'PPARefund' => 'D',
            'SeniorCitizenSubsidy' => 'D22',
            // 'OtherLifelineRateCostAdjustment' => 'D',
            // 'SeniorCitizenDiscountAndSubsidyAdjustment' => 'D',
            // 'MissionaryElectrificationCharge' => 'D',
            // 'EnvironmentalCharge' => 'D',
            // 'StrandedContractCosts' => 'D',
            'NPCStrandedDebt' => 'D35',
            'FeedInTariffAllowance' => 'D36',
            'MissionaryElectrificationREDCI' => 'D32',
            'MissionaryElectrificationSPUG' => 'D30',
            'MissionaryElectrificationSPUGTRUEUP' => 'D31',
            'GenerationVAT' => 'D25',
            'TransmissionVAT' => 'D27',
            'SystemLossVAT' => 'D28',
            'ACRMVAT' => 'D26',
            'FranchiseTax' => 'D37',
            // 'BusinessTax' => 'D',
            // 'RealPropertyTax' => 'D',
            // 'TotalRateVATExcluded' => 'D',
            // 'TotalRateVATExcludedWithAdjustments' => 'D',
            'TotalRateVATIncluded' => 'D39',
        ];
    }
    
    public function model(array $row)
    {
        return new Rates([
            'id' => IDGenerator::generateIDandRandString(),
            'RateFor' => $this->district,
            'ServicePeriod' => $this->servicePeriod,
            'UserId' => $this->userId,
            'ConsumerType' => 'RP',
            'ConsumerTypeDescription' => 'RESIDENTIAL w/o DAA',
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

