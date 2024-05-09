<?php
namespace App\Imports;

use App\Models\Rates;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use App\Models\IDGenerator;

class CommercialRateCM implements WithMappedCells, WithCalculatedFormulas, ToModel 
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
            'GenerationSystemCharge' => 'H10',
            'ACRM' => 'H11',
            'TransmissionDeliveryChargeKWH' => 'H12',
            'SystemLossCharge' => 'H13',
            // 'OtherGenerationRateAdjustment' => 'H',
            // 'OtherTransmissionCostAdjustmentKW' => 'H',
            // 'OtherTransmissionCostAdjustmentKWH' => 'H',
            // 'OtherSystemLossCostAdjustment' => 'H',
            'DistributionDemandCharge' => 'H15',
            'DistributionSystemCharge' => 'H14',
            'SupplyRetailCustomerCharge' => 'H16',
            // 'SupplySystemCharge' => 'H',
            'MeteringRetailCustomerCharge' => 'H18',
            'MeteringSystemCharge' => 'H19',
            // 'RFSC' => 'H',
            'PowerActReduction' => 'H20',
            'LifelineRate' => 'H21',
            'InterClassCrossSubsidyCharge' => 'H23',
            // 'PPARefund' => 'H',
            'SeniorCitizenSubsidy' => 'H22',
            // 'OtherLifelineRateCostAdjustment' => 'H',
            // 'SeniorCitizenDiscountAndSubsidyAdjustment' => 'H',
            // 'MissionaryElectrificationCharge' => 'H',
            // 'EnvironmentalCharge' => 'H',
            // 'StrandedContractCosts' => 'H',
            'NPCStrandedDebt' => 'H35',
            'FeedInTariffAllowance' => 'H36',
            'MissionaryElectrificationREDCI' => 'H32',
            'MissionaryElectrificationSPUG' => 'H30',
            'MissionaryElectrificationSPUGTRUEUP' => 'H31',
            'GenerationVAT' => 'H25',
            'TransmissionVAT' => 'H27',
            'SystemLossVAT' => 'H28',
            'ACRMVAT' => 'H26',
            'FranchiseTax' => 'H37',
            // 'BusinessTax' => 'H',
            // 'RealPropertyTax' => 'H',
            // 'TotalRateVATExcluded' => 'H',
            // 'TotalRateVATExcludedWithAdjustments' => 'H',
            'TotalRateVATIncluded' => 'H39',
        ];
    }
    
    public function model(array $row)
    {
        return new Rates([
            'id' => IDGenerator::generateIDandRandString(),
            'RateFor' => $this->district,
            'ServicePeriod' => $this->servicePeriod,
            'UserId' => $this->userId,
            'ConsumerType' => 'CM',
            'ConsumerTypeDescription' => 'COMMERCIAL w/DAA',
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

