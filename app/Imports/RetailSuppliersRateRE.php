<?php
namespace App\Imports;

use App\Models\Rates;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use App\Models\IDGenerator;

class RetailSuppliersRateRE implements WithMappedCells, WithCalculatedFormulas, ToModel 
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
            'GenerationSystemCharge' => 'G10',
            'ACRM' => 'G11',
            'TransmissionDeliveryChargeKWH' => 'G12',
            'SystemLossCharge' => 'G13',
            // 'OtherGenerationRateAdjustment' => 'G',
            // 'OtherTransmissionCostAdjustmentKW' => 'G',
            // 'OtherTransmissionCostAdjustmentKWH' => 'G',
            // 'OtherSystemLossCostAdjustment' => 'G',
            'DistributionDemandCharge' => 'G15',
            'DistributionSystemCharge' => 'G14',
            'SupplyRetailCustomerCharge' => 'G16',
            // 'SupplySystemCharge' => 'G',
            'MeteringRetailCustomerCharge' => 'G18',
            'MeteringSystemCharge' => 'G19',
            // 'RFSC' => 'G',
            'PowerActReduction' => 'G20',
            'LifelineRate' => 'G21',
            'InterClassCrossSubsidyCharge' => 'G23',
            // 'PPARefund' => 'G',
            'SeniorCitizenSubsidy' => 'G22',
            // 'OtherLifelineRateCostAdjustment' => 'G',
            // 'SeniorCitizenDiscountAndSubsidyAdjustment' => 'G',
            // 'MissionaryElectrificationCharge' => 'G',
            // 'EnvironmentalCharge' => 'G',
            // 'StrandedContractCosts' => 'G',
            'NPCStrandedDebt' => 'G35',
            // 'FeedInTariffAllowance' => 'G',
            'MissionaryElectrificationREDCI' => 'G32',
            'MissionaryElectrificationSPUG' => 'G30',
            'MissionaryElectrificationSPUGTRUEUP' => 'G31',
            'GenerationVAT' => 'G25',
            'TransmissionVAT' => 'G27',
            'SystemLossVAT' => 'G28',
            'ACRMVAT' => 'G26',
            'FranchiseTax' => 'G37',
            // 'BusinessTax' => 'G',
            // 'RealPropertyTax' => 'G',
            // 'TotalRateVATExcluded' => 'G',
            // 'TotalRateVATExcludedWithAdjustments' => 'G',
            'TotalRateVATIncluded' => 'G39',
        ];
    }
    
    public function model(array $row)
    {
        return new Rates([
            'id' => IDGenerator::generateIDandRandString(),
            'RateFor' => $this->district,
            'ServicePeriod' => $this->servicePeriod,
            'UserId' => $this->userId,
            'ConsumerType' => 'RE',
            'ConsumerTypeDescription' => 'RETAIL ELECTRICITY SUPPLIERS w/ DAA',
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

