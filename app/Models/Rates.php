<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Rates
 * @package App\Models
 * @version January 21, 2022, 8:25 am PST
 *
 * @property string $RateFor
 * @property string $ConsumerType
 * @property string $ServicePeriod
 * @property string $Notes
 * @property string $GenerationSystemCharge
 * @property string $TransmissionDeliveryChargeKW
 * @property string $TransmissionDeliveryChargeKWH
 * @property string $SystemLossCharge
 * @property string $DistributionDemandCharge
 * @property string $DistributionSystemCharge
 * @property string $SupplyRetailCustomerCharge
 * @property string $SupplySystemCharge
 * @property string $MeteringRetailCustomerCharge
 * @property string $MeteringSystemCharge
 * @property string $RFSC
 * @property string $LifelineRate
 * @property string $InterClassCrossSubsidyCharge
 * @property string $PPARefund
 * @property string $SeniorCitizenSubsidy
 * @property string $MissionaryElectrificationCharge
 * @property string $EnvironmentalCharge
 * @property string $StrandedContractCosts
 * @property string $NPCStrandedDebt
 * @property string $FeedInTariffAllowance
 * @property string $MissionaryElectrificationREDCI
 * @property string $GenerationVAT
 * @property string $TransmissionVAT
 * @property string $SystemLossVAT
 * @property string $DistributionVAT
 * @property string $TotalRateVATExcluded
 * @property string $TotalRateVATIncluded
 * @property string $UserId
 */
class Rates extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Billing_Rates';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $connection = "sqlsrv";

    public $fillable = [
        'id',
        'RateFor',
        'ConsumerType',
        'ServicePeriod',
        'Notes',
        'GenerationSystemCharge',
        'TransmissionDeliveryChargeKW',
        'TransmissionDeliveryChargeKWH',
        'SystemLossCharge',
        'DistributionDemandCharge',
        'DistributionSystemCharge',
        'SupplyRetailCustomerCharge',
        'SupplySystemCharge',
        'MeteringRetailCustomerCharge',
        'MeteringSystemCharge',
        'RFSC',
        'LifelineRate',
        'InterClassCrossSubsidyCharge',
        'PPARefund',
        'SeniorCitizenSubsidy',
        'MissionaryElectrificationCharge',
        'EnvironmentalCharge',
        'StrandedContractCosts',
        'NPCStrandedDebt',
        'FeedInTariffAllowance',
        'MissionaryElectrificationREDCI',
        'GenerationVAT',
        'TransmissionVAT',
        'SystemLossVAT',
        'DistributionVAT',
        'TotalRateVATExcluded',
        'TotalRateVATIncluded',
        'UserId',
        'RealPropertyTax',
        'AreaCode',
        'OtherGenerationRateAdjustment',
        'OtherTransmissionCostAdjustmentKW',
        'OtherTransmissionCostAdjustmentKWH',
        'OtherSystemLossCostAdjustment',
        'OtherLifelineRateCostAdjustment',
        'SeniorCitizenDiscountAndSubsidyAdjustment',
        'FranchiseTax',
        'BusinessTax',
        'TotalRateVATExcludedWithAdjustments',
        'ACRM',
        'PowerActReduction',
        'ACRMVAT',
        'MissionaryElectrificationSPUG',
        'MissionaryElectrificationSPUGTRUEUP',
        'ConsumerTypeDescription',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'RateFor' => 'string',
        'ConsumerType' => 'string',
        'ServicePeriod' => 'string',
        'Notes' => 'string',
        'GenerationSystemCharge' => 'string',
        'TransmissionDeliveryChargeKW' => 'string',
        'TransmissionDeliveryChargeKWH' => 'string',
        'SystemLossCharge' => 'string',
        'DistributionDemandCharge' => 'string',
        'DistributionSystemCharge' => 'string',
        'SupplyRetailCustomerCharge' => 'string',
        'SupplySystemCharge' => 'string',
        'MeteringRetailCustomerCharge' => 'string',
        'MeteringSystemCharge' => 'string',
        'RFSC' => 'string',
        'LifelineRate' => 'string',
        'InterClassCrossSubsidyCharge' => 'string',
        'PPARefund' => 'string',
        'SeniorCitizenSubsidy' => 'string',
        'MissionaryElectrificationCharge' => 'string',
        'EnvironmentalCharge' => 'string',
        'StrandedContractCosts' => 'string',
        'NPCStrandedDebt' => 'string',
        'FeedInTariffAllowance' => 'string',
        'MissionaryElectrificationREDCI' => 'string',
        'GenerationVAT' => 'string',
        'TransmissionVAT' => 'string',
        'SystemLossVAT' => 'string',
        'DistributionVAT' => 'string',
        'TotalRateVATExcluded' => 'string',
        'TotalRateVATIncluded' => 'string',
        'UserId' => 'string',
        'RealPropertyTax' => 'string',
        'AreaCode' => 'string',
        'OtherGenerationRateAdjustment' => 'string',
        'OtherTransmissionCostAdjustmentKW' => 'string',
        'OtherTransmissionCostAdjustmentKWH' => 'string',
        'OtherSystemLossCostAdjustment' => 'string',
        'OtherLifelineRateCostAdjustment' => 'string',
        'SeniorCitizenDiscountAndSubsidyAdjustment' => 'string',
        'FranchiseTax' => 'string',
        'BusinessTax' => 'string',
        'TotalRateVATExcludedWithAdjustments' => 'string',
        'ACRM' => 'string',
        'PowerActReduction' => 'string',
        'ACRMVAT' => 'string',
        'MissionaryElectrificationSPUG' => 'string',
        'MissionaryElectrificationSPUGTRUEUP' => 'string',
        'ConsumerTypeDescription' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'nullable|string',
        'RateFor' => 'nullable|string|max:100',
        'ConsumerType' => 'nullable|string|max:100',
        'ServicePeriod' => 'nullable',
        'Notes' => 'nullable|string|max:1000',
        'GenerationSystemCharge' => 'nullable|string|max:20',
        'TransmissionDeliveryChargeKW' => 'nullable|string|max:20',
        'TransmissionDeliveryChargeKWH' => 'nullable|string|max:20',
        'SystemLossCharge' => 'nullable|string|max:20',
        'DistributionDemandCharge' => 'nullable|string|max:20',
        'DistributionSystemCharge' => 'nullable|string|max:20',
        'SupplyRetailCustomerCharge' => 'nullable|string|max:20',
        'SupplySystemCharge' => 'nullable|string|max:20',
        'MeteringRetailCustomerCharge' => 'nullable|string|max:20',
        'MeteringSystemCharge' => 'nullable|string|max:20',
        'RFSC' => 'nullable|string|max:20',
        'LifelineRate' => 'nullable|string|max:20',
        'InterClassCrossSubsidyCharge' => 'nullable|string|max:20',
        'PPARefund' => 'nullable|string|max:20',
        'SeniorCitizenSubsidy' => 'nullable|string|max:20',
        'MissionaryElectrificationCharge' => 'nullable|string|max:20',
        'EnvironmentalCharge' => 'nullable|string|max:20',
        'StrandedContractCosts' => 'nullable|string|max:20',
        'NPCStrandedDebt' => 'nullable|string|max:20',
        'FeedInTariffAllowance' => 'nullable|string|max:20',
        'MissionaryElectrificationREDCI' => 'nullable|string|max:20',
        'GenerationVAT' => 'nullable|string|max:20',
        'TransmissionVAT' => 'nullable|string|max:20',
        'SystemLossVAT' => 'nullable|string|max:20',
        'DistributionVAT' => 'nullable|string|max:20',
        'TotalRateVATExcluded' => 'nullable|string|max:20',
        'TotalRateVATIncluded' => 'nullable|string|max:20',
        'UserId' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'RealPropertyTax' => 'nullable|string',
        'AreaCode' => 'nullable|string',
        'OtherGenerationRateAdjustment' => 'nullable|string',
        'OtherTransmissionCostAdjustmentKW' => 'nullable|string',
        'OtherTransmissionCostAdjustmentKWH' => 'nullable|string',
        'OtherSystemLossCostAdjustment' => 'nullable|string',
        'OtherLifelineRateCostAdjustment' => 'nullable|string',
        'SeniorCitizenDiscountAndSubsidyAdjustment' => 'nullable|string',
        'FranchiseTax' => 'nullable|string',
        'BusinessTax' => 'nullable|string',
        'TotalRateVATExcludedWithAdjustments' => 'nullable|string',
        'ACRM' => 'nullable|string',
        'PowerActReduction' => 'nullable|string',
        'ACRMVAT' => 'nullable|string',
        'MissionaryElectrificationSPUG' => 'nullable|string',
        'MissionaryElectrificationSPUGTRUEUP' => 'nullable|string',
        'ConsumerTypeDescription' => 'nullable|string',
    ];

    public static function floatRate($rate) {
        if ($rate != null) {
            return round(floatval($rate), 4);
        } else {
            return 0;
        }
    }

    public static function filterConsumerType($consumerType) {
        if ($consumerType == 'RURAL RESIDENTIAL') {
            return 'RESIDENTIAL';
        } else {
            return $consumerType;
        }
    }
}
