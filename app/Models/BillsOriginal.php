<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class BillsOriginal
 * @package App\Models
 * @version June 2, 2022, 9:21 am PST
 *
 * @property string $BillNumber
 * @property string $AccountNumber
 * @property string $ServicePeriod
 * @property string $Multiplier
 * @property string $Coreloss
 * @property string $KwhUsed
 * @property string $PreviousKwh
 * @property string $PresentKwh
 * @property string $DemandPreviousKwh
 * @property string $DemandPresentKwh
 * @property string $AdditionalKwh
 * @property string $AdditionalDemandKwh
 * @property string $KwhAmount
 * @property string $EffectiveRate
 * @property string $AdditionalCharges
 * @property string $Deductions
 * @property string $NetAmount
 * @property string $BillingDate
 * @property string $ServiceDateFrom
 * @property string $ServiceDateTo
 * @property string $DueDate
 * @property string $MeterNumber
 * @property string $ConsumerType
 * @property string $BillType
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
 * @property string $RealPropertyTax
 * @property string $OtherGenerationRateAdjustment
 * @property string $OtherTransmissionCostAdjustmentKW
 * @property string $OtherTransmissionCostAdjustmentKWH
 * @property string $OtherSystemLossCostAdjustment
 * @property string $OtherLifelineRateCostAdjustment
 * @property string $SeniorCitizenDiscountAndSubsidyAdjustment
 * @property string $FranchiseTax
 * @property string $BusinessTax
 * @property string $AdjustmentType
 * @property string $AdjustmentNumber
 * @property string $AdjustedBy
 * @property string $DateAdjusted
 * @property string $Notes
 * @property string $UserId
 * @property string $BilledFrom
 * @property string $Form2307Amount
 * @property string $Evat2Percent
 * @property string $Evat5Percent
 * @property string $MergedToCollectible
 * @property string $DeductedDeposit
 * @property string $ExcessDeposit
 * @property string $AveragedCount
 * @property string $IsUnlockedForPayment
 * @property string $UnlockedBy
 */
class BillsOriginal extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Billing_BillsOriginal';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'BillNumber',
        'AccountNumber',
        'ServicePeriod',
        'Multiplier',
        'Coreloss',
        'KwhUsed',
        'PreviousKwh',
        'PresentKwh',
        'DemandPreviousKwh',
        'DemandPresentKwh',
        'AdditionalKwh',
        'AdditionalDemandKwh',
        'KwhAmount',
        'EffectiveRate',
        'AdditionalCharges',
        'Deductions',
        'NetAmount',
        'BillingDate',
        'ServiceDateFrom',
        'ServiceDateTo',
        'DueDate',
        'MeterNumber',
        'ConsumerType',
        'BillType',
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
        'OthersVAT',
        'RealPropertyTax',
        'Notes',
        'UserId',
        'BilledFrom',
        'AveragedCount',
        'MergedToCollectible',
        'OtherGenerationRateAdjustment',
        'OtherTransmissionCostAdjustmentKW',
        'OtherTransmissionCostAdjustmentKWH',
        'OtherSystemLossCostAdjustment',
        'OtherLifelineRateCostAdjustment',
        'SeniorCitizenDiscountAndSubsidyAdjustment',
        'FranchiseTax',
        'BusinessTax',
        'AdjustmentType',
        'Form2307Amount',
        'DeductedDeposit',
        'ExcessDeposit',
        'IsUnlockedForPayment',
        'UnlockedBy',
        'Evat2Percent',
        'Evat5Percent',
        'AdjustmentNumber',
        'AdjustedBy',
        'DateAdjusted',
        'ForCancellation',
        'CancelRequestedBy',
        'CancelApprovedBy',
        'KatasNgVat',
        'SolarImportPresent',
        'SolarImportPrevious',
        'SolarExportPresent',
        'SolarExportPrevious',
        'SolarImportKwh',
        'SolarExportKwh',
        'GenerationChargeSolarExport',
        'SolarResidualCredit', // IF NEGATIVE ANG AMOUNT
        'SolarDemandChargeKW',
        'SolarDemandChargeKWH',
        'SolarRetailCustomerCharge',
        'SolarSupplySystemCharge',
        'SolarMeteringRetailCharge',
        'SolarMeteringSystemCharge',
        'Item1', // CURRENT AMOUNT DU TO CUSTOMER / PARTIAL AMOUNT
        'Item2', 
        'Item3',
        'Item4', // CURRENT AMOUNT CUSTOMER TO DU (Solar Gen - Residual sa Previous)
        'Item5',
        'PaidAmount',
        'Balance',
        'ACRM',
        'PowerActReduction',
        'ACRMVAT',
        'MissionaryElectrificationSPUG',
        'MissionaryElectrificationSPUGTRUEUP',
        'FranchiseTaxOthers',
        'AdvancedMaterialDeposit',
        'CustomerDeposit',
        'TransformerRental',
        'AdjustmentRequestedBy',
        'AdjustmentApprovedBy',
        'AdjustmentStatus',
        'DateAdjustmentRequested',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'BillNumber' => 'string',
        'AccountNumber' => 'string',
        'ServicePeriod' => 'string',
        'Multiplier' => 'string',
        'Coreloss' => 'string',
        'KwhUsed' => 'string',
        'PreviousKwh' => 'string',
        'PresentKwh' => 'string',
        'DemandPreviousKwh' => 'string',
        'DemandPresentKwh' => 'string',
        'AdditionalKwh' => 'string',
        'AdditionalDemandKwh' => 'string',
        'KwhAmount' => 'string',
        'EffectiveRate' => 'string',
        'AdditionalCharges' => 'string',
        'Deductions' => 'string',
        'NetAmount' => 'string',
        'BillingDate' => 'string',
        'ServiceDateFrom' => 'string',
        'ServiceDateTo' => 'string',
        'DueDate' => 'string',
        'MeterNumber' => 'string',
        'ConsumerType' => 'string',
        'BillType' => 'string',
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
        'OthersVAT' => 'string',
        'RealPropertyTax' => 'string',
        'Notes' => 'string',
        'UserId' => 'string',
        'BilledFrom' => 'string',
        'AveragedCount' => 'string',
        'MergedToCollectible' => 'string',
        'OtherGenerationRateAdjustment' => 'string',
        'OtherTransmissionCostAdjustmentKW' => 'string',
        'OtherTransmissionCostAdjustmentKWH' => 'string',
        'OtherSystemLossCostAdjustment' => 'string',
        'OtherLifelineRateCostAdjustment' => 'string',
        'SeniorCitizenDiscountAndSubsidyAdjustment' => 'string',
        'FranchiseTax' => 'string',
        'BusinessTax' => 'string',
        'AdjustmentType' => 'string',
        'Form2307Amount' => 'string',
        'DeductedDeposit' => 'string',
        'ExcessDeposit' => 'string',
        'IsUnlockedForPayment' => 'string',
        'UnlockedBy' => 'string',
        'Evat2Percent' => 'string',
        'Evat5Percent' => 'string',
        'AdjustmentNumber' => 'string',
        'AdjustedBy' => 'string',
        'DateAdjusted' => 'string',
        'ForCancellation' => 'string',
        'CancelRequestedBy' => 'string',
        'CancelApprovedBy' => 'string',
        'KatasNgVat' => 'string',
        'SolarImportPresent' => 'string',
        'SolarImportPrevious' => 'string',
        'SolarExportPresent' => 'string',
        'SolarExportPrevious' => 'string',
        'SolarImportKwh' => 'string',
        'SolarExportKwh' => 'string',
        'GenerationChargeSolarExport' => 'string',
        'SolarResidualCredit' => 'string',
        'SolarDemandChargeKW' => 'string',
        'SolarDemandChargeKWH' => 'string',
        'SolarRetailCustomerCharge' => 'string',
        'SolarSupplySystemCharge' => 'string',
        'SolarMeteringRetailCharge' => 'string',
        'SolarMeteringSystemCharge' => 'string',
        'Item1' => 'string',
        'Item2' => 'string',
        'Item3' => 'string',
        'Item4' => 'string',
        'Item5' => 'string',        
        'PaidAmount' => 'string',
        'Balance' => 'string',
        'ACRM' => 'string',
        'PowerActReduction' => 'string',
        'ACRMVAT' => 'string',
        'MissionaryElectrificationSPUG' => 'string',
        'MissionaryElectrificationSPUGTRUEUP' => 'string',
        'FranchiseTaxOthers' => 'string',
        'AdvancedMaterialDeposit' => 'string',
        'CustomerDeposit' => 'string',
        'TransformerRental' => 'string',
        'AdjustmentRequestedBy' => 'string',
        'AdjustmentApprovedBy' => 'string',
        'AdjustmentStatus' => 'string',
        'DateAdjustmentRequested' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'BillNumber' => 'nullable|string|max:255',
        'AccountNumber' => 'nullable|string|max:255',
        'ServicePeriod' => 'nullable',
        'Multiplier' => 'nullable|string|max:255',
        'Coreloss' => 'nullable|string|max:255',
        'KwhUsed' => 'nullable|string|max:255',
        'PreviousKwh' => 'nullable|string|max:255',
        'PresentKwh' => 'nullable|string|max:255',
        'DemandPreviousKwh' => 'nullable|string|max:255',
        'DemandPresentKwh' => 'nullable|string|max:255',
        'AdditionalKwh' => 'nullable|string|max:255',
        'AdditionalDemandKwh' => 'nullable|string|max:255',
        'KwhAmount' => 'nullable|string|max:255',
        'EffectiveRate' => 'nullable|string|max:255',
        'AdditionalCharges' => 'nullable|string|max:255',
        'Deductions' => 'nullable|string|max:255',
        'NetAmount' => 'nullable|string|max:255',
        'BillingDate' => 'nullable',
        'ServiceDateFrom' => 'nullable',
        'ServiceDateTo' => 'nullable',
        'DueDate' => 'nullable',
        'MeterNumber' => 'nullable|string|max:255',
        'ConsumerType' => 'nullable|string|max:255',
        'BillType' => 'nullable|string|max:255',
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
        'OthersVAT' => 'nullable|string',
        'RealPropertyTax' => 'nullable|string|max:20',
        'Notes' => 'nullable|string|max:2500',
        'UserId' => 'nullable|string|max:255',
        'BilledFrom' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'AveragedCount' => 'nullable|string',
        'MergedToCollectible' => 'nullable|string',
        'OtherGenerationRateAdjustment' => 'nullable|string',
        'OtherTransmissionCostAdjustmentKW' => 'nullable|string',
        'OtherTransmissionCostAdjustmentKWH' => 'nullable|string',
        'OtherSystemLossCostAdjustment' => 'nullable|string',
        'OtherLifelineRateCostAdjustment' => 'nullable|string',
        'SeniorCitizenDiscountAndSubsidyAdjustment' => 'nullable|string',
        'FranchiseTax' => 'nullable|string',
        'BusinessTax' => 'nullable|string',
        'AdjustmentType' => 'nullable|string',
        'Form2307Amount' => 'nullable|string',
        'DeductedDeposit' => 'nullable|string',
        'ExcessDeposit' => 'nullable|string',
        'IsUnlockedForPayment' => 'nullable|string',
        'UnlockedBy' => 'nullable|string',
        'Evat2Percent' => 'nullable|string',
        'Evat5Percent' => 'nullable|string',
        'AdjustmentNumber' => 'nullable|string',
        'AdjustedBy' => 'nullable|string',
        'DateAdjusted' => 'nullable|string',
        'ForCancellation' => 'nullable|string',
        'CancelRequestedBy' => 'nullable|string',
        'CancelApprovedBy' => 'nullable|string',
        'KatasNgVat' => 'nullable|string',
        'SolarImportPresent' => 'nullable|string',
        'SolarImportPrevious' => 'nullable|string',
        'SolarExportPresent' => 'nullable|string',
        'SolarExportPrevious' => 'nullable|string',
        'SolarImportKwh' => 'nullable|string',
        'SolarExportKwh' => 'nullable|string',
        'GenerationChargeSolarExport' => 'nullable|string',
        'SolarResidualCredit' => 'nullable|string',
        'SolarDemandChargeKW' => 'nullable|string',
        'SolarDemandChargeKWH' => 'nullable|string',
        'SolarRetailCustomerCharge' => 'nullable|string',
        'SolarSupplySystemCharge' => 'nullable|string',
        'SolarMeteringRetailCharge' => 'nullable|string',
        'SolarMeteringSystemCharge' => 'nullable|string',
        'Item1' => 'nullable|string',
        'Item2' => 'nullable|string',
        'Item3' => 'nullable|string',
        'Item4' => 'nullable|string',
        'Item5' => 'nullable|string',   
        'PaidAmount' => 'nullable|string',
        'Balance' => 'nullable|string',
        'ACRM' => 'nullable|string',
        'PowerActReduction' => 'nullable|string',
        'ACRMVAT' => 'nullable|string',
        'MissionaryElectrificationSPUG' => 'nullable|string',
        'MissionaryElectrificationSPUGTRUEUP' => 'nullable|string',
        'FranchiseTaxOthers' => 'nullable|string',
        'AdvancedMaterialDeposit' => 'nullable|string',
        'CustomerDeposit' => 'nullable|string',
        'TransformerRental' => 'nullable|string',
        'AdjustmentRequestedBy' => 'nullable|string',
        'AdjustmentApprovedBy' => 'nullable|string',
        'AdjustmentStatus' => 'nullable|string',
        'DateAdjustmentRequested' => 'nullable|string',
    ];
}
